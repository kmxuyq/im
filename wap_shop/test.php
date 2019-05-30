<html>
<meta charset="utf-8" />
<span id='msg'></span>
<body>
	<script src="http://localhost/data/resource/js/jquery.js"></script>
	<script>
function getAjaxReturn() 
{ 
	$.ajax({ 
	type:"get", 
	async:false, 
	url:"/wap_shop/?act=test&op=aj", 
	success:function(msg){ 
			if(msg=="1"){ 
			 	alert(msg);
			 	//return false;
			 	alert('fdf');
			} 
		} 
	}); 
}
$(function(){
	$('#btsub').click(function (){
		isbuy('3654654');
		
	});
	function isbuy(goods_id){
    $.ajax({
		url: '/wap_shop/?act=test&op=aj&goods_id='+goods_id,
		type: 'GET',
		async: false,
		dataType: 'text',
		success:function(data){
			if(data!=''){
				alert(data);
				return false;
				alert('fff');
			}
			}
       });
}
	
});

if($('#msg').text()=='1')alert('ok');
//var package_name = $('#tb_package').val().replace(/\+/g, 'jia');//替换特殊字符
var str='10点(20分30分),11点(30分),12点(30分),13点(),14点(),';
//alert(str.substr().length);
str = str.replace(/点\(\)/g, '');
var str_arr=new Array();
str_arr=str.split('点');
var count=str_arr.length-1;
//alert(count);
$('#tt').click(function(){

	//tt2();
});
var tt= encodeURIComponent('1晚住宿+接机+门票');
//$('#tt').text(tt);

</script>
<?php 
$str='ASLDKFJLSAJFWIESALKVJSLDFKUJSOLDFKJLSKDJFPOWEUIJSJDFLSKUOWEULAVLOWEIUROSFJOSJFOSFDOSIFSFWERWERWER';
echo substr($str,0,43);
exit();
$str='{"ocuxlxPWygiLhJkv8QlxWKocGE0s":{"member_id":"135","openid":"ocuxlxPWygiLhJkv8QlxWKocGE0s","reg_time":"1450525188","source_type":"1","nickname":"Z.","sex":"2","country":"中国","city":"","province":"北京","headimgurl":"http://wx.qlogo.cn/mmopen/fhP3JOb0icwBnSmTcuLxPRlOUV7YPHIJDMXOOrjVob93ic5yibuXiaQDfacXqafOVmQPMPJr7hKTCIzic2pCMug7Nbg/0","status":"1","pid":"8"}}';
$json_arr=json_decode($str,true);
$json=array_values($json_arr);
echo (json_encode($json[0]));

exit();

$database = '182.245.121.168:50000/shop';
$user = 'hzcdb2';
$password = 'hzc_db2';

$type = 'ODBC'; //数据库类型
$db_name = 'DB2HZC'; //数据库名
$host = '182.245.121.168:50000';
$username = 'hzcdb2';
$password = 'hzc_db2';

$dsn = "$type:host=$host;dbname=$db_name";
try {
	$pdo = new PDO("odbc:dbname=DB2HZC:host=182.245.121.168", $username, $password);
} catch (Exception $e) {
	die('连接数据库失败!');
}
$stmt = $pdo->query('select * totalCount from db2inst1.hzc_qrcode');
while ($row = $stmt->fetch()) {
	echo "id=$row[0],body=$row[1]<br/>";
}
$pdo = null;

/* try{
	$dbh=new PDO('mysql:dbname=ymtx_gf;host=localhost','root','');
}catch(PDOException $e){
	echo '数据库连接失败：'.$e->getMessage();
	exit;
}
$stmt=$dbh->query("select * totalCount from db2inst1.hzc_qrcode where Invoice=1510202140710080");
	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
		echo $row["member_id"].'<br/>';
	} */
 exit();

