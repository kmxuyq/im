<?php 
class weixin_menuControl extends  BaseHomeControl{

	private $appid;
	private $appacrect;
	private $great_menu_url;
	/**
	 * 微信事件处理入口
	 */
	public function __construct(){
		parent::__construct();
		$this->appid=$GLOBALS["setting_config"]["weixin_appid"];
		$this->appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		$this->great_menu_url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';

	}
	//测试
	public function testOp(){
		$menu='{
		"button":[
		{
		"type":"view",
		"name":"微商城",
		"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri=http://m.gellefreres.com/wap&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
		},
		{
		"name":"精彩活动",
		"sub_button":[
		{
		"type":"click",
		"name":"生成专属二维码",
		"key":"send_user_code_img"
		}]
		}]
		}';
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		echo $this->great_menu_url.$token;
		//$status=curl($this->great_menu_url.$token,'post',$menu);
		echo $status;
	}
	
}
?>