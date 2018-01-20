<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/5/17
 * Time: 15:48
 */
include dirname(__DIR__) . "/autoload.php";
$wechatApi = new \Wechat\WechatApi('wx4b8f78b946eca353', '4aac827690c7f98eb2ac9fce50b0d48e', '4aac827690c7f98eb2ac9fce50b0d48e');
$result = $wechatApi->sendCustomerMessage('{
    "kf_account": "kf2002@gh_26a30bfcfb6e",
    "touser": "oIzphw8oUKZmr7q3v8H94wl9e92w", 
    "msgtype": "news", 
    "news": {
        "articles": [
            {
                "title": "别打游戏了！赶紧干活！", 
                "description": "Is Really A Happy Day", 
                "url": "http://www.haoshupeini.com/module/novel/read.php?tid=3&nid=27&cid=275f17d5325779de13a1d145ef32ffd5d8&recent=1", 
                "picurl": "http://wx.qlogo.cn/mmopen/vi_32/XhaeiaZeHKFmSsoXiaq7fo1adOz86ZD6k0Liac8elnyswbtPTBHvJ9icgztJ9TP7nSArFpYl7K3j2k7N5LWz1eiaSvw/0"
            }, 
            {
                "title": "最近阅读", 
                "description": "最近阅读", 
                "url": "http://www.haoshupeini.com/module/user/read.php?module=novel&page=1"
            }, 
            {
                "title": "书城首页", 
                "description": "书城首页", 
                "url": "http://www.haoshupeini.com/"
            }
        ]
    }
}');
var_dump($result);
//$result = $wechatApi->sendTemplateMessage([
//    ''
//]);
//var_dump($result);
