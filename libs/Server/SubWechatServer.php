<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/3/16
 * Time: 14:49
 */

namespace Server;


use Swoole\Service\Implement\TcpServer;
use Swoole\Util\Config;
use Wechat\WechatTemplateModel;

class SubWechatServer extends TcpServer
{
    /** @var \swoole_atomic */
    public static $taskActiveNum;
    /**
     * @var \swoole_table 缓存微信模板
     */
    public static $templateTable;

    protected $processName = 'SubWechatServer';

    public $setting = [
        'open_eof_check' => false,
        'open_length_check' => true,
        'package_length_type' => 'N',
        'package_length_offset' => 0,
        'package_body_offset' => 4,
        'package_max_length' => 2465792,
    ];

    public function init()
    {

//        swoole_timer_tick(1000, function(){
//            echo "timeout\n";
//        });

    }

}