<?php
defined ( 'InShopNC' ) or exit ( 'Access Invalid!' );

class testControl extends  BaseHomeControl{
	public function indexOp() {

		echo date('t', strtotime("2016-10-1"));
		exit;
		echo C("weixin_appid");exit();
		echo '30:'.encrypt(30)."，60：".encrypt(60);exit();
		list($aa,$bb)=explode(':','127.0.0.1:52');
		echo $aa.'--'.$bb;exit();
		$v30=encrypt('30');
		$v100=encrypt('100');
		echo '30元：'.$v30.',100元：'.$v100.'<br/>';
		echo '30元：'.decrypt($v30).',100元：'.decrypt($v100);
		
		exit();
		print_r($_SESSION);
		echo date('/Y/m/d/');exit();
		/* $where="openid in('ocuxlxEkoyzYVxnVoj3Dc3AS5xgw','ocuxlxCFbNfvu3hNEh4v3dPowJhc','ocuxlxCNv5qvLqwbO9nUTy5JjZYc')";
		$data=Model('wx_present_member')->where($where)->select();
		print_r($data); */
		$order_list["order_id"]=1;
		$reciver_info_res=Model()->table('order_common')->where(array('order_id'=>$order_list["order_id"]))->field('reciver_info')->find();
		$reciver_info_arr=unserialize($reciver_info_res["reciver_info"]);
		$myphone=$reciver_info_arr['phone'];
		echo $myphone;
		
	}
	public function sendOp(){
		/* $myphone=Model()->table('address')->where(array("member_id"=>$_SESSION["member_id"]))->field('mob_phone')->order('is_default desc')->find();
		//print_r($_SESSION);
		print_r($myphone); */
		$send=new SendCms();
		 
		echo $send->send_order_add('18314417267', 'susu', '124654654', '4002549898479'); 
		//echo $send->send_order_payok('13629495562','ymjr','490496766096870001','0.01','13888806937');
		
	}

	function insertall_testOp(){
		
		for($i=1;$i<=3;$i++){
			$golf_insert_data[]=array('commonid'=>111111,'date_month'=>'2015-12','day'=>$i);
		}
		print_r($golf_insert_data);
		//$status=Model()->table('golf')->insertAll($golf_insert_data);//批量插入数据
		//echo $status;
	}
	function pageOp(){
		//多表查询
		//select vr_order_code.vr_code,vr_order.order_id,vr_order.order_sn,vr_order.goods_image,vr_order.goods_name from ymjr_vr_order_code a,ymjr_vr_order c where vr_order.order_id=vr_order_code.order_id and vr_order_code.vr_state=1
		//$keyword_str=array('golf.commonid'=>'golf_stock.commonid','golf.commonid'=>'100389');
		$where_str['vr_order_code.vr_state']=1;
		$where_str['vr_order.order_id']='vr_order.order_id';
		//$where_str=array('vr_order.order_id'=>'vr_order_code.order_id','vr_order_code.vr_state'=>'1');
		$field='vr_order_code.vr_code,vr_order.order_id,vr_order.order_sn,vr_order.goods_image,vr_order.goods_name';
		$list  = Model()->table('vr_order_code,vr_order')->where("vr_order.order_id=vr_order_code.order_id and vr_order_code.vr_state=1")->field($field)->select();
		
		print_r($list);
	}
	
	function real_xmlOp(){
		$source='<?xml version="1.0" encoding="utf-8"?>
<CSubmitState xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://tempuri.org/">
  <State>0</State>
  <MsgID>1511061918503466405</MsgID>
  <MsgState>提交成功</MsgState>
  <Reserve>0</Reserve>
</CSubmitState>';
		$doc = new DOMDocument();
		$doc->loadXML($source);//load('person.xml'); //读取xml文件
		$humans = $doc->getElementsByTagName( "CSubmitState" )->item(0); //取得humans标签的对象数组
		echo $humans;
		print_r($humans);
		foreach( $humans as $human )
		{
			/* $names = $human->getElementsByTagName( "name" ); //取得name的标签的对象数组
			$name = $names->item(0)->nodeValue; //取得node中的值，如<name> </name>
			$sexs = $human->getElementsByTagName( "sex" );
			$sex = $sexs->item(0)->nodeValue;
			$olds = $human->getElementsByTagName( "old" );
			$old = $olds->item(0)->nodeValue;
			echo "$name - $sex - $old\n"; */
			echo $human->getElementsByTagName("State");
			
		}
	}

