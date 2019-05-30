<?php 
/**
 * 优惠券激活页面
 * @author susu
 *
 */
class voucher_activationControl extends  BaseHomeControl{

	public function indexOp(){
		
		Tpl::showpage('voucher_activation','null_layout');
	}
	public function voucher_checkOp(){
		if(empty($_GET["voucher_code"]))exit();
		$voucher_code=$_GET["voucher_code"];
		$state=$this->check($voucher_code);
		echo json_encode($state);
	}
	public function voucher_activationOp(){
		if(empty($_GET["voucher_code"]))exit();
		$voucher_code=$_GET["voucher_code"];
		$result=$this->check($voucher_code);
		//当使用了false ,true,login,使用===检查表达式类型
		if($result["state"]==='login'||$result["state"]===false){
			exit(json_encode($result));
		}elseif($result["state"]===true){
			$state_up=Model()->table('voucher')->where(array('voucher_code'=>$voucher_code))->update(array('voucher_owner_id'=>$_SESSION["member_id"],'voucher_owner_name'=>$_SESSION["member_name"],'voucher_active_date'=>time()));
			if($state_up){
				echo json_encode(array('state'=>true,'msg'=>'激活成功！'));
			}
		}
	}
	private function check($voucher_code){
		if(!isset($_SESSION["member_id"])){
			return array('state'=>'login','msg'=>'请先登录');exit();
		}

		$condition["voucher_code"]=$voucher_code;
		$condition["voucher_end_date"]=array('gt',time());
		$state=Model()->table('voucher')->where($condition)->count('voucher_id');
		if($state=='0'){
			return array('state'=>false,'msg'=>'此券不能使用');exit();
		}
		$condition2["voucher_code"]=$voucher_code;
		$condition2["voucher_owner_id"]=array('gt',0);
		$state2=Model()->table('voucher')->where($condition2)->count('voucher_id');
		if($state2>0){
			return array('state'=>false,'msg'=>'此券已使用');exit();
		}
		return array('state'=>true,'msg'=>'此券可以使用');exit();
	}

}