<?php
/**
 * 商品图片统一调用函数//zmr>v30
 *
 *
 *
 * @package    function* www.abc.com 专业团队 提供售后服务
 */

defined('InShopNC') or exit('Access Invalid!');

/**
 * 取得商品缩略图的完整URL路径，接收商品信息数组，返回所需的商品缩略图的完整URL
 *
 * @param array $goods 商品信息数组
 * @param string $type 缩略图类型  值为60,240,360,1280
 * @return string
 */
function thumb($goods = array(), $type = ''){
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($goods)){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    if (array_key_exists('apic_cover', $goods)) {
        $goods['goods_image'] = $goods['apic_cover'];
    }
    if (empty($goods['goods_image'])) {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$goods['goods_image']);
    $fname = basename($file);
    //取店铺ID
    if (preg_match('/^(\d+_)/',$fname)){
        $store_id = substr($fname,0,strpos($fname,'_'));
    }else{
        $store_id = $goods['store_id'];
    }
    $file = $type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file);
    if((''==UPLOAD_SITE_URL or strpos(C('base_site_url'), UPLOAD_SITE_URL) === 0) and !is_file(BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file) or @file_get_contents(UPLOAD_SITE_URL.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file) === false){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL.'/'.ATTACH_GOODS;
    return $thumb_host.'/'.$store_id.'/'.$file;

}
/**
 * 取得商品缩略图的完整URL路径，接收图片名称与店铺ID
 *
 * @param string $file 图片名称
 * @param string $type 缩略图尺寸类型，值为60,240,360,1280
 * @param mixed $store_id 店铺ID 如果传入，则返回图片完整URL,如果为假，返回系统默认图
 * @return string
 */
function cthumb($file, $type = '', $store_id = false) {
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($file)) {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage ( $type );
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$file);
    $fname = basename($file);
    // 取店铺ID
    if ($store_id === false || !is_numeric($store_id)) {
        $store_id = substr ( $fname, 0, strpos ( $fname, '_' ) );
    }

    if((''==UPLOAD_SITE_URL or strpos(C('base_site_url'), UPLOAD_SITE_URL) === 0) and !is_file(BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file) or @file_get_contents(UPLOAD_SITE_URL.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file) === false){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
    return $thumb_host . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
}
/**
 * 商品二维码
 * @param array $goods_info
 * @return string
 */
function goodsQRCode($goods_info) {
    if (!file_exists(BASE_UPLOAD_PATH. '/' . ATTACH_STORE . '/' . $goods_info['store_id'] . '/' . $goods_info['goods_id'] . '.png' )) {
        return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.'default_qrcode.png';
    }
    return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$goods_info['store_id'].DS.$goods_info['goods_id'].'.png';
}

//zmr>>>
/**
 * 商品二维码
 * @param array $goods_info
 * @return string
 */
function storeQRCode($store_id) {
    if (!file_exists(BASE_UPLOAD_PATH. '/' . ATTACH_STORE . '/' . $store_id . '/' . $store_id . '_store.png' )) {
        return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.'default_qrcode.png';
    }
    return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$store_id.DS.$store_id.'_store.png';
}
//zmr<<<

/**
 * 取得抢购缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small,mid,max
 * @return string
 */
