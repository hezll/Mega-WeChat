<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/5/17
 * Time: 15:48
 */
include dirname(__DIR__) . "/autoload.php";
$wechatApi = new \Wechat\WechatApi('wx4b8f78b946eca353', '4aac827690c7f98eb2ac9fce50b0d48e', '4aac827690c7f98eb2ac9fce50b0d48e');
$result = $wechatApi->sendTemplateMessage('{
  "touser": "oIzphw8oUKZmr7q3v8H94wl9e92w",
  "template_id": "-xZAjLDoxQspXIY08x76D_jaFQsWBsBMe6-V3r7spVU",
  "url": "http://www.haoshupeini.com/module/novel/read.php?tid=3&nid=264&cid=2642c622b58f24c870ecc2b2169c08c6713&recent=1&checkin=1",
  "data": {
    "first": {
      "value": "新年暖心好礼，一大波书币总给你~\n畅读精彩VIP小说，点我继续看书哦！\n\n",
      "color": "#FF0000"
    },
    "keyword1": {
      "value": "新年书币礼包",
      "color": "#FF0000"
    },
    "keyword2": {
      "value": "2018-01-15",
      "color": "#0000CD"
    },
    "keyword3": {
      "value": "你关注我的每一天\n\n",
      "color": "#0000CD"
    },
    "remark": {
      "value": "感谢您对我们一如既往的支持与喜爱，快点击【详情】领取您的书币礼包，马上看小说",
      "color": "#BC8F8F"
    }
  }
}');
var_dump($result);
//$result = $wechatApi->sendTemplateMessage([
//    ''
//]);
//var_dump($result);
