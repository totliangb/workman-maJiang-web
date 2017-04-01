<?php 
require_once('Base.class.php');
/**
* 
*/
class MaJiang extends Base
{
	public $cnNum;				// 中文数字
	public $bankerNum;			// 庄家标示
	public $listCate;			// 总牌种类
	public $listAll = array();			// 总牌种类
	public $listCateName;		// 种类名称
	public $waitPaiIndex;			// 当前等待中的牌标示
	public $waitUserIndex;			// 当前等待用户标示


	public function __construct($params=array())
	{
		parent::__construct($params);
		// 玩家标示 0 1 2 3
		
		$this->cnNum = array(
				'1' => '一',
				'2' => '二',
				'3' => '三',
				'4' => '四',
				'5' => '五',
				'6' => '六',
				'7' => '七',
				'8' => '八',
				'9' => '九',
			);
		$this->listCate = array(
				'W',
				'O',
				'I',
				'H'
			);

		$this->listCateName = array(
				'W' => '万',
				'O' => '筒',
				'I' => '条',
				'H-1' => '中',
				'H-2' => '发',
				'H-3' => '白',
				'H-4' => '东',
				'H-5' => '西',
				'H-6' => '南',
				'H-7' => '北',
			);
		if (!parent::getSavedList('./listLog.log')) {
			$this->getList();
			$this->getSequenceList();
		}

		$this->runPaiIndex = (16)*3+5;
		$this->waitUserIndex = 1;
	}

	/**
	 * 获取总牌
	 */
	public function getList()
	{
		foreach ($this->listCate as $key => $value) {
			$itemNum = ($value == 'H') ? 8 : 10;
			for ($i=1; $i < $itemNum; $i++) { 
				for ($j=0; $j < 4; $j++) { 
					// 牌的属性
					$item['id'] = $value.'-'.$i.'-'.$j;
					$item['cate'] = $value;
					$item['num'] = $i;
					$item['cate_num_index'] = $j;
					$nameIndex = ($value == 'H') ? $value.'-'.$i : $value;
					$item['name'] = ($value == 'H') ? '' : $this->cnNum[$i];
					$item['name'] .= isset($this->listCateName[$nameIndex]) ? $this->listCateName[$nameIndex] : '';
					array_push($this->listAll,$item);
				}
			}
		}

	}

	/**
	 * 定 庄家位置
	 */
	public function getBanker()
	{
		// 随机数 0-3
		$this->bankerNum = rand(0,3);
		return $this->bankerNum;
	}

	/**
	 * 开局洗牌 获取牌的队列
	 */
	public function getSequenceList()
	{
		// 总牌数 136
		shuffle($this->listAll);
		$newListAll = serialize($this->listAll);

		// 保存牌的序列
		parent::saveList('./listLog.log',$newListAll);
	}

	/**
	 * 双次 定数  决定开始人及开始拿牌的位置
	 */
	public function getStartIndex()
	{
		// 暂时为空 从起点开始拿
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
		$z = $ind; // 开始的位置

		$handList = array();
		for ($i=0; $i < 3; $i++) { 
			$groupStartIndex = ($z*4) + ($i*8);
			$groupEndIndex = ($z*4)+3 + ($i*8);

			for ($j=0; $j < ($groupEndIndex - $groupStartIndex); $j++) { 
				array_push($handList, $groupStartIndex+$j);
			}
			array_push($handList, $groupEndIndex);
		}

		array_push($handList,(($z*4)+16)*3);

		if ($ind==0) {
			array_push($handList, (($z*4)+16)*3+4);
		}

		$list = parent::getSavedList('./listLog.log');
		$list = unserialize($list);

		foreach ($handList as $key => $value) {
			$handList[$key] = $list[$value];
		}

		return $handList;

	}

}