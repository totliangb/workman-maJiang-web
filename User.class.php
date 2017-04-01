<?php 

/**
* 用户类
*/

class User extends Base
{

	public $maxPosition = 4;				// 最大位置数

	public function __construct($params=array())
	{
		parent::__construct($params);

		// 确定最大位置
		// $this->save('maxPosition', $this->getInfo('maxPosition') ? $this->getInfo('maxPosition') : $this->maxPosition );
	}

	// 更新 最大位置数 并保存
	public function updateMaxPosition($value)
	{
		$this->maxPosition = $value;
		$this->save('maxPosition', $this->maxPosition);
	}

	// 更新用户信息 并保存
	// $value = array('id'=>123123,'position'=>0);
	public function updateUserInfo($value)
	{
		if ($value['id']) {
			$hasFlag = $this->hasUser($value['id']);
			$fileContent = $this->getInfo('user');
			if ($hasFlag) {
				// 获取 文件
				$fileContent[$value['id']] = $value;

			}else{
				// $fileContent = $value;
				if (!empty($fileContent)) {
					array_push($fileContent, array($value['id']=>$value));
				}else{
					$fileContent = array($value['id']=>$value);
				}
			}
			$this->save('user',$fileContent);
		}else{
			// 参数不正确
		}
	}

	// 验证用户是否存在
	public function hasUser($userId)
	{
		return $this->getInfo('user',$userId) || false;
	}

	// 获取存储的信息
	// maxPosition  或者 用户信息
	public function getInfo($type, $params='')
	{
		switch ($type) {
			case 'user':
					$filePath = './userInfo.log';
					$fileContent = json_decode(parent::getSavedList($filePath));
				break;

			case 'maxPosition':
					$filePath = './maxPosition.log';
					$fileContent = json_decode(parent::getSavedList($filePath));
				break;
		}
		if ($params) {
			$fileContent = $this->object2array($fileContent);
			$returnVal = isset($fileContent[$params]) ? $fileContent[$params] : '';
		}else{
			$returnVal = $fileContent ? $this->object2array($fileContent) : 0;
		}

		return $returnVal;
	}

	// 保存信息
	public function save($type, $value='')
	{
		$savePath = '';
		switch ($type) {
			case 'user':
					$savePath = './userInfo.log';
					$fileContent = json_encode($value);
				break;

			case 'maxPosition':
					$savePath = './maxPosition.log';
					$fileContent = json_encode($value);
				break;
			
		}
		if ($savePath) {
			parent::saveList($savePath,$fileContent);
		}else{
			// 路径不正确 无法存储
		}
		
	}

	public function array2object($array) {
	  if (is_array($array)) {
	    $obj = new StdClass();
	    foreach ($array as $key => $val){
	      $obj->$key = $val;
	    }
	  }
	  else { $obj = $array; }
	  return $obj;
	}
	public function object2array($object) {
	  if (is_object($object)) {
	    foreach ($object as $key => $value) {
	    	if (is_object($value)) {
	    		foreach ($value as $j => $val) {
	    			$array[$key][$j] = $val;
	    		}
	    	}else{
	    		$array[$key] = $value;
	    	}
	    }
	  }
	  else {
	    $array = $object;
	  }
	  return $array;
	}


}