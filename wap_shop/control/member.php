<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class memberControl extends BaseMemberControl {
   public function __construct() {
      parent::__construct();
      //微信进入自动登陆
      if (!empty($_GET["code"]) && !isset($_SESSION["member_id"])) {
         $code   = $_GET["code"];
         $status = Model('member')->weixin_login_handle($code);
      }
   }

   /**
    * 我的商城
    */
   public function homeOp() {
      //如果用户头像不存在则重新生成
      // $this->creater_wx_member_head();
      $model       = Model('share_member');
      $share_member_info = $model->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      $settings    = Model('share_settings')->where(array('store_id' => $_SESSION['share_store_id']))->find();
      // echo '<pre>';var_dump($_SESSION);exit;
      Tpl::output('settings', $settings);
      Tpl::output('share_member_info', $share_member_info);
      Tpl::showpage('member_home');
   }

   /**
    *下级展示
    *@author qiisyhx<sihangshi2011@gmail.com>
    **/
   public function familyOp() {
      $model       = Model('share_member');
      $member_info = $model->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      if (1 != $member_info['isshare'] and 1 != $member_info['status']) {
         showMessage('无权访问', '', '', 'error');
      }
      $lv1_list = $model->where(array('store_id' => $_SESSION['share_store_id'], 'pid' => $_SESSION['member_id']))->select();

      $lv2_pids = array(-1);
      foreach ($lv1_list as $item) {
         $lv2_pids[] = $item['member_id'];
      }
      $lv2_list = $model->where(array('store_id' => $_SESSION['share_store_id'], 'pid' => array('IN', implode(',', $lv2_pids))))->select();
      Tpl::output('lv1_list', $lv1_list);
      Tpl::output('lv2_list', $lv2_list);
      Tpl::output('member_info', $member_info);
      Tpl::showpage('member_family');
   }

   /**
    *佣金提现
    *@author qiisyhx<sihangshi2011@gmail.com>
    **/
   public function cmmsOp() {
      $model = Model('share_apply');
      $where = array('mid' => $_SESSION['member_id']);
      $total = $model->where($where)->count();
      $list  = $model->where($where)->limit(30)->page(30, $total)->order('id desc')->select();
      Tpl::output('list', $list);
      $model       = Model('share_member');
      $member_info = $model->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      Tpl::output('member_info', $member_info);
      Tpl::output('show_page', $model->showpage());
      Tpl::showpage('member_cmms');
   }

   public function cmmsapplyOp() {
      if (empty($_SESSION['auth_cmms'])){
         header('location:index.php?act=member_security&op=auth&type=cmms');
         exit;
      }
      $share_member = Model('share_member')->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      if (1 != $share_member['isshare'] || 1 != $share_member['status']) {
         header('location:index.php?act=member&op=home');
      }
      $share_settings = Model('share_settings')->where(array('store_id' => $_SESSION['share_store_id']))->find();
      if (chksubmit()) {
         if (chksubmit()) {
            $obj_validate                = new Validate();
            $pdc_amount                  = abs(floatval($_POST['pdc_amount']));
            $validate_arr[]              = array("input" => $pdc_amount, "require" => "true", 'validator' => 'Compare', 'operator' => '>=', "to" => '0.01', "message" => Language::get('predeposit_cash_add_pricemin_error'));
            $validate_arr[]              = array("input" => $_POST["pdc_bank_name"], "require" => "true", "message" => Language::get('predeposit_cash_add_shoukuanbanknull_error'));
            $validate_arr[]              = array("input" => $_POST["pdc_bank_no"], "require" => "true", "message" => Language::get('predeposit_cash_add_shoukuanaccountnull_error'));
            $validate_arr[]              = array("input" => $_POST["pdc_bank_user"], "require" => "true", "message" => Language::get('predeposit_cash_add_shoukuannamenull_error'));
            $validate_arr[]              = array("input" => $_POST["password"], "require" => "true", "message" => '请输入支付密码');
            $obj_validate->validateparam = $validate_arr;
            $error                       = $obj_validate->validate();
            if ('' != $error) {
               showDialog($error, '', 'error');
            }

            $model_member = Model('member');
            $member_info  = $model_member->getMemberInfoByID($_SESSION['member_id']);
            //验证支付密码
            if (md5($_POST['password']) != $member_info['member_paypwd']) {
               showDialog('支付密码错误', '', 'error');
            }
            //验证金额是否足够
            if (floatval($share_member['credits']) < $pdc_amount) {
               showDialog('提现金额不足', 'index.php?act=member&op=cmms', 'error');
            }
            if ($share_settings['apply_amount'] > $share_member['credits']) {
               showDialog('余额不足，提现金额需大于' . $share_settings['apply_amount'], '', 'error');
            }
            if ($share_settings['apply_amount'] > $pdc_amount) {
               showDialog('单次提现金额需大于' . $share_settings['apply_amount'], '', 'error');
            }
            #佣金提现 begin
            $model_apply = Model('share_apply');
            try {
               $model_apply->beginTransaction();
               $new_amount = $share_member['credits'] - $pdc_amount;
               $apply_data = array(
                  'openid'    => (string) $share_member['openid'],
                  'mid'       => $_SESSION['member_id'],
                  'nickname'  => (string) $share_member['nickname'],
                  'addtime'   => TIMESTAMP,
                  'status'    => 0,
                  'amount'    => $pdc_amount,
                  'bank_name' => $_POST['pdc_bank_name'],
                  'bank_no'   => $_POST['pdc_bank_no'],
                  'bank_user' => $_POST['pdc_bank_user'],
                  'credits'   => $new_amount,
                  'store_id'  => $_SESSION['share_store_id'],
               );
               if (!$model_apply->insert($apply_data)) {
                  throw new Exception("申请提现失败", 1);
               }
               #变更余额
               $update = array(
                  'credits'      => $new_amount,
                  // 'done_credits' => array('exp', 'done_credits+' . $pdc_amount),
               );
               Model('share_member')->where(array('id' => $share_member['id']))->update($update);
               $model_apply->commit();
               showDialog('提现申请提交成功，请等待审核', 'index.php?act=member&op=cmms', 'succ', '');
            } catch (Exception $e) {
               $model_apply->rollback();
               showDialog($e->getMessage(), 'index.php?act=member&op=cmms', 'error');
            }
            die();
            #佣金提现 end
         }
      }
      Tpl::output('share_member', $share_member);
      Tpl::output('share_settings', $share_settings);
      Tpl::showpage('member_cmmsapply');
   }

   public function cmmsinfoOp() {
      $pdc_id = intval($_GET["id"]);
      if ($pdc_id <= 0) {
         showMessage('参数错误', 'index.php?act=member&op=cmms', 'html', 'error');
      }
      $model = Model('share_apply');
      $where = array('mid' => $_SESSION['member_id'], 'id' => $pdc_id, 'store_id' => $_SESSION['share_store_id']);
      $info  = $model->where($where)->find();
      if (empty($info)) {
         showMessage('提现记录不存在', 'index.php?act=member&op=cmms', 'html', 'error');
      }
      Tpl::output('info', $info);
      Tpl::showpage('member_cmmsinfo');
   }

//资料管理
   public function manageOP() {
      Tpl::showpage('management');
   }

   public function ajax_load_member_infoOp() {

      $member_info                   = $this->member_info;
      $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);

      //代金券数量
      $member_info['voucher_count'] = Model('voucher')->getCurrentAvailableVoucherCount($_SESSION['member_id']);
      Tpl::output('home_member_info', $member_info);

      Tpl::showpage('member_home.member_info', 'null_layout');
   }

   public function ajax_load_order_infoOp() {
      $model_order = Model('order');

      //交易提醒 - 显示数量
      $member_info['order_nopay_count']     = $model_order->getOrderCountByID('buyer', $_SESSION['member_id'], 'NewCount');
      $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer', $_SESSION['member_id'], 'SendCount');
      $member_info['order_noeval_count']    = $model_order->getOrderCountByID('buyer', $_SESSION['member_id'], 'EvalCount');
      Tpl::output('home_member_info', $member_info);

      //交易提醒 - 显示订单列表
      $condition                = array();
      $condition['buyer_id']    = $_SESSION['member_id'];
      $condition['order_state'] = array('in', array(ORDER_STATE_NEW, ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
      $order_list               = $model_order->getNormalOrderList($condition, '', '*', 'order_id desc', 3, array('order_goods'));

      foreach ($order_list as $order_id => $order) {
         //显示物流跟踪
         $order_list[$order_id]['if_deliver'] = $model_order->getOrderOperateState('deliver', $order);
         //显示评价
         $order_list[$order_id]['if_evaluation'] = $model_order->getOrderOperateState('evaluation', $order);
         //显示支付
         $order_list[$order_id]['if_payment'] = $model_order->getOrderOperateState('payment', $order);
         //显示收货
         $order_list[$order_id]['if_receive'] = $model_order->getOrderOperateState('receive', $order);
      }

      Tpl::output('order_list', $order_list);

      //取出购物车信息
      $model_cart = Model('cart');
      $cart_list  = $model_cart->listCart('db', array('buyer_id' => $_SESSION['member_id']), 3);
      Tpl::output('cart_list', $cart_list);
      Tpl::showpage('member_home.order_info', 'null_layout');
   }

   public function ajax_load_goods_infoOp() {
      //商品收藏
      $favorites_model      = Model('favorites');
      $favorites_goods_list = $favorites_model->getGoodsFavoritesList(array('member_id' => $_SESSION['member_id']), '*');
      // if (!empty($favorites_list) && is_array($favorites_list)){
      // $favorites_id = array();//收藏的商品编号
      // foreach ($favorites_list as $key=>$favorites){
      // $fav_id = $favorites['fav_id'];
      // $favorites_id[] = $favorites['fav_id'];
      // $favorites_key[$fav_id] = $key;
      // }
      // $goods_model = Model('goods');
      // $field = 'goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.goods_price,goods.evaluation_count,goods.goods_salenum,goods.goods_collect,store.store_name,store.member_id,store.member_name,store.store_qq,store.store_ww,store.store_domain';
      // $goods_list = $goods_model->getGoodsStoreList(array('goods_id' => array('in', $favorites_id)), $field);
      // $store_array = array();//店铺编号
      // if (!empty($goods_list) && is_array($goods_list)){
      // $store_goods_list = array();//店铺为分组的商品
      // foreach ($goods_list as $key=>$fav){
      // $fav_id = $fav['goods_id'];
      // $fav['goods_member_id'] = $fav['member_id'];
      // $key = $favorites_key[$fav_id];
      // $favorites_list[$key]['goods'] = $fav;
      // }
      // }
      // }
      //Tpl::output('favorites_list',$favorites_list);

      //店铺收藏
      $favorites_store_list = $favorites_model->getStoreFavoritesList(array('member_id' => $_SESSION['member_id']), '*');
      // if (!empty($favorites_list) && is_array($favorites_list)){
      // $favorites_id = array();//收藏的店铺编号
      // foreach ($favorites_list as $key=>$favorites){
      // $fav_id = $favorites['fav_id'];
      // $favorites_id[] = $favorites['fav_id'];
      // $favorites_key[$fav_id] = $key;
      // }
      // $store_model = Model('store');
      // $store_list = $store_model->getStoreList(array('store_id'=>array('in', $favorites_id)));
      // if (!empty($store_list) && is_array($store_list)){
      // foreach ($store_list as $key=>$fav){
      // $fav_id = $fav['store_id'];
      // $key = $favorites_key[$fav_id];
      // $favorites_list[$key]['store'] = $fav;
      // }
      // }
      // }
      //Tpl::output('favorites_store_list',$favorites_list);
      // $goods_count_new = array();
      // if (!empty($favorites_id)) {
      // foreach ($favorites_id as $v){
      // $count = Model('goods')->getGoodsCommonOnlineCount(array('store_id' => $v));
      // $goods_count_new[$v] = $count;
      // }
      // }
      // Tpl::output('goods_count',$goods_count_new);
      //Tpl::showpage('member_home.goods_info','null_layout');
      $favorites_count = array('goods_count' => count($favorites_goods_list), 'store_count' => count($favorites_store_list));
      echo json_encode($favorites_count);
   }

   public function ajax_load_sns_infoOp() {
      //我的足迹
      $goods_list   = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'], 20);
      $viewed_goods = array();
      if (is_array($goods_list) && !empty($goods_list)) {
         foreach ($goods_list as $key => $val) {
            $goods_id                = $val['goods_id'];
            $val['url']              = urlShopWAP('goods', 'index', array('goods_id' => $goods_id));
            $val['goods_image']      = thumb($val, 240);
            $viewed_goods[$goods_id] = $val;
         }
      }
      Tpl::output('viewed_goods', $viewed_goods);

      //我的圈子
      $model              = Model();
      $circlemember_array = $model->table('circle_member')->where(array('member_id' => $_SESSION['member_id']))->select();
      if (!empty($circlemember_array)) {
         $circlemember_array = array_under_reset($circlemember_array, 'circle_id');
         $circleid_array     = array_keys($circlemember_array);
         $circle_list        = $model->table('circle')->where(array('circle_id' => array('in', $circleid_array)))->limit(6)->select();
         Tpl::output('circle_list', $circle_list);
      }

      //好友动态
      $model_fd        = Model('sns_friend');
      $fields          = 'member.member_id,member.member_name,member.member_avatar';
      $follow_list     = $model_fd->listFriend(array('limit' => 15, 'friend_frommid' => "{$_SESSION['member_id']}"), $fields, '', 'detail');
      $member_ids      = array();
      $follow_list_new = array();
      if (is_array($follow_list)) {
         foreach ($follow_list as $v) {
            $follow_list_new[$v['member_id']] = $v;
            $member_ids[]                     = $v['member_id'];
         }
      }
      $tracelog_model = Model('sns_tracelog');
      //条件
      $condition                   = array();
      $condition['trace_memberid'] = array('in', $member_ids);
      $condition['trace_privacy']  = 0;
      $condition['trace_state']    = 0;
      $tracelist                   = Model()->table('sns_tracelog')->where($condition)->field('count(*) as _count,trace_memberid')->group('trace_memberid')->limit(5)->select();
      $tracelist_new               = array();
      $follow_list                 = array();
      if (!empty($tracelist)) {
         foreach ($tracelist as $k => $v) {
            $tracelist_new[$v['trace_memberid']] = $v['_count'];
            $follow_list[]                       = $follow_list_new[$v['trace_memberid']];
         }
      }
      Tpl::output('tracelist', $tracelist_new);
      Tpl::output('follow_list', $follow_list);
      Tpl::showpage('member_home.sns_info', 'null_layout');
   }

   /**
    * 设置常用菜单
    */
   public function common_operationsOp() {
      $type  = $_GET['type'];
      $value = $_GET['value'];
      if (!in_array($type, array('add', 'del')) || empty($value)) {
         echo false;exit;
      }
      $quicklink = $this->quicklink;
      if ('add' == $type) {
         if (!empty($quicklink)) {
            array_push($quicklink, $value);
         } else {
            $quicklink[] = $value;
         }
      } else {
         $quicklink = array_diff($quicklink, array($value));
      }
      $quicklink = array_unique($quicklink);
      $quicklink = implode(',', $quicklink);
      $result    = Model('member')->editMember(array('member_id' => $_SESSION['member_id']), array('member_quicklink' => $quicklink));
      if ($result) {
         echo true;exit;
      } else {
         echo false;exit;
      }
   }
   private function creater_wx_member_head() {
      $face_path = $_SERVER["DOCUMENT_ROOT"] . '/data/weixin/head/';
      $userinfo  = Model('wx_member')->where(array('member_id' => $_SESSION["member_id"]))->field('openid,reg_time,headimgurl')->find();
      if (!empty($userinfo)) {
         $openid        = $userinfo["openid"];
         $reg_time_path = '';//date('Y/m/d/', $userinfo["reg_time"]);
         $face_url      = $face_path . $reg_time_path . $openid . '_weixin.jpg';
         //如果头像不存在，则自动生成
         if (!file_exists($face_url)) {
            $tmp_file_name = $openid . '_tmp.jpg';
            $local_img     = $face_path . $tmp_file_name;
            @mkdir($face_path . $reg_time_path, 0777, true); //创建文件夹
            $this->put_file_from_url_content($userinfo["headimgurl"], $tmp_file_name, $face_path);
            $imageresize = new ImageResize();
            $imageresize->resize($local_img, $face_url, 150, 150); //生成头像缩略图(二维码专用)
            unlink($local_img); //删除原图
         }
      }
   }
   /**
    * 异步将远程链接上的内容(图片或内容)写到本地
    *
    * @param  $url远程地址
    * @param  $saveName保存在服务器上的文件名
    * @param $path保存路径
    * @return boolean
    */
   private function put_file_from_url_content($url, $saveName, $path) {
      // 设置运行时间为无限制
      set_time_limit(0);

      $url  = trim($url);
      $curl = curl_init();
      // 设置你需要抓取的URL
      curl_setopt($curl, CURLOPT_URL, $url);
      // 设置header
      curl_setopt($curl, CURLOPT_HEADER, 0);
      // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      // 运行cURL，请求网页
      $file = curl_exec($curl);
      // 关闭URL请求
      curl_close($curl);
      // 将文件写入获得的数据
      $filename = $path . $saveName;
      $write    = @fopen($filename, "w");
      if (false == $write) {
         return false;
      }
      if (fwrite($write, $file) == false) {
         return false;
      }
      if (fclose($write) == false) {
         return false;
      }
      //$url='http://wx.qlogo.cn/mmopen/oPM8qm1ZTKobh3xER5pLdGtVCf3ZDvRveWutFHrOvZAr4GWKBAPOSFFotLcluTy9ghj2D7prnqSDxgfb0eJYicL1ErPTbRrEib/0';
      //put_file_from_url_content($url, date('His').'.jpg', './');
   }
}
