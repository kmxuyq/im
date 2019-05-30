<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class speed_trainsControl extends BaseMemberControl {
    public function __construct() {
        parent::__construct();
        Tpl::setDir('trans');
        Tpl::setLayout('train_layout');
    }

    public function ticket_listOp(){
        Tpl::showpage('ticket_list','');
        exit;
    }

    public function ticket_searchOp(){
        Tpl::showpage('ticket_search');
    }
}
