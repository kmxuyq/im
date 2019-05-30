<!DOCTYPE html>
<html>
<head lang="zh-CN">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" >
    <meta name="renderer" content="webkit">
    <title>谜之大使排行榜</title>
    <meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/lower_top.css" />
</head>

<body>
    <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/top.jpg" class="head-img" />
    <div class="main-box">
        <table border="0">
            <tr>
                <th colspan="2" width="40%">排名（TOP15)</th>
                <th width="40%">用户昵称</th>
                <th width="20%">关注</th>
            </tr>
            <tr>
            <?php foreach ($output["list"] as $k=>$v){
            		$top_ico='';
            		if(($k+1)<=3) $top_ico="class='no_".($k+1)."'";
            	?>
                <td width="20%" <?php echo $top_ico?>> <?php if(($k+1)>3) echo $k+1?></td>
                <td width="20%"><span style="background-image: url('<?php echo getMemberAvatar($v['openid'].'_weixin.jpg',$v["reg_time"])?>');"></span></td>
                <td width="40%"><b><?php echo $v["nickname"]?></b></td>
                <td width="20%"><?php echo $v["lower_num"]?></td>
            </tr>
           <?php }?>
        </table>
        
    </div>
</body>
</html>