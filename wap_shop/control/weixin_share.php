<?php 

class weixin_shareControl extends  BaseGoodsControl{
    public function __construct() {
        parent::__construct ();
        Language::read('store_goods_index');
    }
    public function indexOp() {
        if($_GET['goods_id']) {
            if($_SERVER["HTTP_REFERER"]==SPREAD_SITE_URL) {
                $this->show_goods($_GET['goods_id']);
            } else {
                if($_GET['code']) {
                    setNcCookie('code',$_GET['code'],365*24*3600);
                }
                redirect(BASE_SITE_URL.'/wap_shop/index.php?act=goods&op=index&goods_id='.$_GET['goods_id']);
            }
        }
    }

    private function show_goods($goods_id) {
        $goods_id = intval($goods_id);
            
        //得到商品咨询信息
        $model_consult = Model('consult');
        $where = array();
        $where['goods_id'] = $goods_id;
        if (intval($_GET['ctid']) > 0) {
            $where['ct_id'] = intval($_GET['ct_id']);
        }
        $consult_list = $model_consult->getConsultList($where,'*','10');
        Tpl::output('consult_list',$consult_list);

        //销售记录
        if ($_GET['vr']) {
            $model_order = Model('vr_order');
            $sales = $model_order->getOrderAndOrderGoodsSalesRecordList(array('goods_id'=>$goods_id), '*', 10);
        } else {
            $model_order = Model('order');
            $sales = $model_order->getOrderAndOrderGoodsSalesRecordList(array('order_goods.goods_id'=>$goods_id), 'order_goods.*, order.buyer_name, order.add_time', 10);
        }
        Tpl::output('show_page',$model_order->showpage());
        Tpl::output('sales',$sales);

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getWapGoodsDetail($goods_id);
        $goods_info = $goods_detail['goods_info'];
//         print_r($goods_detail);exit;
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        
        // by abc.com
        $rs = $model_goods->getGoodsList(array('goods_commonid'=>$goods_info['goods_commonid']));
        $count = 0;
        foreach($rs as $v){
            $count += $v['goods_salenum'];
        }
        $goods_info['goods_salenum'] = $count;
        //  添加 end
        $this->getStoreInfo($goods_info['store_id']);
        Tpl::output('goods_id', $goods_id);
        Tpl::output('spec_list', $goods_detail['spec_list']);
        Tpl::output('spec_image', $goods_detail['spec_image']);
        Tpl::output('goods_image', $goods_detail['goods_image']);
        Tpl::output('mansong_info', $goods_detail['mansong_info']);
        Tpl::output('gift_array', $goods_detail['gift_array']);

        // 生成缓存的键值
        $hash_key = $goods_info['goods_id'];
        $_cache = rcache($hash_key, 'product');
        if (empty($_cache)) {
            // 查询SNS中该商品的信息
            $snsgoodsinfo = Model('sns_goods')->getSNSGoodsInfo(array('snsgoods_goodsid' => $goods_info['goods_id']), 'snsgoods_likenum,snsgoods_sharenum');
            $data = array();
            $data['likenum'] = $snsgoodsinfo['snsgoods_likenum'];
            $data['sharenum'] = $snsgoodsinfo['snsgoods_sharenum'];
            // 缓存商品信息
            wcache($hash_key, $data, 'product');
        }
        $goods_info = array_merge($goods_info, $_cache);
                
        $inform_switch = true;
        // 检测商品是否下架,检查是否为店主本人
        if ($goods_info['goods_state'] != 1 || $goods_info['goods_verify'] != 1 || $goods_info['store_id'] == $_SESSION['store_id']) {
            $inform_switch = false;
        }
        Tpl::output('inform_switch',$inform_switch );

        // 如果使用运费模板
        if ($goods_info['transport_id'] > 0) {
            // 取得三种运送方式默认运费
            $model_transport = Model('transport');
            $transport = $model_transport->getExtendList(array('transport_id' => $goods_info['transport_id'], 'is_default' => 1));
            if (!empty($transport) && is_array($transport)) {
                foreach ($transport as $v) {
                    $goods_info[$v['type'] . "_price"] = $v['sprice'];
                }
            }
        }
        $goods_name=Model()->table('goods_common')->where(array('goods_commonid'=>$goods_info["goods_commonid"]))->field('goods_name')->find();
        $goods_info["goods_name"]=$goods_name["goods_name"];

        Tpl::output('goods', $goods_info);

        $model_plate = Model('store_plate');
        // 顶部关联版式
        if ($goods_info['plateid_top'] > 0) {
            $plate_top = $model_plate->getStorePlateInfoByID($goods_info['plateid_top']);
            Tpl::output('plate_top', $plate_top);
        }
        // 底部关联版式
        if ($goods_info['plateid_bottom'] > 0) {
            $plate_bottom = $model_plate->getStorePlateInfoByID($goods_info['plateid_bottom']);
            Tpl::output('plate_bottom', $plate_bottom);
        }

        Tpl::output('store_id', $goods_info ['store_id']);

        // 输出一级地区
        $area_list = Model('area')->getTopLevelAreas();

        if (strtoupper(CHARSET) == 'GBK') {
            $area_list = Language::getGBK($area_list);
        }
        Tpl::output('area_list', $area_list);
        
        $model_consult = Model('consult');
        $consult_count =  $model_consult->getConsultCount(array());
        Tpl::output('consult_count', $consult_count);
        //优先得到推荐商品
        //$goods_commend_list = $model_goods->getGoodsOnlineList(array('store_id' => $goods_info['store_id'], 'goods_commend' => 1), 'goods_id,goods_name,goods_jingle,goods_image,store_id,goods_price', 0, 'rand()', 5, 'goods_commonid');
        //Tpl::output('goods_commend',$goods_commend_list);

        $member_info    = array();
        $member_model = Model('member');
        if(!empty($_SESSION['member_id'])) $member_info = $member_model->getMemberInfoByID($_SESSION['member_id'],'is_allowtalk');
        Tpl::output('member_info', $member_info);
        // 当前位置导航
        $nav_link_list = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name']);
        Tpl::output('nav_link_list', $nav_link_list);

        //评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);
        if(!empty($GLOBALS["setting_config"]["weixin_appid"])
            && !empty($GLOBALS["setting_config"]["weixin_appsecret"])
        ) {
            $wxjs = new JSSDK(
                $GLOBALS["setting_config"]["weixin_appid"],
                $GLOBALS["setting_config"]["weixin_appsecret"]
            );
            Tpl::output('signPackage', $wxjs->getSignPackage());
        }
        $this->_get_comments($goods_id, 0, 1);
        $seo_param = array();
        $seo_param['name'] = $goods_info['goods_name'];
        $seo_param['key'] = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();
        $show_spread = $this->is_first();
        Tpl::output('is_share', 1);
        Tpl::output('show_spread', $show_spread);
        Tpl::output('title', $goods_info['goods_name']);
        Tpl::output('code', $_GET['code']);
        //------------------------
        Tpl::showpage('goods');
    }
    private function _get_comments($goods_id, $type, $page) {
        $condition = array();
        $condition['geval_goodsid'] = $goods_id;
        switch ($type) {
            case '1':
                $condition['geval_scores'] = array('in', '5,4');
                Tpl::output('type', '1');
                break;
            case '2':
                $condition['geval_scores'] = array('in', '3,2');
                Tpl::output('type', '2');
                break;
            case '3':
                $condition['geval_scores'] = array('in', '1');
                Tpl::output('type', '3');
                break;
                case '0':
                //$condition['geval_scores'] = array('in', '1,2,3,4,5');
                Tpl::output('type', '0');
                break;
                
        }
        //查询商品评分信息
        $model_evaluate_goods = Model("evaluate_goods");
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, $page);
        Tpl::output('goodsevallist',$goodsevallist);

        Tpl::output('show_page',$model_evaluate_goods->showpage('5'));
    }
    private function is_first() {
        $spreaded = cookie('spreaded');
        if(!empty($spreaded)) {
            return false;
        } else {
            setNcCookie('spreaded', 1, 3600*24*365);
            return true;
        }
    }
}