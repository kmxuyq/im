<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>购买咨询2-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">购买咨询2</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <ul class="ui-list qz-background-none">
        <li class="ui-border-t">
            <div class="ui-list-thumb qz-list-thumb">
			<a href="<?php echo urlShopWAP('goods', 'index', array('goods_id' => $output['goods']['goods_id']));?>"> <img src="<?php echo cthumb($output['goods']['goods_image'], 240); ?>" alt="<?php echo $output['goods']['goods_name']; ?>" class="qz-img-block"> </a>
            </div>
            <div class="ui-list-info qz-light">
                <h4 class="ui-nowrap"><a href="<?php echo urlShopWAP('goods', 'index', array('goods_id' => $output['goods']['goods_id']));?>" style="color:#4F5F6F"> <?php echo $output['goods']['goods_name']; ?> </a></h4>
                <p class="ui-nowrap">￥<font class="qz-color2"><span class="qz-f18"><?php echo $output['goods']['goods_price'];?></span></font></p>
				<p class="ui-nowrap"><?php echo $lang['goods_index_evaluation'].$lang['nc_colon'];?> <span class="raty" data-score="<?php echo $output['goods']['evaluation_good_star'];?>"></span></p>
            </div>
        </li>
    </ul>
    
<?php if($output['consult_able']) { ?>
    <div class="qz-padding qz-padding-t">
	<form method="post" id="message" name='message_form' action="index.php?act=goods&op=save_consult">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
        <?php if($output['type_name']==''){?>
        <input type="hidden" name="goods_id" value="<?php echo $_GET['goods_id']; ?>"/>
        <?php }?>
		<?php if (!empty($output['consult_type'])) {?>
        <p class="qz-f20">咨询问题</p>
        <div class="qz-bk10"></div>
          
        <div class="qz-question">
		<?php $checked = true;foreach ($output['consult_type'] as $val) {?>
            <dl>
                <?php echo $val['ct_name'];?>
                <div class="qz-bk5"></div>
                <label class="ui-radio" for="radio">
				<input type="radio" <?php if ($checked) {?>checked="checked"<?php }?> nctype="ctype<?php echo $val['ct_id'];?>" name="consult_type_id" class="radio" value="<?php echo $val['ct_id'];?>" />
                </label>
            </dl> 
			<?php $checked = false;}?>
        </div>
		<?php }?>
        <div class="qz-bk10"></div>
        <p class="qz-color9">商品均为原装正品行货，自带机打发票，严格执行国家三包政策，享受全国联保服务。</p>
        <p class="qz-color9">咨询商品功能建议您拨打各品牌的官方客服电话，以便获得更准确的信息。</p>
        <div class="qz-bk5"></div>
		<?php if($_SESSION['member_id']){ ?>
         <p><?php echo $lang['goods_index_member_name'].$lang['nc_colon'];?><?php echo $_SESSION['member_name'];?></p>
        <div class="qz-bk10"></div>
        <div class="ui-form-item-checkbox">
            <label class="ui-checkbox" for="gbCheckbox">
                <input type="checkbox" class="checkbox" name="hide_name" value="hide" id="gbCheckbox">
            </label>
            <p><?php echo $lang['goods_index_anonymous_publish'];?></p>
        </div>
		<?php }?>
        <div class="qz-bk10"></div>
        <p>咨询内容</p>
        <div class="qz-bk5"></div>
			<textarea name="goods_content" id="textfield3" class="qz-textarea"></textarea>
           <span id="consultcharcount"></span><p>
		   <span id="az"></span>
    </div>

    <div class="ui-btn-wrap">
	<a href="JavaScript:void(0);" id="azheng" title="<?php echo $lang['goods_index_publish_consult'];?>" class="ui-btn-lg ui-btn-primary qz-btn-lg">提 交</a>
	
    </div>
    <div class="qz-bk10"></div>
	</form>
<?php } ?>
<div id="menu"></div>
</section>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script>
$(function(){
    $('.raty').raty({
        path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
        readOnly: true,
        score: function() {
          return $(this).attr('data-score');
        }
    });
    <?php if($output['consult_able']) { ?>
    $('#azheng').click(function(){
		$('#az').html('');
		//document.message_form.submit();
		//alert("www"+$('#message').submit());
		if($('#textfield3').val()==''){
			$('#az').html('咨询内容不能为空');return false;
		}
    	$('#message').submit();
    });

    // textarea 字符个数动态计算
    $("#textfield3").charCount({
    	allowed: 120,
    	warning: 10,
    	counterContainerID:'consultcharcount',
    	firstCounterText:'<?php echo $lang['goods_index_textarea_note_one'];?>',
    	endCounterText:'<?php echo $lang['goods_index_textarea_note_two'];?>',
    	errorCounterText:'<?php echo $lang['goods_index_textarea_note_three'];?>'
    });
    <?php }?>

    $('input[type="radio"]').click(function(){
        $('div[nctype^="ctype"]').hide();
        $('div[nctype="' + $(this).attr('nctype') + '"]').show();
    });
	
});
</script>