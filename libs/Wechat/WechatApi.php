<?php

namespace Wechat;

use Swoole\Util\Config;
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2016/4/30 0030
 * Time: 17:14
 */
class WechatApi extends BaseWechatApi
{
    /**
     * 微信接口基本地址
     */
    const WECHAT_BASE_URL = 'https://api.weixin.qq.com';

    /**
     * 公众号appId
     * @var string
     */
    public $appId = '';

    /**
     * 公众号appSecret
     * @var string
     */
    public $appSecret = '';

    /**
     * 公众号接口验证token,可由您来设定. 并填写在微信公众平台->开发者中心
     * @var string
     */
    public $token = '';

    /**
     * 公众号消息加密键值
     * @var string
     */
    public $encodingAesKey;

    private $_wxtokenTable;
    private $_accessToken;

    public function __construct($appId, $appSecret, $token)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;


        //init swoole table 共享token准备

        $this->_wxtokenTable = new \swoole_table(Config::get('server.table_size'));
        $this->_wxtokenTable->column('access_token', \swoole_table::TYPE_STRING, 1024);
        $this->_wxtokenTable->column('expire', \swoole_table::TYPE_STRING,20);
        $this->_wxtokenTable->create();
    }

    /**
     * 增加微信基本链接
     * @inheritdoc
     */
    protected function httpBuildQuery($url, array $options)
    {
        if (stripos($url, 'http://') === false && stripos($url, 'https://') === false) {
            $url = self::WECHAT_BASE_URL . $url;
        }
        return parent::httpBuildQuery($url, $options);
    }

    /**
     * access token获取
     */
    const WECHAT_ACCESS_TOKEN_PREFIX = '/cgi-bin/token';

    /**
     * 请求服务器access_token
     * @param string $grantType
     * @return array|bool
     */
    protected function requestAccessToken($grantType = 'client_credential')
    {
        $result = $this->httpGet(self::WECHAT_ACCESS_TOKEN_PREFIX, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => $grantType
        ]);
        return isset($result['access_token']) ? $result : false;
    }

    /**
     * 用swoole table 来重写
     * @param bool $force
     * @return mixed
     * @throws \Exception
     */
    public function getAccessToken($force = false)
    {

        $this->_accessToken = $this->_wxtokenTable->get($this->appId);
        $time = time(); // 为了更精确控制.取当前时间计算
        if ($force || !$this->_accessToken ||  $this->_accessToken === null || (isset($this->_accessToken['expire']) && $this->_accessToken['expire'] < ($time - 180)))  {

            echo '--come-new token';
            if (!($result = $this->requestAccessToken())) {
                throw new \Exception('Fail to get access_token from wechat server.'.json_encode($result), 500);
            }
            $result['expire'] = $time + $result['expires_in'];
            $this->setAccessToken($result);
            $this->_wxtokenTable->set($this->appId,$result);

        }
        echo '.t';
        return $this->_accessToken['access_token'];
    }


    /**
    * 设置AccessToken
    * @param array $accessToken  重写，私有函数无法继承
    * @throws \InvalidArgumentException
    */
    public function setAccessToken(array $accessToken)
    {
        if (!isset($accessToken['access_token'])) {
            throw new \InvalidArgumentException('The wechat access_token must be set.');
        } elseif(!isset($accessToken['expire'])) {
            throw new \InvalidArgumentException('Wechat access_token expire time must be set.');
        }
        $this->_accessToken = $accessToken;
    }
    /**
     * @inheritdoc
     * @param bool $force 是否强制获取access_token, 该设置会在access_token使用错误时, 是否再获取一次access_token并再重新提交请求
     */
    public function parseHttpRequest(callable $callable, $url, $postOptions = null, $force = true)
    {
        $result = call_user_func_array($callable, [$url, $postOptions]);
        if (isset($result['errcode']) && $result['errcode']) {
            $this->lastError = $result;
            $result['errcode'] = 40001;
            switch ($result ['errcode']) {
                case 40001: //access_token 失效,强制更新access_token, 并更新地址重新执行请求
                case 42001:
                    if ($force) {
                        $url = preg_replace_callback("/access_token=([^&]*)/i", function(){
                            return 'access_token=' . $this->getAccessToken(true);
                        }, $url);
                        $result = $this->parseHttpRequest($callable, $url, $postOptions, false); // 仅重新获取一次,否则容易死循环
                    }
                    break;
            }
        }
        return $result;
    }

    /**
     * 发送模板消息
     */
    const WECHAT_TEMPLATE_MESSAGE_SEND_PREFIX = '/cgi-bin/message/template/send';

    /**
     * 发送模板消息
     * @param array $data 模板需要的数据
     * @param bool $force 强制获取token
     * @return int|bool
     */
    public function sendTemplateMessage($data, $force = false)
    {
        $token = $this->getAccessToken($force);
        $url = $this->httpBuildQuery(self::WECHAT_TEMPLATE_MESSAGE_SEND_PREFIX, [
            'access_token' => $token
        ]);

        $result = $this->http($url, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data
        ]);

        return $result;
    }


    /**
     * 发送模板消息
     */
    const WECHAT_CUSTOMER_MESSAGE_SEND_PREFIX = '/cgi-bin/message/custom/send';
    /**
     * 发送客服消息
     * @param array $data 模板需要的数据
     * @param bool $force 强制获取token
     * @return int|bool
     */
    public function sendCustomerMessage($data, $force = false)
    {
        $token =$this->getAccessToken($force);//请单独拿出来处理
        $url = $this->httpBuildQuery(self::WECHAT_CUSTOMER_MESSAGE_SEND_PREFIX, [
            'access_token' => $token
        ]);

        $result = $this->http($url, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data
        ]);

        //$result['errcode']='40001';
        //$result['errmsg']='access_token is invalid or not latest hint';

        $i = 0;
        if(isset($result['errcode'])&&$result['errcode']=='40001'&&strpos($result['errmsg'],'access_token is invalid or not latest hint')===true){
            echo '|||---rebuild token when send CustomerMessge';
            $result = $this->sendCustomerMessage($data,true);
        }

        //var_dump($result);exit;
        return $result;
    }

    /**
     * 判断用户是否关注公众号
     */
    const WECHAT_CUSTOMER_SUBSCRIBE_PREFIX = '/cgi-bin/user/info';
    /**
     * 判断是否订阅
     * @param array $data 模板需要的数据
     * @param bool $force 强制获取token
     * @return int|bool
     */
    public function isSubscribe($openid, $force = false)
    {
        $token =$this->getAccessToken($force);//请单独拿出来处理
        $url = $this->httpBuildQuery(self::WECHAT_CUSTOMER_SUBSCRIBE_PREFIX,[
            'access_token' => $token,
            'openid'=>$openid
        ]);
        $result = $this->http($url);
        return $result;
    }
}