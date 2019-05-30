<?php 
/**
 * 微信活动页面
 * @author susu
 *
 */
class weixin_activeControl extends  BaseHomeControl{
	private $message_template;
	private $present_success_url;//中奖后填写地址URL
	public function __construct(){
		parent::__construct();
		$this->message_template=include $_SERVER["DOCUMENT_ROOT"].'/data/weixin/message_template.php';
		//$this->present_success_url=BASE_SITE_URL.'/wap_shop/index.php?act=weixin_active&op=wx_present_success';
		$this->present_success_url=BASE_SITE_URL.'/wap_shop/index.php?act=active&op=index&type=appaly_goods&wx=2';
	}
	/**
	 * /wap_shop?act=weixin_active&openid=".encrypt($openid)."&id=encrypt($id)
	 */
	public function indexOp(){
		//普通用户直接发送生成专属二维码消息
		//扫带参二维码进来的用户:领取礼品,如果是每个人连下级最多5个礼品，中奖率十分之一,成功后跳转到成功页，失败后到失败页
		//显示抽奖页面
		if(empty($_GET["openid"])&&empty($_GET["id"]))exit();
		$openid=decrypt($_GET["openid"]);
		if(!empty($_GET["code"])){
			//微信自动登陆微商城
			$this->login($_GET["code"]);
		}
		$id=decrypt($_GET["id"]);//上线用户ID
		
		//echo $openid.'--'.$id.'--';
		$wx_member=Model('wx_member');
		$wx_present_member=Model('wx_present_member');
		//查询是否参加过
		$where_openid=array('openid'=>$openid);
		$member_info=$wx_member->where($where_openid)->field('active_lower_member,member_id,nickname')->find();
		//log_result('weixin_active:member_info'.json_encode($member_info).'--openid:'.$openid.'--'.$id, '','weixin');
		$active_lower_member=$member_info["active_lower_member"];
		
		//查询库存
		$pre_member=$wx_member->where(array('id'=>$id))->field('openid,stock')->find();//上线的用户信息
		$stock=$pre_member["stock"];
		
		//获取商品ID
		$active_rule_info=Model('wx_active_rule')->where(array('source_type'=>1))->field('*')->find();
		//log_result('active:active_rule_info:'.json_encode($active_rule_info), '','weixin');
		//print_r($active_rule_info);
		$goods_id=$active_rule_info["goods_id"];
		$title=$active_rule_info["title"];
		$message='';
		//已 抽奖
		if(in_array($openid,explode(',', $active_lower_member))){
			$present_member_res=$wx_present_member->where(array('member_id'=>$member_info["member_id"]))->order('status asc')->limit(1)->field('id,status')->find();
			if(!empty($present_member_res)){
				if($present_member_res["status"]=='0'){
					//中过奖未领取
					$present_member_id2=encrypt($present_member_res["id"]);
					$success_url=$this->present_success_url."&openid={$openid}&present_member_id={$present_member_id2}&goods_id=".encrypt($goods_id)."&source=zhongjiang";
					redirect($success_url);
				}elseif($present_member_res["status"]>0){
					//中过奖已领取
					$message=$this->message_template[9];//抱歉！您已经领取过该礼物了，继续邀请好友关注再次申领吧！;
					Tpl::output('message',$message);
					Tpl::showpage('wx_present_fail','null_layout');
					//echo $stock;
				}
			}else{
				//抽过未中奖
				$message=$this->message_template[6];//抱歉！此活动每人只能参加一次，你上次已参加过此活动了，请到微信的个人中心菜单中来生成自已的专属二维码吧！
				Tpl::output('message',$message);
				Tpl::showpage('wx_present_fail','null_layout');
			}
			//您已参加过了
			/* //您已参加过了
			$message=$this->message_template[6];//'抱歉！此活动每人只能参加一次，你上次已参加过此活动了，请到微信的个人中心菜单中来生成自已的专属二维码吧！';
			Tpl::output('message',$message);
			Tpl::showpage('wx_present_fail','null_layout');
			 */
			//echo $stock;
		}else{
			if($stock=='0'){
				//礼品已领取完了
				$message=$this->message_template[7];//'抱歉！活动礼品已派送完了，请到微信的个人中心菜单中来生成自已的专属二维码吧！';
				Tpl::output('message',$message);
				Tpl::showpage('wx_present_fail','null_layout');
			}else{
				//如果没有参加过,还有库存
				$code=rand(10,11);//rand(10, 19);
				$new_active_lower_member=$active_lower_member.$openid.',';
				//$code=='15'
				if($code=='10'){
					//中奖
					//写入中奖表，更新已参加过活动,转到成功页面，下一步填写收货地址
					//只能插一次
					$count=Model('wx_present_member')->where(array('member_id'=>$member_info["member_id"],'goods_id'=>$goods_id))->count('id');
					if($count==0){
						
						$voucher_id=0;
						$voucher_num=0;
						//判断是否有代金券，有则插入代金券表
						//log_result('weixin_active:active_rule_info:'.json_encode($active_rule_info)."--\n voucher_t_id:".$active_rule_info["voucher_t_id"], '','weixin');
						if(!empty($active_rule_info["voucher_t_id"])){
							$voucher_t_id=$active_rule_info["voucher_t_id"];
							$voucher_t_res=Model()->table('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
							$voucher_time=$active_rule_info["voucher_days"]*86400+time();//代金券有效期
							$voucher_num=$active_rule_info["voucher_num"];
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
								$voucher_d["voucher_owner_id"]=$member_info["member_id"];
								$voucher_d["voucher_owner_name"]=$member_info["nickname"];
								$voucher_d["voucher_order_id"]='0';
								//log_result('voucher_d:'.json_encode($voucher_d),'','weixin');
								$voucher_id=$voucher->insert($voucher_d);//插入表
								$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
								$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
							}
						}
						$present_member_data=array('title'=>$title,'openid'=>$openid,'member_id'=>$member_info["member_id"],'add_time'=>time(),'goods_id'=>$goods_id,'pre_openid'=>$pre_member["openid"],'source_type'=>1,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num);
						//log_result(json_encode($present_member_data),'','weixin');
						//插入中奖礼品表
						$present_member_id=Model('wx_present_member')->insert($present_member_data);
		
					}
					$message=$this->message_template[4];//'恭喜您领到了皇后亲赐的神秘之礼！';
					//自动登陆
					$this->login($openid, 'openid');
					//跳转到填写地址页面
					$present_member_id2=encrypt($present_member_id);
					
					$success_url=$this->present_success_url."&openid={$openid}&present_member_id={$present_member_id2}&goods_id=".encrypt($goods_id)."&source=zhongjiang";
					redirect($success_url);
					/* Tpl::output('message',$message);
					//echo $message.'m1';
					Tpl::output('goods_id',$goods_id);
					Tpl::output('openid',$openid);
					Tpl::output('present_member_id',$present_member_id);
					Tpl::showpage('wx_present_success','null_layout'); */
				}else{
					//未中,生成自已的专属二维码
					$message=$this->message_template[5];//'很遗憾 :(<br/>没有领到皇后的恩赐';
					//echo $message.'m2';
					Tpl::output('message',$message);
					Tpl::showpage('wx_present_fail','null_layout');
				}
				//更新放在最后
				$wx_member->where($where_openid)->update(array('active_lower_member'=>$new_active_lower_member));//更新已参加过活动
			}
		}
	}
	/**
	 * 礼品选择
	 */
	public function wx_present_selOp(){
		//显示所有礼品(包括关注和抽奖的)，(领取按钮)可领取的为活动状态，不可领取或不满足领取条件的为灰色不可以选择状态
		if(empty($_GET["openid"]))exit();
		$openid=decrypt($_GET["openid"]);
		$lower_num=decrypt($_GET["lower"]);//下线人数
		if(!empty($_GET["code"])){
			//微信自动登陆微商城
			$this->login($_GET["code"],'code');
		}else{
			$this->login($openid,'openid');
		}
		$errmsg='';
		$status_res=Model('wx_present_member')->where(array('openid'=>$openid,'lower_num'=>$lower_num))->field('status,add_time')->find();
		//print_r($status_res);
		if(empty($status_res))$errmsg='没有找到相关记录';
		if(($status_res["add_time"]+86400*15)<time())$errmsg=$this->message_template[8];//"抱歉！您的礼物已超过领取时间，继续邀请好友关注再次申领吧！";
		if($status_res["status"]>0)$errmsg=$this->message_template[9];//"抱歉！您已经领取过该礼物了，继续邀请好友关注再次申领吧！";
		if($errmsg==''){
			$wx_active_rule=Model('wx_active_rule');
			$active_rule_res=$wx_active_rule->select();//where(array('source_type'=>2))->
			$present_list=array();
			foreach($active_rule_res as $k=>$v){
				$present_list[]=Model()->query("select a.*,b.goods_image,b.goods_id as present_goods_id from az_wx_active_rule as a,az_goods as b where a.id={$v["id"]} and b.goods_id in({$v["goods_id"]})");
			}
			//print_r($present_list);
			Tpl::output('list',$present_list);
			Tpl::output('openid',$openid);
			Tpl::output('lower_num',$lower_num);
		}else{
			Tpl::output('errmsg',$errmsg);
		}
		Tpl::showpage('wx_present_sel');
	}
	/**
	 * 领取成功最终页面
	 */
	public function wx_present_success2Op(){
		$message=$this->message_template[3];
		Tpl::output('message',$message);
		Tpl::showpage('wx_present_success2');
	}
	public function wx_present_successOp(){
		$message=$this->message_template[4];
		Tpl::output('message',$message);
		Tpl::showpage('wx_present_success');
	}
	