$url="http://localhost/wap_shop/tt.php?tt=ggggggggggggggggggggggggggggggggg";
if(strpos($url, 'localhost')>0){
	echo strpos($url, 'localhost');
}else{
	echo 'no';
}
exit();
echo file_get_contents($url);exit();
$str='jIxnH4erMNYxbx-8BX8o1wUuR0YIf65RIvxComFAVkQP1xtQBb4Lg-PJTiL0qLiNHwRpFqzcuVED2m7oAV4krDdhxJ_emho_2WfJC-zcIiUEDKcAIAQP';
file_put_contents($_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt', $str);
echo file_get_contents($_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt');
echo $_SERVER["DOCUMENT_ROOT"].'/data/log/weixin_token.txt';
exit();
//带参二维码
$str2='{"ToUserName":"gh_3bb47c7e2f9d","FromUserName":"ocuxlxEkoyzYVxnVoj3Dc3AS5xgw","CreateTime":"1449712191","MsgType":"event","Event":"subscribe","EventKey":"qrscene_4","Ticket":"gQE58ToAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2NEOExxTXpsN09LVjkxSTR6QkcyAAIEI6BmVgMEgDoJAA=="}';
//常规二维码
$str='{"ToUserName":"gh_3bb47c7e2f9d","FromUserName":"ocuxlxEkoyzYVxnVoj3Dc3AS5xgw","CreateTime":"1449712183","MsgType":"event","Event":"unsubscribe","EventKey":"Array"}';
print_r(json_decode($str,true));exit();
$memu='{
     "button":[
     {	
          "type":"view",
          "name":"微商城",
          "url":"http://m.gellefreres.com"
      },
      {
           "name":"精彩活动",
           "sub_button":[
           {	
               "type":"view",
               "name":"生成专属二维码",
               "url":"http://m.gellefreres.com/wap_shop/?act=weixin&op=send_user_code_img"
            }]
       }]
 }';
print_r(json_decode($memu,true));
$tt=strpos("ocuxSDDlxBJkuCfH6ewSMkLp2_atZV0,ocuxlxBJkuCDFDfH6ewSMkLp2_atZV0,ocuxlxBJkuCfH6ewSMkLp662_atZV0ocuxlxBJkuCfH6ewSMkLp2_atZV0","ocuxlxBJkuCfH6ewSMkLp2_atZV0");
if($tt==''){
	echo 'no';
	
}else{
	
	echo 'yes';
}

exit();

// 定义一个函数getIP()
echo round('4.3');
echo round('4.5');exit();
function getIP(){
global $ip;
if (getenv("HTTP_CLIENT_IP"))
$ip = getenv("HTTP_CLIENT_IP");
else if(getenv("HTTP_X_FORWARDED_FOR"))
$ip = getenv("HTTP_X_FORWARDED_FOR");
else if(getenv("REMOTE_ADDR"))
$ip = getenv("REMOTE_ADDR");
else $ip = "Unknow";
return $ip;
}
// 使用方法：
echo getIP();exit();
$str='a:15:{s:3:"act";s:11:"buy_virtual";s:2:"op";s:9:"buy_step1";s:8:"goods_id";s:6:"104265";s:8:"quantity";s:1:"1";s:7:"package";s:6:"假日";s:8:"man_type";s:0:"";s:4:"date";s:10:"2015-12-04";s:6:"buynum";s:1:"1";s:8:"commonid";s:6:"100232";s:7:"type_id";s:2:"46";s:12:"show_package";s:0:"";s:9:"golf_hour";s:15:"11点12点13点";s:11:"golf_minute";s:36:"11点(30分40分),12点(30分40分),";s:8:"golf_num";s:0:"";s:15:"golf_minute_num";s:1:"4";}';
print_r(unserialize($str));exit();
$str='10点(20分30分),11点(30分),12点(30分),13点(),14点(),';
$str=str_replace('点(),', '', $str);
echo substr_count($str,'点');

exit();
echo urldecode('A线路票（游船单程%2B电瓶车单程）%2B门票');
if(strpos($_GET["man_type"],'成')!==false){
    				$man_type='man_price';
    			}elseif(strpos($_GET["man_type"],'儿')!==false){
    				$man_type='child_price';
    			}
    			echo $man_type;
$other_package='';
$str2='a:4:{i:54;a:1:{i:361310;s:34:"温泉票2张+温泉客栈1间1晚";}i:56;a:2:{i:361035;s:9:"成人票";i:361311;s:9:"双人票";}i:57;a:1:{i:361312;s:24:"请查看产品详情页";}i:58;a:1:{i:361313;s:58:"请在入住前2天完成预订，节假日需提前一周";}}';
$spec_value1=array_values( unserialize($str2));
$spec_value2=array_values($spec_value1[1]);
$man_name='';$child_name='';
foreach($spec_value2 as $v){
	if(strpos($v, '成')!==false){
		$man_name=$v;
	}elseif(strpos($v, '儿')!==false){
		$child_name=$v;
	}
}

if(count($spec_value1)>2){
	for($i=2;$i<count($spec_value1);$i++){
		
		$other_package_arr=array_values($spec_value1[$i]);print_r($other_package_arr);
		$other_package.=$other_package_arr[0];
	}
}
echo $man_name.'--'.$child_name.'--'.$other_package;
exit();
//echo urldecode('我5%2B你是%2B我');
//echo urldecode($_GET['tt']);

//echo file_put_contents($_SERVER["DOCUMENT_ROOT"].'/data/log/20151030.log','1111');
//session_start();
//$_SESSION['SU']='OK';
//print_r($_SESSION);

//echo date('w',strtotime('2015-10-04'));
//echo serialize(array('8','9','10','11','12','13','14','15','16','17','18')).'<br/>';
//echo serialize(array('00','10','20','30','40','50'));
//exit();
//$str='{"9:00-10:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"},"10:00-11:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"},"11:00-12:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"},"13:00-14:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"},"15:00-16:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"},"17:00-18:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"},"19:00-20:00":{"price":{"3-5\u4eba":100,"5-8\u4eba":120,"10\u4eba\u4ee5\u4e0a":150},"stock":"1"}}';
/* $str1='a:2:{i:30;s:12:"套餐类型";i:31;s:12:"出游人群";}';
$str2='a:2:{i:30;a:1:{i:176438;s:27:"九乡七彩云南一日游";}i:31;a:1:{i:176443;s:6:"成人";}}';
$str3='a:2:{i:176438;s:9:"套餐一";i:176443;s:6:"成人";}'; */
//景区
$str1='a:4:{i:54;s:12:"收费项目";i:56;s:12:"门票种类";i:57;s:9:"有效期";i:58;s:12:"入园限制";}';
$str2='a:4:{i:54;a:2:{i:360868;s:9:"大门票";i:360895;s:51:"A线路票（游船单程+电瓶车单程）+门票";}i:56;a:1:{i:360900;s:9:"成人票";}i:57;a:1:{i:360904;s:41:"游客指定入园日期后15天内有效";}i:58;a:1:{i:360911;s:30:"提前一天预订方可入园";}}';
print_r(unserialize($str1));
print_r(unserialize($str2));
echo '<br/>';
echo count(unserialize($str2));
print_r(unserialize($str3));

echo "<br/>";
$goods_spec=array_values(unserialize($str2));
$goods_man_type=array_values($goods_spec[1]);//
$man=$goods_man_type[0];//成人
$child='';
if(!empty($goods_man_type[1]))$child=$goods_man_type[1];//儿童
echo $man.'<br/>';
echo $child.'<br/>';
/* $spec_value=array_values(unserialize('a:1:{i:48;a:3:{i:361314;s:6:"3-5人";i:361315;s:6:"5-8人";i:361316;s:11:"10人以上";}}'));
print_r($spec_value);
foreach($spec_value[0] as $k=>$v){
	$package_price[$v]='100';
}
print_r($package_price);
$site_num=array('8',"9",'10','11','12','13','14','15','16','17','18','19','20');
echo json_encode($site_num);
echo $date=date('Y-m-d',strtotime("+3 day"));
print_r(unserialize('a:1:{i:48;a:3:{i:361314;s:6:"3-5人";i:361315;s:6:"5-8人";i:361316;s:11:"10人以上";}}'));//spec_value   exit();
print_r(unserialize('a:1:{i:48;s:12:"套餐类型";}'));//spec_name  exit(); */
$str='a:11:{i:8;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:9;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:10;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:11;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:12;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:13;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:14;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:15;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:16;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:17;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}i:18;a:6:{s:2:"00";a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:10;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:20;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:30;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:40;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}i:50;a:2:{s:5:"price";N;s:5:"stock";s:2:"39";}}}';
print_r(unserialize($str));exit();
$golf_stock_info=array('8'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'9'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'10'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'11'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'12'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'13'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'14'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'15'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'16'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'17'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'18'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		),'19'=>array(
		'00'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'10'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'20'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'30'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'40'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		'50'=>array('price'=>array('3-5人'=>100,'5-8人'=>120,'10人以上'=>150),'stock'=>1),
		)
		
);
echo serialize($golf_stock_info);exit();
print_r($golf_stock_info);
echo json_encode($golf_package_info);exit();
echo strpos("You love php, I love php too!","php");