function gthumb($image_name = '', $type = ''){
	if (!in_array($type, array('small','mid','max'))) $type = 'small';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
    list($base_name, $ext) = explode('.', $image_name);
    list($store_id) = explode('_', $base_name);
    $file_path = ATTACH_GROUPBUY.DS.$store_id.DS.$base_name.'_'.$type.'.'.$ext;
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得买家缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为240,1024
 * @return string
 */
function snsThumb($image_name = '', $type = ''){
	if (!in_array($type, array('240','1024'))) $type = '240';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }

    list($member_id) = explode('_', $image_name);
    $file_path = ATTACH_MALBUM.DS.$member_id.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得限时折扣缩略图的完整URL路径
 * @param string $imgurl 商品图片地图
 * @param string $type 图片类型  值为120,240,360,1024
 * @return string
 */
function XianShiThumb($file, $type = '', $store_id = false){
    $type_array = explode(',_', ltrim(GOODS_IMAGES_EXT, '_'));
    if (!in_array($type, $type_array)) {
        $type = '240';
    }
    if (empty($file)) {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage ( $type );
    }
    $search_array = explode(',', GOODS_IMAGES_EXT);
    $file = str_ireplace($search_array,'',$file);
    $fname = basename($file);
    // 取店铺ID
    if ($store_id === false || !is_numeric($store_id)) {
        $store_id = substr ( $fname, 0, strpos ( $fname, '_' ) );
    }

    if((''==UPLOAD_SITE_URL or strpos(C('base_site_url'), UPLOAD_SITE_URL) === 0) and !is_file(BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$file)){
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage($type);
    }
    $thumb_host = UPLOAD_SITE_URL . '/' . ATTACH_GOODS;
    return $thumb_host . '/' . $store_id . '/' . ($type == '' ? $file : str_ireplace('.', '_' . $type . '.', $file));
}
/**
 * 取得积分商品缩略图的完整URL路径
 *
 * @param string $imgurl 商品名称
 * @param string $type 缩略图类型  值为small
 * @return string
 */
function pointprodThumb($image_name = '', $type = ''){
	if (!in_array($type, array('small','mid'))) $type = '';
	if (empty($image_name)){
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }

    if($type) {
        $file_path = ATTACH_POINTPROD.DS.str_ireplace('.', '_'.$type.'.', $image_name);
    } else {
        $file_path = ATTACH_POINTPROD.DS.$image_name;
    }
    if(!file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
		return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
	}
	return UPLOAD_SITE_URL.DS.$file_path;
}

/**
 * 取得品牌图片
 *
 * @param string $image_name
 * @return string
 */
function brandImage($image_name = '') {
    if ($image_name != '') {
        return UPLOAD_SITE_URL.'/'.ATTACH_BRAND.'/'.$image_name;
    }
    return UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/default_brand_image.gif';
}

/**
* 取得订单状态文字输出形式
*
* @param array $order_info 订单数组
* @return string $order_state 描述输出
*/
function orderState($order_info) {
    switch ($order_info['order_state']) {
        case ORDER_STATE_CANCEL:
            $order_state = L('order_state_cancel');
        break;
        case ORDER_STATE_NEW:
            $order_state = L('order_state_new');
        break;
        case ORDER_STATE_PAY:
            $order_state = L('order_state_pay');
        break;
        case ORDER_STATE_SEND:
            $order_state = L('order_state_send');
        break;
        case ORDER_STATE_SUCCESS:
            $order_state = L('order_state_success');
        break;
    }
    return $order_state;
}

/**
 * 取得订单支付类型文字输出形式
 *
 * @param array $payment_code
 * @return string
 */
function orderPaymentName($payment_code) {
    return str_replace(
            array('offline','online','alipay','tenpay','chinabank','predeposit'),
            array('货到付款','在线付款','支付宝','财付通','网银在线','站内余额支付'),
            $payment_code);
}

/**
 * 取得订单商品销售类型文字输出形式
 *
 * @param array $goods_type
 * @return string 描述输出
 */
function orderGoodsType($goods_type) {
    return str_replace(
            array('1','2','3','4','5'),
            array('','抢购','限时折扣','优惠套装','赠品'),
            $goods_type);
}

/**
 * 取得结算文字输出形式
 *
 * @param array $bill_state
 * @return string 描述输出
 */
function billState($bill_state) {
    return str_replace(
            array('1','2','3','4'),
            array('已出账','商家已确认','平台已审核','结算完成'),
            $bill_state);
}



function urlShopWap($act = '', $op = '', $args = array(), $store_domain = ''){

    // 如何使自营店则返回javascript:;

//    if ($act == 'show_store' && $op != 'goods_all') {
//        static $ownShopIds = null;
//        if ($ownShopIds === null) {
//            $ownShopIds = Model('store')->getOwnShopIds();
//        }
//        if (isset($args['store_id']) && in_array($args['store_id'], $ownShopIds)) {
//            return 'javascript:;';
//        }
//    }

    // 开启店铺二级域名
    if (intval(C('enabled_subdomain')) == 1 && !empty($store_domain)){
        return 'http://'.$store_domain.'.'.SUBDOMAIN_SUFFIX.'/';
    }

    // 默认标志为不开启伪静态
    $rewrite_flag = false;

    // 如果平台开启伪静态开关，并且为伪静态模块，修改标志为开启伪静态
    $rewrite_item = array(
        'category:index',
        'goods:index',
        'goods:comments_list',
        'search:index',
        'show_store:index',
        'show_store:goods_all',
        'article:show',
        'article:article',
        'document:index',
        'brand:list',
        'brand:index',
        'show_groupbuy:index',
        'show_groupbuy:groupbuy_detail',
        'show_groupbuy:groupbuy_list',
        'show_groupbuy:groupbuy_soon',
        'show_groupbuy:groupbuy_history',
        'show_groupbuy:vr_groupbuy_list',
        'show_groupbuy:vr_groupbuy_soon',
        'show_groupbuy:vr_groupbuy_history',
        'pointshop:index',
        'pointvoucher:index',
        'pointprod:pinfo',
        'pointprod:plist',
        'pointgrade:index',
        'pointgrade:exppointlog',
        'store_snshome:index',
    );
    if(URL_MODEL && in_array($act.':'.$op, $rewrite_item)) {
        $rewrite_flag = true;
        $tpl_args = array();        // url参数临时数组
        switch ($act.':'.$op) {
            case 'search:index':
                if (!empty($args['keyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['cate_id'] = empty($args['cate_id']) ? 0 : $args['cate_id'];
                $tpl_args['b_id'] = empty($args['b_id']) || intval($args['b_id']) == 0 ? 0 : $args['b_id'];
                $tpl_args['a_id'] = empty($args['a_id']) || intval($args['a_id']) == 0 ? 0 : $args['a_id'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['gift'] = empty($args['gift']) ? 0 : $args['gift'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'show_store:goods_all':
                if (isset($args['inkeyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['store_id'] = empty($args['store_id']) ? 0 : $args['store_id'];
                $tpl_args['stc_id'] = empty($args['stc_id']) ? 0 : $args['stc_id'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'brand:list':
                $tpl_args['brand'] = empty($args['brand']) ? 0 : $args['brand'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['gift'] = empty($args['gift']) ? 0 : $args['gift'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'show_groupbuy:index':
            case 'show_groupbuy:groupbuy_detail':
                break;

            case 'show_groupbuy:groupbuy_list':
            case 'show_groupbuy:groupbuy_soon':
            case 'show_groupbuy:groupbuy_history':
                $tpl_args['class'] = empty($args['class']) ? 0 : $args['class'];
                $tpl_args['s_class'] = empty($args['s_class']) ? 0 : $args['s_class'];
                $tpl_args['groupbuy_price'] = empty($args['groupbuy_price']) ? 0 : $args['groupbuy_price'];
                $tpl_args['groupbuy_order_key'] = empty($args['groupbuy_order_key']) ? 0 : $args['groupbuy_order_key'];
                $tpl_args['groupbuy_order'] = empty($args['groupbuy_order']) ? 0 : $args['groupbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'show_groupbuy:vr_groupbuy_list':
            case 'show_groupbuy:vr_groupbuy_soon':
            case 'show_groupbuy:vr_groupbuy_history':
                $tpl_args['vr_class'] = empty($args['vr_class']) ? 0 : $args['vr_class'];
                $tpl_args['vr_s_class'] = empty($args['vr_s_class']) ? 0 : $args['vr_s_class'];
                $tpl_args['vr_area'] = empty($args['vr_area']) ? 0 : $args['vr_area'];
                $tpl_args['vr_mall'] = empty($args['vr_mall']) ? 0 : $args['vr_mall'];
                $tpl_args['groupbuy_price'] = empty($args['groupbuy_price']) ? 0 : $args['groupbuy_price'];
                $tpl_args['groupbuy_order_key'] = empty($args['groupbuy_order_key']) ? 0 : $args['groupbuy_order_key'];
                $tpl_args['groupbuy_order'] = empty($args['groupbuy_order']) ? 0 : $args['groupbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'goods:comments_list':
                $tpl_args['goods_id'] = empty($args['goods_id']) ? 0 : $args['goods_id'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'pointgrade:exppointlog':
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            default:
                break;
        }
    }

    return url($act, $op, $args, $rewrite_flag, WAP_SHOP_SITE_URL);
}
?>
