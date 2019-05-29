<?php
/**
 * 手机短信类,微网联通接口
 */
defined('InShopNC') or exit('Access Invalid!');
class SendCms {
	private $target='http://cf.51welink.com/submitdata/Service.asmx/g_Submit';
	private $sname='dlyimeibj';//dl-fanyao
	private $spwd='kFN1lma9';//fanyao123
	private $scorpid='';
	private $sprdid='1012818';
	private $title='昆明旅游超市';

	/**
	 *
	 * @param $mobile手机号
	 * @param $content消息内容
	 */
	public function sendContent($mobile,$content){
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$mobile}&smsg=".rawurlencode($content);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		//这里需要return 返回的状态
		return $state;
	}
	/**
	 * 订单支付成功－短信
	 * @param  $mobile
	 * @param  $member_name
	 * @param  $order_sn
	 * @param  $money
	 * @param  $web_tel
	 */
	public function send_order_payok($phone,$member_name,$order_sn,$money,$web_tel){
		$smsg="【{$this->title}】亲爱的会员{$member_name}，您好！你的订单号为{$order_sn}的商品已成功支付￥{$money}元，您可以登陆我们的网站查看订单及物流状态，客服电话{$web_tel}。";
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$phone}&smsg=".rawurlencode($smsg);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		//这里需要return 返回的状态
		return $state;
	}
	/**
	 * 订单提交成功－短信
	 * @param  $mobile
	 * @param  $member_name
	 * @param  $order_sn
	 * @param  $web_tel
	 */
	public function send_order_add($phone,$member_name,$order_sn,$web_tel){
		$smsg="【{$this->title}】亲爱的会员{$member_name}，您好！你的订单已成功提交，订单号：{$order_sn}，请在24小时内及时付款，过时则需要重新提交订单，您可以登陆网站进入个人中心进行订单状态查看，客服电话{$web_tel}。";
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$phone}&smsg=".rawurlencode($smsg);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		return $state;
	}
	/**
	 * 手机验证码－短信
	 * @param  $mobile
	 * @param  $code
	 */
	public function send_code($phone,$code){
		$smsg="【{$this->title}】亲爱的会员，您好！您本次的短信验证码是{$code}，请不要告诉任何人。";
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$phone}&smsg=".rawurlencode($smsg);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		return $state;
	}
	/**
	 * 预存款充值
	 * @param  $mobile
	 * @param  $member_name
	 * @param  $order_sn
	 * @param  $money
	 * @param  $web_tel
	 */
	public function send_pre_deposit($phone,$member_name,$order_sn,$money,$web_tel){
		$smsg="【{$this->title}】亲爱的会员{$member_name}，您好！您的预存款充值成功，本次充值金额{$money}元。";
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$phone}&smsg=".rawurlencode($smsg);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		//这里需要return 返回的状态
		return $state;
	}
	/**
	 * 支付成功商家订单提醒
	 */
	public function send_payok_message($phone,$member_name,$order_sn,$money){
		$smsg="【{$this->title}】订单支付消息提醒，会员{$member_name}，订单号为{$order_sn}的商品已成功支付￥{$money}元。";
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$phone}&smsg=".rawurlencode($smsg);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		//这里需要return 返回的状态
		return $state;
	}
	/**
	 * 发货通知
	 */
	public function send_order_product($phone,$order_sn,$number,$shipping_name){
		$smsg="【{$this->title}】亲爱的会员，您好！您的订单号为{$order_sn}的商品已出库，物流单号是：{$shipping_name}:{$number}，我们将以最快的速度送达，请保持手机畅通。";
		$post_data = "sname={$this->sname}&spwd={$this->spwd}&scorpid={$this->scorpid}&sprdid={$this->sprdid}&sdst={$phone}&smsg=".rawurlencode($smsg);
		$gets = $this->Post($post_data, $this->target);
		$state=$this->real_state($gets);
		//这里需要return 返回的状态
		return $state;
	}
	private function Post($data,$target) {
		$url_info = parse_url($target);
		$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
		$httpheader .= "Host:" . $url_info['host'] . "\r\n";
		$httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
		$httpheader .= "Content-Length:" . strlen($data) . "\r\n";
		$httpheader .= "Connection:close\r\n\r\n";
		//$httpheader .= "Connection:Keep-Alive\r\n\r\n";
		$httpheader .= $data;

		$fd = fsockopen($url_info['host'], 80);
		fwrite($fd, $httpheader);
		$gets = "";
		while(!feof($fd)) {
			$gets .= fread($fd, 128);
		}
		fclose($fd);
		if($gets != ''){
			$start = strpos($gets, '<?xml');
			if($start > 0) {
				$gets = substr($gets, $start);
			}
		}
		return $gets;
	}
	/**
	 * 读取XML文件中的State值，0为发送成功
	 * @param  $source发送返回的XML文件内容
	 */
	private function real_state($source){
		$doc = new DOMDocument();
		$doc->loadXML($source);//load('person.xml'); //读取xml文件
		$state_node = $doc->getElementsByTagName( "State" )->item(0); //取得humans标签的对象数组
		$state=$state_node->nodeValue;
		//echo $state;
		if($state=='0'){
			//发送成功
			return true;
		}else{
			//发送失败
			return false;
		}
	}
}
