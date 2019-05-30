<?php 
/**
 * 微信中奖礼品领取选择地址
 * @author Administrator
 *
 */
class wx_present_selControl extends  BaseHomeControl{
	
	public function indexOp(){
		$openid=decrypt($_GET["code"]);
	}
}