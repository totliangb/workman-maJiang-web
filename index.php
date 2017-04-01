<?php
require_once('MaJiang.class.php');
require_once('User.class.php');


// $maJiang = new MaJiang();
// // $maJiang->getSequenceList();
// $user_1 = $maJiang->getMyListByIndex(0);
// // foreach ($user_1 as $key => $value) {
// // 	echo $value['name'].'|';
// // }
// // echo "\n";
// // $user_1 = $maJiang->sortArrByManyField($user_1,'cate',SORT_ASC,'num',SORT_ASC);
// // foreach ($user_1 as $key => $value) {
// // 	echo $value['name'].'|';
// // }
// // echo "\n";
// // exit;
// // echo '<pre>';var_dump($new_str);exit;
// $maJiang->getMyListByIndex(1);
// $maJiang->getMyListByIndex(2);
// $maJiang->getMyListByIndex(3);
// 
// 
$uid = microtime();
$group_id = 1;

$User = new User();

// 保存 当前用户的 用户信息 位置信息 更新最大位置标示数
	// 获取当前最大的位置数
	$max_position = $User->getInfo('maxPosition');
	// 判断当前用户信息是否已存储
	if ($User->hasUser($uid)) {
		// 已存储用户信息 核对信息 并 存储到本地
		$userInfo = $User->getInfo('user', $uid);
	}else{
		// 存储信息 并保存到本地
		$userInfo = array(
				'id' => $uid,
				'position' => (4 - $max_position),
				'group_id' => $group_id
			);
		$User->updateUserInfo($userInfo);
		// 更新最大位置
		$User->updateMaxPosition( ((($max_position-1) >= 0) ? ($max_position-1) : 0) );
	}