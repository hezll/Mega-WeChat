<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/3/17
 * Time: 11:05
 */

namespace Network;

use Monolog\Logger;
use Server\SubWechatServer;
use Swoole\Protocol\Base;
use Swoole\Queue\FileQueue;
use Swoole\Util\Config;
use Swoole\Util\Log;
use Wechat\Template;
use Wechat\WechatApi;
use Wechat\WechatTemplateModel;

class SubWechatProtocol extends Base
{
    private $taskWorkerNum;
    /** @var FileQueue  */
    private $queue;
    /** @var WechatApi */
    private $wechatApi;
    /** @var Logger */
    private $logger;

    public function init()
    {
        parent::init();
        $this->wechatApi = new WechatApi(Config::get('wechat.app_id'),
                        Config::get('wechat.app_secret'), Config::get('wechat.token'));
        $this->logger = Log::getLogger();
    }

    /**
     * @param $server \swoole_server
     * @param $workerId
     */
    function onStart(\swoole_server $server, $workerId)
    {

    }

    function onConnect(\swoole_server $server, $fd, $from_id)
    {
        $this->logger->info("Client@[{$fd}:{$from_id}] connect");
    }

    function onReceive(\swoole_server $server, $fd, $from_id, $data)
    {
        $task_id = $server->task($data);
        $this->logger->info("收到异步任务：id={$task_id}：openid={$data}");

    }

    function onShutdown(\swoole_server $server, $workerId)
    {

    }

    function onTask(\swoole_server $server, $taskId, $fromId, $data)
    {
//$this->logger->info("On Task@[{$taskId}:{$fromId}] close");
        $data = json_decode($data,true);
        //解析操作事件

        //关注后回复消息
        if(isset($data['event'])&&$data['event']=='replySub'){
            //回复最近阅读章节
            $content = $data['content'];
            $result = $this->wechatApi->sendCustomerMessage($content);

            if(isset($result)&&$result['errmsg']=='ok'){
                echo '.s';

            }else{
                echo ('.s推送失败时间：'.date( 'Y-m-d H:i:s' , time()).'...');
                var_dump($result);
            }

            //回复首次关注后的消息



        }elseif(isset($data['event'])&&$data['event']=='replyRecent'){
            //回复书架或者最近阅读url
            //回复最近阅读章节
            $content = ['kf_account'=>'kf2002@gh_26a30bfcfb6e','touser'=>$data['openid'],'msgtype'=>'text','text'=>['content'=>
                "https://w.url.cn/s/AtbX8nN"]
            ];
            $result = $this->wechatApi->sendCustomerMessage($content);

            if(isset($result)&&$result['errmsg']=='ok'){
                echo '.r';
            }else{
                echo ('.r推送失败时间：'.date( 'Y-m-d H:i:s' , time()).'...');
                var_dump($result);
            }




        }elseif(isset($data['event'])&&$data['event']=='allCustomerMsg'){
            //群发客服消息

        }elseif(isset($data['event'])&&$data['event']=='replyText'){
            //待定

        }

//        $data = $this->wechatApi->isSubscribe($data);
//        var_dump($data);
    }

    /**
     * @param $server \swoole_server
     * @param $taskId
     * @param $data
     */
    function onFinish(\swoole_server $server, $taskId, $data)
    {

    }

    function onRequest($request, $response)
    {
        throw new \Exception('not implement onRequest method');
    }

    function onClose(\swoole_server $server, $client_id, $from_id)
    {
        //echo "Client@[{$client_id}:{$from_id}] close" . PHP_EOL;
        $this->logger->info("Client@[{$client_id}:{$from_id}] close");
    }

    function onTimer(\swoole_server $server, $interval)
    {
        throw new \Exception('not implement onTimer method');
    }

}