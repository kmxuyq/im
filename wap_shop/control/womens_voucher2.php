<?php 
/**
 * 38妇女节代金券活动,100元领取，满300元可消费
 * @author susu
 *
 */
class womens_voucher2Control extends  BaseHomeControl{
	private $voucher_t_id_str='26,27';//正式(26,27)';//50,100元，满51,300元可消费
	public function indexOp(){
		$appid=C("weixin_appid");
		$appacrect=C("weixin_appsecret");
		$jssdk = new JSSDK($appid, $appacrect);
		if($jssdk->is_wx_pro()){
			$signPackage = $jssdk->GetSignPackage();
			Tpl::output('signPackage',$signPackage);
			if(!empty($_GET["openid"])&&empty($_GET["code"])){
				$openid=$_GET["openid"];
				Model('member')->weixin_login($openid);
			}
		}
		Tpl::showpage('womens_voucher2','null_layout');
	}
	//领取代金券
	public function voucherOp(){
		if(empty($_GET["type"]))exit();
		$voucher_t_id_arr=explode(',',$this->voucher_t_id_str);
		$voucher_price=decrypt($_GET["type"]);
		if(!empty($_GET["voucher_t_id"])){
			$voucher_t_id=decrypt($_GET["voucher_t_id"]);
		}else{
			$voucher_t_id=str_replace(array('a50','a100'), array($voucher_t_id_arr[0],$voucher_t_id_arr[1]), 'a'.$voucher_price);
		}
		//写入代金券
		if(isset($_SESSION["member_id"])){
			//查看是否领取过
			$is_voucher=$this->check_is_voucher($voucher_t_id);
			if(!$is_voucher){
				//添加代金券信息
				//$voucher_start_date=str_replace(array(26,27), array(strtotime('2016-03-30'),strtotime('2016-04-01')), $voucher_t_id);
				$template_info=Model('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
				$insert_arr = array();
				$insert_arr['voucher_code'] = $this->get_voucher_code($_SESSION["member_id"]);
				$insert_arr['voucher_t_id'] = $template_info['voucher_t_id'];
				$insert_arr['voucher_title'] = $template_info['voucher_t_title'];
				$insert_arr['voucher_desc'] = $template_info['voucher_t_desc'];
				$insert_arr['voucher_start_date'] = $template_info['voucher_t_start_date'];
				$insert_arr['voucher_end_date'] = $template_info['voucher_t_end_date'];//time()+365*86400;//
				$insert_arr['voucher_price'] = $template_info['voucher_t_price'];
				$insert_arr['voucher_limit'] = $template_info['voucher_t_limit'];
				$insert_arr['voucher_store_id'] = $template_info['voucher_t_store_id'];
				$insert_arr['voucher_state'] = 1;
				$insert_arr['voucher_active_date'] = time();
				$insert_arr['voucher_owner_id'] = $_SESSION["member_id"];
				$insert_arr['voucher_owner_name'] = $_SESSION["member_name"];
				$result = Model()->table('voucher')->insert($insert_arr);
				if($result){
					exit(json_encode(array('state'=>'true','msg'=>'领取成功！')));
					//callmsg('红包领取成功！','?act=member_voucher');
				}else{
					exit(json_encode(array('state'=>'false','msg'=>'提交失败！')));
				}
			}else{
				exit(json_encode(array('state'=>'voucher_all','msg'=>'您已经领取过了！')));
			}
		}else{
			exit(json_encode(array('state'=>'login','msg'=>'请先登录！')));
		}
	}
	/**
	 * 这里只判断单张代金券
	 * 返回0为可以领取，1已领取过
	 */
	private function check_is_voucher($voucher_t_id){

		$condition['voucher_owner_id']=$_SESSION["member_id"];
		$condition['voucher_t_id']=$voucher_t_id;
		//$condition["voucher_active_date"]=array('gt'=>strtotime(date('Y').'-03-01'),'lt'=>strtotime(date('Y').'-03-30'));
		$is_voucher_count=Model()->table('voucher')->where($condition)->count('voucher_id');
		return $is_voucher_count;
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
	 echo "<meta charset='utf-8'/>";
		//50:nZiLo96IIJnRuz5epA6nGSW,100:l85m5S8VesFxGb8VtLlspxe,28:w5wXraW9_YtkURxjQTbymgi;118:vlSiXz0uaZg1cWe2kGvlvyi,32:KcL-a-MxC9IfEvKd39Kp7Jp
		//58:VssZSsd5lNoajVBTfWMXZJd,33:aPLxxYhhb3dOE-HTMD9as78
		echo "58:".encrypt(58).",33:".encrypt(33).',28：'.encrypt(28);
	}
}