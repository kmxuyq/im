<!DOCTYPE html>
<html lang="en">
<head>
    <title>一元抢购</title>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元抢购，伊美假日"/>
    <meta name="author" content="伊美假日"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/reset.css">
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/main.css">
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/jquery-1.11.3.min.js"></script>
</head>
<body>
<!-- 头部 -->
<div class="header">
    <div class="arrow"></div>
    <div class="tt">参与记录</div>
    <div class="go-home"></div>
</div>
<div class="page-unfind"></div>
<div class="page-tips">
    <?php echo $output['message'];?>
</div>
<script>
$(function(){
	var time = <?php echo $output['time'];?>;
	var url = '<?php echo $output['url'];?>';
	setTimeout(function(){location.href =url},time);
})
</script>
</body>
</html>
