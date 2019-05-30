<?php 
class weixin_member_homeControl extends  Control{
	public function indexOp(){
		if(isset($_SESSION["member_id"])){
			setNcCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
			session_unset();
			session_destroy();
			setNcCookie('cart_goods_num','',-3600);
		}
		if(!empty($_GET["code"])&&!isset($_SESSION["member_id"])){
			$code=$_GET["code"];
			$status=Model('member')->weixin_login_handle($code);
			if($_SESSION["member_id"]!=''&&$_SESSION["is_login"]=='1'){
				redirect(BASE_SITE_URL.'/wap_shop/index.php?act=member&op=home&code='.$code);
			}else{
				redirect(BASE_SITE_URL.'/wap_shop/?act=weixin_member_home&code='.$code);
			}
		}
		if($_SESSION["member_id"]!=''){
			redirect(BASE_SITE_URL.'/wap_shop/index.php?act=member&op=home');
		}
	}
}