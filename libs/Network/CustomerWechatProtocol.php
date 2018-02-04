<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/3/17
 * Time: 11:05
 */

namespace Network;

use Monolog\Logger;
use Server\MegaWechatServer;
use Swoole\Protocol\Base;
use Swoole\Queue\FileQueue;
use Swoole\Util\Config;
use Swoole\Util\Log;
use Wechat\Template;
use Wechat\WechatApi;
use Wechat\WechatTemplateModel;

class CustomerWechatProtocol extends Base
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
        $this->setCodecFactory(new MegaWechatCodecFactory());
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

    }

    function onShutdown(\swoole_server $server, $workerId)
    {

    }

    function onTask(\swoole_server $server, $taskId, $fromId, $data)
    {

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