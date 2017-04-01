<?php
header("Access-Control-Allow-Origin: *");
//加载GatewayClient
require_once __DIR__.'/Gateway.php';
require_once('MaJiang.class.php');
require_once('User.class.php');
// GatewayClient 3.0.0版本开始要使用命名空间
use GatewayClient\Gateway;
// 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
Gateway::$registerAddress = '127.0.0.1:1238';
$maJiang = new MaJiang();
$User = new User();

$client_id = $_POST['client_id'];
// 假设用户已经登录，用户uid和群组id在session中
// 
$positions = array(true,ture,true,true);

$uid      = $_COOKIE["user"] ? $_COOKIE["user"] : time();
$group_id = 1;

session_start();

if (!$_COOKIE["user"]) {
	// 首次访问

	// 保存 当前用户的 用户信息 位置信息 更新最大位置标示数
	// 获取当前最大的位置数
	$max_position = $User->getInfo('maxPosition');
	if ($max_position == 0) {
		// 房间已满
		$tips = array(
            'type'=>'show',
            'msg' => '房间已满'
          );
		Gateway::sendToUid($uid, json_encode($tips));
	}else{
		// 判断当前用户信息是否已存储
		if ($User->hasUser($uid)) {
			// 已存储用户信息 核对信息 并 存储到本地
			$userInfo = $User->getInfo('user', $uid);
		}else{
			// 存储信息 并保存到本地
			$userInfo = array(
					'id' => $uid,
					'position' => 4 - $max_position,
					'group_id' => $group_id
				);
			$User->updateUserInfo($userInfo);
			// 更新最大位置
			$User->updateMaxPosition( ((($max_position-1) >= 0) ? ($max_position-1) : 0) );
		}


		setcookie("user", $userInfo['id'], time()+3600);
		setcookie("group_id", $userInfo['group_id'], time()+3600);
		setcookie("position", $userInfo['position'], time()+3600);
	}
	
}else{
	// 非首次访问
	// $_SESSION[$uid]['position'] = $_COOKIE['position'] ? $_COOKIE['position'] : 0;
	// $_SESSION[$uid.'position'] = $_COOKIE['position'] ? $_COOKIE['position'] : 0;
}



// client_id与uid绑定
Gateway::bindUid($client_id, $uid);
// 加入某个群组（可调用多次加入多个群组）
Gateway::joinGroup($client_id, $group_id);


$user_1 = $maJiang->getMyListByIndex($_COOKIE['position']);

$nowPai = $maJiang->sortArrByManyField($user_1,'cate',SORT_ASC,'num',SORT_ASC);

$clientData = array(
		'type'=>'pai',
		'paiList' => $nowPai
	);
$data = array(
            'type'=>'show',
            'msg' => '欢迎" '.$uid.' "加入[房间'.$group_id.']！'
          );

$xx = array(
            'type'=>'xxx',
            'aa' => $aa,
            'dd' => $_SESSION,
            'cc' => $_COOKIE,
          );
$message = json_encode($data);
$clientMessage = json_encode($clientData);

// 向任意uid的网站页面发送数据
Gateway::sendToUid($uid, $clientMessage);
// 向任意群组的网站页面发送数据
Gateway::sendToGroup($group_id, $message);
Gateway::sendToGroup($group_id, json_encode($xx));
