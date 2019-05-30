<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		
		<meta name="keywords" content="your keywords">
		<meta name="description" content="your description">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">	
		<title>礼品领取</title>
		<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/base_member.css" />
<style>
.bt_cc{border: 1px solid #ccc;
    color: #ccc;}
.bt_ok{border: 1px solid #097c25;
    color: #097c25;}
</style>
	</head>

<div>
<ul class="pub-list">
<?php if(!empty($output["errmsg"])) echo '<p class="pub-title" style="width:70%">'.$output["errmsg"].'</p>'?>
<?php 
if(!empty($output["list"])){
	foreach($output["list"] as $v){
			if(!empty($v["goods_id"])){
				$imgurl=thumb(array('goods_image'=>$v["goods_image"],'store_id'=>$v["store_id"]), 240);
			}elseif(!empty($v["voucher_t_id"])){
				$imgurl=SHOP_TEMPLATES_URL.'/images/voucher_bg.gif';
			}
			if(!empty($v["goods_id"])||!empty($v["voucher_t_id"])){
		?>
			<li>
				<a href="javascript:" class="i75"><img src="<?php echo $imgurl;?>" width="100%"/></a>
				<p class="h30"><?php echo $v["title"]?></p>
				<?php if($output["lower_num"]==$v["lower_num"]){
						$source=str_replace(array('a1','a2'), array('2','1'), 'a'.$v["source_type"]);
						if(!empty($v["goods_id"])){
							$url="?act=active&op=index&type=appaly_goods&wx={$source}&goods_id=".encrypt($v["goods_id"])."&present_member_id=".encrypt($output["present_member"]["id"])."&openid={$output["present_member"]["openid"]}";
						}elseif(empty($v["goods_id"])&&!empty($v["voucher_t_id"])){
							$url="?act=member_voucher";
						}
					
					?>
				<a href="<?php echo $url?>" class="pub-receive">点击领取</a>
				<?php }else{
					echo "<a class='pub-receive bt_cc'>点击领取</a>";
				}?>
			
			</li>
	<?php }
	}
}?>
		</ul>
</div>
</body>
</html>
