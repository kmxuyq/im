<?php
defined ( 'InShopNC' ) or exit ( 'Access Invalid!' );
class testuControl extends  Control{
	/* public function __construct() {
		parent::__construct();
		Language::read('home_cart_index');
	}*/
        public function delsOp() {
                Model('active')->where("1")->delete();
                Model('wx_member')->where("1")->delete();
                Model('wx_present_member')->where("1")->delete();
                Model('member')->where("member_id>100")->delete();
                echo '<title>清除微信用户</title><p>success!</p>';
        }
}
