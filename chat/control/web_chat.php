<?php
/**
 * web_chat
 *
 *
 */

defined('InShopNC') or exit('Access Invalid!');

class web_chatControl extends BaseControl
{
    public function __construct()
    {
        if ($_GET['op'] != 'login' && $_GET['op'] != 'login_act') {
            self::checkLogin();
        }
        parent::__construct();
        Language::read('member_chat');
        if (strtoupper(CHARSET) == 'GBK') {
            $_GET  = Language::getGBK($_GET);
            $_POST = Language::getGBK($_POST);
        }
    }
    /**
     * 验证会员是否登录
     *
     */
    protected function checkLogin()
    {
        if ('1' !== $_SESSION['is_login']) {
            if (trim($_GET['op']) == 'favoritesgoods' || trim($_GET['op']) == 'favoritesstore') {
                $lang = Language::getLangContent('UTF-8');
                echo json_encode(array('done' => false, 'msg' => $lang['no_login']));
                die;
            }
            $ref_url = request_uri();
            if ($_GET['inajax']) {
                showDialog('', '', 'js', "login_dialog();", 200);
            } else {
                @header("location: index.php?act=web_chat&op=login&ref_url=" . urlencode($ref_url));
            }
            exit;
        }
    }
    /**
     * 会员登录模板页面
     * @Author   xuyq
     * @DateTime 2017-02-22T17:47:12+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public static function loginOp($value = '')
    {
        if ('1' == $_SESSION['is_login']) {
            @header("location: index.php?act=web_chat&op=messagelist");
            exit;
        }
        Tpl::output('nchash', getNchash());
        Tpl::setLayout('null_layout');
        Tpl::showpage('login');
    }
    public function login_actOp()
    {
        $error           = array();
        $error['status'] = '0';
        $error['info']   = '';
        $result          = chksubmit(true, true, 'num');
        if ($result) {
            if ($result === -11) {
                $error['info'] = '用户名或密码错误';
                $error['name'] = 'seller_name';
                echo json_encode($error);
                exit();
            } elseif ($result === -12) {
                $error['info'] = '验证码错误';
                $error['name'] = 'captcha';
                echo json_encode($error);
                exit();
            }
        } else {
            $error['info'] = '非法提交';
            echo json_encode($error);
            exit();
        }

        $model_seller = Model('seller');
        $seller_info  = $model_seller->getSellerInfo(array('seller_name' => $_POST['seller_name']));
        if ($seller_info) {

            $model_member = Model('member');
            $member_info  = $model_member->getMemberInfo(
                array(
                    'member_id'     => $seller_info['member_id'],
                    'member_passwd' => md5($_POST['password']),
                )
            );
            if ($member_info) {
                // 更新卖家登陆时间
                $model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));

                $model_seller_group = Model('seller_group');
                $seller_group_info  = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));

                $model_store = Model('store');
                $store_info  = $model_store->getStoreInfoByID($seller_info['store_id']);

                $_SESSION['is_login']     = '1';
                $_SESSION['member_id']    = $member_info['member_id'];
                $_SESSION['member_name']  = $member_info['member_name'];
                $_SESSION['member_email'] = $member_info['member_email'];
                $_SESSION['is_buy']       = $member_info['is_buy'];
                $_SESSION['avatar']       = $member_info['member_avatar'];

                $_SESSION['grade_id']        = $store_info['grade_id'];
                $_SESSION['seller_id']       = $seller_info['seller_id'];
                $_SESSION['seller_name']     = $seller_info['seller_name'];
                $_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);
                $_SESSION['store_id']        = intval($seller_info['store_id']);
                $_SESSION['store_name']      = $store_info['store_name'];
                $_SESSION['is_own_shop']     = (bool) $store_info['is_own_shop'];
                $_SESSION['bind_all_gc']     = (bool) $store_info['bind_all_gc'];
                $_SESSION['seller_limits']   = explode(',', $seller_group_info['limits']);
                if ($seller_info['is_admin']) {
                    $_SESSION['seller_group_name'] = '管理员';
                    $_SESSION['seller_smt_limits'] = false;
                } else {
                    $_SESSION['seller_group_name'] = $seller_group_info['group_name'];
                    $_SESSION['seller_smt_limits'] = explode(',', $seller_group_info['smt_limits']);
                }
                if (!$seller_info['last_login_time']) {
                    $seller_info['last_login_time'] = TIMESTAMP;
                }
                $_SESSION['seller_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);
                if (!empty($seller_info['seller_quicklink'])) {
                    $quicklink_array = explode(',', $seller_info['seller_quicklink']);
                    foreach ($quicklink_array as $value) {
                        $_SESSION['seller_quicklink'][$value] = $value;
                    }
                }
                $error['status'] = 1;
                $error['info']   = '登录成功';
                $error['url']    = 'index.php?act=web_chat&op=messagelist';
                echo json_encode($error);
                exit();
            } else {
                $error['info'] = '用户名或密码错误';
                $error['name'] = 'seller_name';
                echo json_encode($error);
                exit();
            }
        } else {
            $error['info'] = '用户名或密码错误';
            $error['name'] = 'seller_name';
            echo json_encode($error);
            exit();
        }
    }
    /**
     * add msg
     *
     */
    public function send_msgOp()
    {
        $member     = array();
        $model_chat = Model('web_chat');
        if (empty($_POST)) {
            $_POST = $_GET;
        }

        $member_id   = $_SESSION['member_id'];
        $member_name = $_SESSION['member_name'];
        $f_id        = intval($_POST['f_id']);
        $t_id        = intval($_POST['t_id']);
        $t_name      = trim($_POST['t_name']);
        if (($member_id < 1) || ($member_id != $f_id)) {
            $this->error(Language::get('nc_member_chat_login'));
        }

        $member = $model_chat->getMember($t_id);
        // if ($t_name != $member['member_name']) {
        //     $this->error(Language::get('nc_member_chat_name_error'));
        // }

        $msg             = array();
        $msg['f_id']     = $f_id;
        $msg['f_name']   = $member_name;
        $msg['t_id']     = $t_id;
        $msg['t_name']   = $t_name;
        $msg['t_msg']    = trim($_POST['t_msg']);
        $msg['goods_id'] = intval($_POST['goods_id']);
        if ($msg['t_msg'] != '') {
            $chat_msg = $model_chat->addMsg($msg);
        }

        if ($chat_msg['m_id']) {
            $this->json($chat_msg);
        } else {
            $this->error(Language::get('nc_member_chat_add_error'));
        }
    }
    /**
     * 删除会员消息
     * @Author   xuyq
     * @DateTime 2017-02-21T14:25:44+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function del_user_msgOp($u_id = '')
    {
        $u_id       = intval($_GET['f_id']);
        $model_chat = Model('web_chat');
        $model_chat->del_user_msg($u_id);
    }
    /**
     * friends info
     *
     */
    public function get_user_listOp()
    {
        $member_list = array();
        $model_chat  = Model('web_chat');
        $member_id   = $_SESSION['member_id'];
        $member_name = $_SESSION['member_name'];
        $f_id        = intval($_GET['f_id']);
        if ($member_id < 1) {
            $this->error(Language::get('nc_member_chat_login'));
        }

        $n = intval($_GET['n']);
        if ($n < 1) {
            $n = 50;
        }

        $member_list = $model_chat->getFriendList(array('friend_frommid' => $f_id), $n, $member_list);
        $add_time    = date("Y-m-d");
        $add_time30  = strtotime($add_time) - 60 * 60 * 24 * 30;
        // $member_list = $model_chat->getRecentList(array('r_state' => array('in', '1, 2'), 'f_id' => $f_id, 'add_time' => array('egt', $add_time30)), 10, $member_list);
        $member_list = $model_chat->getRecentFromList(array('r_state' => array('in', '1, 2'), 't_id' => $f_id, 'add_time' => array('egt', $add_time30)), 10, $member_list);
        $this->json($member_list);
    }
    /**
     * 商家客服
     *
     */
    public function get_seller_listOp()
    {
        $member_list = array();
        $model_chat  = Model('web_chat');
        $member_id   = $_SESSION['member_id'];
        $member_name = $_SESSION['member_name'];
        $store_id    = $_SESSION['store_id'];
        $f_id        = intval($_GET['f_id']);
        if ($member_id < 1) {
            return Language::get('nc_member_chat_login');
        }

        $n = intval($_GET['n']);
        if ($n < 1) {
            $n = 50;
        }

        if (empty($_SESSION['seller_list'])) {
            $member_list             = $model_chat->getSellerList(array('store_id' => $store_id), $n, $member_list);
            $_SESSION['seller_list'] = $member_list;
        } else {
            $member_list = $_SESSION['seller_list'];
        }
        $add_time   = date("Y-m-d");
        $add_time30 = strtotime($add_time) - 60 * 60 * 24 * 30;
        // $member_list = $model_chat->getRecentList(array('r_state' => array('in', '1, 2'), 'f_id' => $f_id, 'add_time' => array('egt', $add_time30)), 10, $member_list);
        // $member_list = $model_chat->getRecentFromList(array('r_state' => array('in', '1, 2'), 't_id' => $f_id, 'add_time' => array('egt', $add_time30)), 10, $member_list);
        $this->json($member_list);
    }
    /**
     * member info
     *
     */
    public function get_infoOp()
    {
        if ($_SESSION['member_id'] < 1) {
            $this->error(Language::get('nc_member_chat_login'));
        }

        $val        = '';
        $member     = array();
        $model_chat = Model('web_chat');
        $types      = array('member_id', 'member_name', 'store_id', 'member');
        $key        = $_GET['t'];
        $member_id  = intval($_GET['u_id']);
        if (trim($key) != '' && in_array($key, $types)) {
            $member = $model_chat->getMember($member_id);
            $this->json($member);
        }
    }
    /**
     * chat log
     *
     */
    public function chat_logOp()
    {
        $member_id = $_SESSION['member_id'];
        $f_id      = intval($_GET['uid']);
        $goods_id  = intval($_GET['goods_id']);
        $t_id      = $member_id;
        $page      = 10;
        if (($member_id < 1)) {
            $this->error(Language::get('nc_member_chat_login'));
        }

        if ($page < 1) {
            $page = 20;
        }

        $add_time_to     = date("Y-m-d");
        $time_from       = array();
        $time_from['7']  = strtotime($add_time_to) - 60 * 60 * 24 * 7;
        $time_from['15'] = strtotime($add_time_to) - 60 * 60 * 24 * 15;
        $time_from['30'] = strtotime($add_time_to) - 60 * 60 * 24 * 30;
        $key             = $_GET['t'];
        $model_chat      = Model('web_chat');
        $chat_log        = array();
        $list            = array();
        $condition_sql   = " add_time >= '" . $time_from[$key] . "' and goods_id = '" . $goods_id . "' ";
        $condition_sql .= " and ((f_id = '" . $f_id . "' and t_id = '" . $t_id . "') or (f_id = '" . $t_id . "' and t_id = '" . $f_id . "'))";
        $list = $model_chat->getLogList($condition_sql, $page);
        foreach ($list as $key => $value) {
            $list[$key]['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
            // if (preg_match('/^<div*/', $value['t_msg'])) {

            //     $list[$key]['t_msg'] = '产品链接';
            // }
        }

        $chat_log['list']       = $list;
        $chat_log['total_page'] = $model_chat->gettotalpage();
        $goods_id               = intval($_GET['goods_id']);
        $goods                  = $model_chat->getGoodsInfo($goods_id);
        Tpl::output('goods', $goods);
        Tpl::output('chat_log', $chat_log['list']);
        Tpl::showpage('chat_log', 'null_layout');
    }
    /**
     * chat log
     *
     */
    public function get_chat_logOp()
    {
        $member_id = $_SESSION['member_id'];
        $f_id      = intval($_GET['f_id']);
        $goods_id  = intval($_GET['goods_id']);
        $t_id      = intval($_GET['t_id']);
        $unread    = intval($_GET['unread']);
        $is_chat   = intval($_GET['is_chat']);
        $t_id      = $member_id;
        $curpage   = intval($_GET['curpages']);
        if (($member_id < 1)) {
            $this->error(Language::get('nc_member_chat_login'));
        }

        if ($page < 1) {
            $page = 99999;
        }

        $add_time_to     = date("Y-m-d");
        $time_from       = array();
        $time_from['7']  = strtotime($add_time_to) - 60 * 60 * 24 * 7;
        $time_from['15'] = strtotime($add_time_to) - 60 * 60 * 24 * 15;
        $time_from['30'] = strtotime($add_time_to) - 60 * 60 * 24 * 30;

        $key           = $_GET['t'];
        $model_chat    = Model('web_chat');
        $chat_log      = array();
        $list          = array();
        $condition_sql = " add_time >= '" . $time_from[$key] . "' and goods_id = '" . $goods_id . "' ";
        $condition_sql .= " and ((f_id = '" . $f_id . "' and t_id = '" . $t_id . "') or (f_id = '" . $t_id . "' and t_id = '" . $f_id . "'))";
        $list = $model_chat->getLogList($condition_sql, $page, $offset = $unread, $is_chat = $is_chat);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $list[$key]['add_time'] = date('H:i', $value['add_time']);
                // if (preg_match('/^<div*/', $value['t_msg'])) {

                //     $list[$key]['t_msg'] = '产品链接';
                // }
            }
        }
        $page      = new page();
        $total_num = $model_chat->gettotalpage();
        $page->setTotalNum($total_num);
        $page->setEachNum(10);
        $page->setNowPage($_GET['curpage']);
        if (!isset($_GET['curpage'])) {
            $curpage = 1;
        } else {
            $curpage = $_GET['curpage'];
        }
        $size = 10; //每页显示的记录数
        if (!empty($list)) {
            $list = array_slice($list, ($curpage - 1) * $size, $size);
        }
        $this->json($list);
    }
    /**
     * 商品图片和名称
     *
     */
    public function get_goods_infoOp()
    {
        $model_chat = Model('web_chat');
        $goods_id   = intval($_GET['goods_id']);
        $goods      = $model_chat->getGoodsInfo($goods_id);
        $this->json($goods);
    }
    /**
     * 店铺推荐商品图片和名称
     *
     */
    public function get_goods_listOp()
    {
        $s_id = intval($_GET['s_id']);
        if ($s_id > 0) {
            $model_goods = Model('goods');
            $list        = $model_goods->getGoodsCommendList($s_id, 4);
            if (!empty($list) && is_array($list)) {
                foreach ($list as $k => $v) {
                    $v['goods_promotion_price'] = ncPriceFormat($v['goods_promotion_price']);
                    $v['url']                   = urlShop('goods', 'index', array('goods_id' => $v['goods_id']));
                    $v['pic']                   = thumb($v, 60);
                    $list[$k]                   = $v;
                }
            }
            $this->json($list);
        }
    }
    /**
     * get session
     *
     */
    public function get_sessionOp()
    {
        $key = $_GET['key'];
        $val = '';
        if (!empty($_SESSION[$key])) {
            $val = $_SESSION[$key];
        }

        echo $val;
        exit;
    }
    /**
     * json
     *
     */
    public function json($json)
    {
        if (strtoupper(CHARSET) == 'GBK') {
            $json = Language::getUTF8($json); //GBKtoUTF-8
        }
        echo $_GET['callback'] . '(' . json_encode($json) . ')';
        exit;
    }
    /**
     * error
     *
     */
    public function error($msg = '')
    {
        $this->json(array('error' => $msg));
    }
    /*获取消息列表*/
    public function messagelistOp()
    {
        $where             = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (!$_SESSION['seller_is_admin']) {
            $where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);
        }
        $model_storemsg = Model('store_msg');
        $msg_list       = $model_storemsg->getStoreMsgList($where, ' * ', 999);

        // 整理数据
        if (!empty($msg_list)) {
            foreach ($msg_list as $key => $val) {
                if ($val['sm_readids']) {
                    $msg_list[$key]['sm_readids'] = explode(',', $val['sm_readids']);
                }
            }
        }
        foreach ($msg_list as $key => $value) {
            if (empty($value['sm_readids']) || !in_array($_SESSION['seller_id'], $value['sm_readids'])) {
                $msg_list[$key] = $value;
            } else {
                $msg_list[$key] = '';
            }
        }
        $msg_list = array_filter($msg_list, create_function('$v', 'return !empty($v);'));
        $countnum = count($msg_list);
        Tpl::output('store_msg_num', $countnum);
        self::checkStoreMsg();
        Tpl::output('seller_list', $seller_list);
        Tpl::output('user_list', $user_list);
        Tpl::output('msg_list', $msg_list);
        Tpl::output('nchash', getNchash());
        Tpl::setLayout('null_layout');
        Tpl::showpage('messagelist');
    }

    /**
     * 消息详细
     */
    public function msg_infoOp()
    {
        $sm_id = intval($_GET['sm_id']);
        if ($sm_id <= 0) {
            showMessage(L('wrong_argument'), '', '', 'succ');
        }
        $model_storemsg = Model('store_msg');
        $where          = array();
        $where['sm_id'] = $sm_id;
        if ($_SESSION['seller_smt_limits'] !== false) {
            $where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);
        }
        $msg_info = $model_storemsg->getStoreMsgInfo($where);
        if (empty($msg_info)) {
            showMessage(L('wrong_argument'), '', '', 'succ');
        }

        // 验证时候已读
        $sm_readids = explode(',', $msg_info['sm_readids']);
        if (!in_array($_SESSION['seller_id'], $sm_readids)) {
            // 消息阅读表插入数据
            $condition              = array();
            $condition['seller_id'] = $_SESSION['seller_id'];
            $condition['sm_id']     = $sm_id;
            Model('store_msg_read')->addStoreMsgRead($condition);

            $update               = array();
            $sm_readids[]         = $_SESSION['seller_id'];
            $update['sm_readids'] = implode(',', $sm_readids) . ', ';
            $model_storemsg->editStoreMsg(array('sm_id' => $sm_id), $update);
        }

        // 清除店铺消息数量缓存
        setNcCookie('storemsgnewnum' . $_SESSION['seller_id'], 0, -3600);
        Tpl::output('msg_list', $msg_info);
        Tpl::showpage('inform', 'null_layout');
    }
    /*chat_online 页面*/
    public function chat_onlineOp($uid = '')
    {
        $u_id       = intval($_GET['uid']);
        $model_chat = Model('web_chat');
        $model_chat->is_read_msg($u_id);
        if (is_numeric($_GET['goods_id']) && $_GET['goods_id'] != 0 && $_GET['goods_id'] != 10086 && $_GET['goods_id'] != 'undefined') {
            $is_goods = 1;
        } else {
            $is_goods = 0;
        }
        $goods_id = intval($_GET['goods_id']);
        $goods    = $model_chat->getGoodsInfo($goods_id);
        Tpl::output('goods', $goods);
        Tpl::output('is_goods', $is_goods);
        Tpl::output('nchash', getNchash());
        Tpl::setLayout('null_layout');
        Tpl::showpage('chat_online');
    }
    /**
     * 商家消息数量
     */
    private function checkStoreMsg()
    {
        //判断cookie是否存在
        // $cookie_name = 'storemsgnewnum' . $_SESSION['seller_id'];
        // if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >= 0) {
        //     $countnum = intval(cookie($cookie_name));
        // } else {
        $where               = array();
        $where['store_id']   = $_SESSION['store_id'];
        $where['sm_readids'] = array('notlike', '%,' . $_SESSION['seller_id'] . ',%');
        if (false !== $_SESSION['seller_smt_limits']) {
            $where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);
        }
        $countnum = Model('store_msg')->getStoreMsgCount($where);
        // var_dump($countnum)
        // setNcCookie($cookie_name, intval($countnum), 2 * 3600); //保存2小时
        // }
        Tpl::output('store_msg_num', $countnum);
    }
    /**
     * 退出操作
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function logoutOp()
    {
        Language::read("home_login_index");
        $lang = Language::getLangContent();
        // 清理消息COOKIE
        setNcCookie('msgnewnum' . $_SESSION['member_id'], '', -3600);
        $store_id = $_SESSION['route_store_id'] ? $_SESSION['route_store_id'] : $_SESSION['share_store_id'];
        session_unset();
        session_destroy();
        setNcCookie('cart_goods_num', '', -3600);
        setNcCookie('mctoken', '', -3600, ' / ', ' . gellefreres . com');
        echo 1;
    }
}
