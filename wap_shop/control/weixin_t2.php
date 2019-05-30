<?php
/**
 *
 * 微信接口服务 测试文件
 * @author susu
 */
define('InShopNC', true);

class weixin_t2Control extends  BaseHomeControl{
	private $appid;
	private $appacrect;
	private $send_message_url;
	private $face_path;//微信头像路径
	private $wx_img_path;//用户专属二维码图片路径
	private $wx_present_url;//礼品领取地址
	private $wx_voucher_url;//礼券领取地址（只有礼券没有商品）
	private $get_wx_token_url;//curl获取token地址
	private $subscribe_link_url;//扫描普通二维码发送信息的点击领取链接/生成专属二维码按钮页面
	private $openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';//测试用OPENID
	private $wx_password='123456';//自动注册默认密码
	private $wx_email='admin@admin.com';//自动注册默订邮箱
	
	public function __construct(){
		parent::__construct();
		$this->appid=$GLOBALS["setting_config"]["weixin_appid"];
		$this->appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		$this->send_message_url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';
		$this->face_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/head/';
		$this->wx_img_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/'.date('Y').'/';
		$this->wx_present_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_sel';//GET参数:openid(加密的openid),lower(加密的下线人数)
		$this->wx_voucher_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_voucher_sel';//GET参数:openid(加密的openid),lower(加密的下线人数)
		$this->subscribe_link_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_code';
		//send_user_code_img_link生成二维码链接方法
	}
	public function wx_img_testOp(){
		$openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';
		$scene_id='4';
		//$face_url='http://wx.qlogo.cn/mmopen/oPM8qm1ZTKobh3xER5pLdGtVCf3ZDvRveWutFHrOvZAr4GWKBAPOSFFotLcluTy9ghj2D7prnqSDxgfb0eJYicL1ErPTbRrEib/0';
		
		$nickname='遗忘s';
		/* $face_url=$this->face_path.$openid.'_thum.jpg';
		
		$image_tool=new imagetool();		
		$img=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/wxbg.jpg';
		$wx_img_path=$this->wx_img_path;
		@mkdir($wx_img_path,0777,true);//创建文件夹
		$wx_img_filename=$wx_img_path.'/'.$openid.'.jpg';//新文件路径
		copy($img, $wx_img_filename);//复制文件
		
		$water_code=BASE_SITE_URL.'/wap_shop/index.php?act=weixin&op=creater_wx_code&scene_id='.$scene_id;//$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w1.jpg';
		echo $water_code;
		//$face=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w_face.jpg';
		$image_tool->wx_water_code($wx_img_filename, $water_code);//二维码
		$image_tool->wx_water_face($wx_img_filename, $face_url);//头像
		$image_tool->wx_water_text($wx_img_filename,$nickname,12,'',82,120);//妮称 */
		$wx_img_filename=$this->creater_wx_image($openid,$nickname,$scene_id);
		echo $wx_img_filename;
	}
	public function wx_face_testOp(){
		$face_url='http://wx.qlogo.cn/mmopen/oPM8qm1ZTKobh3xER5pLdGtVCf3ZDvRveWutFHrOvZAr4GWKBAPOSFFotLcluTy9ghj2D7prnqSDxgfb0eJYicL1ErPTbRrEib/0';
		$openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';
		//下载头像到本地
		$face_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/head/';
		@mkdir($face_path,0777,true);//创建文件夹
		$this->put_file_from_url_content($face_url, $openid.'.jpg', $face_path);
		$image_resize=new ImageResize();
		$local_img=$this->face_path.$openid.'.jpg';
		$dst_img=$this->face_path.$openid.'_thum.jpg';
		$image_resize->resize($local_img, $dst_img, 70, 70);//生成头像缩略图
		//--------------
	}
	/**
	 * 微信发送图片消息
	 */
	public function send_message_image_testOp(){
		$openid='o-lvAv79zRSFk76sdexL3MC3rn6w';
		$nickname='叶落归根';
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		ECHO $token;exit();
		$image_file=$this->creater_wx_image($openid, $nickname,4);
		//$image_file=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/2015-12/ocuxlxEkoyzYVxnVoj3Dc3AS5xgw.jpg';
		echo $image_file.'<br/>';
		 $media_id=$this->up_wx_image($image_file);
		echo $media_id.'<br/>';
		 
		$url=$this->send_message_url.$token;
		echo $url.'<br/>';
		$content='{
		"touser":"'.$openid.'",
		"msgtype":"image",
		"image":
		{
		"media_id":"'.$media_id.'"
	}
	}';
		echo $content;
		$status=curl($url,'post',$content);
		echo $status; 
	
	}
	/**
	 * 获取微信access_token
	 */
	public function get_wx_accesstoken_testOp(){
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appacrect;
		$token_file=$_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt';
		echo $token_file."<br/>".filemtime($token_file)."<br/>";
		echo (filemtime($token_file)+6000).'--<br/>'.time();
		
		
		if(!file_exists($token_file)||(filemtime($token_file)+6000)<time()){
			//token文件不存在或都文件过期
			//echo $url.'<br/>';
			$token_str=curl($url);	
			$token_arr=json_decode($token_str);
			if(($token_arr->access_token)!=''){
				file_put_contents($token_file, $token_arr->access_token);
			}
		}
		$token=file_get_contents($token_file);
		//判断token是否失效
		
		$getip_url="https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=";
		echo $getip_url.$token;
		$ip_json=curl($getip_url.$token);
		$ip_json_arr=json_decode($ip_json,true);
		if($ip_json_arr["errcode"]=='40001'||$ip_json_arr["errcode"]=='41001'){
			//如果失效
			$token_str=curl($url);
			$token_arr=json_decode($token_str);
			if(($token_arr->access_token)!=''){
				file_put_contents($token_file, $token_arr->access_token);
			}
		}
		//print_r($ip_json_arr);
		$token=file_get_contents($token_file);
		echo "<br/>".$token;
	}
	public function up_wx_imagetestOp(){
		$openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';
		$image_file=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/wxbg.jpg';
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		echo $token."<br/>";
		$url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";
		$media_id_res=$this->upload_curl_pic($url, $image_file);
		$media_id_arr=json_decode($media_id_res);
		echo $media_id_arr->media_id;
		//echo $this->send_message_image($openid, $media_id_arr->media_id);
		
	}
	/**
	 * 用户关注后自动生成用户专属二维码
	 */
	public function creater_wx_user_code_img_testOp(){
		$openid='o-lvAv79zRSFk76sdexL3MC3rn6w';
		
		$tt=$this->send_user_code_img_index($openid);
		print_r($tt);exit();
		$nickname='叶落归根';
		$scene_id=4;
		$code_path=$this->wx_img_path;
		@mkdir($code_path,0777,true);
		$code_filename=$code_path.$openid.'.jpg';
		//二维码图片三天有效期
		$media_id='';
		if(!file_exists($code_filename||(filemtime($code_filename)+86400*3)<=time())){
			//生成图片
			$image_file=$this->creater_wx_image($openid, $nickname, $scene_id);
			echo $image_file.'<br/>';
			if($image_file!=''){
				//生成media_id
				$media_id=$this->up_wx_image($image_file);
				if($media_id!=''){
					Model('wx_member')->where(array('openid'=>$openid))->update(array('media_id'=>$media_id));
				}
				echo $media_id;
			}
		}
		//-----------
		//如果media_id获取失败
		if($media_id==''){
			//生成图片
			$image_file=$this->creater_wx_image($openid, $nickname, $scene_id);
			echo $image_file.'--<br/>';
			if($image_file!=''){
				//生成media_id
				$media_id=$this->up_wx_image($image_file);
				if($media_id!=''){
					Model('wx_member')->where(array('openid'=>$openid))->update(array('media_id'=>$media_id));
				}
				echo $media_id;
			}
		}
		echo $media_id;
		echo $this->send_message_image($openid, $media_id);
	}
	public function send_text_testOp(){
		$wx_member=Model('wx_member');
		$eventkey=4;
		$lower_member=$wx_member->where(array('id'=>intval($eventkey)))->field('lower_member,openid')->find();
		$lower_member_openid=$lower_member["openid"];//上一级用户的openid
		
		$lower_num=count(explode(',', @substr($lower_member,0,-1)));
		$new_lower_num=$this->send_active_present($lower_num);
		if(!empty($new_lower_num)){
			//信息发给上一级的openid
			//如果符合中奖条件则发送中奖消息，传替下线人数(加密)--发送中奖消息
			$wx_present_sel_url=$this->wx_present_url.'&openid='.encrypt($lower_member_openid);
			$send_text="恭喜，你的下线关注者已达到{$new_lower_num}人，获取赠送护肤品试用一份，点击<a href='{$wx_present_sel_url}'>免费领取!</a>";
			$this->send_message_text($lower_member_openid, $send_text);
		}else{
			$send_text="恭喜，你的下线关注者已达到{$lower_num}人，关注达5人以上即可获赠免费的护肤礼品一份，继续加油哦!";
			$this->send_message_text($lower_member_openid, $send_text);
		}
	}
	
	/**
	 *下线人数到达一定时发送中奖消息
	 *如果符合中奖条件的话就返回下线人数（加密）
	 * @param  $lower_num
	 */
	public function send_active_present_testOp(){
		$lower_num=6;
		$wx_active_role=Model('wx_active_rule');
		$list=$wx_active_role->order('lower_num desc')->select();
		print_r($list);
		foreach($list as $v){
			if($lower_num>=$v["lower_num"]){
				echo $lower_num.'---'.$v["lower_num"];
				//return $lower_num;
				exit();
			}
		}
	}
	/**
	 * 点击公众号菜单生成专属二维码
	 */
	public function send_user_code_img_testOp(){
		/* $openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';//$_POST["FromUserName"];
		$userinfo=Model('wx_member')->where(array('openid'=>$openid))->field('nickname,id')->find();
		print_r($userinfo);
		$status=$this->send_user_code_img($openid, $userinfo["nickname"], $userinfo["id"]);
		echo $status; */
		
	}
	public function tokenOp(){
		echo Model('wx_member')->get_token($this->appid,$this->appacrect);
	}
	public function writer_testOp(){
		echo 'fff';
		if(!empty($_POST)){
			log_result( json_encode($_POST), '','weixin');
		}
		echo json_encode($_POST);
	}
	

	public function thum_testOp(){
		$nickname="susu苏";
		$openid=$this->openid;
		$face_url=$this->face_path.$openid.'_thum.jpg';
		$image_tool=new imagetool();
		$img=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/wxbg.jpg';
		
		$wx_img_path=$this->wx_img_path;
		@mkdir($wx_img_path,0777,true);//创建文件夹
		$wx_img_filename=$wx_img_path.'/'.$openid.'.jpg';//新文件路径
		copy($img, $wx_img_filename);//复制文件
		
		$water_code=BASE_SITE_URL.'/wap_shop/index.php?act=weixin&op=creater_wx_code&scene_id=9000000000';//$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w1.jpg';
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
	public function test2Op(){
		$message_template=include $_SERVER["DOCUMENT_ROOT"].'/data/weixin/message_template.php';
		$fromusername='ocuxlxJuw5O2gKs2uoo5eM7Q_1pM';//417
/* 		$eventkey='222';
		$wx_member=Model('wx_member');
		$is_pid_count=$wx_member->where("openid='{$fromusername}' and source_type=2")->count('id');
		echo ($is_pid_count);
		if($is_pid_count>0){
			$is_pid_res=$wx_member->where("openid='{$fromusername}' and source_type=2")->field('pid')->find();
			print_r($is_pid_res);
			//log_result('pid:'.$is_pid_res["pid"].'--eventkey:'.$eventkey, '','weixin');
			
			if($is_pid_res["pid"]!=$eventkey){
				echo '!='.$is_pid_res["pid"].'---'.$eventkey;
				$send_text='欢迎关注！';//发送欢迎关注消息
				$this->send_message_text($fromusername, $send_text);
				exit();
			}
		}
		echo '==sadfasfasfasfda'; */
		exit();
		//-------------------------------
		
		$wx_member=Model('wx_member');
		$eventkey='21';
	
		$lower_member=$wx_member->where(array('id'=>intval($eventkey)))->field('nickname,member_id,lower_member,openid')->find();
		$lower_member_openid=$lower_member["openid"];//上一级用户的openid;
		//如果此上线用户存在
		if(!empty($lower_member)){

			//log_result("\n\n weixin:lower_memberjson:".json_encode($lower_member).'--'.$lower_member_openid.'--'.$fromusername, '');
			if($lower_member_openid!=$fromusername){
				$lower_num=count(explode(',', substr($lower_member["lower_member"],0,-1)));
				$new_lower_num_arr=$this->send_active_present($lower_num);
				$new_lower_num=$new_lower_num_arr["lower_num"];
				print_r($new_lower_num_arr);
				echo "weixin:lower_num:".$lower_num.'--'.$new_lower_num;//
				if(!empty($new_lower_num)){
					$wx_present_member=Model('wx_present_member');
					//信息发给上一级的openid
					//如果符合中奖条件则发送中奖消息，传替下线人数(加密)--发送中奖消息
					$is_present_count=$wx_present_member->where(array('openid'=>$lower_member_openid,'lower_num'=>$new_lower_num))->count('id');
					if($is_present_count=='0'){
						$voucher_id=0;
						$voucher_num=0;
						//判断是否有代金券，有则插入代金券表
						//log_result('weixin:new_lower_num_arr'.json_encode($new_lower_num_arr)."\n voucher_t_id:{$new_lower_num_arr["voucher_t_id"]}", '','weixin');
						if(!empty($new_lower_num_arr["voucher_t_id"])){
							$voucher_t_id=$new_lower_num_arr["voucher_t_id"];
							$voucher_t_res=Model()->table('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
							$voucher_time=$new_lower_num_arr["voucher_days"]*86400+time();//代金券有效期
							$voucher_num=$new_lower_num_arr["voucher_num"];
							$voucher=Model()->table('voucher');
							for($i=0;$i<$voucher_num;$i++){
								$voucher_d["voucher_code"]='0';
								$voucher_d["voucher_t_id"]=$voucher_t_id;
								$voucher_d["voucher_title"]=$voucher_t_res["voucher_t_title"];
								$voucher_d["voucher_desc"]=$voucher_t_res["voucher_t_desc"];
								$voucher_d["voucher_start_date"]=time();
								$voucher_d["voucher_end_date"]=$voucher_time;
								$voucher_d["voucher_price"]=$voucher_t_res["voucher_t_price"];
								$voucher_d["voucher_limit"]=$voucher_t_res["voucher_t_limit"];
								$voucher_d["voucher_store_id"]=$voucher_t_res["voucher_t_store_id"];
								$voucher_d["voucher_state"]=$voucher_t_res["voucher_t_state"];
								$voucher_d["voucher_active_date"]=time();
								$voucher_d["voucher_type"]='0';
								$voucher_d["voucher_owner_id"]='144';
								$voucher_d["voucher_owner_name"]='紫杉';
								$voucher_d["voucher_order_id"]='0';
								//log_result(json_encode($voucher_d),'','weixin');
								$voucher_id=$voucher->insert($voucher_d);//插入表
								$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
								$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
							}
		
						}
						//插入礼品表，（只要满有礼品或礼券就插入记录），当进入商品页选择完商品后更新goods_id字段和status字段为1,url参数为id(礼品表ID[加密])和openid(加密)
						if(!empty($new_lower_num_arr["goods_id"])||!empty($new_lower_num_arr["voucher_t_id"])){
							$present_member_data=array('title'=>$new_lower_num_arr["title"],'openid'=>$lower_member_openid,'member_id'=>$lower_member["member_id"],'lower_num'=>$new_lower_num,'add_time'=>time(),'pre_openid'=>'','source_type'=>2,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num);
							$present_member_id=$wx_present_member->insert($present_member_data);
						}
						$wx_present_url_common='&openid='.encrypt($lower_member_openid).'&lower='.encrypt($new_lower_num).'&present_member_id='.encrypt($present_member_id);
						if(empty($new_lower_num_arr["goods_id"])&&!empty($new_lower_num_arr["voucher_t_id"])){
							$wx_present_sel_url=$this->wx_voucher_url.$wx_present_url_common;//礼券地址
						}else{
							$wx_present_sel_url=$this->wx_present_url.$wx_present_url_common;//礼品地址
						}
						echo $new_lower_num_arr["message"];
						$send_text=str_replace('点击领取', "<a href='{$wx_present_sel_url}'>点击领取</a>",str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $new_lower_num_arr["message"]));//"恭喜，你的下线关注者已达到{$new_lower_num}人，获取赠送护肤品试用一份，点击<a href='{$wx_present_sel_url}'>免费领取</a>!";
						echo $send_text.'<br/>';
						//$this->send_message_text($lower_member_openid, $send_text);
					}
				}else{
					$lower_rule_num=Model('wx_active_rule')->where(array('source_type'=>2))->order('lower_num asc')->field('lower_num')->limit('1')->find();//查询最少关注人数
					//恭喜，你的下线关注者已达到{$lower_num}人，关注达{$lower_rule_num["lower_num"]}人以上即可获赠免费的护肤礼品一份，继续加油哦!
						
					$send_text=str_replace(array('{code1}','{code2}'),array($lower_num,$lower_rule_num["lower_num"]), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["3"]));
					echo $send_text.'<br/>';
					//$this->send_message_text($lower_member_openid, $send_text);
				}
				//关注者发送信息
				//发送信息--"您的好友{code}赠送了一份从公元1890元来的神秘礼物，<a href=''>点击领取</a>！";
				//log_result('eventkey:'.json_encode($eventkey),'','weixin');
				$link="<a href='".BASE_SITE_URL."/wap_shop?act=weixin_active&openid=".encrypt($fromusername)."&id=".encrypt($eventkey)."'>点击领取</a>";//进入抽奖活动
				$link2="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击这里</a>";//生成专属二维码引导页面
				$send_text=str_replace(array('{code}','{点击领取}','{点击这里}'), array($lower_member["nickname"],$link,$link2), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["1"]));
				echo $send_text.'<br/>';
				//$this->send_message_text($fromusername, $send_text);
			}
		}	
	}
}