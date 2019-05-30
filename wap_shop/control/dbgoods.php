<?php
/**
 * 前台商品
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class dbgoodsControl extends BaseGoodsControl {
   public function __construct() {
      parent::__construct();
      Language::read('store_goods_index');
   }

   /**
    * 单个商品信息页
    */
   public function indexOp() {
      $goods_id = intval($_GET['goods_id']);
      $gr_id    = intval($_GET['gr_id']);

      $model_groupbuy = Model('groupbuy');
      $model_order    = Model('order');
      $model_member   = Model('member');
      //
      $condition                = array();
      $condition['groupbuy_id'] = $gr_id;
      $groupbuy                 = $model_groupbuy->getGroupbuyInfo($condition);
      Tpl::output('group', $groupbuy);
      $goods_id = $goods_id ? $goods_id : intval($groupbuy['goods_id']);
      $status   = $model_groupbuy->where("groupbuy_id = $gr_id")->find();
      Tpl::output('status', $status['state']);
      Tpl::output('gxc', (intval($groupbuy['total_quantity']) - intval($groupbuy['buy_quantity'])));
      //
      $time_cha = (intval($groupbuy['gend_time']) + 1800) - time();
      Tpl::output('ge_time', $time_cha);
      //

      $mid                   = intval($_SESSION['member_id']);
      $condition             = array();
      $condition['gr_id']    = $gr_id;
      $condition['buyer_id'] = $mid;
      $olst                  = $model_order->getOrderList($condition);
      //print_r(count($olst));exit();
      Tpl::output('if_this', count($olst));

      //

      $condition             = array();
      $condition['gr_id']    = $gr_id;
      $condition['buyer_id'] = $mid;
      $olist                 = $model_order->getOrderList($condition);
      $code_str              = "";
      if (!empty($olist)) {

         foreach ($olist as $key => $val) {
            if ('' != $val['gr_code']) {
               $code_str .= ($val['gr_code'] . ',');
            }
            if ('' != $val['gr_code2']) {
               $code_str .= ($val['gr_code2'] . ',');
            }
         }
      }
      if (strlen($code_str) > 1) {
         $code_str = substr($code_str, 0, strlen($code_str) - 1);
         $cd_a     = explode(',', $code_str);
      }
      Tpl::output('code_str', $cd_a);
      //Tpl::output('g_count', count($olist));
      $counts = $model_order->field('count(order_id)AS count')->where("gr_id = $gr_id AND buyer_id = $mid")->find();
      Tpl::output('g_count', $counts['count']);

      //
      $condition          = array();
      $condition['gr_id'] = $gr_id;
      $orderlst           = Model()->table('order')->where($condition)->order('code desc')->field('distinct code')->select();
      //
      if (!empty($orderlst)) {
         foreach ($orderlst as $ky => $vl) {
            $cc = $vl['code'];

            $condition          = array();
            $condition['gr_id'] = $gr_id;
            $condition['code']  = $cc;
            $o_lst              = Model()->table('order')->where($condition)->order('add_time desc')->select();
            if (!empty($o_lst)) {
               foreach ($o_lst as $ko => $vo) {
                  $ipqh                 = explode(':', $vo['shipping_code']);
                  $o_lst[$ko]['ipqian'] = $ipqh[0];
                  $iph                  = explode('.', $ipqh[1]);
                  $o_lst[$ko]['iphou']  = ($iph[0] . '.***.***.' . $iph[3]);
               
                  $mminf = $model_member->getMemberInfo(array('member_id' => $vo['buyer_id']));
				  if (preg_match("/^(http:\/\/|https:\/\/).*$/",  $mminf['member_avatar'])) {
					$o_lst[$ko]['miUrl'] =  $mminf['member_avatar'];
				  } else {
					if ($mminf['member_avatar']) {
					   $o_lst[$ko]['miUrl'] = UPLOAD_SITE_URL . DS . ATTACH_AVATAR . DS .  $mminf['member_avatar'];
					} else {
					   $o_lst[$ko]['miUrl'] = UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . C('default_user_portrait');
					}
				  }
               }
            }
            $orderlst[$ky]['a_oinf'] = $o_lst;
         }
      }
      Tpl::output('grp_a_inf', $orderlst);
      //商品详细信息
      $model_goods  = Model('goods');
      $goods_detail = $model_goods->getGoodsDetail($goods_id);
      $goods_info   = $goods_detail['goods_info'];
      if (empty($goods_info)) {
         // showMessage(L('goods_index_no_goods'), '', 'html', 'error');
         showDialog(L('goods_index_no_goods', 'error'));
      }
      //getGoodeCommonInfo
      $model_member = Model('member');
      $minf         = $model_member->getMemberInfo(array('member_id' => $_SESSION['member_id']));
      if (!empty($minf)) {
         Tpl::output('mem_points', $minf['member_points']);
      }
      // by
      $rs    = $model_goods->getGoodsList(array('goods_commonid' => $goods_info['goods_commonid']));
      $count = 0;
      foreach ($rs as $v) {
         $count += $v['goods_salenum'];
      }
      $goods_info['goods_salenum'] = $count;
      //  添加 end
      $this->getStoreInfo($goods_info['store_id']);

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
      Tpl::output('goods', $goods_info);

      // 当前位置导航
      $nav_link_list   = Model('goods_class')->getGoodsClassGfNav($goods_info['gc_id'], 0);
      $nav_link_list[] = array('title' => $goods_info['goods_name']);
      Tpl::output('nav_link_list', $nav_link_list);

      //评价信息
      $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
      Tpl::output('goods_evaluate_info', $goods_evaluate_info);

      $seo_param                = array();
      $seo_param['name']        = $goods_info['goods_name'];
      $seo_param['key']         = $goods_info['goods_keywords'];
      $seo_param['description'] = $goods_info['goods_description'];
      Model('seo')->type('product')->param($seo_param)->show();
      Tpl::output('uselayout', false);
      Tpl::showpage('yydb.goods.yydb', 'null_layout');

   }
   /**
    * 记录浏览历史
    */
   public function addbrowseOp() {
      $goods_id = intval($_GET['gid']);
      Model('goods_browse')->addViewedGoods($goods_id, $_SESSION['member_id'], $_SESSION['store_id']);
      exit();
   }

   public function finishBuyOp() {
      $groupid = intval($_GET['gp_id']);
      //Model('goods_browse')->addViewedGoods($goods_id,$_SESSION['member_id'],$_SESSION['store_id']);
      Tpl::showpage('goods.finish', 'null_layout');
   }

   /**
    * 商品评论
    */
   public function commentsOp() {
      $goods_id = intval($_GET['goods_id']);
      $this->_get_comments($goods_id, $_GET['type'], 10);
      Tpl::showpage('new.goods.comments', 'null_layout');
   }

   /**
    * 商品评价详细页
    */
   public function comments_listOp() {
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
      $nav_link_list[] = array('title' => $goods_info['goods_name'], 'link' => urlShop('goods', 'index', array('goods_id' => $goods_id)));
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

   private function _get_comments($goods_id, $type, $page) {
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
   public function salelogOp() {
      $goods_id = intval($_GET['goods_id']);
      if ($_GET['vr']) {
         $model_order = Model('vr_order');
         $sales       = $model_order->getOrderAndOrderGoodsSalesRecordList(array('goods_id' => $goods_id), '*', 10);
      } else {
         $model_order = Model('order');
         $sales       = $model_order->getOrderAndOrderGoodsSalesRecordList(array('order_goods.goods_id' => $goods_id), 'order_goods.*, order.buyer_name, order.add_time', 10);
      }
      Tpl::output('show_page', $model_order->showpage());
      Tpl::output('sales', $sales);

      Tpl::output('order_type', array(2 => '抢', 3 => '折', '4' => '套装'));
      Tpl::showpage('goods.salelog', 'null_layout');
   }

   /**
    * 产品咨询
    */
   public function consultingOp() {
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
   public function consulting_listOp() {
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
      Tpl::showpage('goods.consulting_list');
   }

   private function checkConsultAble($store_id = 0) {
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
   public function save_consultOp() {
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
      //表单验证
      /*$result = chksubmit(true,C('captcha_status_goodsqa'),'num');
      if (!$result){
      showDialog(L('invalid_request'));
      } elseif ($result === -11){
      showDialog(L('invalid_request'));
      }elseif ($result === -12){
      showDialog(L('wrong_checkcode'));
      }*/
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
         showDialog(L('goods_index_consult_success'), 'reload', 'succ');
      } else {
         showDialog(L('goods_index_consult_fail'));
      }
   }

   /**
    * 异步显示优惠套装/推荐组合
    */
   public function get_bundlingOp() {
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
   function calcOp() {
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
   public function arrival_noticeOp() {
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
   public function arrival_notice_submitOp() {
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
      $js    = "ajax_form('arrival_notice', '" . $title . "', '" . urlShop('goods', 'arrival_notice_succ', array('type' => $type)) . "', 480);";
      showDialog('', '', 'js', $js);
   }

   /**
    * 到货通知添加成功
    */
   public function arrival_notice_succOp() {
      // 可能喜欢的商品
      $goods_list = Model('goods_browse')->getGuessLikeGoods($_SESSION['member_id'], 4);
      Tpl::output('goods_list', $goods_list);
      Tpl::showpage('arrival_notice.message', 'null_layout');
   }
}
