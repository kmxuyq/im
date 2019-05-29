<?php 
/**
 * 高尔夫库存表 stock_info字段更新
 */
defined('InShopNC') or exit('Access Invalid!');
class golf_stockModel extends Model {
	/**
	 * 高尔夫库存表 stock_info字段更新
	 * @param $commonid商品ID
	 * @param $date2015-10-01
	 * @param $golf_minute 11点(30分40分),12点(30分40分),13点(),
	 * @param $stock为库存，2为预订锁定，0为支付成功
	 */
	public function golf_stock_up($commonid,$date,$golf_minute,$stock){	
		$where_stock=array('commonid'=>$commonid,'date'=>$date);
		$stock_info_res=Model()->table('golf_stock')->where($where_stock)->field('stock_info')->find();
		$stock_info=unserialize($stock_info_res["stock_info"]);
		//print_r($stock_info);
		$golf_minute=str_replace('点(),','', $golf_minute);
		foreach(explode(',',$golf_minute) as $v){ //11 12
			if($v!=''){
				$golf_hour_arr=explode('点',$v);//小时段和分钟数组：小时段为0，分钟段为1
				//print_r($golf_hour_arr);
				$golf_minute_str=str_replace(array('(',')'),array('',''),$golf_hour_arr[1]);
				$golf_minute_arr=explode('分',$golf_minute_str);
				foreach($golf_minute_arr as $v2){
					if($v2!=''){
						$stock_info[$golf_hour_arr[0]][$v2]['stock']=$stock;//将此时段的库存设为2,预订锁定状态,支付后设为0
					}
				}
			}
		}
		//print_r($stock_info);
		Model()->table('golf_stock')->where($where_stock)->update(array('stock_info'=>serialize($stock_info)));
		//如果为0，则将golf_stock表的库存减1
		if($stock=='0'){
			Model()->table('golf_stock')->where($where_stock)->setDec('stock',1);
		}
	}
	/**
	 * 订单支付时获取高尔夫各个场次的库存，如果已支付的返回提示，可以支付的返回空
	 * @param $commonid商品ID
	 * @param $date2015-10-01
	 * @param $golf_minute 11点(30分40分),12点(30分40分),13点(),
	 */
	public function get_golf_stock_status($commonid,$date,$golf_minute){		
		$where_stock=array('commonid'=>$commonid,'date'=>$date);		
		$stock_info_res=Model()->table('golf_stock')->where($where_stock)->field('stock_info')->find();
		$stock_info=unserialize($stock_info_res["stock_info"]);
		//print_r($stock_info);
		$golf_minute=str_replace('点(),','', $golf_minute);
		$message='';
		foreach(explode(',',$golf_minute) as $v){
			if($v!=''){
				$golf_hour_arr=explode('点',$v);//小时段和分钟数组：小时段为0，分钟段为1
				//print_r($golf_hour_arr);
				$golf_minute_str=str_replace(array('(',')'),array('',''),$golf_hour_arr[1]);
				$golf_minute_arr=explode('分',$golf_minute_str);
				foreach($golf_minute_arr as $v2){
					if($v2!=''){
						$stock=$stock_info[$golf_hour_arr[0]][$v2]['stock'];
						//如果库存为空
						if($stock=='0'){
							$message.=$golf_hour_arr[0].'点'.$v2.'分，';
						}
					}
				}
			}
		}
		if($message!=''){
			$message_str='亲，很抱谦，'.$message.'时段刚刚已被抢先订购，你重新预订吧！';
			return $message_str;
		}
	}
}
?>