<?php
/**
 * 微信接口板块初始化文件
 *
 *
 * *  */

$_GET['act'] = 'weixin';
$_GET['op'] = 'index';


define('APP_ID','shop');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
if (!@include(BASE_CORE_PATH.'/33hao.php')) exit('33hao.php isn\'t exists!');

define('WAP_SHOP_SITE_URL',$config['wap_shop_site_url'] );
define('WAP_SITE_URL',$config['wap_site_url'] );
define('CHAT_SITE_URL',$config['chat_site_url'] );
define('MOBILE_SITE_URL',$config['mobile_site_url'] );
define('APP_SITE_URL',WAP_SHOP_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('SHOP_RESOURCE_SITE_URL',WAP_SHOP_SITE_URL.DS.'resource');
define('SHOP_TEMPLATES_URL',WAP_SHOP_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

Base::run();
?>