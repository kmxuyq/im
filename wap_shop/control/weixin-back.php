<?php
/**
 * 
 * 微信事件处理入口
 * @author susu
 */
define('InShopNC', true);

class weixinControl extends  BaseHomeControl{

	private $appid;
	private $appacrect;
	private $send_message_url;
	private $face_path;//微信头像路径
	private $wx_img_path;//用户专属二维码图片路径
	private $wx_present_url;//礼品领取地址
	private $get_wx_token_url;//curl获取token地址
	private $subscribe_link_url;//扫描普通二维码发送信息的点击领取链接/生成专属二维码按钮页面
	private $openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';//测试用OPENID
	private $wx_password='123456';//自动注册默订密码
	private $wx_email='admin@admin.com';//自动注册默订邮箱

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
	/**
	 * 微信开发接口入口 token验证
	 */
	public function indexOp(){
		$token='weixin';
		$wechatObj = new wechatapi();
		if(!empty($GLOBALS["HTTP_RAW_POST_DATA"])){
			$post=$wechatObj->xmlToArray($GLOBALS["HTTP_RAW_POST_DATA"]);
			log_result($GLOBALS["HTTP_RAW_POST_DATA"],'','weixin');
			$this->weixin_data($post);
		}		$wechatObj->valid($token);
	}
	private function weixin_data($post){
		$_POST = $post;
		
		if(empty($_POST))exit();
		//--------------------------		
		//$_POST=$this->xmlToArray($xml);
		//print_r($_POST);
		//log_result("\n\n index".json_encode($_POST), '','weixin');
		//--------------------------
		//log_result(json_encode($_POST),'','weixin');
		$event=$_POST["Event"];//事件类型，subscribe(订阅)、unsubscribe(取消订阅)
		$tousername=$_POST["ToUserName"];//开发都微信号
		$fromusername=$_POST["FromUserName"];//关注者openid
		$greatetime=$_POST["CreateTime"];
		$eventkey=str_replace(array('Array','qrscene_'), array('',''), $_POST["EventKey"]);//事件KEY值，qrscene_为(带参二维码)前缀，后面为二维码的参数值;Array为普通二维码的值
		$ticket=$_POST["Ticket"];//二维码的ticket，可用来换取二维码图片
		if($_POST["MsgType"]=='event'){
			//消息推送事件		
			if($event=='subscribe'){				
				$member=Model()->table('member');
				$wx_member=Model('wx_member');
				$where_openid=array('openid'=>$fromusername);
				$openid_count=$wx_member->where($where_openid)->count('id');
				$message_template=include $_SERVER["DOCUMENT_ROOT"].'/data/weixin/message_template.php';
				//log_result($fromusername.'--'.$greatetime.'--'.$eventkey.'--'.$openid_count, '','weixin');//exit();
				//echo $openid_count;
				//关注事件
				//发送关注欢迎消息
				
				//判断表中是否存在此用户			
				//增加用户,带参二维数，更新上级用户信息
				
				$userinfo=$this->add_member($fromusername,$greatetime,$eventkey,$openid_count);
				//log_result("\n\n weixin:userinfo_json:".json_encode($userinfo));
				//判断关注人数，到达一定人数后-发送中奖信息
				//如果是带参的二维码并成功关注,给上一级用户发送信息事件
				if(!empty($eventkey)){
					$lower_member=$wx_member->where(array('id'=>intval($eventkey)))->field('nickname,member_id,lower_member,openid')->find();
					//如果此上线用户存在
					if(!empty($lower_member)){
						$lower_member_openid=$lower_member["openid"];//上一级用户的openid
						//查询此用户是否取消了关注（如果扫专属二维码关注的用户）,如果再次扫专属二维码不是第一次的上线，只发送一条欢迎消息后退出，不进入级别礼物赠送
						$is_pid_count=$wx_member->where("openid='{$fromusername}' and pid !=''")->count('id');
						//echo ($is_pid_count);
						if($is_pid_count>0){
							$is_pid_res=$wx_member->where("openid='{$fromusername}'")->field('pid')->find();
							//print_r($is_pid_res);
							//log_result('pid:'.$is_pid_res["pid"].'--eventkey:'.$eventkey, '','weixin');
							
							if(!empty($is_pid_res["pid"])&&$is_pid_res["pid"]!=$eventkey){
								//echo '!='.$is_pid_res["pid"].'---'.$eventkey;
								$send_text=$message_template[2];//发送欢迎关注消息
								$this->send_message_text($fromusername, $send_text);
								exit();
							}
						}
						
						//log_result("\n\n weixin:lower_memberjson:".json_encode($lower_member).'--'.$lower_member_openid.'--'.$fromusername, '');
						if($lower_member_openid!=$fromusername){
							$lower_num=count(explode(',', substr($lower_member["lower_member"],0,-1)));
							$new_lower_num_arr=$this->send_active_present($lower_num);
							$new_lower_num=$new_lower_num_arr["lower_num"];
							//log_result("\n\n weixin:lower_num:".$lower_num.'--'.$new_lower_num, '');//
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
											$voucher_d["voucher_owner_id"]=$userinfo["member_id"];
											$voucher_d["voucher_owner_name"]=$userinfo["nickname"];
											$voucher_d["voucher_order_id"]='0';
											//log_result(json_encode($voucher_d),'','weixin');
											$voucher_id=$voucher->insert($voucher_d);//插入表
											$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
											$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
										}
									}
									//插入礼品表，当进入商品页选择完商品后更新goods_id字段和status字段为1,url参数为id(礼品表ID[加密])和openid(加密)
									$present_member_id=$wx_present_member->insert(array('title'=>$new_lower_num_arr["title"],'openid'=>$lower_member_openid,'member_id'=>$lower_member["member_id"],'lower_num'=>$new_lower_num,'add_time'=>time(),'pre_openid'=>'','source_type'=>2,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num));
									$wx_present_sel_url=$this->wx_present_url.'&openid='.encrypt($lower_member_openid).'&lower='.encrypt($new_lower_num).'&present_member_id='.$present_member_id;
									
									
									$send_text=str_replace('{点击领取}', "<a href='{$wx_present_sel_url}'>点击领取</a>",str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $new_lower_num_arr["message"]));//"恭喜，你的下线关注者已达到{$new_lower_num}人，获取赠送护肤品试用一份，点击<a href='{$wx_present_sel_url}'>免费领取</a>!";
									$this->send_message_text($lower_member_openid, $send_text);
								}
							}else{
								$lower_rule_num=Model('wx_active_rule')->where(array('source_type'=>2))->order('lower_num asc')->field('lower_num')->limit('1')->find();//查询最少关注人数
								//恭喜，你的下线关注者已达到{$lower_num}人，关注达{$lower_rule_num["lower_num"]}人以上即可获赠免费的护肤礼品一份，继续加油哦!
					
								$send_text=str_replace(array('{code1}','{code2}'),array($lower_num,$lower_rule_num["lower_num"]), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["3"]));
								$this->send_message_text($lower_member_openid, $send_text);
							}
							//关注者发送信息
							//发送信息--"您的好友{code}赠送了一份从公元1890元来的神秘礼物，<a href=''>点击领取</a>！";
							//log_result('eventkey:'.json_encode($eventkey),'','weixin');
							$link="<a href='".BASE_SITE_URL."/wap_shop?act=weixin_active&openid=".encrypt($fromusername)."&id=".encrypt($eventkey)."'>点击领取</a>";//进入抽奖活动
							$link2="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击这里</a>";//生成专属二维码引导页面
							$send_text=str_replace(array('{code}','{点击领取}','{点击这里}'), array($lower_member["nickname"],$link,$link2), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["1"]));
							$this->send_message_text($fromusername, $send_text);
						}
						
					}else{
						//如果此上线用户不存在
						//发送信息--欢迎您关注捷诺夫，分享自已的专属二维码成达关注达5人以上即可免费获赠的精美化妆品一份，点击个人中心生成自已的专属二维码!
						$lower_rule_num=$this->get_lower_rule_num();//$send_text=str_replace('{code}', $lower_rule_num, $message_template["2"]);
						$link="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击领取</a>";
						$send_text=str_replace('{点击领取}',$link,str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["2"]));
						$this->send_message_text($fromusername, $send_text);
					}
					
				}else{
					
					//关注常规二维码
					//发送信息--欢迎您关注捷诺夫，分享自已的专属二维码成达关注达5人以上即可免费获赠的精美化妆品一份，点击个人中心生成自已的专属二维码!
					$lower_rule_num=$this->get_lower_rule_num();//$send_text=str_replace('{code}', $lower_rule_num, $message_template["2"]);
					$link="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击领取</a>";
					//log_result($link.'---'.$message_template["2"], '','weixin');
					$send_text=str_replace('{点击领取}',$link,str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["2"]));
					$this->send_message_text($fromusername, $send_text);
				}
				//自动生成用户专属二维码
				$scene_id_res=$wx_member->where($where_openid)->field('id')->find();
				$this->creater_wx_user_code_img($fromusername, $userinfo["nickname"], $scene_id_res["id"]);
			}elseif($event=='unsubscribe'){
				$wx_member=Model('wx_member');
				$openid_count=$wx_member->where(array('openid'=>$fromusername))->count('id');
				//取消关注事件
				if($openid_count>0){
					//echo $openid_count;
					$this->un_subscribe($fromusername, $wx_member, $eventkey,$greatetime);
				}
			}elseif($event=='CLICK'){
				$eventkey=$_POST["EventKey"];
				switch ($eventkey){
					//发送用户专属二维码
					 case 'send_user_code_img':$this->send_user_code_img_index($fromusername);break;
				}
			}
		} elseif($_POST["MsgType"]=='text') {
			$fromUsername = $_POST['FromUserName'];
			$toUsername = $_POST['ToUserName'];
			$keyword = trim($_POST['Content']);
			$MsgType = $_POST['MsgType'];
			$time = time();
			$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
			if($keyword == "?" || $keyword == "？")
			{
				$msgType = "text";
				$contentStr = date("Y-m-d H:i:s",time());
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			} elseif($MsgType=='text') {
				$msgType = "text";
				$contentStr = 'welcome!';
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}
		}
	}
	/**
	 * 生成带参的二维码, 此访问地址URL就是图片的地址
	 */
	public function creater_wx_codeOp(){
		$scene_id=$_GET["scene_id"];//wx_member_id,带参
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		$url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
		//echo $token;
		$data='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": "'.$scene_id.'"}}}';
		//echo $data;
		$ticket_json=curl($url,'post',$data);
		//echo $ticket_json;
		$code_url_arr=json_decode($ticket_json,true);
		//ECHO $code_url_arr["url"];exit();
		$code_url=$code_url_arr["url"];
		loadfunc('phpqrcode');
		$qrcode=new QRimage();
		//QRcode::png($code_url);
		QRcode::png($code_url, false, 0, 4.5,1);
		
	}
	
	/**
	 * 点击公众号菜单生成专属二维码
	 */
	private function send_user_code_img_index($openid){
		$userinfo=Model('wx_member')->where(array('openid'=>$openid))->field('nickname,id')->find();
		//log_result('userinfo:'.json_encode($userinfo), '','weixin');
		if(!empty($userinfo)){
			$status=$this->send_user_code_img($openid, $userinfo["nickname"], $userinfo["id"]);
			//log_result('专属二维码发送状态:'.$status, '','weixin');
		}
		return $status;
	}
	/**
	 * 网页点击生成二维码按钮的链接
	 */
	public function send_user_code_img_linkOp(){

		if(isset($_SESSION["member_id"])){
			$openid_res=Model('wx_member')->where(array('member_id'=>$_SESSION["member_id"]))->field('openid')->find();
			$openid=$openid_res["openid"];
		}elseif(!empty($_GET["openid"])){
			$openid=$_GET["openid"];
		}
		
		if(!empty($openid)){
			$status=$this->send_user_code_img_index($openid);
			//echo $status;
			$status_arr=json_decode($status,true);
			if($status_arr['errmsg']=='ok'){
				$message='专属二维码消息已发送到微信<br/>请您查收！';
			}else{
				$message='请点击微信菜单选择专属二维码';
			}
			
		}else{
			$message='请点击微信菜单选择专属二维码';
		}
		echo '<br/><br/><p style="text-align:center;line-height:2;font-size:20px">'.$message.'</p>';
	}
	/**
	 * 
	 */
	private function send_message(){
		$access_token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		$url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
	}
	/**
	 * 成功关注后新增用户,如果带参数的二维码,更新主用户的下线信息
	 * @param  $fromusername openid
	 * @param  $greatetime 时间
	 * @param  $eventkey带参二维码参数
	 * @param  $openid_count wx_member表是否存在记录
	 */
	private function add_member($fromusername,$greatetime,$eventkey,$openid_count){
		$openid=$fromusername;
		$member_id='';
		$userinfo_arr=Model('wx_member')->get_wx_userinfo($openid);//获取用户信息
		//print_r($userinfo_arr);
		$member=Model('member');
		$wx_member=Model('wx_member');
		if(!empty($eventkey)){
			//来源类别（扫描普通二维码1/扫描专属二维码2）
			$source_type='1';
		}else{
			$source_type='2';
		}
		//如果关注成功，则可以获取用户信息
		if($userinfo_arr["subscribe"]=='1'){
			$openid_where=array('openid'=>$openid);
			$member_count=$member->where($openid_where)->count('member_id');
			if($member_count=='0'){
				$nickname=$userinfo_arr['nickname'];
				$password=$this->wx_password;
				$email=$this->wx_email;
				$member_avatar=$openid.'_weixin.jpg';;
				$member_sex=$userinfo_arr['sex'];
				$member_areainfo=$userinfo_arr['country'].$userinfo_arr['province'].$userinfo_arr['city'];
				//注册用户
				$member_info=Model('member')->weixin_register_model($nickname,$password,$email,$member_avatar,$member_sex,$member_areainfo,$openid);
				$member_id=$member_info["member_id"];
				/* $member_d["openid"]=$openid;
				$member_d["member_name"]=$userinfo_arr['nickname'];
				$member_d["member_passwd"]='e10adc3949ba59abbe56e057f20f883e';
				$member_d["member_avatar"]=$openid.'_weixin.jpg';//$userinfo_arr['headimgurl'];
				$member_d["member_sex"]=$userinfo_arr['sex'];
				$member_d["member_time"]=$userinfo_arr['subscribe_time'];
				$member_d["member_areainfo"]=$userinfo_arr['country'].$userinfo_arr['province'].$userinfo_arr['city'];
				$member_d["member_time"]=time();
				$member_d["member_login_time"]=time();
				$member_d["member_old_login_time"]=time();
				$member_d["member_email"]='admin@admin.com';
				$member_id=$member->insert($member_d);//插入用户主表 */
			}else{
				$member_id_res=$member->where($openid_where)->field('member_id')->find();
				$member_id=$member_id_res["member_id"];
			}
			$wx_member_d['member_id']=$member_id;
			$wx_member_d['openid']=$openid;
			$wx_member_d['reg_time']=$userinfo_arr['subscribe_time'];
			$wx_member_d['source_type']=$source_type;
			$wx_member_d['nickname']=$userinfo_arr['nickname'];
			$wx_member_d['sex']=$userinfo_arr['sex'];
			$wx_member_d['country']=$userinfo_arr['country'];
			$wx_member_d['city']=$userinfo_arr['city'];
			$wx_member_d['province']=$userinfo_arr['province'];
			$wx_member_d['headimgurl']=$userinfo_arr['headimgurl'];
			$wx_member_d['status']='1';//已关注1/未关注0
			$wx_member_d["stock"]=5;
			if(!empty($eventkey)){
				$wx_member_d["pid"]=$eventkey;
			}
			//print_r($wx_member_d);
			if($openid_count=='0'){
				//如果表中没有此用户
				$wx_member->insert($wx_member_d);//插入微信用户表

			}else{
				//如果已存在此用户，则更新关注状态
				$wx_member->where($openid_where)->update(array('status'=>1));
			}
			//下载头像到本地
			@mkdir($this->face_path,0777,true);//创建文件夹
			$this->put_file_from_url_content($userinfo_arr["headimgurl"], $openid.'.jpg', $this->face_path);
			$image_resize=new ImageResize();
			$local_img=$this->face_path.$openid.'.jpg';
			$dst_img=$this->face_path.$openid.'_thum.jpg';
			$dst_img_weixin=$this->face_path.$openid.'_weixin.jpg';
			$image_resize->resize($local_img, $dst_img, 50, 50);//生成头像缩略图
			$image_resize->resize($local_img, $dst_img_weixin, 150, 150);//生成头像缩略图
			//-------------
			if(!empty($eventkey)){
				//如果带参数的二维码,更新主用户的下线信息
				$wx_member_where=array('id'=>intval($eventkey));
				$wx_member->where($wx_member_where)->setInc('lower_num',1);//主用户下线加1
				$event_member=$wx_member->where($wx_member_where)->field('*')->find();//上线用户信息
				$new_lower_member=$openid.',';
				//strpos($event_member["lower_member"], $openid)=='';
				if(!in_array($openid, explode(',', $event_member["lower_member"]))){
					//如果下线字段中还没有此用户,则更新此字段
					//echo "<p>".$event_member["lower_member"].'<p>';
					//echo "<p>".strpos($event_member["lower_member"], $openid)."</p>";					
					if($event_member["lower_member"]!='' && !in_array($openid,explode(',', $event_member["lower_member"]))){
						$new_lower_member=$event_member["lower_member"].$openid.',';
					}

					if($event_member["lower_member_info"]!=''){
						$lower_member_info=unserialize($event_member["lower_member_info"]);
					}
					$lower_member_info[$openid]=$wx_member_d;
					//更新下线用户信息
					$wx_member->where($wx_member_where)->update(array('lower_member'=>$new_lower_member,'lower_member_info'=>serialize($lower_member_info)));
					//------------------------
				}
			}
		}
		if(empty($member_id)){
			$member_id_res=$wx_member->where(array('openid'=>$openid))->field('member_id')->find();
			$member_id=$member_id_res["member_id"];
		}
		$userinfo_arr["member_id"]=$member_id;
		return $userinfo_arr;		
	}
	/**
	 * 用户取消关注事件
	 */
	private function un_subscribe($openid,$wx_member,$eventkey,$greatetime){
		$wx_member->where(array('openid'=>$openid))->update(array('status'=>'0'));//更新用户状态
		//更新下线用户的状态
		$condition="lower_member like '%{$openid}%'";
		//$condition="id=2";
		$lower_member=$wx_member->where($condition)->field('id,lower_member,lower_member_info')->find();
		//print_r($lower_member);
		//找出上线用户记录
		if(!empty($lower_member)){
			$wx_member_where=array('id'=>intval($lower_member["id"]));
			$wx_member->where($wx_member_where)->setDec('lower_num',1);//用户下线减1			
			//如果下线字段中存在此用户,则更新此字段
			$new_lower_member=str_replace($openid.',', '', $lower_member["lower_member"]);
			if($lower_member["lower_member_info"]!=''){
				$lower_member_info=unserialize($lower_member["lower_member_info"]);
				//print_r($lower_member_info);
				//print_r($lower_member_info[$openid]);
				//echo $lower_member_info[$openid]["status"];
				if(strpos($lower_member["lower_member_info"], $openid)!==''){
					$lower_member_info[$openid]["status"]='0';
				}
			}
			//更新下线用户信息
			$wx_member->where($wx_member_where)->update(array('reg_time'=>$greatetime,'lower_member'=>$new_lower_member,'lower_member_info'=>serialize($lower_member_info)));
			//------------------------
		}
	}
	/**
	 * 获取最少关注人数
	 */
	private function get_lower_rule_num(){
		$lower_rule_num=Model('wx_active_rule')->where(array('source_type'=>2))->order('lower_num asc')->field('lower_num')->limit('1')->find();//查询最少关注人数
		return $lower_rule_num;
	}
	
	/**
	 * 生成微信发送的图片,返回图片地址
	 */
	private function creater_wx_image($openid,$nickname,$scene_id){
		//log_result("\n creater_wx_image:".$openid.'--'.$nickname.'--'.$scene_id, '','weixin');
		$face_url=$this->face_path.$openid.'_thum.jpg';
		$image_tool=new imagetool();		
		$img=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/wxbg.jpg';
		$wx_img_path=$this->wx_img_path;
		@mkdir($wx_img_path,0777,true);//创建文件夹
		$wx_img_filename=$wx_img_path.$openid.'.jpg';//新文件路径
		copy($img, $wx_img_filename);//复制文件
		
		$water_code=BASE_SITE_URL.'/wap_shop/index.php?act=weixin&op=creater_wx_code&scene_id='.$scene_id;//$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w1.jpg';
		//echo $water_code;
		//$face=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w_face.jpg';
		//log_result("\n wx_img_filename:".$wx_img_filename.'--'.$water_code.'--'.$face_url.'--'.$nickname, '','weixin');
		$text="以下是{$nickname}的迷之大使\n           专属二维码";
		$image_tool->wx_water_code($wx_img_filename, $water_code,0,-132);//二维码
		$image_tool->wx_water_face($wx_img_filename, $face_url,110,34);//头像
		$image_tool->wx_water_text($wx_img_filename,$text,10,'',180,53);//妮称
		return $wx_img_filename;
	}
	/**
	 * 获取微信access_token
	 * $reget=1为强制重新获取token
	 */
	private function get_wx_accesstoken($reget='0'){
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appacrect;
		$token_file=$_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt';
		if(!file_exists($token_file)||(filemtime($token_file)+6000)<time()||$reget=='1'){
			//token文件不存在或都文件过期
			//echo $url.'<br/>';
			$token_str=curl($url);
			
			$token_arr=json_decode($token_str);
			//echo $token_str.'<br/>';
			//echo $token_arr->access_token;
			if(($token_arr->access_token)!=''){
				file_put_contents($token_file, $token_arr->access_token);
			}
		}
		$token=file_get_contents($token_file);//此行不要删
		//判断token是否失效
		
		$getip_url="https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=";
		//echo $getip_url.$token;
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
		return $token;	
	}
	/**
	 *下线人数到达一定时发送中奖消息
	 *如果符合中奖条件的话就返回array('lower_num'=>$v["lower_num"],'message'=>$v["message"],'title'=>$v["title"])
	 * @param  $lower_num
	 */
	private function send_active_present($lower_num){
		$wx_active_role=Model('wx_active_rule');
		$list=$wx_active_role->where(array('source_type'=>2))->field('*')->order('lower_num desc')->select();
		foreach($list as $v){
			if($lower_num>=$v["lower_num"]){
				return array('lower_num'=>$v["lower_num"],'message'=>$v["message"],'title'=>$v["title"],'voucher_t_id'=>$v["voucher_t_id"],'voucher_num'=>$v["voucher_num"],'voucher_days'=>$v["voucher_days"]);exit();
			}
		}
	}
	/**
	 * 发送用户专属二维码图片
	 * @param $openid
	 * @param $nickname
	 * @param $scene_id
	 * @param $image_file
	 */
	private function send_user_code_img($openid, $nickname, $scene_id,$image_file=''){
		if($image_file!=''){
			$media_id=$this->up_wx_image($image_file);
		}else{
			$media_id_res=Model('wx_member')->where(array('openid'=>$openid))->field('media_id')->find();
			//判断二维码图片是否过期和media_id是否存在
			//log_result('media_id_res:'.json_encode($media_id_res), '','weixin');
			$image_filename=$this->wx_img_path."{$openid}.jpg";
			if(file_exists($image_filename) && (filemtime($image_filename)+86400*3)>=time() && !empty($media_id_res["media_id"])){
				$media_id=$media_id_res["media_id"];
			}else{
				$media_id=$this->creater_wx_user_code_img($openid, $nickname, $scene_id);
			}
		}
		$status=$this->send_message_image($openid,$media_id);
		return $status;
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
		//log_result($url."\n\n".$content, '','weixin');
		$status=curl($url,'post',$content);
		return $status;
	}
	/**
	 * 微信发送图片消息
	 */
	private function send_message_image($openid,$media_id){
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		$url=$this->send_message_url.$token;
		$content='{
    "touser":"'.$openid.'",
    "msgtype":"image",
    "image":
    {
      "media_id":"'.$media_id.'"
    }
}';
		$status=curl($url,'post',$content);
		return $status;
		
	}
	/**
	 * 上传微信图片(临时素材)，返回media_id
	 */
	private function up_wx_image($image_file){
		//log_result("\n up_wx_image1:".$image_file, '','weixin');
		$token=Model('wx_member')->get_token($this->appid,$this->appacrect);
		//log_result("\n token:".$token, '','weixin');
		$url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";
		$media_id_json=$this->upload_curl_pic($url, $image_file);
		$media_id_arr=json_decode($media_id_json);
		//log_result("\n up_wx_image2:".$media_id_json, '','weixin');
		return $media_id_arr->media_id;
	}
	/**
	 * 用户关注后自动生成用户专属二维码,返回图片media_id
	 */
	private function creater_wx_user_code_img($openid, $nickname, $scene_id){
		$code_path=$this->wx_img_path;
		@mkdir($code_path,0777,true);
		$code_filename=$code_path.$openid.'.jpg';
		//二维码图片30天有效期
		$media_id='';
		if(!file_exists($code_filename)||(filemtime($code_filename)+86400*30)<=time()){
			//生成图片
			$image_file=$this->creater_wx_image($openid, $nickname, $scene_id);
			//log_result("\n image_file1:".$image_file, '','weixin');
			if($image_file!=''){
				//生成media_id
				$media_id=$this->up_wx_image($image_file);
				if($media_id!=''){
					Model('wx_member')->where(array('openid'=>$openid))->update(array('media_id'=>$media_id));
				}
				//log_result("\n media_id1:".$media_id, '','weixin');
			}
		}
		//-----------
		//如果media_id获取失败
		if($media_id==''){
			//生成图片
			$image_file=$this->creater_wx_image($openid, $nickname, $scene_id);
			//log_result("\n image_file2:".$image_file, '','weixin');
			if($image_file!=''){
				//生成media_id
				$media_id=$this->up_wx_image($image_file);
				if($media_id!=''){
					Model('wx_member')->where(array('openid'=>$openid))->update(array('media_id'=>$media_id));
				}
				//log_result("\n media_id2:".$media_id, '','weixin');
			}
		}
		return $media_id;
	}
	/**
	 * 	作用：array转xml
	 */
	private function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
    /**
     * 	作用：将xml转为array
     */
     private function xmlToArray($xml)
    {
    	//将XML转为array
    	$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    	return $array_data;
    }
    /**
     * curl 上传图片
     * @param $url上传到的处理地址
     * @param $file 要上传的本地文件，带本地上传盘符和路径e:\1.jpg
     * @param $type post
     * @return mixed
     */
    private function upload_curl_pic($url,$file,$type='post', $second='30')
	{
	    $fields['media'] = '@'.$file;
	   // $fields['type'] = 'image';
	    $ch = curl_init();
	    //设置超时
	    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);//
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($type=='post'){
			curl_setopt($ch, CURLOPT_POST, 1);//开启POST
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);//POST数据
		}
		$output = curl_exec($ch);
		//curl_close($ch);
		//返回结果
		if( $output){
			@curl_close($ch);
			return $output;
		}
		else{
			$error = curl_errno($ch);
			@curl_close($ch);
			return false;
		}
	    //if ($error = curl_error($ch) ) {
	    //       die($error);
	    //} else {
	    //    var_dump($data);
	    //}
	    //curl_close($ch);
	}
	/**
	 * 异步将远程链接上的内容(图片或内容)写到本地
	 *
	 * @param  $url远程地址
	 * @param  $saveName保存在服务器上的文件名
	 * @param $path保存路径
	 * @return boolean
	 */
	private function put_file_from_url_content($url, $saveName, $path) {
		// 设置运行时间为无限制
		set_time_limit ( 0 );
	
		$url = trim ( $url );
		$curl = curl_init ();
		// 设置你需要抓取的URL
		curl_setopt ( $curl, CURLOPT_URL, $url );
		// 设置header
		curl_setopt ( $curl, CURLOPT_HEADER, 0 );
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
		// 运行cURL，请求网页
		$file = curl_exec ( $curl );
		// 关闭URL请求
		curl_close ( $curl );
		// 将文件写入获得的数据
		$filename = $path . $saveName;
		$write = @fopen ( $filename, "w" );
		if ($write == false) {
			return false;
		}
		if (fwrite ( $write, $file ) == false) {
			return false;
		}
		if (fclose ( $write ) == false) {
			return false;
		}
		//$url='http://wx.qlogo.cn/mmopen/oPM8qm1ZTKobh3xER5pLdGtVCf3ZDvRveWutFHrOvZAr4GWKBAPOSFFotLcluTy9ghj2D7prnqSDxgfb0eJYicL1ErPTbRrEib/0';
		//put_file_from_url_content($url, date('His').'.jpg', './');
	}
	public function curl_get_testOp(){
		echo 'curl_get_test  ok';
	}
	//--------------------------------------以下为测试--------------------------------------------------------------
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
		$openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';
		$nickname='叶落归根';
		$image_file=$this->creater_wx_image($openid, $nickname,4);
		//$image_file=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/2015-12/ocuxlxEkoyzYVxnVoj3Dc3AS5xgw.jpg';
		echo $image_file.'<br/>';
		 $media_id=$this->up_wx_image($image_file);
		echo $media_id.'<br/>';
		 $token=Model('wx_member')->get_token($this->appid,$this->appacrect);
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
		$openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';
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
		//echo $this->send_message_image($openid, $media_id);
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
	public function test2Op(){
		$fromusername='ocuxlxGNc7E-Ss0EpEEGCczrA5Lg';//417
		$eventkey='222';
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
		echo '==sadfasfasfasfda';
		exit();
		//-------------------------------
		$wx_member=Model('wx_member');
		$eventkey='1';
		
		$lower_member=$wx_member->where(array('id'=>intval($eventkey)))->field('nickname,member_id,lower_member,openid')->find();
		
		$lower_member_openid=$lower_member["openid"];//上一级用户的openid;
		$lower_num=count(explode(',', substr($lower_member["lower_member"],0,-1)));
		echo $lower_num;
		$new_lower_num_arr=$this->send_active_present($lower_num);
		$new_lower_num=$new_lower_num_arr["lower_num"];
		echo ("\n\n weixin:lower_num:".$lower_num.'--new_lower_num:'.$new_lower_num);//
		if(!empty($new_lower_num)){
			$wx_present_member=Model('wx_present_member');
			//信息发给上一级的openid
			//如果符合中奖条件则发送中奖消息，传替下线人数(加密)--发送中奖消息
			$is_present_count=$wx_present_member->where(array('openid'=>$lower_member_openid,'lower_num'=>$new_lower_num))->count('id');
			if($is_present_count=='0'){
				$voucher_id=0;
				$voucher_num=0;
				//判断是否有代金券，有则插入代金券表
				print_r($new_lower_num_arr);
				echo ('weixin:new_lower_num_arr'.json_encode($new_lower_num_arr)."\n voucher_t_id:{$new_lower_num_arr["voucher_t_id"]}");
				if(!empty($new_lower_num_arr["voucher_t_id"])){
					$voucher_t_id=$new_lower_num_arr["voucher_t_id"];
					$voucher_t_res=Model()->table('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
					$voucher_time=$new_lower_num_arr["voucher_days"]*86400+time();//代金券有效期
					$voucher_num=$new_lower_num_arr["voucher_num"];
					echo 'voucher_num:'.$voucher_num;
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
						$voucher_d["voucher_owner_id"]='83';
						$voucher_d["voucher_owner_name"]='susu';
						$voucher_d["voucher_order_id"]='0';
						print_r($voucher_d);
						$voucher_id=$voucher->insert($voucher_d);//插入表
						$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
						$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
					}
				}
			}
		}
	}
}
?>