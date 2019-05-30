<?php
/**
 * 前台分类
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');
class PureControl extends BaseHomeControl {
	public function __construct() {
	    parent::__construct();
	    Language::read('active');
	}
}
class categoryControl extends BaseHomeControl {
	/**
	 * 分类列表
	 */
	public function indexOp(){
		//导航
		$nav_link = array(
			'0'=>array('title'=>$lang['homepage'],'link'=>WAP_SHOP_SITE_URL),
			'1'=>array('title'=>$lang['category_index_goods_class'])
		);
		$model_class = Model('goods_class');
        $goods_class = $model_class->get_all_category();
        $output['show_goods_class'] = $goods_class;
        $tpl_file = BASE_PATH.'/templates/'.TPL_NAME.DS.'home'.DS.'category'.'.php';
        include($tpl_file);
	}
}
