<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/base_member.css" />
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">微信活动</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div>
<p class="pub-title"><?php if(isset($output["list"]) and count($output["list"]) > 0): ?>
<span>关注总人数<em><?php echo count($output["list"]); ?></em>人</span>
<?php else: echo "暂无关注"; endif;?>
</p>
		<ul class="pub-list">
		<?php if(!empty($output["list"])){
		foreach($output["list"] as $k=>$v){
			?>
			<li>
				<a href="" class="i45"><img src="<?php echo getMemberAvatar($v["headimgurl"]);?>" width="100%"/></a>
				<p>
					<a href="" class="name"><?php echo urldecode($v["nickname"])?></a><span class="time"><?php echo date('Y-m-d H:i:s',$v["reg_time"])?></span>
				</p>
				<em class="num"><?php echo $k+1;?></em>
			</li>
			<?php }}?>
		</ul>

</div>
