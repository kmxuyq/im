<?php
/**
 * 发货
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class weixin_blogControl extends BaseHomeControl {
	public function indexOp(){
		$appid=$GLOBALS["setting_config"]["weixin_appid"];
		$appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		$jssdk = new JSSDK($appid, $appacrect);
		$signPackage = $jssdk->GetSignPackage();
		Tpl::output('signPackage',$signPackage);
		if ($this->is_weixin()) {
			
			$web_url="http://m.gellefreres.com/wap_shop/?act=weixin_blog";
			if(empty($_GET["openid"])){
				if(empty($_GET["code"])){
					$url2="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$web_url}&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
					redirect($url2);
				}elseif(!empty($_GET["code"])){
					$code=$_GET["code"];					
					$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appacrect}&code={$code}&grant_type=authorization_code";
					$info=curl($url);
					$info_arr=json_decode($info,true);
					$openid=$info_arr["openid"];
					$userinfo=Model('wx_member')->get_wx_userinfo($openid);
					$nickname=$userinfo["nickname"];
					$url2=$web_url."&openid={$openid}&nickname={$nickname}";
					redirect($url2);
				}
			}else{
				
				Tpl::showpage('wx_blog_tangyun','null_layout');
			}
		}
		Tpl::showpage('wx_blog_tangyun','null_layout');
	}
	private function is_weixin()
	{
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
}