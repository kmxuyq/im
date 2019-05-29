<?php
/**
 * 活动人员
 */
defined ( 'InShopNC' ) or exit ( 'Access Invalid!' );
class activity_personModel extends Model {
	/**
	 * 添加
	 *
	 * @param array $input        	
	 * @return bool
	 */
	public function add($input) {
		return Db::insert ( 'activity_person', $input );
	}
	/**
	 * 更新
	 *
	 * @param array $input
	 *        	更新内容
	 * @param string $id
	 *        	活动内容id
	 * @return bool
	 */
	public function update($input, $id) {
		return Db::update ( 'activity_person', $input, 'person_id in(' . $id . ')' );
	}
	/**
	 * 根据条件更新
	 *
	 * @param array $input
	 *        	更新内容
	 * @param array $condition
	 *        	更新条件
	 * @return bool
	 */
	public function updateList($input, $condition) {
		return Db::update ( 'activity_person', $input, $this->getCondition ( $condition ) );
	}
	/**
	 * 删除
	 *
	 * @param string $id        	
	 * @return bool
	 */
	public function del($id) {
		return Db::delete ( 'activity_person', 'person_id in(' . $id . ')' );
	}
	/**
	 * 根据条件删除
	 *
	 * @param array $condition
	 *        	条件数组
	 * @return bool
	 */
	public function delList($condition) {
		return Db::delete ( 'activity_person', $this->getCondition ( $condition ) );
	}
	/**
	 * 根据条件查询活动内容信息
	 *
	 * @param array $condition
	 *        	查询条件数组
	 * @param obj $page        	
	 * @return array 二维数组
	 */
	public function getList($condition, $page = '') {
		$param = array ();
		$param ['table'] = 'activity_person';
		$param ['where'] = $this->getCondition ( $condition );
		$param ['order'] = $condition ['order'];
		return Db::select ( $param, $page );
	}
	/**
	 *
	 * @param unknown $condition        	
	 * @return multitype:
	 */
	public function getOneByCondition($condition) {
		return $this->table('activity_person')->where($condition)->find();
	}
	/**
	 * 参加人员
	 *
	 * @param unknown $condition        	
	 * @param unknown $page        	
	 */
	public function getGetJoinPersonList($condition,$page) {
		$param = array ();
		$param ['table'] = 'activity_person,member,activity';
		$param ['join_type'] = 'inner join';
		$param ['join_on'] = array (
				'activity_person.member_id=member.member_id',
				'activity_person.activity_id = activity.activity_id' 
		);
		$param ['field'] = 'activity_person.activity_id,
				            activity_person.person_id,
							activity.activity_title,
				            activity.activity_desc,
				            member.member_id,
				            member.member_name,
							member.member_avatar,
							activity_person.addtime';
		$param ['where'] = $this->getCondition ( $condition );
		$param ['order'] = $condition ['order'];
		return Db::select ( $param, $page );
	}
	/**
	 * 中奖人员
	 *
	 * @param unknown $condition        	
	 * @return Ambigous <multitype:, unknown>
	 */
	public function getGetGiftPersonList($condition, $page) {
		$param = array ();
		$param ['table'] = 'activity_person,activity_detail,activity,goods,member';
		$param ['join_type'] = 'inner join';
		$param ['join_on'] = array (
				'activity_person.detail_id = activity_detail.activity_detail_id',
				'activity_person.activity_id = activity.activity_id',
				'activity_detail.item_id=goods.goods_id',
				'activity_person.member_id=member.member_id',
		);
		$param ['field'] = 'activity_person.activity_id,
							activity_person.store_id,
							activity_detail.store_name,
							activity_person.addtime,
							activity_person.active_time,
							activity_person.az_code,
				            activity.activity_title,
				            activity.activity_desc,
				            member.member_id,
				            member.member_name,
							member.member_avatar,
							goods.goods_id,
							goods.goods_name,
							goods.goods_price,
							goods.goods_image';
		$param ['where'] = $this->getCondition($condition);		
		$param ['order'] = $condition ['order'];
		return Db::select ( $param, $page );
	}
	/**
	 * 构造查询条件
	 *
	 * @param array $condition
	 *        	查询条件数组
	 * @return string
	 */
	private function getCondition($condition) {
		$conditionStr = '';
		// person_id
		if ($condition ['person_id'] > 0) {
			$conditionStr .= " and activity_person.person_id = '{$condition['person_id']}'";
		}
		if (isset ( $condition ['person_id_in'])) {
			if ($condition ['person_id_in'] == '') {
				$conditionStr .= " and activity_person.person_id in ('')";
			} else {
				$conditionStr .= " and activity_person.person_id in ({$condition['person_id_in']})";
			}
		}
		// activity_state
		if ($condition ['activity_state'] != '') {
			$conditionStr .= " and activity_person.activity_state='" . $condition ['activity_state'] . "'";
		}
		if (isset ( $condition ['activity_state_in'] )) {
			if ($condition ['activity_state_in'] == '') {
				$conditionStr .= " and activity_person.activity_state in ('')";
			} else {
				$conditionStr .= " and activity_person.activity_state in ({$condition['activity_state_in']})";
			}
		}
		// activity_type
		if ($condition ['activity_type'] != '') {
			$conditionStr .= " and activity_person.activity_type='" . $condition ['activity_type'] . "'";
		}
		if (isset ( $condition ['activity_type_in'] )) {
			if ($condition ['activity_type_in'] == '') {
				$conditionStr .= " and activity_person.activity_type in ('')";
			} else {
				$conditionStr .= " and activity_person.activity_type in ({$condition['activity_type_in']})";
			}
		}
		if ($condition ['activity_id'] != '') {
			$conditionStr .= " and activity_person.activity_id='{$condition['activity_id']}'";
		}
		if ($condition ['store_id'] != '' ) {
			$conditionStr .= " and activity_person.store_id='{$condition['store_id']}'";
		}
		if ($condition ['member_id'] != '') {
			$conditionStr .= " and activity_person.member_id='{$condition['member_id']}'";
		}
		//
		if($condition['member_id_not_in']!=''){
			$conditionStr.="and activity_person.member_id not in (".$condition['member_id_not_in'].")";
		}
		// 活动明细
		if ($condition['detail_id'] != '' ) {
			$conditionStr .= " and activity_person.detail_id= '{$condition['detail_id']}'";
		}
		//商品组合
		if ($condition['detail_id_not_null'] != '' ) {
			$conditionStr .= " and activity_person.detail_id is not null ";
		}
		if ($condition['detail_id_is_null'] != '' ) {
			$conditionStr .= " and activity_person.detail_id is null ";
		}
		// 商品
		if ($condition ['goods_show'] != '') {
			$conditionStr .= " and goods.goods_show= '{$condition['goods_show']}'";
		}
		//
		if($condition ['activity_detail_sort']!=''){
			$conditionStr .= " and activity_detail.activity_detail_sort= '{$condition['activity_detail_sort']}'";
		}
		return $conditionStr;
	}
}