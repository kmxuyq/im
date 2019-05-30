<?php
/**
 * @author SUSU
 * 手机号注册并领取礼券
 */
defined('InShopNC') or exit('Access Invalid!');

class mobile_hongbaoControl extends BaseHomeControl {
	private $voucher_t_id;
	private $voucher_arr;
	public function __construct(){
		parent::__construct();
		$voucher=decrypt($_GET["voucher"]);
		$this->voucher_arr=array('21','22');
		$this->voucher_t_id=str_replace(array('a30','a100'), $this->voucher_arr,'a'.$voucher);
	}
	public function indexOp(){
		if(empty($_GET["voucher"]))exit();
		Tpl::output('voucher',decrypt($_GET["voucher"]));
		if(!empty($_GET["user"])&&$_GET["user"]=='mobile'){
			Tpl::showpage('mobile_hongbao','null_layout');
		}else{
			Tpl::showpage('mobile_hongbao_pc','null_layout');
		}
	}
	/**
	 * 统一发送身份验证码
	 */
	public function send_auth_codeOp() {
		if(empty($_GET["mobile"]))exit();
		if (!in_array($_GET['type'],array('email','mobile'))) exit();
		$member_info=array();
		$mobile=$_GET["mobile"];
		$status=$this->check_member_mobile($mobile);
		if(!$status['state']){
			exit(json_encode(array('state'=>'false','msg'=>$status["msg"])));
		}
		$verify_code = rand(100,999).rand(100,999);
		$data = array();
		$data['auth_code'] = $verify_code;
		$data['send_acode_time'] = TIMESTAMP;
		$_SESSION["send_auth_code"]=$data;//创建session
	
		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));
	
		$param = array();
		$param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
		$param['verify_code'] = $verify_code;
		$param['site_name']	= C('site_name');
		$subject = ncReplaceText($tpl_info['title'],$param);
		$message = ncReplaceText($tpl_info['content'],$param);
		if ($_GET['type'] == 'email') {
			$email	= new Email();
			$result	= $email->send_sys_email($member_info["member_email"],$subject,$message);
		} elseif ($_GET['type'] == 'mobile') {
			//$sms = new Sms();
			//$result = $sms->send($mobile,$message);
			$sms=new SendCms();
			$result=$sms->send_code($mobile, $verify_code);
		}
		if ($result) {
			exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
		} else {
			exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
		}
	}
	/**
	 * 提交时ajax校验手机号唯一和验证码是否正确
	 */
	public function checkSubmitOp(){
		if(empty($_GET["mobile"])||empty($_GET["code"]))exit();
		$mobile=$_GET["mobile"];
		$code=$_GET["code"];
		$password=$_GET["password"];
		
		$status=$this->check_member_mobile($mobile,$password);
		if(!$status['state']){
			exit(json_encode(array('state'=>false,'msg'=>$status["msg"])));
		}
		if (!isset($_SESSION["send_auth_code"]) || $_SESSION["send_auth_code"]['auth_code'] != $code || TIMESTAMP - $_SESSION["send_auth_code"]['send_acode_time'] > 18000) {
			//echo '验证码已被使用或超时，请重新获取验证码';
			exit(json_encode(array('state'=>false,'msg'=>'验证码已被使用或超时，请重新获取验证码')));
		}
		exit(json_encode(array('state'=>true,'msg'=>'')));
	}
	//ajax校验手机号是否已被注册
	public function checkmobileOp(){
		if(empty($_GET["mobile"]))exit();
		$status=$this->check_member_mobile($_GET["mobile"]);
		if(!$status['state']){
			exit(json_encode(array('state'=>false,'msg'=>$status["msg"])));
		}
		exit(json_encode(array('state'=>true,'msg'=>'')));
	}
	/**
	 * 判断手机号是否注册和代金券是否领取过
	 * @param $mobile手机号
	 * @param $voucher_t_id代金券模板ID
	 * return 返回false/true
	 */
	private function check_member_mobile($mobile,$password=''){	
		$member_info_res=Model('member')->where("member_name='{$mobile}' or member_mobile='{$mobile}'")->field('member_id,member_passwd')->find();
		if(!empty($member_info_res["member_id"])){
			//如果用户存在，查看手机号和密码是否正确
			if($password!='' && $member_info_res["member_passwd"]!=md5($password)){
				return array('state'=>false,'msg'=>'手机和密码不匹配！');exit();
			}
			//查看是否已领取过，只能领取一次
			$member_id=$member_info_res["member_id"];
			list($aa,$bb)=$this->voucher_arr;
			$voucher_count=Model()->table('voucher')->where(array('voucher_owner_id'=>$member_id,'voucher_t_id'=>array('in',$aa.','.$bb)))->count();
			if($voucher_count>0){
				return array('state'=>false,'msg'=>'优惠券每人只能领取一次，您已经领取过了！');exit();
			}
		}
		return array('state'=>true,'msg'=>'');
	}
	public function hongbao2Op(){
		if(empty($_GET["voucher"])||empty($_GET["mobile"]))exit();//此行放在最后
		$voucher=decrypt($_GET["voucher"]);
		Tpl::output('voucher',$voucher);//代金券类型30/100
		if(!empty($_GET["user"])&&$_GET["user"]=='mobile'){
			Tpl::showpage('mobile_hongbao2','null_layout');
		}else{
			Tpl::showpage('mobile_hongbao2_pc','null_layout');
		}
	}
	public function hongbao2_subOp(){
		if(!empty($_POST)){
			
			$mobile=$_POST["mobile"];
			$code=$_POST["auth_code"];
			$password=$_POST["password"];
			$voucher=decrypt($_POST["voucher"]);//代金券模板类别加密（30/100）
			$status=$this->check_member_mobile($mobile,$password);
			if(!$status['state']){
				exit(json_encode(array('state'=>false,'msg'=>$status["msg"])));
			}
			if ($_SESSION["send_auth_code"]['auth_code'] != $code || TIMESTAMP - $_SESSION["send_auth_code"]['send_acode_time'] > 18000) {
				exit(json_encode(array('state'=>false,'msg'=>'验证码已被使用或超时，请重新获取验证码！')));
			}
			//查看用户是否存在，不存在则注册用户，已存在则更新登陆状态
			$member_info=Model('member')->where("member_name='{$mobile}' or member_mobile='{$mobile}'")->field('member_id,member_name')->find();
			
			if(empty($member_info)){
				$member_info=Model('member')->weixin_register_model($mobile,$password,'','','','','','',$mobile);
			}else{
				//通过member_id登陆
				Model('member')->weixin_login($member_info["member_id"],'member_id');
			}
				
			//写入代金券
			if(!empty($member_info)){
				//添加代金券信息
				$voucher_t_id=$this->voucher_t_id;
				$template_info=Model('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
				$insert_arr = array();
				$insert_arr['voucher_code'] = $this->get_voucher_code($member_info["member_id"]);
				$insert_arr['voucher_t_id'] = $template_info['voucher_t_id'];
				$insert_arr['voucher_title'] = $template_info['voucher_t_title'];
				$insert_arr['voucher_desc'] = $template_info['voucher_t_desc'];
				$insert_arr['voucher_start_date'] = time();
				$insert_arr['voucher_end_date'] = $template_info['voucher_t_end_date'];//time()+365*86400;//
				$insert_arr['voucher_price'] = $template_info['voucher_t_price'];
				$insert_arr['voucher_limit'] = $template_info['voucher_t_limit'];
				$insert_arr['voucher_store_id'] = $template_info['voucher_t_store_id'];
				$insert_arr['voucher_state'] = 1;
				$insert_arr['voucher_active_date'] = time();
				$insert_arr['voucher_owner_id'] = $member_info["member_id"];
				$insert_arr['voucher_owner_name'] = $member_info["member_name"];
				$result = Model()->table('voucher')->insert($insert_arr);
				if($result){
					exit(json_encode(array('state'=>true,'msg'=>'')));
					//callmsg('红包领取成功！','?act=member_voucher');
				}else{
					exit(json_encode(array('state'=>false,'msg'=>'提交失败！')));
				}
			}
		}
	}
	/**
	 * 获取代金券编码
	*/
	private function get_voucher_code($member_id) {
		return mt_rand(10,99)
		. sprintf('%010d',time() - 946656000)
		. sprintf('%03d', (float) microtime() * 1000)
		. sprintf('%03d', (int) $member_id % 1000);
	}
	public function testOp(){
		Tpl::showpage('mobile_hongbao2_pc','null_layout');
	}
	
}