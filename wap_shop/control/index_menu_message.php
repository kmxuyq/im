<?php
/**
 * 导航个人消息数
 ***/
defined('InShopNC') or exit('Access Invalid!');
class index_menu_messageControl extends Control {
	public function indexOp(){
		$message_num='0';
		$cart_num='0';
		if(isset($_SESSION["member_id"])){
			$member_id=$_SESSION["member_id"];
			//$message_num=Model()->table('message')->where(array('message_state'=>'0','to_member_id'=>$member_id))->count();
			$message_num=Model('message')->countNewMessage($member_id);
			$cart_num=Model()->table('cart')->where(array('buyer_id'=>$member_id))->count();
		}
		echo $message_num.'|'.$cart_num;
	}
}