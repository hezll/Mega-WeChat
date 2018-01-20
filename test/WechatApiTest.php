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
  "template_id": "9lPW5ofAo8QGxsp6gCfMe0ZBNbzc9zoezKhFrh9FhEA",
  "url": "http://www.haoshupeini.com/module/novel/read.php?tid=3&nid=264&cid=2642c622b58f24c870ecc2b2169c08c6713&recent=1&checkin=1",
  "data": {
    "first": {
      "value": "亲，今天你还没有签到哦~\n\n",
      "color": "#0000CD"
    },
    "keyword1": {
      "value": "\n签到领取书币，回复\"退订签到通知\"将不再接收此消息，但你可能会错过几亿免费书币",
      "color": ""
    },
   "keyword2": {
      "value": "进行中\n\n",
      "color": ""
    },
    "keyword3": {
      "value": "今晚24:00前，别忘了哦\n\n",
      "color": "#FF0000"
    },
    "remark": {
      "value": "点击下方【抢书币】\n太好看啦每天送你免费书币⤵️⤵️ ",
      "color": "#BC8F8F"
    }
  }
}');
var_dump($result);
//$result = $wechatApi->sendTemplateMessage([
//    ''
//]);
//var_dump($result);
