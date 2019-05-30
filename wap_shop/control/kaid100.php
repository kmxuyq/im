<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * @author susu
 * 快递100
 */

class kaid100Control extends BaseHomeControl{
	//回调地址
	public function indexOp(){
		$param=$_POST['param'];
		//log_result($param."<br/>", '--orderpost');
	//	$param='{&quot;message&quot;:&quot;ok&quot;,&quot;nu&quot;:&quot;227213779832&quot;,&quot;ischeck&quot;:&quot;1&quot;,&quot;condition&quot;:&quot;F00&quot;,&quot;com&quot;:&quot;shentong&quot;,&quot;status&quot;:&quot;200&quot;,&quot;state&quot;:&quot;3&quot;,&quot;data&quot;:[{&quot;time&quot;:&quot;2016-01-04 20:16:42&quot;,&quot;ftime&quot;:&quot;2016-01-04 20:16:42&quot;,&quot;context&quot;:&quot;\u5df2\u7b7e\u6536,\u7b7e\u6536\u4eba\u662f: \u672c\u4eba\u7b7e\u6536&quot;},{&quot;time&quot;:&quot;2016-01-04 16:06:09&quot;,&quot;ftime&quot;:&quot;2016-01-04 16:06:09&quot;,&quot;context&quot;:&quot;\u5e7f\u4e1c\u6df1\u5733\u798f\u7530\u77f3\u5ca9\u4e00\u90e8 \u7684\u6d3e\u4ef6\u5458 \u6e29\u6653\u8212 \u6b63\u5728\u6d3e\u4ef6&quot;},{&quot;time&quot;:&quot;2016-01-04 13:02:12&quot;,&quot;ftime&quot;:&quot;2016-01-04 13:02:12&quot;,&quot;context&quot;:&quot;\u5e7f\u4e1c\u6df1\u5733\u798f\u7530\u77f3\u5ca9\u4e00\u90e8 \u6b63\u5728\u8fdb\u884c \u7591\u96be\u4ef6 \u626b\u63cf,\u539f\u56e0\u662f\uff1a \u8054\u7cfb\u597d\u4e0b\u5348\u9001&quot;},{&quot;time&quot;:&quot;2016-01-04 08:46:56&quot;,&quot;ftime&quot;:&quot;2016-01-04 08:46:56&quot;,&quot;context&quot;:&quot;\u5e7f\u4e1c\u6df1\u5733\u798f\u7530\u77f3\u5ca9\u4e00\u90e8 \u7684\u6d3e\u4ef6\u5458 \u6e29\u6653\u8212 \u6b63\u5728\u6d3e\u4ef6&quot;},{&quot;time&quot;:&quot;2016-01-04 07:30:58&quot;,&quot;ftime&quot;:&quot;2016-01-04 07:30:58&quot;,&quot;context&quot;:&quot;\u5feb\u4ef6\u5df2\u5230\u8fbe \u5e7f\u4e1c\u6df1\u5733\u798f\u7530\u77f3\u5ca9\u4e00\u90e8&quot;},{&quot;time&quot;:&quot;2016-01-03 22:01:58&quot;,&quot;ftime&quot;:&quot;2016-01-03 22:01:58&quot;,&quot;context&quot;:&quot;\u7531 \u5e7f\u4e1c\u5b9d\u5b89\u516c\u53f8 \u53d1\u5f80&quot;},{&quot;time&quot;:&quot;2015-12-31 23:59:05&quot;,&quot;ftime&quot;:&quot;2015-12-31 23:59:05&quot;,&quot;context&quot;:&quot;\u7531 \u5317\u4eac\u4e2d\u8f6c\u90e8 \u53d1\u5f80 \u5e7f\u4e1c\u6df1\u5733\u4e2d\u8f6c\u90e8&quot;},{&quot;time&quot;:&quot;2015-12-31 21:24:15&quot;,&quot;ftime&quot;:&quot;2015-12-31 21:24:15&quot;,&quot;context&quot;:&quot;\u7531 \u5317\u4eac\u5927\u5174\u516c\u53f8(010-80462111-843) \u53d1\u5f80 \u5317\u4eac\u4e2d\u8f6c\u90e8&quot;},{&quot;time&quot;:&quot;2015-12-31 21:24:15&quot;,&quot;ftime&quot;:&quot;2015-12-31 21:24:15&quot;,&quot;context&quot;:&quot;\u5317\u4eac\u5927\u5174\u516c\u53f8(010-80462111-843) \u6b63\u5728\u8fdb\u884c \u88c5\u888b \u626b\u63cf&quot;},{&quot;time&quot;:&quot;2015-12-31 20:45:57&quot;,&quot;ftime&quot;:&quot;2015-12-31 20:45:57&quot;,&quot;context&quot;:&quot;\u5317\u4eac\u5927\u5174\u516c\u53f8(010-80462111-843) \u7684\u6536\u4ef6\u5458 \u4e03\u5f69\u4e91\u5357 \u5df2\u6536\u4ef6&quot;}]}';
		$data=str_replace(array('&quot;'),array('"'),$param);
		$data_arr=json_decode($data,true);
		$number=$data_arr['lastResult']["nu"];
		$company=$data_arr['lastResult']["com"];
		$filename=$number.'-'.$company.'.log';
		$filename2=$_SERVER["DOCUMENT_ROOT"]."/data/log/order/".$filename;
		$count=file_put_contents($filename2, $data);
		if($count>0){
			$json='{"result":"true","returnCode":"200",	"message":"成功"}';
			echo $json;
		}
	}
	private function log_result($word,$filename){
		$path      = $_SERVER["DOCUMENT_ROOT"]."/data/log/order/".$filename;
		$fp        = fopen($path, "w");
		flock($fp, LOCK_EX);
		fwrite($fp, $word);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	public function testOp(){
		//$data=$_POST['param'];
		$param='{&quot;status&quot;:&quot;shutdown&quot;,&quot;billstatus&quot;:&quot;check&quot;,&quot;message&quot;:&quot;&quot;,&quot;lastResult&quot;:{&quot;message&quot;:&quot;ok&quot;,&quot;nu&quot;:&quot;603034372704&quot;,&quot;ischeck&quot;:&quot;1&quot;,&quot;condition&quot;:&quot;F00&quot;,&quot;com&quot;:&quot;shunfeng&quot;,&quot;status&quot;:&quot;200&quot;,&quot;state&quot;:&quot;3&quot;,&quot;data&quot;:[{&quot;time&quot;:&quot;2015-11-10 17:59:27&quot;,&quot;ftime&quot;:&quot;2015-11-10 17:59:27&quot;,&quot;context&quot;:&quot;已签收,感谢使用顺丰,期待再次为您服务&quot;},{&quot;time&quot;:&quot;2015-11-10 17:04:13&quot;,&quot;ftime&quot;:&quot;2015-11-10 17:04:13&quot;,&quot;context&quot;:&quot;正在派送途中(派件人:王波,电话:13408774585)&quot;},{&quot;time&quot;:&quot;2015-11-10 16:56:19&quot;,&quot;ftime&quot;:&quot;2015-11-10 16:56:19&quot;,&quot;context&quot;:&quot;快件到达 【昆明盘龙虹桥社区营业点】&quot;},{&quot;time&quot;:&quot;2015-11-10 13:31:31&quot;,&quot;ftime&quot;:&quot;2015-11-10 13:31:31&quot;,&quot;context&quot;:&quot;快件离开【昆明呈贡惠兰园营业点】,正发往下一站&quot;},{&quot;time&quot;:&quot;2015-11-10 12:58:56&quot;,&quot;ftime&quot;:&quot;2015-11-10 12:58:56&quot;,&quot;context&quot;:&quot;快件派送不成功(应客户要求,按新地址转寄),待转运&quot;},{&quot;time&quot;:&quot;2015-11-10 12:58:34&quot;,&quot;ftime&quot;:&quot;2015-11-10 12:58:34&quot;,&quot;context&quot;:&quot;正在派送途中,请您准备签收(派件人:谢贤祥,电话:18287101956)&quot;},{&quot;time&quot;:&quot;2015-11-10 09:30:29&quot;,&quot;ftime&quot;:&quot;2015-11-10 09:30:29&quot;,&quot;context&quot;:&quot;已与收方客户约定新派送时间 ,待派送&quot;},{&quot;time&quot;:&quot;2015-11-10 08:12:13&quot;,&quot;ftime&quot;:&quot;2015-11-10 08:12:13&quot;,&quot;context&quot;:&quot;正在派送途中,请您准备签收(派件人:谢贤祥,电话:18287101956)&quot;},{&quot;time&quot;:&quot;2015-11-10 08:03:43&quot;,&quot;ftime&quot;:&quot;2015-11-10 08:03:43&quot;,&quot;context&quot;:&quot;快件到达 【昆明呈贡惠兰园营业点】&quot;},{&quot;time&quot;:&quot;2015-11-09 22:10:53&quot;,&quot;ftime&quot;:&quot;2015-11-09 22:10:53&quot;,&quot;context&quot;:&quot;快件离开【昆明官渡集散中心】,正发往 【昆明呈贡惠兰园营业点】&quot;},{&quot;time&quot;:&quot;2015-11-09 22:07:01&quot;,&quot;ftime&quot;:&quot;2015-11-09 22:07:01&quot;,&quot;context&quot;:&quot;快件到达 【昆明官渡集散中心】&quot;},{&quot;time&quot;:&quot;2015-11-08 02:59:23&quot;,&quot;ftime&quot;:&quot;2015-11-08 02:59:23&quot;,&quot;context&quot;:&quot;快件离开【上海集散中心】,正发往 【昆明官渡集散中心】&quot;},{&quot;time&quot;:&quot;2015-11-08 02:55:46&quot;,&quot;ftime&quot;:&quot;2015-11-08 02:55:46&quot;,&quot;context&quot;:&quot;快件到达 【上海集散中心】&quot;},{&quot;time&quot;:&quot;2015-11-07 21:57:26&quot;,&quot;ftime&quot;:&quot;2015-11-07 21:57:26&quot;,&quot;context&quot;:&quot;快件离开【南通兴东集散中心】,正发往 【上海集散中心】&quot;},{&quot;time&quot;:&quot;2015-11-07 21:21:36&quot;,&quot;ftime&quot;:&quot;2015-11-07 21:21:36&quot;,&quot;context&quot;:&quot;快件到达 【南通兴东集散中心】&quot;},{&quot;time&quot;:&quot;2015-11-07 19:26:32&quot;,&quot;ftime&quot;:&quot;2015-11-07 19:26:32&quot;,&quot;context&quot;:&quot;快件离开【南通海安王府国际营业点】,正发往下一站&quot;},{&quot;time&quot;:&quot;2015-11-07 16:45:30&quot;,&quot;ftime&quot;:&quot;2015-11-07 16:45:30&quot;,&quot;context&quot;:&quot;顺丰速运 已收取快件&quot;}]}}';
		$data=str_replace(array('&quot;'),array('"'),$param);
		//$data='{"status":"shutdown","billstatus":"check","message":"","lastResult":{"message":"ok","nu":"227213764418","ischeck":"1","condition":"F00","com":"shentong","status":"200","state":"3","data":[{"time":"2016-01-04 07:00:47","ftime":"2016-01-04 07:00:47","context":"已签收,签收人是: 草签"},{"time":"2016-01-04 06:58:49","ftime":"2016-01-04 06:58:49","context":"北京新世界公司 的派件员 王伍群 正在派件"},{"time":"2016-01-04 06:53:43","ftime":"2016-01-04 06:53:43","context":"快件已到达 北京新世界公司"},{"time":"2016-01-01 10:58:52","ftime":"2016-01-01 10:58:52","context":"北京新世界公司 正在进行 疑难件 扫描,原因是： 节假日延迟派送"},{"time":"2016-01-01 08:57:25","ftime":"2016-01-01 08:57:25","context":"北京新世界公司 的派件员 邱海清 正在派件"},{"time":"2016-01-01 08:46:05","ftime":"2016-01-01 08:46:05","context":"快件已到达 北京新世界公司"},{"time":"2016-01-01 01:43:30","ftime":"2016-01-01 01:43:30","context":"由 北京中转部 发往 北京新世界公司"},{"time":"2015-12-31 22:22:24","ftime":"2015-12-31 22:22:24","context":"由 北京大兴公司(010-80462111-843) 发往 北京中转部"},{"time":"2015-12-31 21:50:29","ftime":"2015-12-31 21:50:29","context":"北京大兴公司(010-80462111-843) 的收件员 七彩云南 已收件"}]}}';
		$data_arr=json_decode($data,true);
		print_r($data_arr);
		$number='227142979155';//$_GET["nu"];//$data_arr['lastResult']["nu"];
		$company=$data_arr['lastResult']["com"];
		$filename=$number.'-'.$company.'.log';
		//echo $filename;
		//log_result($data, '----order----');
		echo $data.'---'.$filename;
		
		$this->log_result($data,$filename);
	}
	
}