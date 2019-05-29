<?php 
class wechatapi
{
	/**
	 * 微信验证
	 */
	public function valid($token)
	{
		$echoStr = $_GET["echostr"];
		if($this->checkSignature($token)){
			echo $echoStr;
			exit;
		}
	}

	private function checkSignature($token)
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];

		//$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	public function responseMsg()
	{
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = $postObj->FromUserName;
			$toUsername = $postObj->ToUserName;
			$keyword = trim($postObj->Content);
			$MsgType = $postObj->MsgType;
			$time = time();
			$textTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>0</FuncFlag>
			</xml>";
			if($keyword == "?" || $keyword == "？")
			{
				$msgType = "text";
				$contentStr = date("Y-m-d H:i:s",time());
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			} elseif($MsgType=='text') {
				$msgType = "text";
				$contentStr = 'welcome!';
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
				echo $resultStr;
			}
		}else{
			echo "";
			exit;
		}
	}
	/**
	 *
	 * @param $file文件名前缀
	 * @param $word文件内容
	 * @param $path存存的文件夹
	 */
	function log_result($word,$file,$path='pay')
	{
		$file_name = md5('dxj.1301'.date('Y-m-d')).$file.date('Y-m-d').'.log';
		$path      = $_SERVER["DOCUMENT_ROOT"]."/data/log/{$path}/";
		$filename      = $path.$file_name;
		$fp        = fopen($filename, "a");
		flock($fp, LOCK_EX);
		//$word="\n".date('Y-m-d H:i:s')."\n".base64_encode($word);
		fwrite($fp, date('Y-m-d H:i:s')."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	/**
	 * 	作用：将xml转为array
	 */
	function xmlToArray($xml)
	{
		//将XML转为array
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
	/**
	 * curl获取远程网址内容,替代file_get_contents
	 * @param  $url
	 * @param  $type方式默认为GET,有post和get两种方式，获取POST数据$_POST['']
	 * @param  $post_data,post方式的数组
	 */
	function curl($url,$type='get',$post_data=null,$second=30){
		$ch = curl_init();
		//设置超时
		//curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);//
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//
		//设置header
		//curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($type=='post'){
			curl_setopt($ch, CURLOPT_POST, 1);//开启POST
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);//POST数据
		}
		$output = curl_exec($ch);
		curl_close($ch);
		return  $output;//返回或者显示结果
	}
}