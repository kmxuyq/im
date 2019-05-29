<?php
/**
 * 活动规则
 */
defined ( 'InShopNC' ) or exit ( 'Access Invalid!' );
class activity_ruleModel extends Model {
	public function __construct() {
		parent::__construct();
	}
	/**
	 * 通过 $condition 得到规则
	 *
	 * @param unknown $condition        	
	 * @return unknown
	 */
	public function getRuleByCondtion($condition) {
		$fields = 'activity_rule_id,activity_id,store_id,store_name,is_check,is_collect,is_buy,activity_time,activity_desc';
		$return = $this->table ( 'activity_rule' )->field ( $fields )->where ( $condition )->find ();
		if (is_array ( $return )) {
			return $return;
		} else {
			return mysql_error ();
		}
	}
	/**
	 *
	 * @param unknown $data        	
	 * @return boolean
	 */
	public function addRule($data) {
		$return = $this->table('activity_rule')->insert($data);
		if ($return) {
			return array (
					'state' => TRUE,
					'msg' => '成功' 
			);
		} else {
			return array (
					'state' => FALSE,
					'msg' => mysql_errno () 
			);
		}
	}
	/**
	 * 
	 * @param unknown $data
	 * @param unknown $condition
	 * @return multitype:boolean string |multitype:boolean number
	 */
	public function updateRule($data,$condition){
		$return  = $this->table ('activity_rule')->where($condition)->update($data);
		if ($return) {
			return array (
					'state' => TRUE,
					'msg' => '成功'
			);
		} else {
			return array (
					'state' => FALSE,
					'msg' => mysql_errno ()
			);
		}
	}
	
	public function getStoreRuleByCondition($condition) {
		$model = Model();
		// 内链接查询member和store表
		$field = 'DISTINCT(activity_rule.store_id) as store_id,activity_rule.is_check as is_check ,activity_rule.is_collect as is_collect,activity_rule.is_buy as is_buy, activity_rule.activity_time';
		$on = 'activity_rule.activity_id = activity_detail.activity_id';
		$return = $model->table('activity_rule,activity_detail')->join ( 'inner' )->on ( $on )->field ( $field )->where ( $condition )->select ();
		return $return;
	}
}
?>