	public function test5Op(){
		$userinfo["reg_time"]='1450852867';
		$userinfo["headimgurl"]='http://wx.qlogo.cn/mmopen/oLU121BePGnVgH2j5VGaUxSfKjRBiaYAmNPsuDeUBT7ibggAy4pTa2u0RVVbj779sneRWYicXt2Ldzdso0l5eX6obnvrdaGLaD2/0';
		$openid='ocuxlxJuw5O2gKs2uoo5eM7Q_1pM';
		$reg_time_path=date('Y/m/d/',$userinfo["reg_time"]);
		$this->face_path=$_SERVER["DOCUMENT_ROOT"].'/data/weixin/test/';
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
		/* echo '<title>打印用户表信息</title>';
		$data=Model('member')->where("member_id>100")->select();
		print_r($data); */
	}
	public function test6Op(){
		$wx_present_member=Model('wx_present_member');
		$member_id='83';
		$present_member_res=$wx_present_member->where(array('member_id'=>$member_id))->order('status asc')->limit(1)->field('id,status')->find();
		print_r($present_member_res);
		Tpl::showpage('act_address');
		/* $active_rule_info=Model('wx_active_rule')->where(array('source_type'=>1))->field('title,goods_id')->find();
		//print_r($active_rule_info);
		$goods_id=$active_rule_info["goods_id"];
		echo $goods_id;
		$present_member_id=Model('wx_present_member')->insert(array('title'=>'标题asf','openid'=>'ocuxlxMWj0MVkfFgHxaIUDThHM1o','member_id'=>'83','add_time'=>time(),'goods_id'=>$goods_id,'pre_openid'=>'','source_type'=>1));
		echo $present_member_id; */
	}
	public function del_wx_memberOp(){
		echo '<title>清除微信用户</title>';
		//exit();
		//超哥：ocuxlxMhsiKGgAlrEc86aDKvixTs//文：ocuxlxEkoyzYVxnVoj3Dc3AS5xgw//正能：ocuxlxMWj0MVkfFgHxaIUDThHM1o
		//大树大树:ocuxlxCFbNfvu3hNEh4v3dPowJhc//Me兹:ocuxlxCNv5qvLqwbO9nUTy5JjZYc
		//$where="openid in('ocuxlxEkoyzYVxnVoj3Dc3AS5xgw','ocuxlxCFbNfvu3hNEh4v3dPowJhc','ocuxlxCNv5qvLqwbO9nUTy5JjZYc')";//MF//'ocuxlxMhsiKGgAlrEc86aDKvixTs','ocuxlxPBBNtkDorcqtAv2fqvE6CI'
		//GF:超：o-lvAv2HPbfGySJ0vDSQPgOrcbcs//正能：o-lvAv_XggvhlU2qce2KmH4Jl_5I//雄文：o-lvAv79zRSFk76sdexL3MC3rn6w
		$where="openid in('o-lvAv79zRSFk76sdexL3MC3rn6w')";//'o-lvAv2HPbfGySJ0vDSQPgOrcbcs','o-lvAv_XggvhlU2qce2KmH4Jl_5I',//GF
		$status=Model('wx_member')->where($where)->delete();
		$status.=Model('member')->where($where)->delete();
		$status.=Model('wx_present_member')->where($where)->delete();
		echo $status;
	}
	public function print_wx_memberOp(){
		echo '<title>打印微信用户信息</title>';
		$wx_active_rule=Model('wx_active_rule')->where('1=1')->select();
		print_r($wx_active_rule);exit();
		$wx_member=Model('wx_member')->where('1=1')->select();
		$wx_present_member=Model('wx_present_member')->where('1=1')->select();
		$member=Model('member')->where("member_id>100")->select();
		print_r($wx_member);
		echo '<p style="color:Red">*****************************************************************************************************************************************************************************</p>';		
		print_r($wx_present_member);
		echo '<p style="color:Red">*****************************************************************************************************************************************************************************</p>';
		print_r($member);
	}
	public function send_messageOp(){
		
		$url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=e9mYFo0KAyx17cqEiUrLW-80htzCdWTCqHtNVDPU0W26e5Ec81nM9n8uEHIQdVobrae8xxr38xoFtggzURJXolzEGt_u48r54W5JD74qTskSLQbADABUZ';
		
		$content='{
			"touser":"ocuxlxEkoyzYVxnVoj3Dc3AS5xgw",
			"msgtype":"text",
			"text":
			{
				"content":"试一下"
			}
		}';
		echo curl($url,'post',$content);
	}
	public function ajOp(){
		echo '1';
	}
	//删除历史数据
	public function querysql_sdsdsdtmp20151219Op(){
		
		if($_GET["date"]=='2015-12-31'){
	/* 		$where_store="store_id!=7";
			$commonid=Model('goods_common')->where($where_store)->field('goods_commonid')->select();
			foreach ($commonid as $v){
				$commonid_str.=$v['goods_commonid'].',';
			}
			
			$commonid_str=substr($commonid_str,0,-1);
			echo $commonid_str.'<br/>';
			exit();
			//-----------
			$where="goods_commonid in ({$commonid_str})";
			$status=Model('goods')->where($where)->delete();
			echo '--';
			$status.=Model('goods_attr_index')->where($where)->delete();
			echo '--';
			$status.=Model('goods_fcode')->where($where)->delete();
			echo '--';
			$status.=Model('goods_images')->where($where)->delete();
			echo '--';
			$status.=Model('goods_package')->where($where)->delete();echo '--';
			$status.=Model('goods_common')->where($where)->delete();echo '--';
			//---------------- */
			/* echo date('Y-m-d','1450080813');
			$time=strtotime("-2 day");
			//echo date($time;exit();
			$where_order="add_time <{$time}";
			$orderid_list=Model()->table('order')->where($where_order)->field('order_id')->select();
			//print_r($orderid_list);
			foreach ($orderid_list as $v){
				$order_id_str.=$v['order_id'].',';
			}
				
			$order_id_str=substr($order_id_str,0,-1);
			echo $order_id_str.'<br/>';
			$where="order_id in({$order_id_str})";
			
			$status.=Model()->table('order')->where($where)->delete();echo '--';
			$status.=Model()->table('order_common')->where($where)->delete();echo '--';
			$status.=Model()->table('order_goods')->where($where)->delete();echo '--';
			$status.=Model()->table('order_log')->where("log_time <{$time}")->delete();echo '--';
			$status.=Model()->table('pay_log')->where("d_time <{$time}")->delete();echo '--';
			echo $status; */
		}
		/* $url=BASE_SITE_URL.'/wap_shop/index.php?act=test2&op=index';
		echo $url;
		//echo curl($url).'<br/>';
		echo file_get_contents($url); */
	}
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
	public function del_member_voucherOp(){
		$voucher_t_id='26,27';//测试(25,26),正式(26,27)';//50,100元，满51,300元可消费
		
		if(isset($_SESSION["member_id"])){
			echo $_SESSION["member_name"]."<br/>";
			$member_id=$_SESSION["member_id"];
			$state=Model()->table('voucher')->where("voucher_owner_id={$member_id} and voucher_t_id in({$voucher_t_id})")->delete();
			echo '清除状态(1为成功，0为失败)：'.$state;
		}else{
			
			echo "请登陆后再执行";
		}
		
	}
	public function gf_testOp(){
		$fromusername='ocuxlxBJkuCfH6ewSMkLp2_atZV0';
		$member_id=27;
		$wx_member_d['member_id']=$member_id;
		$wx_member_d['openid']=$fromusername;
		$wx_member_d['reg_time']=1450179488;
		$wx_member_d['source_type']=1;
		$wx_member_d['nickname']='susu';
		$wx_member_d['sex']=1;
		$wx_member_d['country']='中国';
		$wx_member_d['city']='昆明';
		$wx_member_d['province']='云南';
		$wx_member_d['headimgurl']='http://wx.qlogo.cn/mmopen/fhP3JOb0icwBnSmTcuLxPRlOUV7YPHIJDMXOOrjVob93ic5yibuXiaQDfacXqafOVmQPMPJr7hKTCIzic2pCMug7Nbg/0';
		$wx_member_d['status']='1';//已关注1/未关注0
		$wx_member_d["stock"]=5;
		
		$userinfo_arr["member_id"]=$member_id;
		$userinfo_arr["wx_member_d"]=$wx_member_d;
		
		
		
	
		$userinfo=$userinfo_arr;
		$eventkey=1;
		echo $this->eventkey30($fromusername, $userinfo, $eventkey, '');
	}
	private function eventkey30($fromusername, $userinfo, $eventkey, $message_template){
		//$eventkey为gf_order表的id
		$gf_shopping_guide_lower=Model()->table('gf_shopping_guide_lower');
		$gf_shopping_guide_lower_count=$gf_shopping_guide_lower->where(array('gf_order_id'=>$eventkey,'lower_openid'=>$fromusername))->count('id');
		$gf_order=Model()->table('gf_order');
		if(empty($gf_shopping_guide_lower_count)){
			//如果没有此条数据则插入数据			
			$gf_order_res=$gf_order->where(array('id'=>$eventkey))->field('shopping_guide_id')->find();
			if(!empty($gf_order_res)){
				$data["gf_order_id"]=$eventkey;
				$data["shopping_guide_id"]=$gf_order_res["shopping_guide_id"];
				$data["lower_openid"]=$fromusername;
				$data["lower_info"]=json_encode($userinfo["wx_member_d"]);
				$data["lower_member_id"]=$userinfo["member_id"];
				$data["status"]=1;
				//插入数据
				$gf_shopping_guide_lower->insert($data);;
			}
		}else{
			//如果有数据：是取消了关注再重新关注的，更新关注状态
			$gf_shopping_guide_lower->where(array('gf_order_id'=>$eventkey,'lower_openid'=>$fromusername))->update(array('status'=>1));
		}
		//关注数加1
		$status=$gf_order->where(array('id'=>$eventkey))->setInc('subscribe',1);
		return $status;
	}
	public function get_ticketOp(){
		$scene_id='123';
		$appid=C('weixin_appid');
		$appsecret=C('weixin_appsecret');
		$accestoken='OkCo3fRAQIA0Mm4xnnewZDhfd9JoWIAuhJlv2-lcLBC6TPyiX8pXwsxzickD_8-0kb8EEYvXkwdCU0hHJp-2dEd3jzq21whDb1gfeJyhRamTuctJ5eqnxmafkvth8auMKHZiAEAZWO';
		$data='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
		$ticket_json=curl('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accestoken,'post',$data);
	
		$ticket_arr=json_decode($ticket_json,true);
		$ticket=$ticket_arr["ticket"];
		echo "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
	}
	public function test00Op(){
	
		//------------
		$str='{"content":"您获得 4 张抽奖码\r\n 111 112 555 666 \r\n{{#qr}}http:\/\/weixin.qq.com\/q\/b0RP-x7ltUfM92A2P2o_{{\/qr}}\r\n\n 扫描小票二维码，点击“魔药社群”-“魔礼大抽奖”，输入抽奖码，即可参与抽奖，浪漫法国游，iPone6S等大奖等您拿，100%中奖！"}';
		print_r(json_decode($str,true));
		exit();
		$code_count=3;
		$code='2323 44654';
		$wx_code_url='http://weixin.qq.com/q/0z-dYoXlMeJI1-H5GhG2';
		$wecome_text="您获得 {$code_count} 张抽奖码\r\n {$code} \r\n";
		$wecome_text2="\r\n\n 扫描小票二维码，点击“魔药社群”-“魔礼大抽奖”，输入抽奖码，即可参与抽奖，浪漫法国游，iPone6S等大奖等您拿，100%中奖！";
		$content='{"content": "'.$wecome_text.'{{#qr}}'.$wx_code_url.'{{/qr}}'.$wecome_text2.'"}';
		$tt=$wecome_text.'{{#qr}}'.$wx_code_url.'{{/qr}}'.$wecome_text2;
		$content_arr=array('content'=>$tt);
		
		print_r($content_arr);
		echo json_encode($content_arr,JSON_UNESCAPED_UNICODE);
		exit();
		//----------------
		echo "<meta charset='utf-8'/>";
		$msg='{"event":"data","deviceId":"d70d67f0fe","providerId":"E8A1B078-DB82-0E88-4DB1-9D96F3D83253","msgId":"B846092B-1D6F-0D81-2204-02CE9F4FB52F","data":{"content":" \u9500\u8d27\u51ed\u8bc1 \uff08YMTX\uff09\r\n\u6536\u94f6:2120032 10110\u77e3\u949f\u73b2 \r\n\u65e5\u671f: 2016-03-17 13:41\r\n\u9500\u552e\u67dc\u53f0:\u5a55\u73de\u82991012 \u8865\u6253\r\n\u9500\u552e\u67dc\u7ec4:B2\u5a55\u73de\u8299\u4e13\u5356\r\n\u672c\u673a\u6d41\u6c34:1603171110120005 \r\n\u79ef\u5206\u5361\u53f7: \r\n-------------------------------\r\n \u5546\u54c1\u7f16\u7801 \/ \u54c1\u540d \r\n\u539f\u4ef7\/ \u4f1a\u5458\u5c0a\u4eab\u4ef7 \r\n\u89c4\u683c \/ \u6570\u91cf \/ \u91d1\u989d \r\n203210100001 50ml\u5a55\u73de\u8299\u7075\u611f\u82b1\u56ed\r\n 380.00 304.00\r\n 50ml 1 304.00\r\n203210100006 150ml\u5a55\u73de\u8299\u6668\u9732\u8537\r\n 190.00 152.00\r\n 150ml 1 152.00\r\n-------------------------------\r\n\u9500\u552e\u603b\u8ba1: 570.00 \r\n\u672c\u6b21\u79ef\u5206: 0 \r\n\u5361\u5185\u79ef\u5206\u4f59\u989d: 0 \r\n\u6298\u8ba9\u91d1\u989d: 114.00 \r\n\u5b9e\u552e\u91d1\u989d: 456.00 \r\n-------------------------------\r\n\u5b9e\u6536(\u94f6\u884c\u5361): 456.00\r\n\u5546\u54c1\u6570\u91cf: 2 \r\n\u8865\u6253 \r\n-------------------------------\r\n2015 \r\n\u6e29\u99a8\u63d0\u793a\uff1a\r\n\u8bf7\u59a5\u5584\u4fdd\u7ba1\u6b64\u5355\uff0c\u5e76\u6838\u5b9e\u5546\u54c1\u3002\r\n\u552e\u540e\u7535\u8bdd: 400 871 8833 ,\u7aed\u8bda\u4e3a\u60a8\u670d\u52a1\u3002\r\n"}}';
		$msg_arr=json_decode($msg,true);
		$content=$msg_arr["data"]["content"];
		print_r($msg_arr);
		echo $content;
		
		exit();
		$str='{"id":17,"time":1457922343,"protocol":"text","encoding":"","content":" 销货凭证 （YMTX） \r\n收银:2120032 10110矣钟玲 \r\n日期: 2016-03-14 10:20\r\n销售柜台:婕珞芙1012 补打\r\n销售柜组:B2婕珞芙专卖\r\n本机流水:1603141110120001 \r\n积分卡号:60000363941 \r\n———————————————\r\n 商品编码 / 品名 \r\n 原价/ 会员尊享价 \r\n 规格 / 数量 / 金额 \r\n203270100012 香蜂套装（30ml香蜂\r\n 400.00 400.00\r\n30ml香蜂精华油 1 400.00\r\n———————————————\r\n销售总计: 400.00 \r\n本次积分: 0 \r\n卡内积分余额: 329 \r\n折让金额: 0.00 \r\n实售金额: -400.00 \r\n———————————————\r\n实收(婕珞芙促销券): 200.00\r\n实收(银行卡): 200.00\r\n商品数量: 1 \r\n补打 \r\n———————————————\r\n2031 \r\n温馨提示：\r\n请妥善保管此单，并核实商品。\r\n售后电话: 400 871 8833 ,竭诚为您服务。\n服务码:654654 \r\n\r\n\r\n\r\n\r\n\r\n"}';
		$json_arr=json_decode($str,true);
		$content=$json_arr["content"];
		echo '<br/>'.$content;
		echo $content.'<br/><br/><br/>';
		$order=preg_match('/本机流水: *([\d]+)/',$content,$order_arr);
		print_r($order_arr);
		$cashier=preg_match('/收银: *([\d]+)/',$content,$cashier_arr);
		print_r($cashier_arr);
		$price=preg_match('/实售金额: *-*([\d]+)/',$content,$price_arr);
		
		if(strpos($price_arr[0], '-')>0){
			$price_arr[1]='-'.$price_arr[1];
		}
		echo $price_arr[1];
		if($price_arr[1]<0)echo '小于0';
		print_r($price_arr);
		$server_code=preg_match('/([\d]+) *\r\n温馨提示/',$content,$server_arr);

		print_r($server_arr);
		//print_r(json_decode($str,true)); 
	}
}