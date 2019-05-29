<?php
// define('NODE_SITE_URL', 'http://127.0.0.1:8090');

$config                       = array();
$config['base_site_url']      = 'http://im.xarkx.com';
$config['shop_site_url']      = $config['base_site_url'] . '/shop';
$config['cms_site_url']       = $config['base_site_url'] . '/cms';
$config['microshop_site_url'] = $config['base_site_url'] . '/microshop';
$config['circle_site_url']    = $config['base_site_url'] . '/circle';
$config['admin_site_url']     = $config['base_site_url'] . '/admin';
$config['mobile_site_url']    = $config['base_site_url'] . '/mobile';

$config['wap_site_url']        = $config['base_site_url'] . '/wap';
$config['wap_circle_site_url'] = $config['base_site_url'] . '/wap_circle';
$config['wap_shop_site_url']   = $config['base_site_url'] . '/wap_shop';

$config['chat_site_url']        = $config['base_site_url'] . '/chat';
$config['node_site_url']        = $config['base_site_url'] . ':8090';
$config['upload_site_url']      = $config['base_site_url'] . '/data/upload';
$config['resource_site_url']    = $config['base_site_url'] . '/data/resource';
$config['version']              = '201502020388';
$config['setup_date']           = '2015-07-30 10:18:23';
$config['gip']                  = 0;
$config['dbdriver']             = 'mysqli';
$config['tablepre']             = 'az_';
$config['db']['1']['dbhost']    = '127.0.0.1';
$config['db']['1']['dbport']    = '3306';
$config['db']['1']['dbuser']    = 'root';
$config['db']['1']['dbpwd']     = 'root';
$config['db']['1']['dbname']    = 'im';
$config['db']['1']['dbcharset'] = 'UTF-8';
$config['db']['slave']          = $config['db']['master'];
$config['session_expire']       = 3600;
$config['lang_type']            = 'zh_cn';
$config['cookie_pre']           = 'E0E1_';
$config['thumb']['cut_type']    = 'gd';
$config['thumb']['impath']      = '';
$config['cache']['type']        = 'file';
$config['debug']                = false;
$config['default_store_id']     = '1';
//如果开始伪静态，这里设置为true
$config['url_model'] = false;
//如果店铺开启二级域名绑定的，这里填写主域名如baidu.com
$config['subdomain_suffix'] = '';
//$config['session_type'] = 'redis';
//$config['session_save_path'] = 'tcp://123.57.234.165:6379';
$config['node_chat'] = true;
//流量记录表数量，为1~10之间的数字，默认为3，数字设置完成后请不要轻易修改，否则可能造成流量统计功能数据错误
$config['flowstat_tablenum']   = 3;
$config['sms']['gwUrl']        = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService';
$config['sms']['serialNumber'] = '';
$config['sms']['password']     = '';
$config['sms']['sessionKey']   = '';
$config['sms']['plugin']       = 'yunpian';
$config['queue']['open']       = false;
$config['queue']['host']       = 'localhost';
$config['queue']['port']       = 6379;
$config['cache_open']          = false;
$config['delivery_site_url']   = $config['base_site_url'] . '/delivery';
return $config;
