<?php
/**
 * 
 * 微信接口服务器环境验证
 * @author susu
 */
define('InShopNC', true);

class weixin_tControl extends  BaseHomeControl{

	private $appid;
	private $appacrect;
	private $send_message_url;
	private $face_path;//微信头像路径
	private $wx_img_path;//用户专属二维码图片路径
	private $wx_present_url;//礼品领取地址
	private $get_wx_token_url;//curl获取token地址
	private $subscribe_link_url;//扫描普通二维码发送信息的点击领取链接/生成专属二维码按钮页面
	private $openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';//ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';//测试用OPENID


	public function __construct(){
		parent::__construct();
		$this->appid=$GLOBALS["setting_config"]["weixin_appid"];
		$this->appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		$this->send_message_url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';
		$this->face_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/head/';
		$this->wx_img_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/'.date('Y').'/';
		$this->wx_present_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_sel';//GET参数:openid(加密的openid),lower(加密的下线人数)
		$this->subscribe_link_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_code';
		//send_user_code_img_link生成二维码链接方法
	}
	public function indexOp(){
		Tpl::showpage('weixin_t');
	}
	public function curl_testOp(){
		echo '<meta charset="utf-8"/>';
		$url=BASE_SITE_URL."/wap_shop/index.php?act=weixin&op=curl_get_test";
		$curl_shopnc=curl($url);
		$openid=$this->openid;
		echo 'openid:'.$openid.'<br/>';
		$userinfo=Model('wx_member')->get_wx_userinfo($openid);
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		echo 'curl_shopnc测试:'.$curl_shopnc.'<br/>';
		echo 'access_toke服务器获取测试：'.$token.'<br/>';
		echo '微信用户信息获取测试：';
		print_r($userinfo);
		
	}
	public function curl_baiduOp(){
		echo '<meta charset="utf-8"/>';
		echo 'curl_baidu测试：';
		echo curl('http://www.baidu.com');
	}
	public function thum_testOp(){
		echo '<meta charset="utf-8"/>';
		$nickname="susu苏";
		$openid=$this->openid;
		$face_url=$this->face_path.$openid.'_thum.jpg';
		$image_tool=new imagetool();
		$img=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/wxbg.jpg';
	
		$wx_img_path=$this->wx_img_path;
		@mkdir($wx_img_path,0777,true);//创建文件夹
		$wx_img_filename=$wx_img_path.'/'.$openid.'.jpg';//新文件路径
		copy($img, $wx_img_filename);//复制文件
	
		$water_code=BASE_SITE_URL.'/wap_shop/index.php?act=weixin&op=creater_wx_code&scene_id=6';//$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w1.jpg';
		//echo $water_code;
		//$face=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w_face.jpg';
		echo $wx_img_filename.'--'.$water_code.'--'.$face_url;
		log_result("\n wx_img_filename:".$wx_img_filename.'--'.$water_code.'--'.$face_url.'--'.$nickname, '','weixin');
		$text="以下是{$nickname}的迷之大使\n           专属二维码";
		$image_tool->wx_water_code($wx_img_filename, $water_code,0,-132);//二维码
		$image_tool->wx_water_face($wx_img_filename, $face_url,110,34);//头像
		$image_tool->wx_water_text($wx_img_filename,$text,10,'',180,53);//妮称
		echo "<img src='{$wx_img_filename}'/>";
	}
	public function file_contentOp(){
		echo '<meta charset="utf-8"/>';
		$token_url=$_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt';
		$message_url=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/message_template.php';
		$access_token=file_get_contents($token_url);
		$message=file_get_contents($message_url);
		echo "access_token获取测试：".$access_token.'<br/>';
		$status=file_put_contents($token_url, $access_token);
		echo "access_token写入测试：".$status.'<br/>';
		//-----------
		$wx_message_template=Model('wx_message_template');
		$list=$wx_message_template->select();
		foreach($list as $v){
			$new_list[$v["id"]]=str_replace("\n", "<br/>", $v["d_content"]) ;
		}
		//生成缓存文件
		echo '写入message_template.php测试：';	
		echo $this->great_array_file($new_list,'message_template.php');
	}
	public function send_messageOp(){
		echo '<meta charset="utf-8"/>';
		echo $this->send_message_text($this->openid,'测试一>--&gt;下'.date('H:i:s'));
	}
	public function show_logOp(){
		$file_name = md5('dxj.1301'.date('Y-m-d')).date('Y-m-d').'.log';
		$path=$_SERVER["DOCUMENT_ROOT"].'/data/log/weixin/'.$file_name;
		echo file_get_contents($path);
	}
	//-------------------------------
	/**
	 * 传入数组生成配置文件
	 * @param $arr_data传入数组
	 * @param unknown_type $要写入的文件夹名称$_SERVER["DOCUMENT_ROOT"].'/data/'.$path
	 */
	private function great_array_file($arr_data,$filename, $path='weixin') {
		$arr_data = "<?php \n\t return ".var_export($arr_data, true).';';
		if (file_put_contents($_SERVER["DOCUMENT_ROOT"].'/data/'.$path.'/'.$filename, $arr_data)) {
			return "写入成功";
		} else {
			return '文件写入失败';
		}
	}
	private function write_file($arr_data,$filename, $path='weixin'){
		$filename=$_SERVER["DOCUMENT_ROOT"].'/data/'.$path.'/'.$filename;
		$fp        = fopen($filename, "w");
		flock($fp, LOCK_EX);
		fwrite($fp, $arr_data);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	/**
	 * 微信发送文本消息
	 * @param $openid
	 * @param  $text发送的文字内容
	 * @return mixed
	 */
	private function send_message_text($openid,$text){
		$text=str_replace('"', "'", $text);
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);//Model('wx_member')->get_token($this->appid,$this->appacrect);
		$url=$this->send_message_url.$token;
	
		$content='{
		"touser":"'.$openid.'",
		"msgtype":"text",
		"text":
		{
		"content":"'.$text.'"
	}
	}';
		log_result($url."\n\n".$content, '','weixin');
		$status=curl($url,'post',$content);
		return $status;
	}
}