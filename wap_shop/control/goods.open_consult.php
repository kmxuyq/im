<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>购买提问</title>
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SHOP_SITE_URL;?>/templates/default/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SHOP_SITE_URL;?>/templates/default/css/style.css" />
		<style >
		.qz-question dl{
			float:left;
			width:24%;
			text-align:center;
		}
		.qz-question dl label{
			display:block;
			width:100%;
		}
		.none{
			display:none;
		}
		.block{
			display:block;
		}
		
		</style>
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="window.location.href = 'index.php?act=goods&op=consulting_list&goods_id=49'" style="color:#10b0e6;"></i>
    <h1 class="qz-color">购买咨询</h1>
</header>
<form method="post" id="message" action="index.php?act=goods&op=save_consult">
<?php Security::getToken();?>
        <input type="hidden" name="goods_id" value="<?php echo $output['goods']['goods_id']; ?>"/>
<section class="ui-container">
    <ul class="ui-list qz-background-none">
        <li class="ui-border-t">
            <div class="ui-list-thumb qz-list-thumb">
			    <a href="index.php?act=goods&op=index&goods_id=<?php echo $output['goods']['goods_id'];?>">
                <img src="<?php echo cthumb($output['goods']['goods_image'], 240); ?>" alt="<?php echo $output['goods']['goods_name']; ?>" class="qz-img-block">
				</a>
            </div>
            <div class="ui-list-info qz-light">
                <h4 class="ui-nowrap">
				<a href="index.php?act=goods&op=index&goods_id=<?php echo $output['goods']['goods_id'];?>">
				<?php echo $output['goods']['goods_name']; ?></h4>
				</a>
                <p class="ui-nowrap"><font class="qz-color2"><?php echo $lang['currency'].$output['goods']['goods_price'];?></font></p>
            </div>
        </li>
    </ul>
    
    <div class="qz-padding qz-padding-t">
	<?php if (!empty($output['consult_type'])) {?>
        <p class="qz-f20">咨询类型</p>
        <div class="qz-bk10"></div>
        <div class="qz-question type-list">
		<?php foreach($output['consult_type'] as $k => $v){ ?>
            <dl>
                <?php echo $v['ct_name'];?>
                <div class="qz-bk5"></div>
                <label class="ui-radio" for="radio">
                    <input type="radio" value="<?php echo $v['ct_id'];?>" data-form="<?php echo $v['ct_id'];?>" name="consult_type_id" <?php if($k == 1){ echo 'checked=checked';}?> >
                </label>
            </dl> 
	    <?php }?>
        </div>
        <div class="qz-bk10"></div>
        <p class="type-centent">
		<?php foreach($output['consult_type'] as $k => $v){ ?>
		<span class="type-centent-<?php echo $v['ct_id'];?> <?php if($k !== 1){echo 'none';} ?>" >
		<?php if(!empty($v['ct_introduce'])){
			echo $v['ct_introduce'];}else{echo '当前找不到有关<'.$v['ct_name'].'>信息。';};?>
		</span>
	    <?php } ?>
		</p>
		<?php }?>
        <div class="qz-bk5"></div>
		<?php if($_SESSION['member_id']){ ?>
        <p>用户名：<?php echo $_SESSION['member_name'];?></p>
        <div class="qz-bk10"></div>
        <div class="ui-form-item-checkbox">
            <label class="ui-checkbox">
            <input type="checkbox" class="checkbox" name="hide_name" value="hide" id="gbCheckbox">
            </label>
            <p>匿名发布</p>
        </div>
		<?php }?>
        <div class="qz-bk10"></div>
        <p>咨询内容</p>
        <div class="qz-bk5"></div>
        <textarea  name="goods_content" style =" width:95%;height:150px;"id="textfield3" ></textarea>
    </div>

    <div class="ui-btn-wrap">
        <input type="submit" value="提 交" id="form-sub"class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
    <div class="qz-bk10"></div>
</section>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
$(function(){
	  var list_dom = $('.type-list').find('dl')
	  list_dom.find('input').each(function(){
	  $(this).click(function(){
		  var ct_id = $(this).attr('data-form')
		   $('.type-centent').find('span').each(function(){
			   if($(this).hasClass('type-centent-'+ct_id)){
				   $('.type-centent-'+ct_id).removeClass('none');
		           $('.type-centent-'+ct_id).addClass('block');  
			   }else{
				   $(this).removeClass('block');
				   $(this).addClass('none');
			   }
		   })
		  
	  })
	  })
	      // textarea 字符个数动态计算
})
</script>
</body>
</html>