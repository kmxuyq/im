<?php 
/**
 * 微信事件处理入口
 * @author susu
 */
define('InShopNC', true);
class test2Control extends  BaseHomeControl{

 	private $appid;
	private $appacrect;
	/*private $send_message_url;
	private $face_path;//微信头像路径
	private $wx_img_path;//用户专属二维码图片路径
	private $wx_present_url;//礼品领取地址
	private $get_wx_token_url;//curl获取token地址*/
	
	
	public function __construct(){
		//parent::__construct();
		
		$this->appid=$GLOBALS["setting_config"]["weixin_appid"];
		$this->appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		/*$this->send_message_url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';
		$this->face_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/head/';
		$this->wx_img_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/'.date('Y-m');
		$this->wx_present_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_sel';//GET参数:openid(加密的openid),lower(加密的下线人数)*/
		
	} 
	public function indexOp(){
		$tablepre=$GLOBALS["setting_config"]["tablepre"];
		echo $tablepre;
		exit();
		//$time=strtotime("-5 day");
		
		$tt=Model('wx_member')->where("lower_member !=''")->order('id desc')->limit(50)->select();
		//$tt=Model('wx_member')->where("1=1")->order("id desc")->limit(50)->select();
		//$tt2=Model('wx_present_member')->where("1=1")->order("id desc")->limit(10)->select();
		//$tt=Model('wx_member')->where("member_id=37812")->find();
		print_r($tt);
		//print_r($tt2);
	}
	public function auto_loginOp(){
		$openid='o-lvAv6y9z0TOwXUmbPTpNy0yZdc';
		if(!isset($_SESSION["member_id"])){
			//自动登陆
			$status=Model('member')->weixin_login_handle($openid,'openid');
		}
		print_r($_SESSION);
	}

	public function testOp(){
		/* $voucher=Model()->table('voucher');
		$str='{"voucher_code":"0","voucher_t_id":"7","voucher_title":"20000\u6d88\u8d39 \u53ef\u4ee5\u51cf\u5c111000","voucher_desc":"\u4ee3\u91d1\u5238\u63cf\u8ff0\uff1a\u4ee3\u91d1\u5238\u63cf\u8ff0\uff1a\u4ee3\u91d1\u5238\u63cf\u8ff0\uff1a\u4ee3\u91d1\u5238\u63cf\u8ff0\uff1a\u4ee3\u91d1\u5238\u63cf\u8ff0\uff1a","voucher_start_date":1451444796,"voucher_end_date":1452308796,"voucher_price":"1000","voucher_limit":"20000.00","voucher_store_id":"7","voucher_state":"1","voucher_active_date":1451444796,"voucher_type":"0","voucher_owner_id":"83","voucher_owner_name":"\u5c0f\u80fd\u732b","voucher_order_id":"0"}';
		$voucher_d=(json_decode($str,true));
		$voucher_id=$voucher->insert($voucher_d);//插入表
		echo $voucher_id; */
		/* $wx_member=Model('wx_member');
		$where_openid=array('openid'=>'ocuxlxEkoyzYVxnVoj3Dc3AS5xgw');
		$member_info=$wx_member->where($where_openid)->field('*')->find();//active_lower_member,member_id,nickname
		print_r($member_info); */
	}
	/* public function up_wx_member_informationOp(){
		exit();
		set_time_limit(15*60);
		ini_set("memory_limit", "1024M");
		$wx_member=Model('wx_member');
		$wx_member_res=$wx_member->where("lower_member!=''")->select();
		//$wx_member_res=$wx_member->where("member_id=37998")->select();
		//echo count($wx_member_res);exit();
		foreach($wx_member_res as $v){
			$openid_arr=array();
			$openid_str2='';
			$openid_arr=explode(',', $v["lower_member"]);
			foreach($openid_arr as $v2){
				if(!empty($v2)){
					$openid_str2.="'{$v2}',";
				}
			}
			$openid_str3=substr($openid_str2,0,-1);
			//echo $openid_str3;
			$wx_member_info=$wx_member->where("openid in({$openid_str3})")->select();
			$wx_member_arr=array();
			foreach ($wx_member_info as $v3){
				$wx_member_d['member_id']=$v3["member_id"];
				$wx_member_d['openid']=$v3["openid"];
				$wx_member_d['reg_time']=$v3['reg_time'];
				$wx_member_d['source_type']=$v3["source_type"];
				$wx_member_d['nickname']= urlencode(str_replace(array('"',"'",'{','}','[',']',':',';','(',')'), array('','','','','','','','','',''), $v3['nickname']));//因转为中文，没有经过JSON编码，所有不能有双引号
				$wx_member_d['sex']=$v3['sex'];
				$wx_member_d['country']=$v3['country'];
				$wx_member_d['city']=$v3['city'];
				$wx_member_d['province']=$v3['province'];
				$wx_member_d['headimgurl']=$v3['headimgurl'];
				$wx_member_d['status']='1';//已关注1/未关注0
				$wx_member_d["pid"]=$v["id"];
				$wx_member_arr[$v3["openid"]]=$wx_member_d;
			}
			//print_r($wx_member_arr);
		    //echo '<br/>***************************************************************************************************************************************************<br/>';
			$lower_member_info_json=str_replace('\/', '/', json_encode($wx_member_arr,JSON_UNESCAPED_UNICODE));
			//echo $lower_member_info_json;
			//$status.=$wx_member->where(array('id'=>$v["id"]))->update(array('lower_member_information'=>$lower_member_info_json));
		}
		echo $status;
	} */
		
