<?php
/**
 *
 * 微信事件处理入口
 * @author susu
 */
define('InShopNC', true);

class weixinControl extends  BaseHomeControl{

	private $store_id;
	private $appid;
	private $appacrect;
	private $token;
	private $send_message_url;
	private $face_path;//微信头像路径
	private $wx_img_path;//用户专属二维码图片路径
	private $wx_present_url;//礼品领取地址
	private $wx_voucher_url;//礼券领取地址（只有礼券没有商品）
	private $get_wx_token_url;//curl获取token地址
	private $subscribe_link_url;//扫描普通二维码发送信息的点击领取链接/生成专属二维码按钮页面
	private $openid='ocuxlxEkoyzYVxnVoj3Dc3AS5xgw';//测试用OPENID
	private $wx_password='123456';//自动注册默认密码
	private $wx_email='admin@admin.com';//自动注册默认邮箱
	
	public function __construct(){
		parent::__construct();
		//对应附带的strore_id的参数
		$store_id  = intval(isset($_GET['store_member_info'])?$_POST['store_member_info']:$_GET['store_member_info']);
		if (empty($store_id)) exit('无效的参数');
	    $info = Model()->table('store_wxinfo')->where(array('store_id'=>$store_id))->find();
		if(empty($info)) exit('没有找到对应的$store_id的微信配置信息');
		$this->store_id =$store_id;
		$this->appid = $info['appid'];
		$this->appacrect= $info['appsecret'];
		$this->token = $info['token'];
		$this->send_message_url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';
		$this->face_path='../data/weixin/head/';
		$this->wx_img_path='../data/weixin/';
		$this->wx_present_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_sel';//GET参数:openid(加密的openid),lower(加密的下线人数)
		$this->wx_voucher_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_voucher_sel';//GET参数:openid(加密的openid),lower(加密的下线人数)
		$this->subscribe_link_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_code';
		//send_user_code_img_link生成二维码链接方法
	}
	/**
	 * 微信开发接口入口 token验证
	 */
	public function indexOp(){
		$token=$this->token;
		$wechatObj = new wechatapi();
		if(!empty($GLOBALS["HTTP_RAW_POST_DATA"])){
			$post=$wechatObj->xmlToArray($GLOBALS["HTTP_RAW_POST_DATA"]);
			log_result($GLOBALS["HTTP_RAW_POST_DATA"],'','weixin');
			$this->weixin_data($post);
		}
		$wechatObj->valid($token);
	}
	private function weixin_data($post){
		$_POST = $post;
		if(empty($_POST))exit();
		//对应包含相应的store_id的配置信息 如果没有就包含默认的
		if(file_exists('../data/weixin/message_template_'.$this->store_id.'.php')){
			$message_template = include '../data/weixin/message_template_'.$this->store_id.'.php';
		}else{
			$message_template = include '../data/weixin/message_template.php';
		}
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
				//$eventkey2='';
				//$code_type='10';
				//关注事件//发送关注欢迎消息
				//判断表中是否存在此用户
				//增加用户,带参二维数，更新上级用户信息
				//OLDEVENT_FAZHI 阀值 原先裂变活动的二维码 10w
				if(!empty($eventkey)){
					/* if($eventkey<OLDEVENT_FAZHI) {
						$code_type = '10';
						$eventkey2 = $eventkey;
					} else {  */
						$code_type=@substr($eventkey,0,2);
						$eventkey2=@substr($eventkey,2,strlen($eventkey));
					//}
				}
				$userinfo=$this->add_member($fromusername,$greatetime,$eventkey2,$openid_count,$code_type);
				$reg_time_path=date('Y/m/d/',$userinfo['wx_member_d']["reg_time"]);
				$message_time_path=$this->wx_img_path.$reg_time_path;//上次发送信息的时间，防止重发
				//log_result("\n\n weixin:userinfo_json:".json_encode($userinfo));
				//判断关注人数，到达一定人数后-发送中奖信息
				//如果是带参的二维码并成功关注,给上一级用户发送信息事件
				if(!empty($eventkey)){
					switch ($code_type){
						case '10':$this->eventkey10($wx_member,$eventkey2,$fromusername,$userinfo,$message_template,$message_time_path);break;
						case '20':$this->eventkey20($fromusername, $userinfo, $eventkey2, $message_template);break;
						case '30':$this->eventkey30($fromusername, $userinfo, $eventkey2, $message_template);break;
						default:$this->eventkey10($wx_member,$eventkey2,$fromusername,$userinfo,$message_template,$message_time_path);break;
					}
				}else{
					//关注常规二维码
					//发送信息--欢迎您关注捷诺夫，分享自已的专属二维码成达关注达5人以上即可免费获赠的精美化妆品一份，点击个人中心生成自已的专属二维码!
					$lower_rule_num=$this->get_lower_rule_num();//$send_text=str_replace('{code}', $lower_rule_num, $message_template["2"]);
					$link="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击领取</a>";
					$link2_url=base64_encode("/wap_shop/?act=member_security&op=auth&type=modify_mobile");

					$link2="<a href='".BASE_SITE_URL."/wap_shop/wx_return_url.php?openid={$fromusername}&return_url={$link2_url}'>完善个人信息</a>";
					//log_result($link.'---'.$message_template["2"], '','weixin');
					$send_text=str_replace(array('{点击领取}','{完善个人信息}'),array($link,$link2),str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["2"]));
					$message_time_filename=$message_time_path.$fromusername.'.txt';
					if(!file_exists($message_time_filename)||(filemtime($message_time_filename)+12)<time()){
						$this->send_message_text($fromusername, $send_text);
						file_put_contents($message_time_filename, time());
					}

				}
				//自动生成用户专属二维码
				$scene_id_res=$wx_member->where($where_openid)->field('id')->find();
				$scene_id_res["id"] = $this->fix_id($scene_id_res["id"]);
				Model('wx_member')->creater_wx_user_code_img($this->face_path,$this->wx_img_path,$fromusername, $userinfo["nickname"], $scene_id_res["id"],$code_type,$reg_time_path);

