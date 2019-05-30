<?php
define("LOG_FILE",'log.txt');
require_once ("tools.class.php");
require_once ("TenpayHttpClient.class.php");
class get_wx_addr{
	
	public function addr($appid,$appsecret,$siteurl,$redirect_url){
		$siteurl='http://m.test.gellefreres.com';
		$appid="wx237f54616784474f";
		$appsecret="e3752dcb2e4d225842b507a116df0058";
		//$get_string = $_SERVER["QUERY_STRING"];
		//获取get参数
		//获取 code
		$redirect_uri =  $siteurl.$_SERVER["REQUEST_URI"];
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
		//$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=jsapi_address&state=cft#wechat_redirect";
		if(empty($_GET['code'])){
		    header("location: $url");
		}
		//https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
		//获取access_token
		 $accesstoken=$this->get_access_token($appid,$appsecret,$_GET["code"]);
		
		//调起地址控件签名
		$timestamp = time();
		$noncestr = rand(100000,999999);
		$url=$siteurl.$_SERVER['REQUEST_URI'];
		
		$myaddr = new SignTool();
		$myaddr->setParameter ("appid", $appid);
		$myaddr->setParameter ("url", $url);
		$myaddr->setParameter ("noncestr", $noncestr);
		$myaddr->setParameter ("timestamp", $timestamp);
		$myaddr->setParameter ("accesstoken", $accesstoken);
		
		$addrsign=$myaddr->genSha1Sign();
		
		$addrstring=$myaddr->getDebugInfo();
		$this->log_result("addr|back|addsign:".$addrstring);
	}
	private function get_access_token($appid,$appsecret,$code){
		// 以$code为键的token只能获取一次token,所以需要保存此token,将token以$code为键存取，时间为获取token时间，过期时间为7200秒，如果$code为键的token不存在则重新写入新的token,键为$code
		$tokenurl= "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code={$code}&grant_type=authorization_code";
		$tokenclient = new TenpayHttpClient();
		$tokenclient->setReqContent($tokenurl);
		$tokenclient->setMethod("GET");
		$tokenres="";
		if( $tokenclient->call()){
			$tokenres =  $tokenclient->getResContent();
		}
		
		if( $tokenres != ""){
			$tk = json_decode($tokenres);
			if( $tk->access_token != "" )
			{
				log_result("addr|back|access_token:".$tk->access_token."|openid:".$tk->openid);
				$accesstoken=$tk->access_token;
			}else{
				echo "get access token empty";
				exit(0);
			}
		}
		else {
			echo "get access token error";
			exit(0);
		}
		return $accesstoken;
	}
	// 请注意服务器是否开通fopen配置
	private function  log_result($word) {
		date_default_timezone_set("Etc/GMT-8");
		$fp = fopen(LOG_FILE,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"\n".date('Y-m-d H:i:s').":".$word.PHP_EOL);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}


