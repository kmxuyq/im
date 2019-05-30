<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>商品咨询</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
</head>
<style>
.show_page_1 li{
	float:left;
	margin:8px;
}
</style>
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <a href="index.php?act=member&op=home">
    <i class="ui-icon-return"></i>
	</a>
    <h1 class="qz-color"><?php echo $lang['store_consult_reply'];?></h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div id="leftTabBox" class="tabBox-main">
    <div class="hd tabBox-tt">
        <ul>
            <li onclick="location.href ='index.php?act=member_consult&op=my_consult';"class="<?php if($_GET['type']=='consult_list'){ echo 'on';}?>">全部咨询</li>
            <li onclick="location.href ='index.php?act=member_consult&op=my_consult&type=to_reply';" class="<?php if($_GET['type']=='to_reply'){ echo 'on';}?>">未回复咨询</li>
            <li onclick="location.href = 'index.php?act=member_consult&op=my_consult&type=replied';" class="<?php if($_GET['type']=='replied'){ echo 'on';}?>">已回复咨询</li>
        </ul>
    </div>
    <div class="tempWrap" style="clear: both">
	
        <div class="bd">
		
            <div class="tab_text1">
			    <?php  if (count($output['list_consult'])>0){ ?>
                <?php foreach($output['list_consult'] as $consult){?>
                <div class="counseling">
                    <div class="counseling_tt">
					<a href="index.php?act=goods&goods_id=<?php echo $consult['goods_id']; ?>" target="_blank">
					<?php echo $consult['goods_name'];?>
	            	</a>
					</div>
                    <p class="counseling_text">
					<?php echo nl2br($consult['consult_content']);?>
					</p>
				    <div class="counseling_dec">
					    <span class="counseling_name"><?php echo $_SESSION['member_name'];?></span>
                        <em class="counseline_times"><?php echo date("Y-m-d H:i:s",$consult['consult_addtime']);?></em>
				    </div>
                </div>	
				<?php if($consult['consult_reply'] != ""){?>
                     <div class="counseling_message"><?php echo nl2br($consult['consult_reply']);?></div>
			    <?php }?>
				<?php }?>
                <?php }?>
            </div>
			
			<div class="tab_text1 show_page_1">
			<?php echo $output['show_page'];?>
			</div>
        </div>
		
    </div>
</div>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>