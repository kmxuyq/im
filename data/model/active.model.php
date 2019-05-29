<?php
/**
 * 活动
 *
 * 
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');
class activeModel extends Model {
    public function __construct(){
        parent::__construct('active');
    }
    
    /**
     * 获取所有免费申领列表
     */
    public function getAppalyList($condition, $pagesize = '', $field = '*', $order, $limit = '', $extend = array(), $master = false){
         $appaly_list = $this->table('active')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
         if (empty($appaly_list)) return array();
         foreach ($appaly_list as $key => $value) {
             $appaly_list[$key]['address'] = Model()->table('address')->where("address_id='{$value['address_id']}'")->find();
             $appaly_list[$key]['goods'] = Model()->table('goods')->where("goods_id='{$value['goods_id']}'")->find();
             $appaly_list[$key]['member'] = Model()->table('member')->where("member_id='{$value['member_id']}'")->find();
         }
//          print_r($appaly_list);
         return $appaly_list;
    }

    /***
     * 获取免费申领详情
     */
    public function getAppaly($condition){
         
        $appaly_detail = $this->table('active')->where($condition)->find();
        if(empty($appaly_detail)) return array();
    
        $appaly_detail['goods'] = Model()->table('goods')->where("goods_id='{$appaly_detail['goods_id']}'")->find();
        $appaly_detail['address'] = Model()->table('address')->where("address_id='{$appaly_detail['address_id']}'")->find();
        $appaly_detail['member'] = Model()->table('member')->where("member_id='{$appaly_detail['member_id']}'")->find();
        return $appaly_detail;
    }
        
}