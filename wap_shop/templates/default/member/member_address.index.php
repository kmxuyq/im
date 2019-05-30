<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>选择地址-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
 <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js"></script>
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">收货地址</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-address-list">
	<?php if(!empty($output['address_list']) && is_array($output['address_list'])){?>
	    <?php foreach($output['address_list'] as $key=>$address){?>
        <dl>
            <div class="qz-padding qz-top-b qz-bottom-b qz-background-white clearfix">
                <span class="qz-fr"><a href="index.php?act=member_address&op=address&type=edit&id=<?php echo $address['address_id'];?>">编辑</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index.php?act=member_address&op=address&id=<?php echo $address['address_id'];?>">删除</a></span>
            </div>
            
            <div class="qz-padding qz-background-white qz-bottom-b clearfix">
			<?php if ($address['is_default'] == '1') {?>
                <div class="lt">
                    <label class="ui-checkbox" onclick="setDefaultAddress($(this),'<?php echo $address['address_id'];?>');">
                        <input type="checkbox" checked>
                    </label>
                </div>
            <?php }else{ ?>
				<div class="lt">
				    <label class="ui-checkbox" onclick="setDefaultAddress($(this),'<?php echo $address['address_id'];?>');">
                        <input type="checkbox" >
                    </label>
				</div>
			<?php } ?>    
                <div class="ct">
                    <?php echo $address['true_name'];?><br><?php echo $address['area_info'];?><br><?php echo $address['address'];?>
                </div>
                
                <div class="rt qz-text-r"><?php if($address['mob_phone'] != ''){ echo $address['mob_phone'];}else{echo $address['tel_phone'];}?></div>
            </div>
        </dl>
		<?php } ?>
    <?php }else{ ?>
	    <dl>
            <div class="qz-padding qz-top-b qz-bottom-b qz-background-white clearfix">
                <?php echo $lang['no_record'];?>
            </div>
        </dl>
	<?php } ?>          
    </div>
    
    <div class="ui-btn-wrap">
        <input type="button" value="使用新地址" onclick="location.href = 'index.php?act=member_address&op=address&type=add'" class="ui-btn ui-btn-lg ui-btn-primary qz-padding-30 qz-background-yellow" />
    </div>
</section>
<script>
function setDefaultAddress(_this,address_id){
	//var input_check = This.getElementsByTagName
	if(_this.find('input').is(":checked")){
		$.post('index.php',{'act':'member_address','op':'setDefault','id':address_id},function(data){
			if(data == '1'){
				history.back();
			}
		})
	}
}
</script>
</body>
</html>