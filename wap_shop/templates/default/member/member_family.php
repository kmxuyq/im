<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/base_member.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.css">

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">我的团队</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div>
<div class="index_tab_wrap">
   <ul class="tab_tt">
      <li style="width:100%;font-size:16px;" class="on"><span>游客总人数：<?php echo count($output['list']); ?></span></li>
   </ul>
</div>
		<ul class="pub-list">
		<?php if(!empty($output["list"])){
		foreach($output["list"] as $k=>$v){
			?>
			<li <?php if(empty($_GET["type"])&&$v["status"]=='0')echo "style='display:none'";?> style="font-size:16px;padding:0.7em 5%;">
				<a href="javascript:;" class="i45"><img src="<?php echo getMemberAvatar($v["headimgurl"]);?>" width="100%"/></a>
				<p>
					<a href="" class="name" style="font-size:1em;"><?php echo urldecode($v["nickname"])?></a><span class="time" style="font-size:1em;"><?php echo date('Y-m-d H:i:s',$v["reg_time"])?></span>
				</p>
				<em class="num" style="top:1em;" <?php if($v["status"]=='0')echo "style='color:#ccc;border: 1px solid #ccc;'"?>><?php echo $k+1?></em>
			</li>
			<?php }}?>
		</ul>

</div>
