<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class loginControl extends BaseHomeControl {

   public function __construct() {
      parent::__construct();
      //微信进入自动登陆
      if (!empty($_GET["code"]) && !isset($_SESSION["member_id"])) {
         $code   = $_GET["code"];
         $status = Model('member')->weixin_login_handle($code);
      }

      Tpl::output('hidden_nctoolbar', 1);
   }

   /**
    * 登录操作
    *
    */
   public function indexOp() {
      if (isset($_SESSION["member_id"]) && empty($_GET["ref_url"])) {
         redirect(BASE_SITE_URL . '/wap_shop/?act=member&op=home');
      }
      Language::read("home_login_index");
      $lang         = Language::getLangContent();
      $model_member = Model('member');
      //检查登录状态
      $model_member->checkloginMember();

      if (1 == $_GET['inajax'] && C('captcha_status_login') == '1') {
         $script = "document.getElementById('codeimage').src='" . APP_SITE_URL . "/index.php?act=seccode&op=makecode&nchash=" . getNchash() . "&t=' + Math.random();";
      }
      $result = chksubmit(true, C('captcha_status_login'), 'num'); //echo "<meta charset='utf-8'/><pre>";print_r($result);echo "</pre>";exit;
      if (false !== $result) {
         if (-11 === $result) {
            showDialog($lang['login_index_login_illegal'], '', 'error', $script);
         } elseif (-12 === $result) {
            //showDialog($lang['login_index_wrong_checkcode'],'','error',$script);
         }
         //
         if (process::islock('login')) {
            //showDialog($lang['nc_common_op_repeat'],WAP_SHOP_SITE_URL,'','error',$script);
            showDialog($lang['nc_common_op_repeat'], WAP_SITE_URL, '', 'error', $script);
         }
         $obj_validate                = new Validate();
         $obj_validate->validateparam = array(
            array("input" => $_POST["user_name"], "require" => "true", "message" => $lang['login_index_username_isnull']),
            array("input" => $_POST["password"], "require" => "true", "message" => $lang['login_index_password_isnull']),
         );
         //print_r($_POST);exit;
         $error = $obj_validate->validate();
         if ('' != $error) {
            //showDialog($error,WAP_SHOP_SITE_URL,'error',$script);
            showDialog($error, WAP_SITE_URL, 'error', $script);
         }
         $array                  = array();
         $array['member_name']   = $_POST['user_name'];
         $array['member_passwd'] = md5($_POST['password']);
         $member_info            = $model_member->getMemberInfo($array);
         if (is_array($member_info) and !empty($member_info)) {
            if (!$member_info['member_state']) {

               showDialog($lang['login_index_account_stop'], '' . 'error', $script);
            }
         } else {
            process::addprocess('login');
            showDialog($lang['login_index_login_fail'], '', 'error', $script);
         }
         $model_member->createSession($member_info);
         process::clear('login');

         // cookie中的cart存入数据库
         Model('cart')->mergecart($member_info, $_SESSION['store_id']);

         // cookie中的浏览记录存入数据库
         Model('goods_browse')->mergebrowse($_SESSION['member_id'], $_SESSION['store_id']);

         if (1 == $_GET['inajax']) {
            showDialog('', '' == $_POST['ref_url'] ? 'reload' : $_POST['ref_url'], 'js');
         } else {
            redirect($_POST['ref_url']);
         }
      } else {

         //登录表单页面
         $_pic = @unserialize(C('login_pic'));
         if ('' != $_pic[0]) {
            Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . $_pic[array_rand($_pic)]);
         } else {
            Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . rand(1, 4) . '.jpg');
         }

         if (empty($_GET['ref_url'])) {
            $ref_url = getReferer();
            if (!preg_match('/act=login&op=logout/', $ref_url)) {
               $_GET['ref_url'] = $ref_url;
            }
         }
         setNcCookie('ref_url', urlencode($_GET['ref_url']), 3600);
         Tpl::output('html_title', C('site_name') . ' - ' . $lang['login_index_login']);
         if (1 == $_GET['inajax']) {
            Tpl::showpage('login_inajax', 'null_layout');
         } else {
            Tpl::showpage('login');
         }
      }
   }
   /**
    * login操作
    *
    * @param int $id 记录ID
    * @return array $rs_row 返回数组形式的查询结果
    */
   public function loginOp() {
    if ($_SESSION['member_id']) {
      redirect('/wap_shop/index.php?act=goods&op=index&goods_id=104642');
    }
   }
   /**
    * 退出操作
    *
    * @param int $id 记录ID
    * @return array $rs_row 返回数组形式的查询结果
    */
   public function logoutOp() {
      Language::read("home_login_index");
      $lang = Language::getLangContent();
      // 清理消息COOKIE
      setNcCookie('msgnewnum' . $_SESSION['member_id'], '', -3600);
      $store_id = $_SESSION['route_store_id'] ? $_SESSION['route_store_id'] : $_SESSION['share_store_id'];
      session_unset();
      session_destroy();
      setNcCookie('cart_goods_num', '', -3600);
      setNcCookie('mctoken', '', -3600, '/', '.gellefreres.com');
      if (empty($_GET['ref_url'])) {
         $ref_url = getReferer();
      } else {
         $ref_url = $_GET['ref_url'];
      }
      redirect('index.php?act=show_store&op=index&store_id=' . $store_id);
   }

   /**
    * 会员注册页面
    *
    * @param
    * @return
    */
   public function registerOp() {
      //zmr>v30
      $zmr = intval($_GET['zmr']);
      if ($zmr > 0) {
         setcookie('zmr', $zmr);
      }

      Language::read("home_login_register");
      $lang         = Language::getLangContent();
      $model_member = Model('member');
      $model_member->checkloginMember();
      Tpl::output('html_title', C('site_name') . ' - ' . $lang['login_register_join_us']);
      Tpl::showpage('register');
   }

   /**
    * 会员添加操作
    *
    * @param
    * @return
    */
   public function usersaveOp() {
      //重复注册验证
      if (process::islock('reg')) {
         showDialog(Language::get('nc_common_op_repeat'));
      }
      Language::read("home_login_register");
      $lang         = Language::getLangContent();
      $model_member = Model('member');
      $model_member->checkloginMember();
      $result = chksubmit(true, C('captcha_status_register'), 'num');
      if ($result) {
         if (-11 === $result) {
            showDialog($lang['invalid_request'], '', 'error');
         } elseif (-12 === $result) {
            showDialog($lang['login_usersave_wrong_code'], '', 'error');
         }
      } else {
         showDialog($lang['invalid_request'], '', 'error');
      }
      $register_info                     = array();
      $register_info['username']         = $_POST['user_name'];
      $register_info['password']         = $_POST['password'];
      $register_info['password_confirm'] = $_POST['password_confirm'];
      $register_info['email']            = $_POST['email'];
      //添加奖励积分zmr>v30
      // $zmr=intval($_COOKIE['zmr']);
      // if($zmr>0)
      // {
      //    $pinfo=$model_member->getMemberInfoByID($zmr,'member_id');
      //    if(empty($pinfo))
      //    {
      //       $zmr=0;
      //    }
      // }
      // $register_info['inviter_id'] = $zmr;
      $register_info['inviter_id'] = 0;
      $member_info                 = $model_member->registerNotEmail($register_info);
      if (!isset($member_info['error'])) {
         $model_member->createSession($member_info, true);
         process::addprocess('reg');

         // cookie中的cart存入数据库
         Model('cart')->mergecart($member_info, $_SESSION['store_id']);

         // cookie中的浏览记录存入数据库
         Model('goods_browse')->mergebrowse($_SESSION['member_id'], $_SESSION['store_id']);

         /*$_POST['ref_url']   = (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_information&op=member');
         redirect($_POST['ref_url']);*/
         if (empty($_POST['ref_url'])) {
            $resiect_rul = WAP_SITE_URL;
         } else {
            $resiect_rul = $_POST['ref_url'];
         }
         redirect($resiect_rul);
      } else {
         showDialog($member_info['error']);
      }
   }
   /**
    * 会员名称检测
    *
    * @param
    * @return
    */
   public function check_memberOp() {
      /**
       * 实例化模型
       */
      $model_member = Model('member');

      $check_member_name = $model_member->getMemberInfo(array('member_name' => $_GET['user_name']));
      if (is_array($check_member_name) and count($check_member_name) > 0) {
         echo 'false';
      } else {
         echo 'true';
      }
   }

   /**
    * 电子邮箱检测
    *
    * @param
    * @return
    */
   public function check_emailOp() {
      $model_member       = Model('member');
      $check_member_email = $model_member->getMemberInfo(array('member_email' => $_GET['email']));
      if (is_array($check_member_email) and count($check_member_email) > 0) {
         echo 'false';
      } else {
         echo 'true';
      }
   }

   /**
    * 忘记密码页面
    */
   public function forget_passwordOp() {
      /**
       * 读取语言包
       */
      Language::read('home_login_register');
      $_pic = @unserialize(C('login_pic'));
      if ('' != $_pic[0]) {
         Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . $_pic[array_rand($_pic)]);
      } else {
         Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . rand(1, 4) . '.jpg');
      }
      Tpl::output('html_title', C('site_name') . ' - ' . Language::get('login_index_find_password'));
      Tpl::showpage('find_password');
   }

   /**
    * 找回密码的发邮件处理
    */
   public function find_passwordOp() {

      Language::read('home_login_register');
      $lang   = Language::getLangContent();
      $result = chksubmit(true, true, 'num');
      if (!$result) {
         exit('非法提交');
         // elseif ($result === -12){
         //    showDialog('验证码错误');
         //}
      }

      if (empty($_POST['username'])) {
         exit($lang['login_password_input_username']);
      }

      if (process::islock('forget')) {
         exit($lang['nc_common_op_repeat']);
      }

      $member_model = Model('member');
      $member       = $member_model->getMemberInfo(array('member_name' => $_POST['username']));
      if (empty($member) or !is_array($member)) {
         process::addprocess('forget');
         exit($lang['login_password_username_not_exists']);
      }

      if (empty($_POST['email'])) {
         exit($lang['login_password_input_email']);
      }

      if (strtoupper($_POST['email']) != strtoupper($member['member_email'])) {
         process::addprocess('forget');
         exit($lang['login_password_email_not_exists']);
      }
      process::clear('forget');
      //产生密码
      $new_password = rand(111111, 999999); //random(15);
      if (!($member_model->editMember(array('member_id' => $member['member_id']), array('member_passwd' => md5($new_password))))) {
         exit($lang['login_password_email_fail']);
      }

      $model_tpl             = Model('mail_templates');
      $tpl_info              = $model_tpl->getTplInfo(array('code' => 'reset_pwd'));
      $param                 = array();
      $param['site_name']    = C('site_name');
      $param['user_name']    = $_POST['username'];
      $param['new_password'] = $new_password;
      $param['site_url']     = WAP_SHOP_SITE_URL;
      $subject               = ncReplaceText($tpl_info['title'], $param);
      $message               = ncReplaceText($tpl_info['content'], $param);

      $email  = new Email();
      $result = $email->send_sys_email($_POST["email"], $subject, $message);
      exit('新密码已经发送至您的邮箱，请尽快登录并更改密码！');
   }

   /**
    * 邮箱绑定验证
    */
   public function bind_emailOp() {
      $model_member                   = Model('member');
      $uid                            = @base64_decode($_GET['uid']);
      $uid                            = decrypt($uid, '');
      list($member_id, $member_email) = explode(' ', $uid);

      if (!is_numeric($member_id)) {
         showMessage('验证失败', WAP_SHOP_SITE_URL, 'html', 'error');
      }

      $member_info = $model_member->getMemberInfo(array('member_id' => $member_id), 'member_email');
      if ($member_info['member_email'] != $member_email) {
         showMessage('验证失败', WAP_SHOP_SITE_URL, 'html', 'error');
      }

      $member_common_info = $model_member->getMemberCommonInfo(array('member_id' => $member_id));
      if (empty($member_common_info) || !is_array($member_common_info)) {
         showMessage('验证失败', WAP_SHOP_SITE_URL, 'html', 'error');
      }
      if (md5($member_common_info['auth_code']) != $_GET['hash'] || TIMESTAMP - $member_common_info['send_acode_time'] > 24 * 3600) {
         showMessage('验证失败', WAP_SHOP_SITE_URL, 'html', 'error');
      }

      $update = $model_member->editMember(array('member_id' => $member_id), array('member_email_bind' => 1));
      if (!$update) {
         showMessage('系统发生错误，如有疑问请与管理员联系', WAP_SHOP_SITE_URL, 'html', 'error');
      }

      $data                    = array();
      $data['auth_code']       = '';
      $data['send_acode_time'] = 0;
      $update                  = $model_member->editMemberCommon($data, array('member_id' => $_SESSION['member_id']));
      if (!$update) {
         showDialog('系统发生错误，如有疑问请与管理员联系');
      }
      showMessage('邮箱设置成功', 'index.php?act=member_security&op=index');

   }
   /**
    * 官网登陆接口，成功为1，失败为0
    */
   public function login_handleOp() {
      header("Access-Control-Allow-Origin: *");
      if (!empty($_POST)) {
         $model_member           = Model('member');
         $array                  = array();
         $array['member_name']   = $_POST['user_name'];
         $array['member_passwd'] = md5($_POST['password']);
         $member_info            = $model_member->getMemberInfo($array);
         if (is_array($member_info) and !empty($member_info)) {
            if ($member_info['member_state']) {
               process::addprocess('login');
            }
            $model_member->createSession($member_info);

            $status = array('status' => 1, 'member_info' => $member_info);
         } else {
            $status = array('status' => 0, 'member_info' => '');
         }
         echo json_encode($status);
      }
   }
   /**
    * 官网会员注册接口
    */
   public function register_handleOp() {
      header("Access-Control-Allow-Origin: *");
      if (!empty($_POST)) {
         $model_member                      = Model('member');
         $register_info                     = array();
         $register_info['username']         = $_POST['user_name'];
         $register_info['password']         = $_POST['password'];
         $register_info['password_confirm'] = $_POST['password_confirm'];
         $register_info['email']            = $_POST['email'];
         //添加奖励积分zmr>v30
         $zmr                         = intval($_COOKIE['zmr']);
         $register_info['inviter_id'] = $zmr;
         $member_info                 = $model_member->register($register_info);
         //如果返回的error不为空则表示有错，如：return array('error' => '邮箱已存在')， return array('error' => '注册失败');，error为空则为注册成功，返回的值为用户信息数组
         if (!isset($member_info['error'])) {
            $model_member->createSession($member_info, true);
            process::addprocess('reg');
            // cookie中的cart存入数据库
            Model('cart')->mergecart($member_info, $_SESSION['store_id']);
         }
         echo json_encode($member_info);
      }
   }

   public function weixin_login_testOp() {
      $openid = 'ocuxlxMWj0MVkfFgHxaIUDThHM1o';
      $status = Model('member')->weixin_login($openid);
   }
   public function weixin_register_testOp() {
      $member_info = Model('member')->weixin_register_model('suge', '123456', 'admin@admin.com', '.jpg', 1, '中国云南昆明', 'ocuxlxMWj0MVkfFgHxaIUDThHM1o');
      print_r($member_info);
   }
}
