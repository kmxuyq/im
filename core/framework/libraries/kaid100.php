<?php
/**
 * 快递100
 */
defined('InShopNC') or exit('Access Invalid!');
class kaid100{
	/**
	 * 
	 * @param  $company快递 公司编码
	 * @param  $number订阅单号
	 * @param  $mobile用户手机号
	 * @param  $seller商家名称
	 * @param  $goods_name购买的商品
	 */
	public function postorder($company,$number,$mobile,$seller,$goods_name){
		
		$post_data = array();
		$post_data["schema"] = 'json' ;
		$kaid100_key='oBQiTsRk2390';
		$callbackurl='http://m.gellefreres.com/wap_shop/?act=kaid100';
		//callbackurl请参考callback.php实现，key经常会变，请与快递100联系获取最新key
		$post_data["param"] = '{"company":"'.$company.'", "number":"'.$number.'","key":"'.$kaid100_key.'", "parameters":{"callbackurl":"'.$callbackurl.'","mobiletelephone":"'.$mobile.'","seller":"'.$seller.'","commodity":"'.$goods_name.'"}}';
		log_result($post_data["param"], '---order');
		$url='http://poll.kuaidi100.com/poll';
		$status=$this->kaid100_curl($post_data,$url);
		return $status;
		
	}
	private function kaid100_curl($post_data,$url){
		$o="";
		foreach ($post_data as $k=>$v)
		{
			$o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
		}
		$post_data=substr($o,0,-1);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result = curl_exec($ch);		//返回提交结果，格式与指定的格式一致（result=true代表成功）
		curl_close($ch);
		return $result;
		
	}
}