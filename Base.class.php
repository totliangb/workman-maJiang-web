<?php 

/**
* 基础类
*/

class Base
{

	
	public function __construct($params=array())
	{

	}

	/**
	 * 定 庄家位置
	 */
	public function getBanker()
	{
		# code...
	}

	/**
	 * 开局洗牌 获取牌的队列
	 */
	public function getList()
	{

	}

	/**
	 * 双次 定数  决定开始人及开始拿牌的位置
	 */
	public function getStartIndex()
	{

	}

	/**
	 * 检查牌 是否听 胡 
	 * @return [type] [description]
	 */
	public function checkCards()
	{

	}

	/**
	 * 根据拿牌顺序 获取自己的手牌列表
	 * @param  integer $ind [description]
	 * @return [type]       [description]
	 */
	public function getMyListByIndex($ind=0)
	{
		# code...
	}

	/**
	 * 保存序列
	 * @param  string $list [description]
	 * @return [type]       [description]
	 */
	public function saveList($path,$data)
	{
		if ( ! $fp = @fopen($path, 'w+'))
		{
			return FALSE;
		}

		fwrite($fp, $data);
		fclose($fp);
	}

	public function getSavedList($path)
	{
		return file_get_contents($path);
	}

	public function sortArrByManyField(){
        $args = func_get_args();
        if(empty($args)){
            return null;
        }
        $arr = array_shift($args);
        if(!is_array($arr)){
            throw new Exception("第一个参数不为数组");
        }
        foreach($args as $key => $field){
            if(is_string($field)){
                $temp = array();
                foreach($arr as $index=> $val){
                    $temp[$index] = $val[$field];
                }
                $args[$key] = $temp;
            }
        }
        $args[] = &$arr;//引用值
        call_user_func_array('array_multisort',$args);
        return array_pop($args);
    }

}