	public function order_searchOp(){
		if($_GET["code"]!=''){
			$order=Model()->table('delivery_order')->where(array('shipping_code'=>$_GET["code"]))->find();
			echo $order["order_id"];
		}
	}
	public function copy_wx_imgOp(){
		//exit();
		set_time_limit(15*60);
		ini_set("memory_limit", "1024M");
		$list=Model('wx_member')->where("headimgurl!=''")->select();
		$face_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/head/';
		foreach($list as $v){
			$reg_time_path=date('Y/m/d/',$v["reg_time"]);
			$ds_path=$face_path.$reg_time_path;
			$face_file_name=$v["openid"].'_weixin.jpg';
			$thum_file_name=$v["openid"].'_thum.jpg';
			$local_path=$face_path;
			@mkdir($ds_path,0777,true);
			copy($local_path.$face_file_name,$ds_path.$face_file_name);
			copy($local_path.$thum_file_name,$ds_path.$thum_file_name);
		}
	}
	public function copy_wx_code_imgOp(){
		//exit();
		set_time_limit(15*60);
		ini_set("memory_limit", "1024M");
		$list=Model('wx_member')->where("headimgurl!=''")->select();
		$img_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/';
		foreach($list as $v){
			$reg_time_path=date('Y/m/d/',$v["reg_time"]);
			$ds_path=$img_path.$reg_time_path;
			$img_file_name=$v["openid"].'.jpg';
			$local_path=$img_path;
			@mkdir($ds_path,0777,true);
			copy($local_path.$img_file_name,$ds_path.$img_file_name);
		}
	}
	public function del_member_mobileOp(){
		$state=Model()->table('address')->where("city_id is null")->delete();
		echo $state;exit();
		if(!empty($_GET["mobile"])){
			$mobile=$_GET["mobile"];
			$memberinfo=Model('member')->where("member_mobile={$mobile}")->field('member_id')->find();
			if(!empty($memberinfo)){
				$status=Model()->table('voucher')->where("voucher_owner_id={$memberinfo["member_id"]} and voucher_t_id in(21,22)")->delete();
			}
			echo $status;
		}
	}