/* require_once($_SERVER["DOCUMENT_ROOT"]."/wap_shop/framework/function/function.php");

$tt=log_result('', json_encode($_POST));

print_r($tt); */
/* function test2(){
	require_once $_SERVER["DOCUMENT_ROOT"].'/data/config/config.ini.php';
	echo $config['db']['1']['dbhost'];
		
}
test2(); */

/* require_once $_SERVER["DOCUMENT_ROOT"].'/wap_shop/framework/libraries/conn.php';
//支付成功
//商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）......
$order_id_arr=explode("-",'7000000000046001-510190');
$order_id=$order_id_arr[0];
//判断商品是否是虚拟商品,1为1，0为否,以调用不同的订单表
$is_virtual='0';
$is_virtual_table='';
//$is_virtual_count=Model()->table('order')->where(array('order_sn'=>$order_id))->count();
echo "select order_id from ymjr_order where order_sn='{$order_id}'";
$is_virtual_count=$conne->getRowsNum("select order_id from ymjr_order where order_sn='{$order_id}'");
echo $is_virtual_count; */

/* 
require_once $_SERVER["DOCUMENT_ROOT"].'/wap_shop/framework/libraries/PaymentHandle.php';
//ECHO LIB_PATH;
 $payment=new PaymentHandle();
 $status=$payment->pay_add('wxpay', '7000000000046001-510192', 'cmb0', 2, 'rmb0', '无0','5555555555555060', 'okok0');
echo $status; */