	/**
	 * 生成专属二维码/普通用户关注二维码点击领取页面
	 * 参数：GET openid
	 */
	public function wx_present_codeOp(){
		Tpl::showpage('wx_present_code','null_layout');
	}
	/**
	 * 微信自动登陆
	 * @param  $微信code
	 */
	private function login($code,$source_type){
		//自动登陆
		if(!isset($_SESSION["member_id"])){
			$status=Model('member')->weixin_login_handle($code,$source_type);
			return $status;
		}
	}
	public function testOp(){
		$openid='ocuxlxMWj0MVkfFgHxaIUDThHM1o';
		$wx_member=Model('wx_member');
		$wx_present_member=Model('wx_present_member');
		//查询是否参加过
		$where_openid=array('openid'=>$openid);
		$member_info=$wx_member->where($where_openid)->field('active_lower_member,member_id,nickname')->find();
		echo 'member_info:';
		print_r($member_info);
		//获取商品ID
		$active_rule_info=Model('wx_active_rule')->where(array('source_type'=>1))->field('*')->find();
		echo '<br/>active_rule_info:';
		print_r($active_rule_info);
		//print_r($active_rule_info);
		$goods_id=$active_rule_info["goods_id"];
		$title=$active_rule_info["title"];
		
		//中奖
		//写入中奖表，更新已参加过活动,转到成功页面，下一步填写收货地址
		//只能插一次
		$count=Model('wx_present_member')->where(array('member_id'=>$member_info["member_id"],'goods_id'=>$goods_id))->count('id');
		echo '<br/>count:'.$count;
		if($count==0){
			
			$voucher_id=0;
			$voucher_num=0;
			//判断是否有代金券，有则插入代金券表
			echo "<br/>voucher_t_id:".$active_rule_info["voucher_t_id"];
			//log_result('weixin_active:active_rule_info:'.json_encode($active_rule_info)."--\n voucher_t_id:".$active_rule_info["voucher_t_id"], '','weixin');
			if(!empty($active_rule_info["voucher_t_id"])){
				$voucher_t_id=$active_rule_info["voucher_t_id"];
				$voucher_t_res=Model()->table('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
				$voucher_time=$active_rule_info["voucher_days"]*86400+time();//代金券有效期
				$voucher_num=$active_rule_info["voucher_num"];
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
					$voucher_d["voucher_owner_id"]=$member_info["member_id"];
					$voucher_d["voucher_owner_name"]=$member_info["nickname"];
					$voucher_d["voucher_order_id"]='0';
					print_r($voucher_d);
					$voucher_id=$voucher->insert($voucher_d);//插入表
					$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
					$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
				}
			}
			//$present_member_data=array('title'=>$title,'openid'=>$openid,'member_id'=>$member_info["member_id"],'add_time'=>time(),'goods_id'=>$goods_id,'pre_openid'=>$pre_member["openid"],'source_type'=>1,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num);
			//log_result(json_encode($present_member_data),'','weixin');
			//插入中奖礼品表
			//$present_member_id=Model('wx_present_member')->insert($present_member_data);

		}
	
	}
}
?>