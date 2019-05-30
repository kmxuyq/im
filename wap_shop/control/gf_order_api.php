<?php
/**
 * 婕珞芙商城订单接口文件
 *@author susu
 *
 ***/
defined('InShopNC') or exit('Access Invalid!');

class gf_order_apiControl extends Control {
	const token='glfkey';
	const appid='2D06A38F-F79F-64AB-39DF-0CE87C688935';//'测试：E8A1B078-DB82-0E88-4DB1-9D96F3D83253';//正式：2D06A38F-F79F-64AB-39DF-0CE87C688935
	const encodeAeckey='ASLDKFJLSAJFWIESALKVJSLDFKUJSOLDFKJLSKDJFPO';
	//url:http://m.gellefreres.com/wap_shop/gf_order_api.php
	/**
	 * 接口首页文件
	 */
	public function indexOp(){
		//token校验
		$this->valid(self::token);
		header("Content-type:text/html;charset=utf-8");
		//$_POST=$GLOBALS ['HTTP_RAW_POST_DATA'];
		$encryptMsg = file_get_contents("php://input");
		//log_result('接收POST:'.$encryptMsg, '--gf_order--');
		if(!empty($encryptMsg)){
			//载入weixin加解密类
			loadfunc('wxBizMsgCrypt');
			$token=self::token;
			$encodingAesKey=self::encodeAeckey;
			$appId=self::appid;
			//解密post消息
			$msg=$this->decode_content($encryptMsg, $token, $encodingAesKey, $appId);
			//$msg='{"event":"data","deviceId":"d70d67f0fe","providerId":"E8A1B078-DB82-0E88-4DB1-9D96F3D83253","msgId":"B846092B-1D6F-0D81-2204-02CE9F4FB52F","data":{"content":" \u9500\u8d27\u51ed\u8bc1 \uff08YMTX\uff09\r\n\u6536\u94f6:2120032 10110\u77e3\u949f\u73b2 \r\n\u65e5\u671f: 2016-03-17 13:41\r\n\u9500\u552e\u67dc\u53f0:\u5a55\u73de\u82991012 \u8865\u6253\r\n\u9500\u552e\u67dc\u7ec4:B2\u5a55\u73de\u8299\u4e13\u5356\r\n\u672c\u673a\u6d41\u6c34:1603171110120005 \r\n\u79ef\u5206\u5361\u53f7: \r\n-------------------------------\r\n \u5546\u54c1\u7f16\u7801 \/ \u54c1\u540d \r\n\u539f\u4ef7\/ \u4f1a\u5458\u5c0a\u4eab\u4ef7 \r\n\u89c4\u683c \/ \u6570\u91cf \/ \u91d1\u989d \r\n203210100001 50ml\u5a55\u73de\u8299\u7075\u611f\u82b1\u56ed\r\n 380.00 304.00\r\n 50ml 1 304.00\r\n203210100006 150ml\u5a55\u73de\u8299\u6668\u9732\u8537\r\n 190.00 152.00\r\n 150ml 1 152.00\r\n-------------------------------\r\n\u9500\u552e\u603b\u8ba1: 570.00 \r\n\u672c\u6b21\u79ef\u5206: 0 \r\n\u5361\u5185\u79ef\u5206\u4f59\u989d: 0 \r\n\u6298\u8ba9\u91d1\u989d: 114.00 \r\n\u5b9e\u552e\u91d1\u989d: 456.00 \r\n-------------------------------\r\n\u5b9e\u6536(\u94f6\u884c\u5361): 456.00\r\n\u5546\u54c1\u6570\u91cf: 2 \r\n\u8865\u6253 \r\n-------------------------------\r\n2015 \r\n\u6e29\u99a8\u63d0\u793a\uff1a\r\n\u8bf7\u59a5\u5584\u4fdd\u7ba1\u6b64\u5355\uff0c\u5e76\u6838\u5b9e\u5546\u54c1\u3002\r\n\u552e\u540e\u7535\u8bdd: 400 871 8833 ,\u7aed\u8bda\u4e3a\u60a8\u670d\u52a1\u3002\r\n"}}';
			//log_result($msg, '--gf_order--');
			$msg_arr=json_decode($msg,true);
			//小票内容
			$content=$msg_arr["data"]["content"];
			//---------------
			$order=preg_match('/本机流水: *([\d]+)/',$content,$order_arr);
			$cashier=preg_match('/收银: *([\d]+)/',$content,$cashier_arr);
			$price=preg_match('/实售金额: *-*([\d]+)/',$content,$price_arr);
			$shopping_guide_id=preg_match('/([\d]+) *\r\n温馨提示/',$content,$shopping_guide_id_arr);
			//log_result("小票内容content:{$content}--{$order_arr[1]}--{$cashier_arr[1]}--{$shopping_guide_id_arr[1]}--{$price_arr[1]}--".intval($price_arr[1]/100), '--gf_order--');
			//如果是退款
			if(strpos($price_arr[0], '-')>0){
				$price_arr[1]='-'.$price_arr[1];
			}
			$data["order_id"]=$order_arr[1];
			$gf_order=Model()->table('gf_order');
			$gf_luck_draw_code=Model()->table('gf_luck_draw_code');
			$gf_order_id_res=$gf_order->where(array('order_id'=>$data["order_id"]))->field('id')->find();
			$data["order_price"]=$price_arr[1];
			$data["order_time"]=time();
			$data["cashier_id"]=$cashier_arr[1];
			$data["shopping_guide_id"]=$shopping_guide_id_arr[1];
			$data["content"]=$content;
			$code_count=intval($price_arr[1]/100);//取消四舍五入
			if(empty($gf_order_id_res)){
				//插入gf_order表
				$gf_order_id=$gf_order->insert($data);
				//获取并更新抽奖码
				if($code_count>0){
					$code_list=$gf_luck_draw_code->where(array('status'=>0))->field('code')->limit($code_count)->select();
					if(!empty($code_list)){
						foreach ($code_list as $v){
							$code.=$v["code"].' ';
							//更新中奖码
							$gf_luck_draw_code->where(array('code'=>$v["code"]))->update(array('status'=>1,'order_id'=>$data["order_id"],'user_time'=>time()));
						}
					}
				}
				
			}else{
				if($code_count>0){
					$gf_order_id=$gf_order_id_res["id"];
					$code_list=$gf_luck_draw_code->where(array('order_id'=>$data["order_id"]))->field('code')->select();
					foreach($code_list as $v){
						$code.=$v["code"].' ';
					}
				}
				
			}
			if($price_arr[1]<0||$code_count=='0')$code='';
			$scene_id='30'.$gf_order_id;
			$wx_code_url=Model('wx_member')->get_weixin_code_url($scene_id);
			$wecome_text='';
			if($code!=''&&$code_count>0){
				$wecome_text="您获得 {$code_count} 张抽奖码\r\n {$code}\r\n";
			}
			$wecome_text2="\r\n扫描小票二维码，点击“魔药社群”-“魔礼大抽奖”，输入抽奖码，即可参与抽奖，浪漫法国游，iPone6S等大奖等您拿，100%中奖！";
			$content_str=$wecome_text.'{{#qr}}'.$wx_code_url.'{{/qr}}'.$wecome_text2;
			$content_arr=array('content'=>$content_str);
			$content=json_encode($content_arr,JSON_UNESCAPED_UNICODE);
			//log_result('content:'.$content, '--gf_order--');
			//加密回复的消息
			//$content = '{"content":"\u60a8\u83b7\u5f97 2 \u5f20\u62bd\u5956\u7801\uff1a\nNF8566  JQ8327  \n{{#qr}}http:\/\/weixin.qq.com\/r\/LESHnxrE0UeurV789xE_{{\/qr}}\n\u626b\u63cf\u5c0f\u7968\u4e8c\u7ef4\u7801\uff0c\u70b9\u51fb \u201c\u9b54\u836f\u793e\u7fa4\u201d - \u201c\u9b54\u793c\u5927\u62bd\u5956\u201d\uff0c\u8f93\u5165\u62bd\u5956\u7801\uff0c\u5373\u53ef\u53c2\u4e0e\u62bd\u5956\uff0c\u6d6a\u6f2b\u6cd5\u56fd\u6e38\u3001iPhone6s\u7b49\u5927\u5956\u7b49\u60a8\u62ff\uff0c100%\u4e2d\u5956\uff01\n{{#feed}}5{{\/feed}}"}';
			$return_data=$this->encode_content($content, $token, $encodingAesKey, $appId);
			echo $return_data;
		}
	}
	/**
	 * token验证
	 */
	public function valid($token)
	{
		$echoStr = $_GET["echostr"];
		if($this->checkSignature($token)){
			echo $echoStr;
			exit;
		}
	}
	//token校验
	private function checkSignature($token)
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 对接收来的POST数据进行解密
	 * @param $post
	 */
	private function decode_content($post,$token, $encodingAesKey, $appId){
		$encryptMsg=$post;
		// 第三方收到公众号平台发送的消息
		
		$bt = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
		
		$encryptMsg = json_decode($encryptMsg, true);
		$msg = '';
		$errCode = $bt->decryptMsg($encryptMsg['msgSignature'], $encryptMsg['timeStamp'], $encryptMsg['nonce'], $encryptMsg['encrypt'], $msg);
		//log_result('接收encryptMsg:'.$encryptMsg['msgSignature'].'--'.$encryptMsg['timeStamp'].'--'.$encryptMsg['nonce'].'--'.$encryptMsg['encrypt'], '--gf_order--');
		if ($errCode == 0) {
			//log_result('msg接收:'.$msg, '--gf_order--');
			return $msg;//解密后的json数据
		} else {
			//log_result('errCode接收:'.$errCode, '--gf_order--');
		}
	}
	/**
	 * 对返回值进行加密
	 * @param $content
	 */
	private function encode_content($content,$token, $encodingAesKey, $appId){
		//返回值
		$encryptMsg = '';
		$timeStamp = strval(time());
		$nonce = rand(100000,999999);
		$bt = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
		$errCode = $bt->encryptMsg($content, $timeStamp, $nonce, $encryptMsg);
		if ($errCode == 0) {
			//create('log', array('cont'=>$encryptMsg));
			//log_result('encryptMsg发送:'.$encryptMsg, '--gf_order--');		
			return $encryptMsg;
		} else {
			//log_result('errCode发送:'.$errCode, '--gf_order--');
		}
	}
	//测试
	public function testOp(){
		header("Content-type:text/html;charset=utf-8");
		if(!empty($_GET["order_id"])){
			$gf_order=Model()->table('gf_order');
			$order_id=$_GET["order_id"];
			$list=$gf_order->where(array('order_id'=>$order_id))->select();
			echo '<font color="Red">订单信息：</font><br/>';
			foreach($list as $v){
				echo "订单号：{$v["order_id"]},价格：{$v["price"]},收银：{$v["cashier_id"]},导购：{$v["shopping_guide_id"]},关注：{$v["subscribe"]}<br/><font color='green'>小票：</font>{$v["content"]}<br/>----------------------------------------------------------------------------<br/>";
			}
		}
		if(!empty($_GET["guide_id"])){
			echo '<font color="Red">导购下线信息：</font><br/>';
			$gf_shopping_guide_lower=Model()->table('gf_shopping_guide_lower');
			$list2=$gf_shopping_guide_lower->where(array('shopping_guide_id'=>$_GET["guide_id"]))->select();
			foreach($list2 as $v2){
				echo "id:{$v2["gf_order_id"]},导购：{$v2["shopping_guide_id"]},导购OPENID:{$v2["shopping_guide_openid"]},下线openid:{$v2["lower_openid"]},下线信息：{$v2["lower_info"]},下线用户ID:{$v2["lower_member_id"]},状态：{$v2["status"]}<br/>----------------------------------------------------------------------------<br/>";
			}
		
		}
	}
	
	public function up_codeOp(){
		$status=Model()->table('gf_luck_draw_code')->where('1=1')->update(array('order_id'=>'','status'=>0,'user_time'=>''));
		echo $status;
	}
	/* public function clear_orderOp(){
		exit();
		//$status=Model()->table('gf_order')->where('1=1')->delete().'--';
		//$status.=Model()->table('gf_shopping_guide_lower')->where('1=1')->delete();
		//echo $status;
	} */
}