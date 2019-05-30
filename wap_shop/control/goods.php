<?php
/**
 * 前台商品
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class goodsControl extends BaseGoodsControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('store_goods_index');
    }

    /** 新增客服聊天窗**/
    public function load_serviceOp()
    {
        Tpl::showpage('customer_service', 'null_layout');
    }
    /********商品详情*******/
    public function azOP()
    {
        $goods_id = intval($_GET['goods_id']);

        // 商品详细信息
        $model_goods  = Model('goods');
        $goods_detail = $model_goods->getWapGoodsDetail($goods_id);
        $goods_info   = $goods_detail['goods_info'];
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        // by abc.com
        $rs    = $model_goods->getGoodsList(array('goods_commonid' => $goods_info['goods_commonid']));
        $count = 0;
        foreach ($rs as $v) {
            $count += $v['goods_salenum'];
        }
        $goods_info['goods_salenum'] = $count;
        //  添加 end
        $this->getStoreInfo($goods_info['store_id']);
        Tpl::output('goods_id', $goods_id);
        Tpl::output('spec_list', $goods_detail['spec_list']); //echo "<pre>"; print_r($goods_info);echo "</pre>";
        Tpl::output('spec_image', $goods_detail['spec_image']);
        Tpl::output('goods_image', $goods_detail['goods_image']);
        Tpl::output('mansong_info', $goods_detail['mansong_info']);
        Tpl::output('gift_array', $goods_detail['gift_array']);

        Tpl::showpage('az');
    }

    /**
     * 单个商品信息页
     */
    public function indexOp()
    {
        $goods_id = intval($_GET['goods_id']);

        //得到商品咨询信息
        $model_consult     = Model('consult');
        $where             = array();
        $where['goods_id'] = $goods_id;
        if (intval($_GET['ctid']) > 0) {
            $where['ct_id'] = intval($_GET['ct_id']);
        }
        $consult_list = $model_consult->getConsultList($where, '*', '10');
        Tpl::output('consult_list', $consult_list);

        //销售记录
        if ($_GET['vr']) {
            $model_order = Model('vr_order');
            $sales       = $model_order->getOrderAndOrderGoodsSalesRecordList(array('goods_id' => $goods_id), '*', 10);
        } else {
            $model_order = Model('order');
            $sales       = $model_order->getOrderAndOrderGoodsSalesRecordList(array('order_goods.goods_id' => $goods_id), 'order_goods.*, order.buyer_name, order.add_time', 10);
        }
        Tpl::output('show_page', $model_order->showpage());
        Tpl::output('sales', $sales);

        // 商品详细信息
        $model_goods  = Model('goods');
        $goods_detail = $model_goods->getWapGoodsDetail($goods_id); //goods表数据
        $goods_info   = $goods_detail['goods_info'];
//         print_r($goods_info);exit;
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }

        /*   // by abc.com  商品规格 总销量
        $rs    = $model_goods->getGoodsList(array('goods_commonid' => $goods_info['goods_commonid']));
        $count = 0;
        foreach ($rs as $v) {
        $count += $v['goods_salenum'];
        }
        //      $goods_info['goods_salenum'] = $model_goods->fake_sale_num($count);//销量假显示
        $goods_info['goods_salenum'] = $count;*/

        $this->getStoreInfo($goods_info['store_id']);
        Tpl::output('goods_id', $goods_id);
        Tpl::output('spec_list', $goods_detail['spec_list']);
        Tpl::output('spec_image', $goods_detail['spec_image']);
        Tpl::output('goods_image', $goods_detail['goods_image']);
        Tpl::output('mansong_info', $goods_detail['mansong_info']);
        Tpl::output('gift_array', $goods_detail['gift_array']);

        // 生成缓存的键值
        $hash_key = $goods_info['goods_id'];
        $_cache   = rcache($hash_key, 'product');
        if (empty($_cache)) {
            // 查询SNS中该商品的信息
            $snsgoodsinfo     = Model('sns_goods')->getSNSGoodsInfo(array('snsgoods_goodsid' => $goods_info['goods_id']), 'snsgoods_likenum,snsgoods_sharenum');
            $data             = array();
            $data['likenum']  = $snsgoodsinfo['snsgoods_likenum'];
            $data['sharenum'] = $snsgoodsinfo['snsgoods_sharenum'];
            // 缓存商品信息
            wcache($hash_key, $data, 'product');
        }
        $goods_info = array_merge($goods_info, $_cache);

        $inform_switch = true;
        // 检测商品是否下架,检查是否为店主本人
        if (1 != $goods_info['goods_state'] || 1 != $goods_info['goods_verify'] || $goods_info['store_id'] == $_SESSION['store_id']) {
            $inform_switch = false;
        }
        Tpl::output('inform_switch', $inform_switch);

        // 如果使用运费模板
        if ($goods_info['transport_id'] > 0) {
            // 取得三种运送方式默认运费
            $model_transport = Model('transport');
            $transport       = $model_transport->getExtendList(array('transport_id' => $goods_info['transport_id'], 'is_default' => 1));
            if (!empty($transport) && is_array($transport)) {
                foreach ($transport as $v) {
                    $goods_info[$v['type'] . "_price"] = $v['sprice'];
                }
            }
        }
        $goods_name               = Model()->table('goods_common')->where(array('goods_commonid' => $goods_info["goods_commonid"]))->field('goods_name')->find();
        $goods_info["goods_name"] = $goods_name["goods_name"];

        //处理车票数据
        if ($goods_info['calendar_type'] == 4 && isset($_GET['date'])) {
            $station_data               = Model()->table('cart_operate_date')->field('storage,date')->where("goods_id = {$goods_info['goods_id']} AND date = '{$_GET['date']}'")->find();
            $goods_info['date_storage'] = $station_data['storage'];
            $goods_info['get_date']     = $station_data['date'];
        }
        //add by xuyq
        $store = Model('store');
        $kefu  = $store->where(array('store_id' => $goods_info['store_id']))->find();
        $kefu  = unserialize($kefu['store_presales']);
        $kefu  = $this->my_sort($kefu, 'priority', SORT_DESC, SORT_NUMERIC);
        foreach ($kefu as $key => $value) {
            $cate_id = explode('_', $value['cate_id']);
            if (in_array($goods_info['gc_id'], $cate_id)) {
                $goods_info['kefu_id']   = $value['num'];
                $goods_info['kefu_time'] = $value['time'];
                $kefu_time               = explode('-', $value['time']);
                $start_time              = explode(':', $kefu_time[0]);
                $start_time              = $start_time[0];
                $end_time                = explode(':', $kefu_time[1]);
                $end_time                = $end_time[0];
                $now_time                = date('H', time());
                $goods_info['flag']      = 0;
                if ($now_time >= $start_time && $now_time <= $end_time) {
                    $goods_info['flag'] = 1;
                }
                break;
            }
        }
        //end adds
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

        Tpl::output('store_id', $goods_info['store_id']);
        // 输出一级地区
        $area_list = Model('area')->getTopLevelAreas();

        if (strtoupper(CHARSET) == 'GBK') {
            $area_list = Language::getGBK($area_list);
        }
        Tpl::output('area_list', $area_list);

        $model_consult = Model('consult');
        $consult_count = $model_consult->getConsultCount(array());
        Tpl::output('consult_count', $consult_count);
        //优先得到推荐商品
        //$goods_commend_list = $model_goods->getGoodsOnlineList(array('store_id' => $goods_info['store_id'], 'goods_commend' => 1), 'goods_id,goods_name,goods_jingle,goods_image,store_id,goods_price', 0, 'rand()', 5, 'goods_commonid');
        //Tpl::output('goods_commend',$goods_commend_list);

        $member_info  = array();
        $member_model = Model('member');
        if (!empty($_SESSION['member_id'])) {
            $member_info = $member_model->getMemberInfoByID($_SESSION['member_id'], 'is_allowtalk');
        }

        Tpl::output('member_info', $member_info);
        // 当前位置导航
        $nav_link_list   = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name']);
        Tpl::output('nav_link_list', $nav_link_list);

        //评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);

        $this->_get_comments($goods_id, 0, 1);
        $seo_param                = array();
        $seo_param['name']        = $goods_info['goods_name'];
        $seo_param['key']         = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();

        //收藏
        $favorites_model = Model('favorites');
        //判断是否已经收藏
        $favorites_info = $favorites_model->getOneFavorites(array('fav_id' => $goods_id, 'fav_type' => 'goods', 'member_id' => "{$_SESSION['member_id']}"));
        Tpl::output('favorites_info', $favorites_info);

        if ("act_goods" == $_GET['type']) {
            $active_state = Model()->table('active')->field('az_active_state,az_active_addtime')->where(array('goods_id' => $goods_id, 'member_id' => $_SESSION['member_id']))->find();
            Tpl::output('active_state', $active_state);
            Tpl::showpage('act_goods');
        } elseif ("appaly_goods" == $_GET['type']) {
            $con          = 'active.type=0 and active.goods_id=goods.goods_id and member_id=' . $_SESSION['member_id'] . ' and active.goods_id=' . $_GET['goods_id'];
            $field        = "active.goods_id,active.member_id,active.az_active_state";
            $appaly_list  = Model()->table('active,goods')->field($field)->where($con)->find();
            $appaly_list1 = Model()->table('active,goods')->field($field)->where('active.type=0 and active.goods_id=goods.goods_id and member_id=' . $_SESSION['member_id'] . ' and goods_id=' . $goods_id)->find();
            Tpl::output('appaly_list', $appaly_list);
            Tpl::output('appaly_list1', $appaly_list1);
            $con         = "active.type=0 and active.goods_id=goods.goods_id and member_id=" . $_SESSION['member_id'];
            $field       = "active.goods_id,active.member_id,active.az_active_state";
            $active_list = Model()->table('active,goods')->field($field)->where($con)->select();
            Tpl::output('active_list', $active_list);
            Tpl::showpage('appaly_goods');
        } else {
            Tpl::showpage('goods');
        }
    }
    //二维数组排序
    public function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
    {
        if (is_array($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }

    //ajax 验证车票数据
    public function get_cart_ticketOp()
    {
        if ($data = Model()->table('cart_operate_date')->field('date,storage')->where("goods_id = " . intval($_POST['goods_id']) . " AND date = '{$_POST['dates']}'")->find()) {
            echo json_encode(array('respond' => '1', 'date_storage' => $data['storage'], 'get_date' => $data['date']), true);
        } else {
            echo json_encode(array('respond' => '0', 'msg' => '没有找到' . $_POST['dates'] . '相关车次'), true);
        }
    }

    public function get_spec_priceOp($goods_commonid = '')
    {
        $commonid         = isset($_POST['goods_commonid']) ? $_POST['goods_commonid'] : $goods_commonid;
        $s_date           = isset($_POST['hotel_in_date']) ? $_POST['hotel_in_date'] : date("Y-m-d", TIMESTAMP);
        $e_date           = isset($_POST['hotel_out_date']) ? $_POST['hotel_out_date'] : date("Y-m-d", (TIMESTAMP + 60 * 60 * 24));
        $hotel_in_date    = date("Y-m", strtotime($s_date));
        $hotel_out_date   = date("Y-m", strtotime($e_date));
        $hotel_stock_data = Model()->table('stock')->where(array('commonid' => $commonid, 'package' => array('gt', 0), 'date' => array('egt', $hotel_in_date), 'date' => array('elt', $hotel_out_date)))->field('stock_info,date,package')->order('stock_id', ASC)->select();

        if (!empty($hotel_stock_data)) {
            $spec_arr = array();
            foreach ($hotel_stock_data as $a => $b) {
                $stock[$b['package']]              = json_decode($b['stock_info'], true); //库存信息
                $stock[$b['package']]['pack_name'] = $b['package'];
            }
            foreach ($stock as $key => $value) {
                foreach ($value as $v) {
                    if ($v['date'] == trim($e_date) && ($v['man_stock'] > 0)) {
                        $spec_arr[$key]['man_stock'] = $v['man_stock'];
                        $spec_arr[$key]['man_price'] = $v['man_price'];
                        $spec_arr[$key]['package']   = $key;
                        $spec_arr[$key]['date']      = $v['date'];
                    }
                }
            }
        }
        Tpl::output('spec_arr', $spec_arr);
        Tpl::showpage("spec_info", "null_layout");
    }

    /**
     * 判断是否已收藏
     */
    public function member_favOp()
    {
        if (isset($_SESSION['member_id'])) {
            $goods_id        = $_GET["goods_id"];
            $favorites_model = Model('favorites');
            $fav_where       = array('fav_id' => "$goods_id", 'fav_type' => 'goods', 'member_id' => "{$_SESSION['member_id']}");
            $fav_count       = $favorites_model->where($fav_where)->count();
            echo $fav_count;
            Tpl::output('is_fav', $fav_count);
        }
    }

    /**
     * 判断是否已经购买过
     */
    public function isbuyOp()
    {
        if (isset($_SESSION['member_id'])) {
            $goods_id    = $_GET['goods_id'];
            $isbuy_model = Model('order');
            $condition   = array('buyer_id' => $_SESSION['member_id'], 'goods_id' => $goods_id);
            $is_buy      = $isbuy_model->getBuy($condition);
            if (is_array($is_buy) && !empty($is_buy)) {
                echo json_encode(array('is_buy' => true, 'msg' => Language::get('az_is_buy')));
            } else {
                echo json_encode(array('is_buy' => false, 'msg' => Language::get('az_is_buy')));
            }
        }
    }

    /**
     * 选择日期及套餐后载入日历库存数据
     * 线路、门票GET参数：type_id(商品大类),commonid(商品主ID),package(套餐),date(格式2015-10)
     * 高尔夫GET参数：type_id(商品大类),commonid(商品主ID),date(格式2015-10)
     */
    public function stock_infoOp()
    {
        if (!empty($_GET["type_id"])) {

            $commonid = $_GET['commonid'];
            $type_id  = $_GET["type_id"];
            $package  = $_GET["package"]; //套餐要转码，否则有运算符时输出为空

            $date = $_GET["date"];
            if ('40' == $type_id || '41' == $type_id || '42' == $type_id || '43' == $type_id) {

                //线路类型
                //没有库存，自动插入本月库存
                $date        = substr($date, 0, 7);
                $where_stock = array('commonid' => $commonid, 'date' => $date, 'package' => $package);
                $goods_info  = '';

                $this->insert_stock_info($goods_info, $package, $commonid, $where_stock, $date, '100');

                $data = Model()->table('stock')->where(array('commonid' => $commonid, 'date' => $date, 'package' => $package))->field('stock_info')->find();
                //print_r($data);
                foreach (json_decode($data['stock_info'], true) as $days_key => $days) {
                    $current_time  = date('Y-m-d'); //date('Y-m-d',strtotime("+3 day"));//提前预订天数
                    $no_date_class = '';
                    $days_str      = $days_key . '日';
                    if ($days["date"] <= $current_time || '0' == $days["stock"]) {
                        $no_date_class = 'no_date_class';
                    }
                    if ($days["stock"] > 0 && $days["stock"] <= 5) {
                        $days_str = '库存余' . $days["stock"];
                    }
                    echo "<span class=\"ui-btn qz-padding-20 {$no_date_class}\" value='{$days['man_price']}|{$days['child_price']}' style=\"float:left; margin:5px;\" title=\"-{$days_key}\">$days_str</span>";
                }
                echo '<i></i>';

            } elseif ('46' == $type_id) {
                //高尔夫
                //没有库存，自动插入本月库存
                $date        = substr($date, 0, 7);
                $where_stock = array('commonid' => $commonid, 'date_month' => $date);
                $goods_info  = '';
                Model('goods')->insert_golf_stock_info($goods_info, $commonid, $where_stock, $date, 1);

                $where_days["commonid"]   = $commonid;
                $where_days["date_month"] = $date;
                $days_arr                 = Model()->table('golf')->where($where_days)->order('day asc')->select();

                //print_r($data);
                foreach ($days_arr as $days_key => $days) {
                    $current_time  = date('Y-m-d');
                    $no_date_class = '';
                    $days_str      = $days["day"] . '日';

                    $golf_date = date('Y-m-d', strtotime($days["date_month"] . '-' . $days["day"]));
                    if ($golf_date < $current_time) {
                        $no_date_class = 'no_date_class';
                    };
                    echo "<span class=\"ui-btn qz-padding-20 {$no_date_class}\" style=\"float:left; margin:5px;\"  title=\"-{$days["day"]}\">$days_str</span>";
                }
                echo '<i></i>';
            }
        }
    }
    /**
     * 小时段
     * 高尔夫，选择日期后列出所有小时段及分钟的场次（时间场次，有时间、价格、库存）详细
     * GET参数：$package(套餐),$date(日期：2015-10),$day(天:-1),$commonid(商品主表ID)
     */
    public function golf_hourOp()
    {
        //高尔夫
        if (!empty($_GET["day"])) {
            $package  = $_GET["package"];
            $commonid = $_GET["commonid"];
            // $site_id=$_GET["site_id"];//场地ID
            $day  = $_GET["day"];
            $date = substr($_GET["date"], 0, 7);

            $date_day = date('Y-m-d', strtotime($date . $day)); //

            $where_stock['commonid'] = $commonid;
            $where_stock['date']     = $date_day;
            $where_stock['stock']    = array(
                'gt',
                0,
            );
            $data = Model()->table('golf_stock')->where($where_stock)->field('stock')->find();

            $stock     = $data["stock"];
            $golf_site = unserialize($GLOBALS["setting_config"]["golf_site"]);
            // print_r($data);
            // 多表查询，使用query
            echo "<div class='qz-tcxz clearfix' nctyle='ul_sign' title='时间：'>";
            foreach ($golf_site as $v) {
                $no_date_class = '';
                $current_time  = date('Y-m-d H:s');

                $golf_date_minute = $v . ':00';
                $golf_date        = $date_day . " " . $golf_date_minute;
                // 如果场地时间小于当前时间和库存为0
                if (strtotime($golf_date) <= strtotime($current_time) || '0' == $stock) {
                    $no_date_class = 'no_date_class';
                }
                echo "<span class=\"ui-btn qz-padding-20 {$no_date_class}\" style=\"float:left; margin:5px;\"  title=\"{$v}\">{$v}点
            <input type='hidden' style='width:20px' name='tb_golf_hour' value=''/>
            </span>";
            }
            echo '<i></i>
            </div>';
        }
    }
    /**
     * 分钟段，10分钟1段
     * 高尔夫，选择日期后列出所有小时段及分钟的场次（时间场次，有时间、价格、库存）详细
     * GET参数：$package(套餐),$date(日期：2015-10),$day(天:-1),$commonid(商品主表ID),hour(小时段)
     */
    public function golf_minuteOp()
    {
        //高尔夫
        if (!empty($_GET["hour"])) {
            $package  = $_GET["package"];
            $commonid = $_GET["commonid"];
            // $site_id=$_GET["site_id"];//场地ID
            $day  = $_GET["day"];
            $hour = $_GET["hour"];
            $date = substr($_GET["date"], 0, 7);

            $date_day = date('Y-m-d', strtotime($date . $day)); //

            $where_stock['commonid'] = $commonid;
            $where_stock['date']     = $date_day;
            $where_stock['stock']    = array(
                'gt',
                0,
            );
            $data       = Model()->table('golf_stock')->field('stock_info')->find();
            $stock_info = unserialize($data["stock_info"]);
            print_r($stock_info);exit;

            $minute = unserialize($GLOBALS["setting_config"]["golf_minute"]);
            //print_r($minute);
            // 多表查询，使用query
            echo "<div class='qz-tcxz clearfix' nctyle='ul_sign' hour='{$hour}' title='点：'>
         <input type='hidden' id='tb_hour_info{$hour}' name='tb_hour_info'/>

         <div>{$hour}点：</div>";
            foreach ($minute as $v) {
                $no_date_class = '';
                $current_time  = date('Y-m-d H:s');
                $stock         = $stock_info[$hour][$v]["stock"];
                $price         = $stock_info[$hour][$v]["price"][$package];

                $golf_date_minute = $hour . ':' . $v;
                $golf_date        = $date_day . " " . $golf_date_minute;
                // 如果场地时间小于当前时间和库存为0
                if (strtotime($golf_date) <= strtotime($current_time) || '0' == $stock) {
                    $no_date_class = 'no_date_class';
                }

                echo "<span class=\"ui-btn qz-padding-20 {$no_date_class}\" style=\"float:left; margin:5px;\"title=\"{$v}\">{$v}分
            <input type='hidden' style='width:20px' name='tb_golf_minute' price='{$price}' />
            </span>";
            }
            echo '</div>';
        }
    }
    //商品评级
    public function consulting_list_pjOp()
    {
        $goods_id = intval($_GET['goods_id']);
        if ($goods_id <= 0) {
            showMessage(Language::get('wrong_argument'), '', 'html', 'error');
        }

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_info  = $model_goods->getGoodsInfoByID($goods_id, '*');
        // 验证商品是否存在
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        Tpl::output('goods', $goods_info);

        $this->getStoreInfo($goods_info['store_id']);

        // 当前位置导航
        $nav_link_list   = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name'], 'link' => urlShop('goods', 'index', array('goods_id' => $goods_id)));
        $nav_link_list[] = array('title' => '商品咨询');
        Tpl::output('nav_link_list', $nav_link_list);

        //得到商品咨询信息
        $model_consult     = Model('consult');
        $where             = array();
        $where['goods_id'] = $goods_id;
        if (intval($_GET['ctid']) > 0) {
            $where['ct_id'] = intval($_GET['ctid']);
        }
        $consult_list = $model_consult->getConsultList($where, '*', 0, 20);
        Tpl::output('consult_list', $consult_list);
        Tpl::output('show_page', $model_consult->showpage());

        // 咨询类型
        $consult_type = rkcache('consult_type', true);
        Tpl::output('consult_type', $consult_type);

        $seo_param                = array();
        $seo_param['name']        = $goods_info['goods_name'];
        $seo_param['key']         = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();

        Tpl::output('consult_able', $this->checkConsultAble($goods_info['store_id']));
        //Tpl::showpage('goods.consulting_list');
        Tpl::showpage('consulting_list_pj');
    }
    public function good_pj_listOp()
    {
        $order_id = intval($_GET['order_id']);
        //print_r($order_id);exit();
        $goods_id = intval($_GET["goods_id"]);
        if ($goods_id <= 0) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        $model_goods  = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        $goods_info   = $goods_detail['goods_info'];
        Tpl::output('html_title', "评价列表-" . $goods_info["goods_name"] . C('site_name'));
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);
        Tpl::output('goods_id', $goods_id);
        $condition['geval_goodsid']  = $goods_id;
        $condition1['geval_scores']  = array('in', '1'); //差
        $condition1['geval_goodsid'] = $goods_id;
        $condition2['geval_goodsid'] = $goods_id;
        $condition3['geval_goodsid'] = $goods_id;
        $condition2['geval_scores']  = array('in', '3,2'); //中
        $condition3['geval_scores']  = array('in', '5,4'); //好
        $model_evaluate_goods        = Model("evaluate_goods");
        $order                       = "geval_addtime DESC";
        $goodsevallist               = $model_evaluate_goods->field($field)->where($condition)->limit(0, 5)->order($order)->select();
        $goodsevallist1              = $model_evaluate_goods->field($field)->where($condition1)->limit(0, 5)->order($order)->select();
        $goodsevallist2              = $model_evaluate_goods->field($field)->where($condition2)->limit(0, 5)->order($order)->select();
        $goodsevallist3              = $model_evaluate_goods->field($field)->where($condition3)->limit(0, 5)->order($order)->select();

        Tpl::output('goodsevallist', $goodsevallist);
        Tpl::output('goodsevallist1', $goodsevallist1);
        Tpl::output('goodsevallist2', $goodsevallist2);
        Tpl::output('goodsevallist3', $goodsevallist3);
        Tpl::showpage('good_pj');
    }

    public function pjOp()
    {
        $goods_id = intval($_GET["goods_id"]);
        if ($goods_id <= 0) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        $member_model = Model('member');
        $member_info  = $member_model->getMemberInfo(array('member_id' => $_SESSION['member_id']));

        if (empty($member_info) || 0 == $member_info['is_allowtalk']) {
            $ref_url = request_uri();
            @header("location: index.php?act=login&ref_url=" . urlencode($ref_url));
        }
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);
        $model_goods  = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id);
        $goods_info   = $goods_detail['goods_info'];
        Tpl::output('goods_info', $goods_info);
        Tpl::output('html_title', "评价-" . $goods_info["goods_name"] . C('site_name'));
        Tpl::showpage('good_pjcz');
    }
    /**
     * 记录浏览历史
     */
    public function addbrowseOp()
    {
        $goods_id = intval($_GET['gid']);
        Model('goods_browse')->addViewedGoods($goods_id, $_SESSION['member_id'], $_SESSION['store_id']);
        exit();
    }

    /**
     * 商品评论
     */
    public function commentsOp()
    {
        $goods_id = intval($_GET['goods_id']);
        $this->_get_comments($goods_id, $_GET['type'], 10);
        Tpl::showpage('goods.comments', 'null_layout');
    }

    /**
     * 商品评价详细页
     */
    public function comments_listOp()
    {
        $goods_id = intval($_GET['goods_id']);

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_info  = $model_goods->getGoodsInfoByID($goods_id, '*');
        // 验证商品是否存在
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        Tpl::output('goods', $goods_info);

        $this->getStoreInfo($goods_info['store_id']);

        // 当前位置导航
        $nav_link_list   = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name'], 'link' => urlShopWAP('goods', 'index', array('goods_id' => $goods_id)));
        $nav_link_list[] = array('title' => '商品评价');
        Tpl::output('nav_link_list', $nav_link_list);

        //评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);

        $seo_param = array();

        $seo_param['name']        = $goods_info['goods_name'];
        $seo_param['key']         = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();

        $this->_get_comments($goods_id, $_GET['type'], 20);

        Tpl::showpage('goods.comments_list');
    }

    private function _get_comments($goods_id, $type, $page)
    {
        $condition                  = array();
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
        $goodsevallist        = $model_evaluate_goods->getEvaluateGoodsList($condition, $page);
        Tpl::output('goodsevallist', $goodsevallist);

        Tpl::output('show_page', $model_evaluate_goods->showpage('5'));
    }

    /**
     * 销售记录
     */

    public function salelogOp()
    {
        $goods_id = intval($_GET['goods_id']);
        if ($_GET['vr']) {
            $model_order = Model('vr_order');
            $sales       = $model_order->getOrderAndOrderGoodsSalesRecordList(array('goods_id' => $goods_id), '*', 1000);
        } else {
            $model_order = Model('order');
            $sales       = $model_order->getOrderAndOrderGoodsSalesRecordList(array('order_goods.goods_id' => $goods_id), 'order_goods.*, order.buyer_name, order.add_time', 1000);
        }
        Tpl::output('show_page', $model_order->showpage());
        Tpl::output('sales', $sales);

        Tpl::output('order_type', array(2 => '抢', 3 => '折', '4' => '套装'));
        Tpl::showpage('goods.salelog', 'null_layout');
    }

    /**
     * 产品咨询
     */
    public function consultingOp()
    {
        $goods_id = intval($_GET['goods_id']);
        if ($goods_id <= 0) {
            showMessage(Language::get('wrong_argument'), '', 'html', 'error');
        }

        //得到商品咨询信息
        $model_consult     = Model('consult');
        $where             = array();
        $where['goods_id'] = $goods_id;
        if (intval($_GET['ctid']) > 0) {
            $where['ct_id'] = intval($_GET['ct_id']);
        }
        $consult_list = $model_consult->getConsultList($where, '*', '10');
        Tpl::output('consult_list', $consult_list);

        // 咨询类型
        $consult_type = rkcache('consult_type', true);
        Tpl::output('consult_type', $consult_type);

        Tpl::output('consult_able', $this->checkConsultAble());
        Tpl::showpage('goods.consulting', 'null_layout');
    }

    /**
     * 产品咨询
     */
    public function consulting_listOp()
    {
        $goods_id = intval($_GET['goods_id']);
        if ($goods_id <= 0) {
            showMessage(Language::get('wrong_argument'), '', 'html', 'error');
        }

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_info  = $model_goods->getGoodsInfoByID($goods_id, '*');
        // 验证商品是否存在
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        Tpl::output('goods', $goods_info);

        $this->getStoreInfo($goods_info['store_id']);

        // 当前位置导航
        $nav_link_list   = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name'], 'link' => urlShopWAP('goods', 'index', array('goods_id' => $goods_id)));
        $nav_link_list[] = array('title' => '商品咨询');
        Tpl::output('nav_link_list', $nav_link_list);

        //得到商品咨询信息
        $model_consult = Model('consult');
        $consult_list1 = $model_consult->getConsultList(array("ct_id" => 1), '*', 0, 20);
        Tpl::output('consult_list1', $consult_list1);
        $consult_list2 = $model_consult->getConsultList(array("ct_id" => 2), '*', 0, 20);
        Tpl::output('consult_list2', $consult_list2);
        $consult_list3 = $model_consult->getConsultList(array("ct_id" => 3), '*', 0, 20);
        Tpl::output('consult_list3', $consult_list3);
        $consult_list4 = $model_consult->getConsultList(array("ct_id" => 4), '*', 0, 20);
        Tpl::output('consult_list4', $consult_list4);

        // 咨询类型
        $consult_type = rkcache('consult_type', true);
        Tpl::output('consult_type', $consult_type);

        $seo_param                = array();
        $seo_param['name']        = $goods_info['goods_name'];
        $seo_param['key']         = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();

        Tpl::output('consult_able', $this->checkConsultAble($goods_info['store_id']));
        Tpl::showpage('goods.consulting_list');
    }

    private function checkConsultAble($store_id = 0)
    {
        //检查是否为店主本身
        $store_self = false;
        if (!empty($_SESSION['store_id'])) {
            if ((0 == $store_id && intval($_GET['store_id']) == $_SESSION['store_id']) || (0 != $store_id && $store_id == $_SESSION['store_id'])) {
                $store_self = true;
            }
        }
        //查询会员信息
        $member_info  = array();
        $member_model = Model('member');
        if (!empty($_SESSION['member_id'])) {
            $member_info = $member_model->getMemberInfoByID($_SESSION['member_id'], 'is_allowtalk');
        }

        //检查是否可以评论
        $consult_able = true;
        if ((!C('guest_comment') && !$_SESSION['member_id']) || true == $store_self || ($_SESSION['member_id'] > 0 && 0 == $member_info['is_allowtalk'])) {
            $consult_able = false;
        }
        return $consult_able;
    }

    /**
     * 商品咨询添加
     */
    public function save_consultOp()
    {
        //检查是否可以评论
        if (!C('guest_comment') && !$_SESSION['member_id']) {
            showDialog(L('goods_index_goods_noallow'));
        }
        $goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0) {
            showDialog(L('wrong_argument'));
        }
        //咨询内容的非空验证
        if (trim($_POST['goods_content']) == "") {
            showDialog(L('goods_index_input_consult'));
        }
        if (process::islock('commit')) {
            showDialog(L('nc_common_op_repeat'));
        } else {
            process::addprocess('commit');
        }
        if ($_SESSION['member_id']) {
            //查询会员信息
            $member_model = Model('member');
            $member_info  = $member_model->getMemberInfo(array('member_id' => $_SESSION['member_id']));
            if (empty($member_info) || 0 == $member_info['is_allowtalk']) {
                showDialog(L('goods_index_goods_noallow'));
            }
        }
        //判断商品编号的存在性和合法性
        $goods      = Model('goods');
        $goods_info = $goods->getGoodsInfoByID($goods_id, 'goods_name,store_id');
        if (empty($goods_info)) {
            showDialog(L('goods_index_goods_not_exists'));
        }
        //判断是否是店主本人
        if ($_SESSION['store_id'] && $goods_info['store_id'] == $_SESSION['store_id']) {
            showDialog(L('goods_index_consult_store_error'));
        }
        //检查店铺状态
        $store_model = Model('store');
        $store_info  = $store_model->getStoreInfoByID($goods_info['store_id']);
        if ('0' == $store_info['store_state'] || intval($store_info['store_state']) == '2' || (intval($store_info['store_end_time']) != 0 && $store_info['store_end_time'] <= time())) {
            showDialog(L('goods_index_goods_store_closed'));
        }
        //接收数据并保存
        $input                    = array();
        $input['goods_id']        = $goods_id;
        $input['goods_name']      = $goods_info['goods_name'];
        $input['member_id']       = intval($_SESSION['member_id']) > 0 ? $_SESSION['member_id'] : 0;
        $input['member_name']     = $_SESSION['member_name'] ? $_SESSION['member_name'] : '';
        $input['store_id']        = $store_info['store_id'];
        $input['store_name']      = $store_info['store_name'];
        $input['ct_id']           = intval($_POST['consult_type_id']);
        $input['consult_addtime'] = TIMESTAMP;
        if (strtoupper(CHARSET) == 'GBK') {
            $input['consult_content'] = Language::getGBK($_POST['goods_content']);
        } else {
            $input['consult_content'] = $_POST['goods_content'];
        }
        $input['isanonymous'] = 'hide' == $_POST['hide_name'] ? 1 : 0;
        $consult_model        = Model('consult');
        if ($consult_model->addConsult($input)) {
            showDialog(L('goods_index_consult_success'), 'index.php?act=goods&op=consulting_list&goods_id=' . $goods_id, 'succ');
        } else {
            showDialog(L('goods_index_consult_fail'));
        }
    }

    /**
     * 异步显示优惠套装/推荐组合
     */
    public function get_bundlingOp()
    {
        $goods_id = intval($_GET['goods_id']);
        if ($goods_id <= 0) {
            exit();
        }
        $model_goods = Model('goods');
        $goods_info  = $model_goods->getGoodsOnlineInfoByID($goods_id);
        if (empty($goods_info)) {
            exit();
        }

        // 优惠套装
        $array = Model('p_bundling')->getBundlingCacheByGoodsId($goods_id);
        if (!empty($array)) {
            Tpl::output('bundling_array', unserialize($array['bundling_array']));
            Tpl::output('b_goods_array', unserialize($array['b_goods_array']));
        }

        // 推荐组合
        if (!empty($goods_info) && $model_goods->checkIsGeneral($goods_info)) {
            $array = Model('goods_combo')->getGoodsComboCacheByGoodsId($goods_id);
            Tpl::output('goods_info', $goods_info);
            Tpl::output('gcombo_list', unserialize($array['gcombo_list']));
        }

        Tpl::showpage('goods_bundling', 'null_layout');
    }

    /**
     * 商品详细页运费显示
     *
     * @return unknown
     */
    public function calcOp()
    {
        if (!is_numeric($_GET['id']) || !is_numeric($_GET['tid'])) {
            return false;
        }

        $model_transport = Model('transport');
        $extend          = $model_transport->getExtendList(array('transport_id' => array(intval($_GET['tid']))));
        if (!empty($extend) && is_array($extend)) {
            $calc         = array();
            $calc_default = array();
            foreach ($extend as $v) {
                if (strpos($v['top_area_id'], "," . intval($_GET['id']) . ",") !== false) {
                    $calc = $v['sprice'];
                }
                if (1 == $v['is_default']) {
                    $calc_default = $v['sprice'];
                }
            }
            //如果运费模板中没有指定该地区，取默认运费
            if (empty($calc) && !empty($calc_default)) {
                $calc = $calc_default;
            }
        }
        echo json_encode($calc);
    }

    /**
     * 到货通知
     */
    public function arrival_noticeOp()
    {
        if (!$_SESSION['is_login']) {
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'], 'member_email,member_mobile');
        Tpl::output('member_info', $member_info);

        Tpl::showpage('arrival_notice.submit', 'null_layout');
    }

    /**
     * 到货通知表单
     */
    public function arrival_notice_submitOp()
    {
        $type     = intval($_POST['type']) == 2 ? 2 : 1;
        $goods_id = $_POST['goods_id'];
        if ($goods_id <= 0) {
            showDialog(L('wrong_argument'), 'reload');
        }
        // 验证商品数是否充足
        $goods_info = Model('goods')->getGoodsInfoByID($goods_id, 'goods_id,goods_name,goods_storage,goods_state');
        if (empty($goods_info) || ($goods_info['goods_storage'] > 0 && 1 == $goods_info['goods_state'])) {
            showDialog(L('wrong_argument'), 'reload');
        }

        $model_arrivalnotice = Model('arrival_notice');
        // 验证会员是否已经添加到货通知
        $where              = array();
        $where['goods_id']  = $goods_info['goods_id'];
        $where['member_id'] = $_SESSION['member_id'];
        $where['an_type']   = $type;
        $notice_info        = $model_arrivalnotice->getArrivalNoticeInfo($where);
        if (!empty($notice_info)) {
            if (1 == $type) {
                showDialog('您已经添加过通知提醒，请不要重复添加', 'reload');
            } else {
                showDialog('您已经预约过了，请不要重复预约', 'reload');
            }
        }

        $insert               = array();
        $insert['goods_id']   = $goods_info['goods_id'];
        $insert['goods_name'] = $goods_info['goods_name'];
        $insert['member_id']  = $_SESSION['member_id'];
        $insert['an_mobile']  = $_POST['mobile'];
        $insert['an_email']   = $_POST['email'];
        $insert['an_type']    = $type;
        $model_arrivalnotice->addArrivalNotice($insert);

        $title = 1 == $type ? '到货通知' : '立即预约';
        $js    = "ajax_form('arrival_notice', '" . $title . "', '" . urlShopWAP('goods', 'arrival_notice_succ', array('type' => $type)) . "', 480);";
        showDialog('', '', 'js', $js);
    }

    /**
     * 到货通知添加成功
     */
    public function arrival_notice_succOp()
    {
        // 可能喜欢的商品
        $goods_list = Model('goods_browse')->getGuessLikeGoods($_SESSION['member_id'], 4);
        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('arrival_notice.message', 'null_layout');
    }
    /**
     * 获取当前月份的商品库存价格信息，如果没有则自动插入当月的库存数据
     * @param unknown_type $goods_info
     */
    private function get_stock_info($goods_info)
    {
        //print_r($goods_info);
        //echo $goods_info["spec_value"];
        $type_id = $goods_info["type_id"];
        if (is_array($goods_info["spec_value"])) {
            $spec_value1 = array_values($goods_info["spec_value"]);
        } else {
            $spec_value1 = array_values(unserialize($goods_info["spec_value"]));
        }
        $spec_value2 = array_values($spec_value1[0]);
        $package     = $spec_value2[0];
        //--------------
        $commonid = $goods_info["goods_commonid"];

        //线路和门票
        if ('40' == $type_id || '41' == $type_id || '42' == $type_id || '43' == $type_id) {
            $date                    = date('Y-m'); //date('Y-m',strtotime("+3 day"));//提前预订天数
            $date2                   = date('Y-m', strtotime("{$date}+1 month")); //第二个月
            $where_stock["commonid"] = $commonid;
            $where_stock["date"]     = array('egt', $date);
            $where_stock["package"]  = $package;
            //没有库存，自动插入本月库存

            //生成两个月的数据
            $this->insert_stock_info('', $package, $commonid, $where_stock, $date, '100'); //这里的goods_info要为空，因为传过去的商品名称不对
            $this->insert_stock_info('', $package, $commonid, $where_stock, $date2, '100'); //这里的goods_info要为空，因为传过去的商品名称不对

            $month = Model()->table('stock')->where($where_stock)->order('date asc')->select();
            Tpl::output('month', $month);
        } elseif ('46' == $type_id) {
            //高尔夫
            $date                      = date('Y-m');
            $date2                     = date('Y-m', strtotime("{$date}+1 month")); //第二个月
            $where_month["commonid"]   = $commonid;
            $where_month["date_month"] = array('egt', $date);
            //没有库存，自动插入本月库存
            //生成两个月的数据
            Model('goods')->insert_golf_stock_info($goods_info, $commonid, $where_month, $date, '66');
            Model('goods')->insert_golf_stock_info($goods_info, $commonid, $where_month, $date2, '66');
            $month = Model()->table('golf')->where($where_month)->group('date_month')->order('date_month asc')->select();

            $where_days["commonid"]   = $commonid;
            $where_days["date_month"] = $date;
            $days                     = Model()->table('golf')->where($where_days)->order('day asc')->select();
            Tpl::output('month', $month);
            Tpl::output('days', $days);
        }
    }
    /**
     * 线路和门票，没有库存，自动插入本月库存
     * @param  $goods_info商品表goods_common信息，字段spec_value,goods_price,goods_name：,可以为空
     * @param  $package套餐
     * @param  $commonid商品commonid
     * @param  $where_stock查谟库存表where条件数组
     * @param  $date当前日期，格式：2015-10
     * @param  $stock库存，默认100
     */
    private function insert_stock_info($goods_info = '', $package, $commonid, $where_stock, $date, $stock = '100')
    {
        //echo "<br/>".$package.'<br/>';
        $where_stock["date"] = $date;
        $count               = Model()->table('stock')->where($where_stock)->count();
        //没有库存，自动插入本月库存
        if ('0' == $count) {
            if (empty($goods_info)) {
                $goods_info = Model()->table('goods_common')->where(array('goods_commonid' => $commonid))->field('spec_value,goods_price,goods_name')->find();
            }
            //获取成人或儿童
            if (is_array($goods_info["spec_value"])) {
                $goods_spec = array_values($goods_info["spec_value"]);
            } else {
                $goods_spec = array_values(unserialize($goods_info["spec_value"]));
            }
            $goods_man_type = array_values($goods_spec[1]); //
            $child          = '';
            $man            = $goods_man_type[0]; //成人
            if (!empty($goods_man_type[1])) {
                $child = $goods_man_type[1]; //儿童
            }
            //-------
            //商品表商品名

            $goods_name_man   = $goods_info["goods_name"] . ' ' . $package . ' ' . $man;
            $goods_name_child = $goods_info["goods_name"] . ' ' . $package . ' ' . $child;
            $spec_value_count = count($goods_spec);
            if ($spec_value_count > 2) {
                for ($i = 2; $i < $spec_value_count; $i++) {
                    $goods_spec_arr = array_values($goods_spec[$i]);
                    $goods_spec_str .= ' ' . $goods_spec_arr[0];
                }
                $goods_name_man .= $goods_spec_str;
                $goods_name_child .= $goods_spec_str;
            }

            /* echo $goods_info["goods_name"]."<br/>";
            echo $goods_name_man."<br/>";
            echo $goods_name_child."<br/>";
            echo $commonid;  */
            //获取商品表的详细价格

            $man_price = Model()->table('goods')->where(array('goods_name' => $goods_name_man, 'goods_commonid' => $commonid))->field('goods_price')->find(); //成人价
            if (!empty($goods_man_type[1])) {
                //如果有儿童则调用儿童价
                $child_price_data = Model()->table('goods')->where(array('goods_name' => $goods_name_child, 'goods_commonid' => $commonid))->field('goods_price')->find(); //儿童价
                $child_price      = $child_price_data["goods_price"];
            } else {
                $child_price = $man_price["goods_price"];
            }

            //-------------
            $stock_d['commonid'] = $commonid;
            $stock_d['package']  = $package;
            $stock_d['date']     = $date;

            $days_curent = date("t", mktime(0, 0, 0, substr($date, -2), 1, substr($date, 0, 4))); //本月天数
            for ($i = 1; $i <= $days_curent; $i++) {
                $stock_info[$i]['stock']       = $stock;
                $stock_info[$i]['man_price']   = $man_price["goods_price"];
                $stock_info[$i]['child_price'] = $child_price;
                $stock_info[$i]['date']        = date('Y-m-d', strtotime($stock_d['date'] . '-' . $i));
            }
            //print_r($stock_info);
            $stock_d['stock_info'] = json_encode($stock_info);
            $status                = Model()->table('stock')->insert($stock_d);
            //插入结束
        }
    }

    /*
     * 测试
     */
    public function insert_golf_stock_testOp()
    {
        $goods_info = Model()->table('goods_common')->where('goods_commonid=100559')->select();
        //获取当前月份的商品库存价格信息，如果没有则自动插入当月的库存数据
        $this->get_stock_info($goods_info[0]);
        exit();
        /* $date_month='2016-03';
        $where_stock=array('commonid'=>'100389','date_month'=>$date_month);
        Model('goods')->insert_golf_stock_info('',  '100389', $where_stock, $date_month); */
        $commonid = '100559';
        $date     = '2015-10-03';

        $golf_site_arr   = unserialize($GLOBALS["setting_config"]["golf_site"]);
        $golf_minute_arr = unserialize($GLOBALS["setting_config"]["golf_minute"]);
        //print_r($goods_info);
        //$golf_stock=new golf_stock2Model();
        $arr = Model('goods')->get_golf_stock_info($goods_info[0], $commonid, $date, $golf_site_arr, $golf_minute_arr, $stock = '1');
        print_r($arr);
    }
    /**
     * 判断是否只有一个套餐而且套餐是商品的名称，则将页面上的选择套餐隐藏并设置表单的套餐值
     */
    private function is_show_package($goods_info)
    {
        if (!empty($goods_info['spec_value']) && is_array($goods_info['spec_value'])) {
            $show_package       = '';
            $goods_spec         = array_values($goods_info['spec_value']);
            $package_spec_count = count($goods_spec[0]);
            $package_spec       = array_values($goods_spec[0]);
            $goods_name         = Model()->table('goods_common')->where(array('goods_commonid' => $goods_info["goods_commonid"]))->field('goods_name')->find();
            //echo $package_spec[0].'--'.$goods_name["goods_name"];
            if ('1' == $package_spec_count && $package_spec[0] == $goods_name["goods_name"]) {
                $show_package = 'no';
            }
            Tpl::output('show_package', $show_package); //是否显示套餐，no为不显示
            Tpl::output('goods_package', $package_spec[0]); //是否显示套餐，no为不显示
        }
    }
    /**
     * 获取golf_price表中高尔夫单人价格
     */
    public function get_golf_single_priceOp()
    {
        $package   = $_GET["package"];
        $data      = Model()->table('golf_price')->where(array('commonid' => $_GET["commonid"], 'package' => $package))->field('price')->find();
        $price_arr = explode('|', $data["price"]);
        echo $price_arr[2];
    }
    /**
     * 商品详细页获取评价信息
     */
    public function get_goods_commondOp()
    {
        if (!empty($_GET["goods_id"])) {
            $goods_id   = intval($_GET["goods_id"]);
            $common_res = Model()->table('evaluate_goods,member')->where("evaluate_goods.geval_goodsid={$goods_id} and geval_frommemberid=member.member_id")->field('evaluate_goods.*,member.member_avatar')->limit(10)->order("geval_addtime DESC")->select();
            $html_str   = '';
            if (!empty($common_res)) {
                foreach ($common_res as $v) {
                    $geval_image_str = '';
                    if (!empty($v["geval_image"])) {
                        $geval_image = explode(',', $v["geval_image"]);
                        foreach ($geval_image as $v2) {
                            // $v2 = thumb($geval_image);
                            $v2 = C('remote_upload_url') . $v2;
                            $geval_image_str .= " <div class='single_pic'><div class='self_width'><img src='{$v2}' /></div></div>";
                        }
                        //晒图
                        $geval_image_str = "<div class='updata_pic'>{$geval_image_str}</div>";
                    }
                    $star = str_replace(array('1', '2', '3', '4', '5', '6'), array('one', 'two', 'three', 'four', 'five'), round($v["geval_scores"]));
                    $html_str .= "<li>
                        <div class='appraiser'>
                           <div class='photo'><img src='" . getMemberAvatar($v["member_avatar"]) . "' /></div>
                              <div class='nickname'>";
                    if (1 == $v['geval_isanonymous']) {
                        $html_str .= str_cut($v['geval_frommembername'], 2) . '***';
                    } else {
                        $html_str .= $v["geval_frommembername"];
                    }
                    $html_str .= "</div>
                              <div class='score'><span class='{$star}star'></span></div>
                              <div class='clear'></div>
                          </div>
                          <p>{$v["geval_content"]}</p>
                             {$geval_image_str}
                          <div class='evaluation_time'>" . date('m-d H:i:s', $v["geval_addtime"]) . "</div>
                      </li>";
                }
                echo $html_str;
            } else {
                echo '暂无评论';
            }
        }
    }
    /**
     * 选择规格后AJAX获取价格
     */
    public function get_goods_priceOp()
    {
        if (!empty($_GET["commonid"]) && !empty($_GET["goods_name"])) {
            $goods_commonid = intval($_GET["commonid"]);
            $goods_name     = $_GET["goods_name"];
            $price_arr      = Model()->table('goods')->where(array('goods_commonid' => $goods_commonid, 'goods_name' => $goods_name))->field('goods_price,goods_marketprice,goods_id')->find();
            echo json_encode($price_arr);
        }
    }

}
