<?php
/**
 * 用户中心微信模板
 * @author susu
 */
defined('InShopNC') or exit('Access Invalid!');
class member_weixinControl extends BaseMemberControl{
	public function indexOp(){
	//微信进入自动登陆
    	if(!empty($_GET["code"])&&!isset($_SESSION["member_id"])){
    		$code=$_GET["code"];
    		$status=Model('member')->weixin_login_handle($code);
    	}
		Tpl::showpage('member_weixin.index');
	}
	/**
	 * 我的下线
	 */
	public function lower_memberOp(){
		$wx_member=Model('wx_member');
		$wx_member_info=$wx_member->where(array('member_id'=>$_SESSION["member_id"]))->field('*')->find();
		if($wx_member_info["lower_member"]!=''){
			$count=count(explode(',',@substr($wx_member_info["lower_member"],0,-1)));
			$lower_info_count=count(json_decode($wx_member_info["lower_member_information"],true));
			//校验下线人数是和下线人数信息字段数据是否正确，不正确则更新下线人数信息字段
			if($lower_info_count<$count){
				$this->up_lower_member_information($wx_member,$wx_member_info["lower_member"],$wx_member_info["id"]);
			}
			$lower_member=$wx_member_info["lower_member"];
			if($lower_member!=''){
				$list=@array_values(json_decode($wx_member_info["lower_member_information"],true));
				if(empty($_GET["type"])){
					foreach ($list as $k=>$v){
						if($v["status"]=='0')unset($list[$k]);
					}
				}
			}
		}
		Tpl::output('list',@array_values($list));
		Tpl::output('count',$count);
		Tpl::showpage('member_wx_lower_member');
	}
	public function my_presentOp(){
		if(!empty($_GET["openid"])&&!isset($_SESSION["member_id"])){
			//自动登陆
			$openid=decrypt($_GET["openid"]);
			$status=Model('member')->weixin_login_handle($openid,'openid');
		}
		
		if(isset($_SESSION["member_id"])){
			$member_id=$_SESSION["member_id"];
			$wx_member=Model("wx_member");
			$wx_present_member=Model('wx_present_member');
			//判断是否用了新规则，新规则中是否有没有领取过的礼品，一个用户同一个礼品ID只能领取一次
			$this->check_wx_present_member($wx_member,$wx_present_member,$member_id);
			//判断以往满足赠送礼券的微信用户礼品表中是否存在此用户礼券记录，没有则插入礼品表礼券记录和用户礼券
			Model('wx_member')->member_voucher($member_id);
			$present_member_res=$wx_present_member->where(array('member_id'=>$_SESSION["member_id"],'status'=>0))->field('*')->order('lower_num desc')->find();
			if(!empty($present_member_res)){
				$lower_num=$present_member_res["lower_num"];
			}
			
			$active_rule_list=Model()->query('SELECT b.goods_image,b.store_id,a.* from az_wx_active_rule as a LEFT JOIN az_goods as b on a.goods_id=b.goods_id order by a.lower_num asc');

			//if(empty($status_res))$errmsg='没有找到相关记录';
			//if(($status_res["add_time"]+86400*15)<time())$errmsg=$this->message_template[8];//"抱歉！您的礼物已超过领取时间，继续邀请好友关注再次申领吧！";
			//if($status_res["status"]>0)$errmsg=$this->message_template[9];//"抱歉！您已经领取过该礼物了，继续邀请好友关注再次申领吧！";
			Tpl::output('list',$active_rule_list);
			Tpl::output('present_member',$present_member_res);
			//Tpl::output('openid',$openid);
			Tpl::output('lower_num',$lower_num);
			//Tpl::output('errmsg',$errmsg);
		}
		Tpl::showpage('member_wx_my_present');
	}
	/**
	 * 我的礼品
	 */
	public function my_present00Op(){
		$wx_present_member=Model()->table('wx_present_member,goods');
		//$list=$wx_present_member->where("wx_present_member.member_id={$_SESSION["member_id"]} and wx_present_member.goods_id=goods.goods_id")->field('wx_present_member.*,goods.image')->select();
		$list= Model()->query("select a.*,b.goods_image from az_wx_present_member as a,az_goods as b where a.member_id={$_SESSION["member_id"]} and a.goods_id=b.goods_id");
		//echo $_SESSION["member_id"];print_r($list);
		Tpl::output('list',$list);
		Tpl::showpage('member_wx_my_present');
	}
	/**
	 * 下线排行榜
	 */
	public function lower_topOp(){
		$model=Model('wx_member');
		$list=$model->where("lower_num>1")->limit(15)->order('lower_num desc')->select();
		Tpl::output('list',$list);
		Tpl::showpage('member_wx_lower_top');
	}
	public function wx_present_codeOp(){
		$openid_res=Model('member')->where(array('member_id'=>$_SESSION["member_id"]))->field('openid')->find();
		Tpl::output('openid',$openid_res["openid"]);
		Tpl::showpage('member_wx_present_code');
	}
	/**
	 * //判断是否用了新规则，新规则中是否有没有领取过的礼品，一个用户同一个礼品ID(goods_id)只能领取一次
	 * @param  $wx_member
	 * @param  $wx_present_member
	 * @param  $member_id
	 */
	private function check_wx_present_member($wx_member,$wx_present_member,$member_id){
		$wx_member_res=$wx_member->where(array('member_id'=>$member_id))->field("lower_member,openid,nickname")->find();
		if(!empty($wx_member)){
			//获取用户关注人数
			$lower_member_count=count(explode(',',@substr($wx_member_res["lower_member"], 0,-1)));
			$present_lower_num_str='';//表中已有礼品 lower_num
			$present_goods_id_str='';//表中已有的礼品 goods_id
			$present_member_res2=$wx_present_member->where(array('member_id'=>$member_id))->select();

			if(!empty($present_member_res2)){
				foreach($present_member_res2 as $v2){
					$present_lower_num_str.=$v2["lower_num"].',';
					if(!empty($v2["goods_id"])){
						$present_goods_id_str.=$v2["goods_id"].',';
					}
				}
			}
			$active_rule_list2=Model('wx_active_rule')->where("lower_num<{$lower_member_count} and lower_num>0 and (goods_id !=0 or voucher_t_id !=0)")->select();
			if(!empty($active_rule_list2)){
				$new_lower_num_str='';//所有规则中除去表中已有礼品，应用些新规则还需插入的新礼品记录 lower_num
				if($present_lower_num_str!=''){
					foreach($active_rule_list2 as $v){
						$present_goods_id_arr=explode(',',@substr($present_goods_id_str,0,-1));
						$present_lower_num_arr=explode(',', @substr($present_lower_num_str,0,-1));
						if(!in_array($v["goods_id"],$present_goods_id_arr)&&!in_array($v["lower_num"], $present_lower_num_arr)){
							$new_lower_num_str.=$v["lower_num"].',';
						}
					}
				}else{
					foreach($active_rule_list2 as $v){
						$new_lower_num_str.=$v["lower_num"].',';
					}
				}
			}
			//echo $new_lower_num_str;exit();
			//插入礼品表记录
			if($new_lower_num_str!=''){
				$active_rule_list3=Model('wx_active_rule')->where("lower_num in (".@substr($new_lower_num_str,0,-1).") and lower_num>0 and (goods_id !=0 or voucher_t_id !=0)")->select();
				if(!empty($active_rule_list3)){
					foreach ($active_rule_list3 as $v3){
						//插入礼品表，（只要满足'有礼品或礼券'就插入记录），当进入商品页选择完商品后更新goods_id字段和status字段为1,url参数为id(礼品表ID[加密])和openid(加密)
						if(!empty($v3["goods_id"])||!empty($v3["voucher_t_id"])){
							$voucher_id=0;
							$voucher_num=0;
							if(!empty($v3["voucher_t_id"])){
								$voucher_t_id=$v3["voucher_t_id"];
								$voucher_t_res=Model()->table('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
								$voucher_time=$v3["voucher_days"]*86400+time();//代金券有效期
								$voucher_num=$v3["voucher_num"];
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
									$voucher_d["voucher_owner_id"]=$member_id;
									$voucher_d["voucher_owner_name"]=$wx_member_res["nickname"];
									$voucher_d["voucher_order_id"]='0';
									//log_result(json_encode($voucher_d),'','weixin');
									$voucher_id=$voucher->insert($voucher_d);//插入表
									$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
									$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
								}
							}
							$present_member_d_status=0;
							if(!empty($voucher_id)&&$voucher_id>0)$present_member_d_status=1;
							$present_member_data=array('title'=>$v3["title"],'openid'=>$wx_member_res["openid"],'member_id'=>$member_id,'lower_num'=>$v3["lower_num"],'add_time'=>time(),'pre_openid'=>'','source_type'=>2,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num,'status'=>$present_member_d_status);
							$present_member_id.=$wx_present_member->insert($present_member_data);//插入礼品表
						}
					}
				}
			}
			//return $present_member_id;
		}
	}
	/**
	 * 校验下线人数是和下线人数信息字段数据是否正确，不正确则更新下线人数信息字段
	 * @param  $wx_member,Model('wx_member')
	 * @param  $lower_member,wx_member表lower_member字段
	 * @param  $id,wx_member表ID
	 */
	private function up_lower_member_information($wx_member,$lower_member,$id){
		$openid_arr=array();
		$openid_str2='';
		$openid_arr=explode(',', $lower_member);
		foreach($openid_arr as $v2){
			if(!empty($v2)){
				$openid_str2.="'{$v2}',";
			}
		}
		$openid_str3=substr($openid_str2,0,-1);
		$wx_member_info=$wx_member->where("openid in({$openid_str3})")->select();
		$wx_member_arr=array();
		foreach ($wx_member_info as $v3){
			$wx_member_d['member_id']=$v3["member_id"];
			$wx_member_d['openid']=$v3["openid"];
			$wx_member_d['reg_time']=$v3['reg_time'];
			$wx_member_d['source_type']=$v3["source_type"];
			$wx_member_d['nickname']=urlencode(str_replace(array('"',"'",'{','}','[',']',':',';','(',')'), array('','','','','','','','','',''), $v3['nickname']));//因转为中文，没有经过JSON编码，所有不能有双引号
			$wx_member_d['sex']=$v3['sex'];
			$wx_member_d['country']=$v3['country'];
			$wx_member_d['city']=$v3['city'];
			$wx_member_d['province']=$v3['province'];
			$wx_member_d['headimgurl']=$v3['headimgurl'];
			$wx_member_d['status']='1';//已关注1/未关注0
			$wx_member_d["pid"]=$wx_member_info["id"];
			$wx_member_arr[$v3["openid"]]=$wx_member_d;
		}
		$lower_member_info_json=str_replace('\/', '/', json_encode($wx_member_arr,JSON_UNESCAPED_UNICODE));
		$status=$wx_member->where(array('id'=>$id))->update(array('lower_member_information'=>$lower_member_info_json));
		return $status;
	}
}
?>