/* $config=include_once $_SERVER["DOCUMENT_ROOT"].'/data/config/config.ini.php';

define('WAP_SITE_URL',$config["wap_shop_site_url"]);
 echo WAP_SITE_URL; */
 $arr = array(
		"套餐一" => array (
				"2015-10" => array (
						'1' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01' 
						), 
						'2' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'3' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'4' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'5' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'6' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'7' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'8' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
						'9' => array (
								'man_price' => 1200,
								'child_price' => 1000,
								'stock' => 20,
								'date' => '2015-10-01'
						),
				) 
		),
		"套餐二" => array () 
);echo array_keys($arr);
foreach ($arr as $k=>$v){
	
}
exit();


//获取产品表的产品类别goods_common.typeid=type.type_id     where goods_common.typeid in(40,41,42)//线路
$str='a:4:{i:1;a:2:{i:102992;s:9:"套餐一";i:102993;s:9:"套餐二";}i:22;a:2:{i:102997;s:6:"成人";i:102998;s:6:"儿童";}i:23;a:1:{i:103008;s:5:"10月";}i:24;a:8:{i:103011;s:4:"1日";i:103012;s:4:"2日";i:103013;s:4:"3日";i:103014;s:4:"4日";i:103015;s:4:"5日";i:103016;s:4:"6日";i:103017;s:4:"7日";i:103018;s:4:"8日";}}';
$str1='a:5:{i:48;a:2:{i:361221;s:6:"平日";i:361222;s:6:"假日";}i:49;a:2:{i:361232;s:5:"10月";i:361233;s:5:"11月";}i:50;a:4:{i:361220;s:2:"14";i:361235;s:4:"2日";i:361236;s:4:"3日";i:361237;s:4:"4日";}i:52;a:2:{i:361264;s:4:"7:00";i:361269;s:4:"9:40";}i:53;a:2:{i:361276;s:2:"00";i:361277;s:2:"40";}}';
$str2='a:7:{i:35;a:1:{i:361056;s:4:"9月";}i:36;a:19:{i:361071;s:5:"12日";i:361072;s:5:"13日";i:361073;s:5:"14日";i:361074;s:5:"15日";i:361075;s:5:"16日";i:361076;s:5:"17日";i:361077;s:5:"18日";i:361078;s:5:"19日";i:361079;s:5:"20日";i:361080;s:5:"21日";i:361081;s:5:"22日";i:361082;s:5:"23日";i:361083;s:5:"24日";i:361084;s:5:"25日";i:361085;s:5:"26日";i:361086;s:5:"27日";i:361087;s:5:"28日";i:361088;s:5:"29日";i:361089;s:5:"30日";}i:54;a:1:{i:360895;s:51:"A线路票（游船单程+电瓶车单程）+门票";}i:55;a:1:{i:360898;s:9:"电子票";}i:56;a:1:{i:360900;s:9:"成人票";}i:57;a:1:{i:360904;s:41:"游客指定入园日期后15天内有效";}i:58;a:1:{i:360911;s:30:"提前一天预订方可入园";}}';
print_r(unserialize($str2));
?>
</body>
</html>