<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>账户安全</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member_information&op=index'" ></i>
    <h1 class="qz-color">账户安全</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<style>
body{ background:#FFF;}
</style>
<section class="ui-container">
    <div class="accountUser">
    	<p><a href=""><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>" /></a></p>
        <dl class="accountUserDetail">
        	<dd>
            	<span>登录账号：</span>
                <em><?php echo $output['member_info']['member_name'];?></em>
            </dd>
<!--             <dd>
            	<span>绑定邮箱：</span>
                <em><?php echo encryptShow($output['member_info']['member_email'],4,4);?></em>
            </dd> -->
            <dd>
            	<span>手机号码：</span>
                <em><?php echo encryptShow($output['member_info']['member_mobile'],4,4);?></em>
            </dd>
            <dd>
            	<span>上次登录：</span>
                <em><?php echo date('Y年m月d日 H:i:s',$output['member_info']['member_old_login_time']);?>&nbsp&nbsp(不是您登录的？请立即<a href="index.php?act=member_security&op=auth&type=modify_pwd">“更改密码”</a>)。</em>
            </dd>
        </dl>
    </div>
    <div class="accountText1">	
	<?php if ($output['member_info']['security_level'] <= 1) { ?>
    <span>安全等级：<i>低</i></span><em>(建议您开启全部安全设置，以保障账户及资金安全)</em>
    <?php } else if ($output['member_info']['security_level'] == 2) { ?>
    <span>安全等级：<i>中</i></span><em>(建议您开启全部安全设置，以保障账户及资金安全)</em>
    <?php } else { ?>
    <span>安全等级：<i>高</i></span><em>(您目前账户运行很安全)</em>
    <?php } ?>
    </div>
    <dl class="accountList">
    	<dd>
        	<p>
            	<span>
                	<Img src="<?php echo SHOP_TEMPLATES_URL;?>/images/ficn3.jpg" />
                    <i class="i1"></i>
                </span>
                <em>登录密码</em>
            </p>
            <div>
            	<span>
                	安全性高的密码可以使账号更安全。建议您定期更换密码，且设置一个包含数字和字母，并长度超过6位以上的密码，为保证您的账户安全，只有在您绑定邮箱或手机后才可以修改密码。
                </span>
                <em>
                	<a class="a1" href="index.php?act=member_security&op=auth&type=modify_pwd">修改密码</a>
                </em>
            </div>
        </dd>
        <dd>
        	<p>
            	<span>
                	<Img src="<?php echo SHOP_TEMPLATES_URL;?>/images/ficn4.jpg" />
                    <i class="<?php echo $output['member_info']['member_email_bind'] == 1 ? 'i1' : '';?>"></i>
                </span>
                <em>邮箱绑定</em>
            </p>
            <div>
            	<span>
                	进行邮箱验证后，可用于接收敏感操作的身份验证信息，以及订阅更优惠商品的促销邮件。
                </span>
                <em>
				<a class="a1" href="index.php?act=member_security&op=auth&type=modify_email"><?php echo $output['member_info']['member_email_bind'] == 1 ? '修改邮箱' : '绑定邮箱';?></a>
                </em>
            </div>
        </dd>
        <dd>
        	<p>
            	<span>
                	<Img src="<?php echo SHOP_TEMPLATES_URL;?>/images/ficn5.jpg" />
                    <i class="<?php echo $output['member_info']['member_mobile_bind'] == 1 ? 'i2' : '';?>"></i>
                </span>
                <em>手机绑定</em>
            </p>
            <div>
            	<span>
                	进行手机验证后，可用于接收敏感操作的身份验证信息，以及进行积分消费的验证确认，非常有助于保护您的账号和账户财产安全。
                </span>
                <em>
                	<a class="a2" href="index.php?act=member_security&op=auth&type=modify_mobile"><?php echo $output['member_info']['member_mobile_bind'] == 1 ? '更换手机' : '绑定手机';?></a>
                </em>
            </div>
        </dd>
        <dd>
        	<p>
            	<span>
                	<Img src="<?php echo SHOP_TEMPLATES_URL;?>/images/ficn6.jpg" />
                    <i class="<?php echo $output['member_info']['member_paypwd'] != ''  ? 'i1' : '';?>"></i>
                </span>
                <em>支付密码</em>
            </p>
            <div>
            	<span>
                	设置支付密码后，在使用账户中余额时，需输入支付密码。
                </span>
                <em>
                	<a class="a1" href="index.php?act=member_security&op=auth&type=modify_paypwd"><?php echo $output['member_info']['member_paypwd'] != '' ? '修改密码' : '设置密码';?></a>
                </em>
            </div>
        </dd>
    </dl>
</section>


<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
</body>
</html>