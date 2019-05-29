<?php
/**
 * 活动细节
 *
 * 
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');
class activity_detailModel extends Model{
	/**
	 * 添加
	 *
	 * @param array $input 
	 * @return bool
	 */
	public function add($input){
		return Db::insert('activity_detail',$input);
	}
	/**
	 * 更新
	 *
	 * @param array $input 更新内容
	 * @param string $id 活动内容id
	 * @return bool
	 */
	public function update($input,$id){
		return Db::update('activity_detail',$input,'activity_detail_id in('.$id.')');
	}
	/**
	 * 根据条件更新
	 *
	 * @param array $input 更新内容
	 * @param array $condition 更新条件
	 * @return bool
	 */
	public function updateList($input,$condition){
		return Db::update('activity_detail',$input,$this->getCondition($condition));
	}
	/**
	 * 删除
	 *
	 * @param string $id
	 * @return bool
	 */
	public function del($id){
		return Db::delete('activity_detail','activity_detail_id in('.$id.')');
	}
	/**
	 * 根据条件删除
	 *
	 * @param array $condition 条件数组
	 * @return bool
	 */
	public function delList($condition){
		return Db::delete('activity_detail',$this->getCondition($condition));
	}
	/**
	 * 根据条件查找单条记录
	 * @param unknown $condition
	 */
	public function getDetail($condition){
		return  $this->table('activity_detail')->where($condition)->find();
	}
	
	/**
	 * 根据条件查询活动内容信息
	 *
	 * @param array $condition 查询条件数组
	 * @param obj $page	分页对象
	 * @return array 二维数组
	 */
	public function getList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity_detail';
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	/**
	 * 根据条件查询活动商品内容信息
	 *
	 * @param array $condition 查询条件数组
	 * @param obj $page	分页对象
	 * @return array 二维数组
	 */
	public function getGoodsJoinList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity_detail,goods';
		$param['join_type']	= 'inner join';
		$param['field']	= 'activity_detail.*,goods.*';
		$param['join_on'] = array('activity_detail.item_id=goods.goods_id');
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	/**
	 * 查询活动商品信息
	 * @param array $condition 查询条件数组
	 * @param obj $page	分页对象
	 * @return array 二维数组
	 */
	public function getGoodsList($condition,$page=''){
		$param	= array();
		$param['table']	   = 'activity_detail,goods';
		$param['join_type']	= 'inner join';
		//modify 增加 activity_detail.activity_detail_id  goods.store_name
		$param['field']	= 'activity_detail.activity_detail_id,activity_detail.activity_detail_sort,goods.goods_id,goods.store_id, goods.store_name,goods.goods_name,goods.goods_price,goods.goods_image';
		$param['join_on']	= array('activity_detail.item_id=goods.goods_id');
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	
	/**
	 * 得到对应的detail信息，与detail_person, goods 关联查询
	 * 查询得到中奖的信息
	 * @param unknown $condition
	 */
	public function getDetailAndDetailPerson($condition){
		$model = Model();
		//内链接查询activity_detail和goods,然后左链接activity_person
		$field = 'goods.goods_id,goods.goods_name,goods.goods_image,goods.goods_price,
 				  activity_detail.activity_detail_id,activity_detail.activity_detail_sort,
				  activity_detail.activity_id,activity_person.member_id';
		$on    = 'activity_detail.item_id=goods.goods_id,activity_detail.activity_detail_id=activity_person.detail_id';
		$order = 'activity_detail.activity_id desc, activity_detail.activity_detail_sort';
		$model->table('activity_detail,goods,activity_person')->field($field);
		return $model->join('inner,left')->on($on)->where($condition)->order($order)->select();
	}
	/**
	 * 
	 * @param unknown $condition
	 */
	public function getGoodsListByGroup($condition){
		$model = Model();
		//内链接查询activity_detail和goods,然后左链接activity_person
		//activity_detail.activity_detail_id,
		$field = 'activity_detail.activity_detail_sort,
				  activity_detail.activity_id,
				  goods.goods_id,
				  goods.store_id,
				  goods.store_name,
				  goods.goods_name,
				  goods.goods_price,
				  goods.goods_image';
		$on    = 'activity_detail.item_id=goods.goods_id';
		$order = 'activity_detail.activity_detail_sort';
		$model->table('activity_detail,goods')->field($field);
		return $model->join('inner')->on($on)->where($condition)->order($order)->group($field)->select();
	}
	
	/**
	 * 构造查询条件
	 *
	 * @param array $condition 查询条件数组
	 * @return string
	 */
	private function getCondition($condition){
		$conditionStr	= '';
		if($condition['activity_id']>0){
			$conditionStr	.= " and activity_detail.activity_id = '{$condition['activity_id']}'";
		}
		if(isset($condition['activity_detail_id'])){
			$conditionStr	.= " and activity_detail.activity_detail_id  = '".$condition['activity_detail_id']."'";
		}
		if (isset($condition['activity_detail_id_in'])){
			if ($condition['activity_detail_id_in'] == ''){
				$conditionStr	.= " and activity_detail.activity_detail_id in ('')";
			}else{
				$conditionStr	.= " and activity_detail.activity_detail_id in ({$condition['activity_detail_id_in']})";
			}
		}
		
		if(isset($condition['activity_detail_id_not_in'])){
			if ($condition['activity_detail_id_not_in'] == ''){
				$conditionStr	.= " and activity_detail.activity_detail_id not in ('')";
			}else{
				$conditionStr	.= " and activity_detail.activity_detail_id not in ({$condition['activity_detail_id_not_in']})";
			}
		}
		
		if(isset($condition['activity_detail_state_in'])){
			if ($condition['activity_detail_state_in'] == ''){
				$conditionStr	.= " and activity_detail.activity_detail_state in ('')";
			}else{
				$conditionStr	.= " and activity_detail.activity_detail_state in ({$condition['activity_detail_state_in']})";
			}
		}
		if($condition['activity_detail_state'] != ''){
			$conditionStr	.= " and activity_detail.activity_detail_state='".$condition['activity_detail_state']."'";
		}
		if($condition['activity_detail_sort']!=''){
			$conditionStr	.= " and activity_detail.activity_detail_sort='".$condition['activity_detail_sort']."'";
		}
		if($condition['gc_id'] != ''){
		$conditionStr	.= " and goods.gc_id='{$condition['gc_id']}'";
		}
		if($condition['brand_id'] != ''){
			$conditionStr	.= " and goods.brand_id='{$condition['brand_id']}' ";
		}
		if($condition['name'] != ''){
			$conditionStr	.= " and goods.goods_name like '%{$condition['name']}%'";
		}
		if(intval($condition['item_id'])>0){
			$conditionStr	.= " and activity_detail.item_id='".intval($condition['item_id'])."'";
		}
		if($condition['item_name'] != ''){
			$conditionStr	.= " and activity_detail.item_name like '%{$condition['item_name']}%'";
		}
		if(intval($condition['store_id'])>0){
			$conditionStr	.= " and activity_detail.store_id='".intval($condition['store_id'])."'";
		}
		if($condition['store_name'] != ''){
			$conditionStr	.= " and activity_detail.store_name like '%{$condition['store_name']}%'";
		}
		if ($condition_array['goods_show'] != '') {
			$condition_sql	.= " and goods.goods_show= '{$condition_array['goods_show']}'";
		}
		return $conditionStr;
	}
}