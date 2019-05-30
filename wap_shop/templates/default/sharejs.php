<?php if($_SESSION['share_shop'] == 1): ?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php
function get_access_token(){
   $token = rkcache('access_token');
   if(!empty($token)){
      $token = unserialize($token);
   } else{
      $token = array();
   }
   if(empty($token) || TIMESTAMP - $token['create_time'] >= 7200){
      $uri = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $GLOBALS["setting_config"]["weixin_appid"] . '&secret=' . $GLOBALS["setting_config"]["weixin_appsecret"];
      $token = curl($uri);
      $token = json_decode($token,true);
      if(!isset($token['access_token']) || empty($token['access_token'])){
         return '';
      }
      $token['create_time'] = TIMESTAMP;
      wkcache('access_token', serialize($token));
   }
   return $token['access_token'];

}
function get_jsapi_ticket() {
   $tickets = rkcache('jsapi_ticket');
   if ($tickets) {
      $tickets = unserialize($tickets);
   }
   if (empty($tickets) || time() - $tickets['create_time'] >= 7200) {
      $access_token = get_access_token();
      if ('' == $access_token) {
         return '';
      }
      $uri     = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token . '&type=jsapi';
      $tickets = curl($uri);
      $tickets = json_decode($tickets, true);
      if (empty($tickets['ticket'])) {
         return '';
      }
      $tickets['create_time'] = time();
      wkcache('jsapi_ticket', serialize($tickets));
   }
   return $tickets['ticket'];
}
$jsapi_ticket = get_jsapi_ticket();
$timestamp = time();
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$str   = '';
for ($i = 0; $i < 16; $i++) {
   $str .= $chars{mt_rand(0, strlen($chars) - 1)};
}
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') !== false ? 'https://' : 'http://';
$host     = $protocol . $_SERVER['HTTP_HOST'];
$url      = $host . $_SERVER['REQUEST_URI'];
$string1   = "jsapi_ticket={$jsapi_ticket}&noncestr={$str}&timestamp={$timestamp}&url={$url}";
$signature = sha1($string1);
?>
<script>
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $GLOBALS["setting_config"]["weixin_appid"]; ?>', // 必填，公众号的唯一标识
    timestamp: '<?php echo $timestamp; ?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $str; ?>', // 必填，生成签名的随机串
    signature: '<?php echo $signature; ?>',// 必填，签名，见附录1
    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
var sharedata = {
   title:'<?php echo $_SESSION['member_name']; ?>的小店',
   desc:'<?php echo $_SESSION['member_name']; ?>的小店',
   link:'<?php echo C('wap_shop_site_url'); ?>/index.php?act=show_store&op=share&share_uid=<?php echo $_SESSION['member_id'];?>&store_id=<?php echo $_SESSION['share_store_id']; ?>',
   imgUrl:'',
   success:function(){
   },
   cancel: function(){
      console.log('user cancel share');
   }
};
wx.ready(function () {
   wx.onMenuShareAppMessage(sharedata);
   wx.onMenuShareTimeline(sharedata);
});
</script>
<?php endif;?>
