<?php
class JSSDK {
  private $appId;
  private $appSecret;

  public function __construct($appId, $appSecret) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    if(ip2long($_SERVER[HTTP_HOST])) {
      $_SERVER[HTTP_HOST] = $_SERVER[SERVER_NAME];
    }
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $file_name=$_SERVER["DOCUMENT_ROOT"].'/wap_shop/jsapi_ticket.json';
    $data = json_decode(file_get_contents($file_name));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen($file_name, "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }
  public function getAccessToken(){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
    $token_file = $_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt';
    if(!file_exists($token_file)||(filemtime($token_file)+7200)<time()){
      $token_str = $this->httpGet($url);
      $token_arr = json_decode($token_str);

      if(($token_arr->access_token)!=''){
        file_put_contents($token_file, $token_arr->access_token);
      }
    }
    $token = file_get_contents($token_file);//此行不要删
  
    $getip_url="https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=";
    //echo $getip_url.$token;
    $ip_json = $this->httpGet($getip_url.$token);
    $ip_json_arr = json_decode($ip_json,true);
    if($ip_json_arr["errcode"]=='40001'||$ip_json_arr["errcode"]=='41001'){
      //如果失效
      $token_str = $this->httpGet($url);
      $token_arr = json_decode($token_str);
      if(($token_arr->access_token)!=''){
        file_put_contents($token_file, $token_arr->access_token);
      }
    }
    $token=file_get_contents($token_file);
    return $token;
  }
  /*
  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $fp = fopen("access_token.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }
*/
  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
  /**
   * 是否是微信打开
   * */
  public function is_wx_pro(){
  	$user_agent = $_SERVER['HTTP_USER_AGENT'];
  	if (strpos($user_agent, 'MicroMessenger') === false) {
  		// 非微信浏览器禁止浏览
  		return false;
  	} else {
  		// 微信浏览器，允许访问
  		//preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);//echo "MicroMessenger";
  		//echo '<br>Version:'.$matches[2];// 获取版本号
  		return true;
  	}
  }
}

