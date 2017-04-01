<?php
//加载GatewayClient
require_once __DIR__.'/Gateway.php';
// GatewayClient 3.0.0版本开始要使用命名空间
use GatewayClient\Gateway;
// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
Gateway::$registerAddress = '127.0.0.1:1238';
$uid      = 22;
$group_id = 1;

$data = array(
            'type'=>'show',
            'msg' => 'hello'
          );
$message = json_encode($data);

// 向任意uid的网站页面发送数据
Gateway::sendToUid($uid, $message);
// 向任意群组的网站页面发送数据
Gateway::sendToGroup($group_id, $message);