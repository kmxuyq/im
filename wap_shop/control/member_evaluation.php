<?php
/**
 * 我的评价
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class member_evaluationControl extends BaseMemberControl{

    public function __construct() {

        parent::__construct() ;

        /**
         * 读取语言包
         */
        Language::read('member_layout,member_evaluation');

        //定义状态常量
    }

    /*
     * 默认显示我的评价页面
     */
    public function indexOp() {
        $this->evaluate_goods_listOp() ;
    }

    /*
     * 获取当前用户的评价列表
     */
    public function evaluate_goods_listOp() {
        /*
         * 得到当前用户的评价列表
         */
        $model_evaluate_goods = Model('evaluate_goods');
        $condition = array();
        $condition['geval_frommemberid'] = $_SESSION['member_id'];
        $evaluate_goods = $model_evaluate_goods->getEvaluateGoodsList($condition);
        self::profile_menu('member','member');
        Tpl::output('list', $evaluate_goods);
        Tpl::showpage('member_evaluation.list','',0);
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array     = array();
        switch ($menu_type) {
            case 'member':
                $menu_array = array(
                1=>array('menu_key'=>'member',  'menu_name'=>Language::get('home_member_base_infomation'),'menu_url'=>'index.php?act=member_information&op=member'),
                2=>array('menu_key'=>'more',    'menu_name'=>Language::get('home_member_more'),'menu_url'=>'index.php?act=member_information&op=more'),
                5=>array('menu_key'=>'avatar',  'menu_name'=>Language::get('home_member_modify_avatar'),'menu_url'=>'index.php?act=member_information&op=avatar'));
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}