				//$scene_id=$code_type.$scene_id_res["id"];
				//Model('wx_member')->creater_wx_image($this->face_path,$this->wx_img_path,$fromusername, $userinfo["nickname"], $scene_id,$reg_time_path);
			}elseif($event=='unsubscribe'){
				$wx_member=Model('wx_member');
				$openid_count=$wx_member->where(array('openid'=>$fromusername))->count('id');
				//取消关注事件
				if($openid_count>0){
					//echo $openid_count;
					$this->un_subscribe($fromusername, $wx_member, $greatetime);
					$gf_shopping_guide_lower=Model()->table('gf_shopping_guide_lower');
					$gf_order=Model()->table('gf_order');
					$gf_order_id_res=$gf_shopping_guide_lower->where(array('lower_openid'=>$fromusername))->field('gf_order_id')->find();
					//如果此下线用户存在则更新关注状态
					if(!empty($gf_order_id_res)){
						$gf_shopping_guide_lower->where(array('lower_openid'=>$fromusername))->update(array('status'=>0));//更新关注状态
						$gf_order->where(array('id'=>$gf_order_id_res["gf_order_id"]))->setDec('subscribe',1);//关注数减1
					}
				}
			}elseif($event=='CLICK'){
				$eventkey=$_POST["EventKey"];
				//时间是click就发送的memssage_template的11条？？
				$sendtext = $message_template["11"];
				switch ($eventkey){
					//发送用户专属二维码
					 case 'send_user_code_img':$this->send_user_code_img_index($fromusername);break;
					 case 'send_text':$this->send_message_text($fromusername,$sendtext);break;
				}
			}elseif($event=='SCAN'){
					$wx_model = Model('wx_member');
					$member_model = Model('member');
					$code_type=@substr($eventkey,0,2);
					$eventkey2=@substr($eventkey,2,strlen($eventkey));
					if($code_type=='20'){
						// scaner's wx_member data
						$this->spread_scaner($fromusername, $eventkey2, $message_template);
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
			//自动回复
			$msgType = "text";
			$this->MsgType_text_send($textTpl, $fromUsername, $toUsername, $time, $msgType,$keyword,$message_template);
		} else {
			$this->transfer_customer_service($fromusername, $tousername, $message_template);
		}
	}
	/**
	 * 生成带参的二维码, 此访问地址URL就是图片的地址
	 */
	public function creater_wx_codeOp(){
		$scene_id=$_GET["scene_id"];//$scene_id=类别ID+wx_member表ID
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
		$userinfo=Model('wx_member')->where(array('openid'=>$openid))->field('nickname,id,media_id,reg_time,headimgurl')->find();
		//log_result('userinfo:openid:'.$openid.'---'.json_encode($userinfo), '---','weixin');
		if(!empty($userinfo)){
			$status=$this->send_user_code_img($openid, $userinfo);
			log_result('专属二维码发送状态:'.$status, '---','weixin');
		}
		return $status;
	}
	/**
	 * 网页点击生成二维码按钮的链接
	 */
	public function send_user_code_img_linkOp(){
		echo "<meta charset='utf-8'/>";
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
				$message='请点击微信菜单魔药社群选择专属二维码';
			}

		}else{
			$message='请点击微信菜单魔药社群选择专属二维码';
		}
		echo '<br/><br/><p style="text-align:center;line-height:2;font-size:20px;padding:0 10% 0 10%">'.$message.'</p>';
	}
	/**
	 * 关注带参二维码（裂变活动）处理
	 */
	private function eventkey10($wx_member_model,$eventkey,$fromusername,$userinfo,$message_template,$message_time_path){
		//log_result('eventkey10:'.$eventkey.'--'.$fromusername.'--'.$userinfo.'--'.$message_template, '','weixin');
		$wx_member=$wx_member_model;
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
				if(!empty($is_pid_res["pid"])&&$is_pid_res["pid"]!=$eventkey){
					//echo '!='.$is_pid_res["pid"].'---'.$eventkey;
					$send_text=str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template[2]);//发送欢迎关注消息
					//更新关注状态
					$this->up_wx_member_info($eventkey,$fromusername,$wx_member,$userinfo["wx_member_d"]);
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
								$voucher_d["voucher_state"]=1;
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
						$wx_present_sel_url='';
						//插入礼品表，（只要满足'有礼品或礼券'就插入记录），当进入商品页选择完商品后更新goods_id字段和status字段为1,url参数为id(礼品表ID[加密])和openid(加密)
						if(!empty($new_lower_num_arr["goods_id"])||!empty($new_lower_num_arr["voucher_t_id"])){
							$present_member_d_status=0;
							if(!empty($voucher_id)&&$voucher_id>0)$present_member_d_status=1;
							$present_member_data=array('title'=>$new_lower_num_arr["title"],'openid'=>$lower_member_openid,'member_id'=>$lower_member["member_id"],'lower_num'=>$new_lower_num,'add_time'=>time(),'pre_openid'=>'','source_type'=>2,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num,'status'=>$present_member_d_status);
							$present_member_id=$wx_present_member->insert($present_member_data);

							$wx_present_url_common='&openid='.encrypt($lower_member_openid).'&lower='.encrypt($new_lower_num).'&present_member_id='.encrypt($present_member_id);
							if(empty($new_lower_num_arr["goods_id"])&&!empty($new_lower_num_arr["voucher_t_id"])){
								$wx_present_sel_url=$this->wx_voucher_url.$wx_present_url_common;//礼券地址
							}else{
								$wx_present_sel_url=$this->wx_present_url.$wx_present_url_common;//礼品地址
							}
						}
						//判断是否已领取，没有领取则发送点击领取的消息(下线取消关注重新关注，人员重新变动，会重复发送中奖消息)
						$present_member_status=$wx_present_member->where(array('member_id'=>$userinfo["member_id"],'lower_num'=>$new_lower_num,'status'=>0))->count('id');
						if($present_member_status=='0'){
							$send_text=str_replace('{点击领取}', "<a href='{$wx_present_sel_url}'>点击领取</a>",str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $new_lower_num_arr["message"]));//"恭喜，你的下线关注者已达到{$new_lower_num}人，获取赠送护肤品试用一份，点击<a href='{$wx_present_sel_url}'>免费领取</a>!";

							$message_time_filename=$message_time_path.$lower_member_openid.'.txt';
							if(!file_exists($message_time_filename)||(filemtime($message_time_filename)+12)<time()){
								$this->send_message_text($lower_member_openid, $send_text);
								file_put_contents($message_time_filename, time());
							}
						}
					}
				}/*else{
				//$lower_rule_num=Model('wx_active_rule')->where(array('source_type'=>2))->order('lower_num asc')->field('lower_num')->limit('1')->find();//查询最少关注人数
				//恭喜，你的下线关注者已达到{$lower_num}人，关注达{$lower_rule_num["lower_num"]}人以上即可获赠免费的护肤礼品一份，继续加油哦!

				//$send_text=str_replace(array('{code1}','{code2}'),array($lower_num,$lower_rule_num["lower_num"]), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["3"]));
				//$this->send_message_text($lower_member_openid, $send_text);
				}*/
				//关注者发送信息
				//发送信息--"您的好友{code}赠送了一份从公元1890元来的神秘礼物，<a href=''>点击领取</a>！";
				//log_result('eventkey:'.json_encode($eventkey),'','weixin');
				$link="<a href='".BASE_SITE_URL."/wap_shop?act=weixin_active&openid=".encrypt($fromusername)."&id=".encrypt($eventkey)."'>点击领取</a>";//进入抽奖活动
				$link2="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击这里</a>";//生成专属二维码引导页面
				$send_text=str_replace(array('{code}','{点击领取}','{点击这里}'), array($lower_member["nickname"],$link,$link2), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["1"]));
				$message_time_filename=$message_time_path.$fromusername.'.txt';
				if(!file_exists($message_time_filename)||(filemtime($message_time_filename)+12)<time()){
					$this->send_message_text($fromusername, $send_text);
					file_put_contents($message_time_filename, time());
				}
			}

		}else{
			//如果此上线用户不存在
			//发送信息--欢迎您关注捷诺夫，分享自已的专属二维码成达关注达5人以上即可免费获赠的精美化妆品一份，点击个人中心生成自已的专属二维码!
			$lower_rule_num=$this->get_lower_rule_num();//$send_text=str_replace('{code}', $lower_rule_num, $message_template["2"]);
			$link="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击领取</a>";
			$send_text=str_replace('{点击领取}',$link,str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["2"]));
			$this->send_message_text($fromusername, $send_text);
		}
	}
	private function eventkey20($fromusername, $userinfo, $eventkey, $message_template){
		$link="<a href='".BASE_SITE_URL."/wap_shop?act=weixin_active&openid=".encrypt($fromusername)."&id=".encrypt($eventkey)."'>点击领取</a>";//进入抽奖活动
		$link2="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击这里</a>";//生成专属二维码引导页面
		$send_text=str_replace(array('{点击领取}','{点击这里}'), array($link,$link2), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["1"]));
		$this->send_message_text($fromusername, $send_text);
	}
	private function eventkey30($fromusername, $userinfo, $eventkey, $message_template){
		//$eventkey为gf_order表的id
		$gf_shopping_guide_lower=Model()->table('gf_shopping_guide_lower');
		$gf_shopping_guide_lower_res=$gf_shopping_guide_lower->where(array('gf_order_id'=>$eventkey,'lower_openid'=>$fromusername))->field('status')->find();
		$gf_order=Model()->table('gf_order');
		if(empty($gf_shopping_guide_lower_res)){
			//如果没有此条数据则插入数据
			$gf_order_res=$gf_order->where(array('id'=>$eventkey))->field('shopping_guide_id')->find();
			if(!empty($gf_order_res)){
				$wx_member_d=$userinfo["wx_member_d"];
				$wx_member_d['nickname']=urlencode(str_replace(array('"',"'",'{','}','[',']',':',';','(',')'), array('','','','','','','','','',''), $wx_member_d['nickname']));//因转为中文，没有经过JSON编码，所有不能有双引号
				$lower_member_info_json=str_replace('\/', '/', json_encode($wx_member_d,JSON_UNESCAPED_UNICODE));
				$data["gf_order_id"]=$eventkey;
				$data["shopping_guide_id"]=$gf_order_res["shopping_guide_id"];
				$data["lower_openid"]=$fromusername;
				$data["lower_info"]=$lower_member_info_json;
				$data["lower_member_id"]=$userinfo["member_id"];
				$data["status"]=1;
				$data["add_time"]=time();
				//插入数据
				$gf_shopping_guide_lower->insert($data);
				//关注数加1
				$status=$gf_order->where(array('id'=>$eventkey))->setInc('subscribe',1);
			}
		}else{
			//如果有数据：是取消了关注再重新关注的，更新关注状态
			//关注数加1
			//由于微信发送三次请求，防止加3，判断用户表状态是否未关注，是未关注则加1
			$subscribe_status=$gf_shopping_guide_lower_res["status"];
			if($status=='0'){
				$status=$gf_order->where(array('id'=>$eventkey))->setInc('subscribe',1);
			}
			//更新用户关注状态（放在最后）
			$gf_shopping_guide_lower->where(array('gf_order_id'=>$eventkey,'lower_openid'=>$fromusername))->update(array('status'=>1));
		}
		return $status;
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
	private function add_member($fromusername,$greatetime,$eventkey,$openid_count,$code_type='10'){
		$openid=$fromusername;
		$member_id='';
		$userinfo_arr=Model('wx_member')->get_wx_userinfo($openid,$this->appid,$this->appacrect);//获取用户信息
		//print_r($userinfo_arr);
		$member=Model('member');
		$wx_member=Model('wx_member');
		if(empty($eventkey)){
			//来源类别（扫描普通二维码1/扫描专属二维码2）
			$source_type='1';
		}else{
			if($code_type==20) {
				$source_type='3';
			} else {
				$source_type='2';
			}
		}
		//如果关注成功，则可以获取用户信息
		if($userinfo_arr["subscribe"]=='1'){
		    //对应的openid 以 store_member里面的为主
			$openid_where = array('openid'=>$openid);
			$member_count = Model()->table('store_wxinfo')->where($openid_where)->count('member_id');
			if($member_count=='0'){
				$nickname=$userinfo_arr['nickname'];
				$password=$this->wx_password;
				$email=$this->wx_email;
				$member_avatar=$openid.'_weixin.jpg';
				$member_sex=$userinfo_arr['sex'];
				$member_areainfo=$userinfo_arr['country'].$userinfo_arr['province'].$userinfo_arr['city'];
				//注册用户
				$member_info=Model('member')->weixin_register_model($nickname,$password,$email,$member_avatar,$member_sex,$member_areainfo,$openid,$eventkey);
				$member_id=$member_info["member_id"];
				//同时往store_member中塞入数据
			    Model()->table('store_wxinfo')->insert(array('store_id'=>$this->store_id,'openid'=>$openid,'member_id'=>$member_id));
			}else{
				$member_id_res=Model()->table('store_wxinfo')->where($openid_where)->field('member_id')->find();
				$member_id=$member_id_res["member_id"];
				$update_member['member_name'] = $userinfo_arr['nickname'];
				$update_member['member_avatar'] = $openid.'_weixin.jpg';
				$update_member['member_sex'] = $userinfo_arr['sex'];
				$update_member['member_areainfo'] = $userinfo_arr['country'].$userinfo_arr['province'].$userinfo_arr['city'];
				$member->editMember($member_id_res, $update_member);
				if($source_type=='3') {
					$this->spread_update($fromusername,$eventkey,$member_id);
				}
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
			$openid_count = $wx_member->where($openid_where)->count('id');
			//print_r($wx_member_d);
			if($openid_count=='0'){
				//如果表中没有此用户
				$wx_member->insert(array_merge($wx_member_d,array('store_id'=>$this->store_id)));//插入微信用户表

			}else{
				//如果已存在此用户，则更新关注状态
				$wx_member->where($openid_where)->update(array('status'=>1));
			}
			$reg_time_res=Model('wx_member')->where($openid_where)->field('reg_time')->find();
			$reg_time_path=date('Y/m/d/',$reg_time_res["reg_time"]);
			//下载头像到本地
			$face_url=$this->face_path.$reg_time_path;
			$tmp_file_name=$openid.'_tmp.jpg';
			@mkdir($face_url,0777,true);//创建文件夹
			$this->put_file_from_url_content($userinfo_arr["headimgurl"], $tmp_file_name,$face_url);
			$image_resize=new ImageResize();
			$local_img=$face_url.$tmp_file_name;
			$dst_img=$face_url.$openid.'_thum.jpg';
			$dst_img_weixin=$face_url.$openid.'_weixin.jpg';
			$image_resize->resize($local_img, $dst_img, 50, 50);//生成头像缩略图(二维码专用)
			$image_resize->resize($local_img, $dst_img_weixin, 150, 150);//生成头像缩略图
			unlink($local_img);//删除原图
			//-------------
			if($source_type==2) {
				$this->up_wx_member_info($eventkey,$openid,$wx_member,$wx_member_d);
			}

		}
		if(empty($member_id)){
			$member_id_res=$wx_member->where(array('openid'=>$openid))->field('member_id')->find();
			$member_id=$member_id_res["member_id"];
		}
		$userinfo_arr["member_id"]=$member_id;
		$userinfo_arr["wx_member_d"]=$wx_member_d;
		return $userinfo_arr;
	}
	private function spread_scaner($fromusername, $eventkey2, $message_template) {
		$member_model = Model('member');
		$sendtext = '';
		//对应的openid以store_member为主
		$m  = $member_model->table('member,store_member')->join('inner')->on('member.member_id = store_member.member_id')
		      ->field('member.*')->where(array('store_member.openid'=>$fromusername))->find(); 
		if(!empty($m)) {
			if(empty($m['inviter_id'])){
				// update scaner's inviter
				$wx_inviter = $member_model->where(array('member_id'=>$eventkey2))->find();
				if(!empty($wx_inviter)) {
					$inviter = $member_model->
					where(array('member_id'=>$wx_inviter['member_id']))
					->field('member_name')
					->find();
					if(!empty($inviter)) {
						$member_data['inviter_id'] = $wx_inviter['member_id'];
						$member_data['inviter_name'] = $inviter['member_name'];
						$member_data['invite_at'] = TIMESTAMP;
						Model('member')->editMember(array('member_id'=>$m['member_id']), $member_data);
					} else {
						$sendtext = '';//'邀请人不是会员';
					}
				} else {
					$sendtext = '';//'邀请人的微信记录丢失';
				}
			}
			$pid = $wx_m['pid'];
			$link="<a href='".BASE_SITE_URL."/wap_shop?act=weixin_active&openid=".encrypt($fromusername)."&id=".encrypt($pid)."'>点击领取</a>";//进入抽奖活动
			$link2="<a href='{$this->subscribe_link_url}&openid={$fromusername}'>点击这里</a>";//生成专属二维码引导页面
			$sendtext = str_replace(array('{点击领取}','{点击这里}'), array($link,$link2), str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["1"]));
		} else {
			$sendtext = '您的微信记录丢失,请您重新关注公众号';
		}
		$this->send_message_text($fromusername,$sendtext);
	}
	private function spread_update($fromusername, $eventkey2,$member_id) {
		$member_model = Model('member');
		$sendtext = '';
		$m_condition['member_id'] = $member_id;
		$m = $member_model->where($m_condition)->find();
		if(!empty($m)) {
			if(!empty($m)&&empty($m['inviter_id'])){
				// update scaner's inviter
				$wx_inviter = $member_model->where(array('member_id'=>$eventkey2))->find();
				if(!empty($wx_inviter)) {
					$inviter = $member_model->
					where(array('member_id'=>$wx_inviter['member_id']))
					->field('member_name')
					->find();
					if(!empty($inviter)) {
						$member_data['inviter_id'] = $wx_inviter['member_id'];
						$member_data['inviter_name'] = $inviter['member_name'];
						$member_data['invite_at'] = TIMESTAMP;
						Model('member')->editMember($m_condition, $member_data);
					} else {
						$sendtext = '';//'邀请人不是会员';
					}
				} else {
					$sendtext = '';//'邀请人的微信记录丢失';
				}
			}
		} else {
			$sendtext = '';//'您的微信记录丢失,请您重新关注公众号';
		}
	}
    private function fix_id($wx_id) {
        $n = strlen(OLDEVENT_FAZHI);
        if(OLDEVENT_FAZHI>$wx_id){
            return sprintf("%0{$n}s", $wx_id);
        } else {
            return $wx_id;
        }
    }
	/**
	 * 如果带参数的二维码,更新主用户的下线信息
	 */
	private function up_wx_member_info($eventkey,$openid,$wx_member,$wx_member_d){
		if(!empty($eventkey)){

			//如果带参数的二维码,更新主用户的下线信息
			$wx_member_where=array('id'=>intval($eventkey));

			$event_member=$wx_member->where($wx_member_where)->field('*')->find();//上线用户信息
			$new_lower_member=$openid.',';
			//strpos($event_member["lower_member"], $openid)=='';
			if(!in_array($openid, explode(',', $event_member["lower_member"]))){
				$wx_member->where($wx_member_where)->setInc('lower_num',1);//主用户下线加1
				//如果下线字段中还没有此用户,则更新此字段
				//echo "<p>".$event_member["lower_member"].'<p>';
				//echo "<p>".strpos($event_member["lower_member"], $openid)."</p>";
				if($event_member["lower_member"]!='' && !in_array($openid,explode(',', $event_member["lower_member"]))){
					$new_lower_member=$event_member["lower_member"].$openid.',';
				}

				if($event_member["lower_member_information"]!=''){
					$lower_member_info=json_decode($event_member["lower_member_information"],true);
				}

				$wx_member_d['nickname']=urlencode(str_replace(array('"',"'",'{','}','[',']',':',';','(',')'), array('','','','','','','','','',''), $wx_member_d['nickname']));//因转为中文，没有经过JSON编码，所有不能有双引号
				$lower_member_info[$openid]=$wx_member_d;
				//更新下线用户信息
				$lower_member_info_json=str_replace('\/', '/', json_encode($lower_member_info,JSON_UNESCAPED_UNICODE));
				$status=$wx_member->where($wx_member_where)->update(array('lower_member'=>$new_lower_member,'lower_member_information'=>$lower_member_info_json));
				//------------------------
				return $status;
			}
		}
	}
	/**
	 * 用户取消关注事件
	 */
	private function un_subscribe($openid,$wx_member,$greatetime){
		$wx_member->where(array('openid'=>$openid))->update(array('status'=>'0'));//更新用户状态
		//更新下线用户的状态
		$condition="lower_member like '%{$openid}%'";
		//$condition="id=2";
		$lower_member=$wx_member->where($condition)->field('id,lower_member,lower_member_information')->find();
		//print_r($lower_member);
		//找出上线用户记录
		if(!empty($lower_member)){
			$wx_member_where=array('id'=>intval($lower_member["id"]));
			$wx_member->where($wx_member_where)->setDec('lower_num',1);//用户下线减1
			//如果下线字段中存在此用户,则更新此字段
			$new_lower_member=str_replace($openid.',', '', $lower_member["lower_member"]);
			if($lower_member["lower_member_information"]!=''){
				$lower_member_info=json_decode($lower_member["lower_member_information"],true);
				if(strpos($lower_member["lower_member_information"], $openid)!==''){
					$lower_member_info[$openid]["status"]='0';
				}
			}
			//更新下线用户信息
			$lower_member_info_json=str_replace('\/', '/', json_encode($lower_member_info,JSON_UNESCAPED_UNICODE));
			$wx_member->where($wx_member_where)->update(array('reg_time'=>$greatetime,'lower_member'=>$new_lower_member,'lower_member_information'=>$lower_member_info_json));
			//------------------------
		}
	}
	/**
	 * 获取最少关注人数
	 */
	private function get_lower_rule_num(){
		$lower_rule_num=Model('wx_active_rule')->where(array('source_type'=>2,'store_id'=>$this->store_id))->order('lower_num asc')->field('lower_num')->limit('1')->find();//查询最少关注人数
		return $lower_rule_num;
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
			if($lower_num==$v["lower_num"]){
				return $v;exit();
			}
		}
	}
	/**
	 * 发送用户专属二维码图片
	 * @param $openid
	 * @param $userinfo (nickname,id,media_id,reg_time)
	 * @param $image_file
	 */
	private function send_user_code_img($openid, $userinfo,$source_type='10',$image_file=''){
		$nickname=$userinfo["nickname"];
		$scene_id=$userinfo["id"];
		if($image_file!=''){
			$media_id=$this->up_wx_image($image_file);
		}else{
			//判断二维码图片是否过期和media_id是否存在
			$reg_time_path=date('Y/m/d/',$userinfo["reg_time"]);
			$face_url=$this->face_path.$reg_time_path.$openid.'_thum.jpg';
			//如果头像不存在，则自动生成
			if(!file_exists($face_url)){
				$tmp_file_name=$openid.'_tmp.jpg';
				$local_img=$this->face_path.$tmp_file_name;
				@mkdir($this->face_path.$reg_time_path,0777,true);//创建文件夹
				$this->put_file_from_url_content($userinfo["headimgurl"], $tmp_file_name,$this->face_path);
				$imageresize=new ImageResize();
				$imageresize->resize($local_img, $face_url, 50, 50);//生成头像缩略图(二维码专用)
				unlink($local_img);//删除原图
			}
			$image_filename=$this->wx_img_path.$reg_time_path."{$openid}.jpg";
			if(file_exists($image_filename) && (filemtime($image_filename)+86400*3)>=time() && !empty($userinfo["media_id"])){
				$media_id=$userinfo["media_id"];
			}else{
				$scene_id = $this->fix_id($scene_id);
				$media_id=Model('wx_member')->creater_wx_user_code_img($this->face_path,$this->wx_img_path,$openid, $nickname, $scene_id,$source_type,$reg_time_path);
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
	/**
	 * curl 上传图片-----
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
	 	}else{
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
	public function curl_get_testOp(){
		echo 'curl_get_test  ok';
	}
	/**
	 * 自动回复
	 */
	private function MsgType_text_send($textTpl, $fromUsername, $toUsername, $time, $msgType,$keyword,$message_template){
		$condition["isclose"]=0;
		$condition["keywords"]=array('like','%'.strtoupper($keyword).'%');
		$condition["store_id"]=$this->store_id;
		$auto_reply_res=Model('wx_auto_reply')->where($condition)->find();
		if(!empty($auto_reply_res["content"])){
			$contentStr=str_replace(array('{openid}','&amp;'), array($fromUsername,'&'), $auto_reply_res["content"]);
			$msgType = "text";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
		}else{
			$this->transfer_customer_service($fromUsername, $toUsername, $message_template);
		}
	}
	/**
	 * 自动处理多客服
	 */
	private function transfer_customer_service($fromUsername, $toUsername, $message_template){
		$time = time();
// 		$textTpl = "<xml>
// <ToUserName><![CDATA[%s]]></ToUserName>
// <FromUserName><![CDATA[%s]]></FromUserName>
// <CreateTime>%s</CreateTime>
// <MsgType><![CDATA[transfer_customer_service]]></MsgType>
// </xml>";
// 		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
// 		echo $resultStr;
// 		exit;
		// 多客服启动之前系统默认的回复
		$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
		$contentStr=str_replace(array('<br/>','<br />','&gt;'), array("\n","\n",'>'), $message_template["10"]);
		$msgType = "text";
		$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
		echo $resultStr;
		exit;
	}
	//--------------------------------------以下为测试--------------------------------------------------------------
}
?>
