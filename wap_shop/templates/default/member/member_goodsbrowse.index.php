<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>浏览历史</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member&op=home'"></i>
    <h1 class="qz-color">浏览历史</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php if (is_array($output['browselist']) && !empty($output['browselist'])) { ?>
<ul class="timeline">

<?php foreach ((array)$output['browselist'] as $k=>$v){?>
   <li class="timeline_cell">
        <div class="timeline_tt"><?php echo $v['browsetime_text'];?></div>
        <div class="timeline_main">
            <div class="imgBox">
			<a href="index.php?act=goods&op=index&goods_id=<?php echo $v['goods_id']; ?>" target="_blank">
			<img src="<?php echo cthumb($v['goods_image'], 240);?>" alt=""/>
			</a>
			</div>
            <div class="text">
                <p class="text_dec">
				<a href="index.php?act=goods&op=index&goods_id=<?php echo $v['goods_id']; ?>" target="_blank">
				<?php echo $v['goods_name'];?>
				</a>
				</p>
                <span class="text_money">
				<span class="tip_red"><?php echo $lang['currency'];?><?php echo $v['goods_promotion_price'];?></span>&nbsp
				</span>

            </div>
			<div class="button">
			<div class="text" id="button-box" style="display:none">

			    <span class="del-button"  nc_type="delbtn" data-param='{"goods_id":<?php echo $v['goods_id'];?>}'>删  除</span>
                <!-- delete by tanzn start
				<span class="add-ctar" onclick="javascript:addcart(<?php echo $v['goods_id'];?>,1,'');">加入购物车</span>
				delete by tanzn stop -->
				</div>
            </div>
        </div>
    </li>
<?php }?>

</ul>
<div style="width:100%; height:30px;" id="show_page">
<?php echo $output['show_page']; ?>
</div>
<?php } else { ?>
<ul class="timeline">
<li class="timeline_cell">
<?php echo $lang['no_record'];?>
</li>
</ul>
<?php } ?>
<script type="text/javascript" >
/* 加入购物车 */
function addcart(goods_id,quantity,callbackfunc) {
    var url = 'index.php?act=cart&op=add';
    quantity = parseInt(quantity);
    $.getJSON(url, {'goods_id':goods_id, 'quantity':quantity}, function(data) {
        if (data != null) {
            if (data.state) {
            	alert('添加成功')
            } else {
                alert(data.msg);
            }
        }
    });
}
 //清除单条浏览记录
   $("[nc_type='delbtn']").bind('click',function(){
	   if(confirm("确实要删除吗？")){
		   var data_str = $(this).attr('data-param');
		   eval( "data_str = "+data_str);
		   $.getJSON('index.php?act=member_goodsbrowse&op=del&goods_id='+data_str.goods_id,function(data){
				if(data.done == true){
					location.reload()
				}else{
					showDialog(data.msg);
				}
			});
	   }
   });
$(function(){
	$('.timeline').find('.timeline_cell').each(function(){
		$(this).mouseover(function(){
			$(this).find('#button-box').css({'display':'block'})
		})
		$(this).mouseout(function(){
			$(this).find('#button-box').css({'display':'none'})
		})
	})
})
</script>
<style>
#show_page li{
	float:left;
	margin:6px 6px;
}
.del-button{
width:60px;display:block;float:right;background:#BBA059; border:1px solid #BBA059;text-align:center;
	height: 30px;line-height: 30px; background: #3b84ed; border: none; color: #fff
}
.add-ctar{
width:100px;display:block;float:right; background:#BBA059; border:1px solid #BBA059;text-align:center;margin-right:8px;
}

body{ background:#f5f5f5;}
</style>
</body>
</html>


























