<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>虚拟订单列表-圈子</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" href="/data/resource/icon/css/font-awesome.min.css" />
    
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color"><?php if($_GET['ac_id'] == 2){ echo '帮助中心';}else if($_GET['ac_id'] == 5){ echo '售后服务';} ?></h1>
    <em class="recharge_em">
    </em>
</header>
<?php require_once template('menu');?>
<?php if(!empty($output['article']) and is_array($output['article'])){?>
<div class="help_says">
<?php foreach ($output['article'] as $article) {?>
<div class="help_says_line">
    	<a  <?php if($article['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($article['article_url']!='')echo $article['article_url'];else echo'index.php?act=article&op=show&ac_id='.$_GET['ac_id'].'&article_id='.$article['article_id'];?>">
            <?php echo $article['article_title'];?>
            <span class="fa-ico"><i class="fa fa-angle-right"></i></span>
        </a>
    </div>

<?php } ?>
</div>
<?php }else{ ?> 
<div class="counseling respond_list">
暂无相关文章！
</div>
<?php } ?>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>