	private function is_weixin(){
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
	/*public function up_wx_member_source_typeOp(){
	 * 	exit();
		 if($_GET["comm"]=='2016-1-12'){
			$time=strtotime("-1 day");
			//$status=Model('wx_member')->where("source_type=1 and reg_time<{$time}")->update(array('source_type'=>11));
			//$status.=Model('wx_member')->where("source_type=2 and reg_time<{$time}")->update(array('source_type'=>1));
			//$status.=Model('wx_member')->where("source_type=11 and reg_time<{$time}")->update(array('source_type'=>2));
			//$status.=Model('wx_member')->where('1=1')->update(array('lower_member_info'=>''));
			echo $status;
		} 
	}*/
	/* //批量插入外发代金券
	public function voucher_insert_allOp(){
		set_time_limit(15*60);
		ini_set("memory_limit", "1024M");
		$count=Model()->table('voucher')->where(array('voucher_start_date'=>strtotime('2016-03-02 00:00')))->count();
		echo $count;
		exit();
		//if($_GET["voucher_t_id"]!=''){
			//添加代金券信息
			$voucher_t_id=25;//正式的25,测试24
			$pre_voucher_id_res=Model()->table('voucher')->field('voucher_id')->order('voucher_id desc')->limit(1)->find();			
			$template_info=Model('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
			$insert_arr=array();
			for($i=1;$i<=25000;$i++){
				//echo $pre_voucher_id_res["voucher_id"]+$i.'--'.rand(111111, 999999).time()."|";
				$voucher_code=substr(($pre_voucher_id_res["voucher_id"]+$i).rand(111111, 999999),0,6);
				
				$insert_arr['voucher_code'] = $voucher_code;
				$insert_arr['voucher_t_id'] = $voucher_t_id;
				$insert_arr['voucher_title'] = $template_info['voucher_t_title'];
				$insert_arr['voucher_desc'] = $template_info['voucher_t_desc'];
				$insert_arr['voucher_start_date'] = strtotime('2016-03-02 00:00');
				
				$insert_arr['voucher_end_date'] = $template_info['voucher_t_end_date'];//time()+365*86400;//
				$insert_arr['voucher_price'] = $template_info['voucher_t_price'];
				$insert_arr['voucher_limit'] = $template_info['voucher_t_limit'];
				$insert_arr['voucher_store_id'] = $template_info['voucher_t_store_id'];
				$insert_arr['voucher_state'] = 1;
				$insert_arr['voucher_active_date'] = time();
				$insert_arr['voucher_owner_id'] =0;
				$insert_arr['voucher_owner_name'] = '';
				$insertall_arr[]=$insert_arr;
				$voucher_code_str.=$voucher_code."\r\n";
			}
			
			$count2=Model()->table('voucher')->where(array('voucher_t_id'=>$voucher_t_id))->count();
			if(count2<270000){
				//$result = Model()->table('voucher')->insertAll($insertall_arr);
				 if($result){
					 $this->log_result_code($voucher_code_str,'--voucher_code');
				 }
			}
			echo $result;
			echo "<br/>总数：".Model()->table('voucher')->where(array('voucher_t_id'=>$voucher_t_id))->count();
			//print_r($insertall_arr);
		//}
	} */
	 //批量插入外发代金券2
	/* public function voucher_insert_allOp(){
		echo "<meta charset='utf-8'/>";
		set_time_limit(15*60);
		ini_set("memory_limit", "1024M");
		$voucher_t_id=34;
		echo "全部：".Model()->table('voucher')->where('1=1')->count().'<br/>';
		$count=Model()->table('voucher')->where(array('voucher_t_id'=>$voucher_t_id))->count();
		echo $count;
		exit();
		
		//添加代金券信息
		
		$pre_voucher_id_res=Model()->table('voucher')->field('voucher_id')->order('voucher_id desc')->limit(1)->find();
		$template_info=Model('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
		$insert_arr=array();
		for($i=1;$i<=25000;$i++){
			//echo $pre_voucher_id_res["voucher_id"]+$i.'--'.rand(111111, 999999).time()."|";
			$voucher_code=substr(($pre_voucher_id_res["voucher_id"]+$i).rand(111111, 999999),0,8);
		
			$insert_arr['voucher_code'] = $voucher_code;
			$insert_arr['voucher_t_id'] = $voucher_t_id;
			$insert_arr['voucher_title'] = $template_info['voucher_t_title'];
			$insert_arr['voucher_desc'] = $template_info['voucher_t_desc'];
			$insert_arr['voucher_start_date'] =strtotime("2016-04-01");
		
			$insert_arr['voucher_end_date'] = $template_info['voucher_t_end_date'];//time()+365*86400;//
			$insert_arr['voucher_price'] = $template_info['voucher_t_price'];
			$insert_arr['voucher_limit'] = $template_info['voucher_t_limit'];
			$insert_arr['voucher_store_id'] = $template_info['voucher_t_store_id'];
			$insert_arr['voucher_state'] = 1;
			$insert_arr['voucher_active_date'] = time();
			$insert_arr['voucher_owner_id'] =0;
			$insert_arr['voucher_owner_name'] = '';
			$insertall_arr[]=$insert_arr;
			$voucher_code_str.=$voucher_code."\r\n";
		}
		$count2=Model()->table('voucher')->where(array('voucher_t_id'=>$voucher_t_id))->count();
		if(count2>=100010){
			echo "full";
		}else{
			$result = Model()->table('voucher')->insertAll($insertall_arr);
			if($result){
				$this->log_result_code($voucher_code_str,'--voucher_code-34-');
			}
		}
		echo $result;
		echo "<br/>总数：".Model()->table('voucher')->where(array('voucher_t_id'=>$voucher_t_id))->count();
		//print_r($insertall_arr);
	} */
	//批量插入F码
	//导入抽奖码，格式为记事本
	/* public function input_codeOp(){
		exit();
		echo "<meta charset='utf-8'/>";
		set_time_limit(15*60);
		ini_set("memory_limit", "1024M");
		$txt_string	= file_get_contents("goods_fcode.txt");
		$common_id=100527;
		if($txt_string!=''){
			$code_arr=explode("\n",$txt_string);
			if(count($code_arr)>25000){
				callmsg("单次导入不能超过25000条!",-1);
			}
			$goods_fcode=Model('goods_fcode');
			for($i=0;$i<count($code_arr);$i++){
				if($code_arr[$i]!=''){
					//查看此条记录是否存在
					$count=$goods_fcode->where(array('fc_code'=>$code_arr[$i],'goods_commonid'=>$common_id))->count();
					if($count=='0'){
						$data[]=array('goods_commonid'=>$common_id,'fc_code'=>$code_arr[$i]);
					}
				}
			}
			$status=$goods_fcode->insertAll($data);
			if($status){
				echo ('导入成功，共导入'.count($data).'条');
			}else{
				echo ('导入失败');
			}
		}
	} */
	function log_result_code($word,$file)
	{
		$file_name = md5('dxj.1301'.date('Y-m-d')).$file.date('Y-m-d').'.log';
		$path      = $_SERVER["DOCUMENT_ROOT"].'/data/log/pay/';
		$file      = $path.$file_name;
		$fp        = fopen($file, "a");
		flock($fp, LOCK_EX);
		//$word="\n".date('Y-m-d H:i:s')."\n".base64_encode($word);
		fwrite($fp, $word);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	public function get_voucher_output_timeOp(){
		echo "<meta charset='utf-8'/>";
		if(!empty($_GET['date'])){
			$date=$_GET["date"];
		}else{
			$date=date('Y-m-d');
		}
		$start_time=strtotime($date.' 00:00:00');
		$end_time=strtotime($date.' 23:59:59');
		echo '<br/>'.date('Y-m-d H:i:s',$start_time).'--';
		echo date('Y-m-d H:i:s',$end_time).'<br/>';
		echo $date."数据sql:<br/>select * from az_voucher where voucher_owner_id >0 and voucher_t_id in(23,24,25,26,27) and voucher_active_date>{$start_time} and voucher_active_date<{$end_time}";
		
	}
	/* public function up_voucher_t_idOp(){
		exit();
		$list=Model()->table('voucher_template')->where("voucher_t_id>=22")->order("voucher_t_id desc")->select();
		//print_r($list);
		foreach($list as $v){
			$state.=Model()->table('voucher_template')->where("voucher_t_id={$v["voucher_t_id"]}")->setInc('voucher_t_id',1);
		}
		echo $state;
	} */
/* 	public function up_voucher_code_allOp(){
		exit();
		if($_GET["voucher_t_id"]!=''){
			//$pre_voucher_id_res=Model()->table('voucher')->field('voucher_id')->order('voucher_id desc')->limit(1)->find();
			//echo $pre_voucher_id_res["voucher_id"];
			
			$voucher_t_id=25;//25
			$list=Model()->table('voucher')->where("voucher_t_id={$voucher_t_id} and voucher_code>1000000")->field('voucher_id,voucher_code')->select();
			//print_r($list);
			$voucher_code_str='';
			$state_str='';
			echo count($list);
			for($i=0;$i<count($list);$i++){
				$code=substr($list[$i]["voucher_code"],0,6);
				$state_str.=Model()->table('voucher')->where(array('voucher_id'=>$list[$i]["voucher_id"]))->update(array('voucher_code'=>$code));
				$voucher_code_str.=$code."\r\n";
			}
			echo $state_str;
			file_put_contents('voucher_code.txt', $voucher_code_str);
		}
	} */
	/* public function up_voucher_endtime_allOp(){
		exit();
		$starttime=strtotime('2016-04-01');
		//$endtime=strtotime('2016-03-31 23:59:59');
		echo date('Y-m-d H:i:s',$starttime).'--';
		//$state=Model()->table('voucher')->where("voucher_t_id in(34)")->update(array('voucher_start_date'=>$starttime));
		echo $state;
	}  */
	public function fcode_searchOp(){
		if(empty($_GET["code"]))exit();
		$goods_fcode=Model('goods_fcode');
		$list=$goods_fcode->where(array('fc_code'=>$_GET["code"]))->find();
		print_r($list);
	}
}