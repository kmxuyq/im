<?php
/**
 * 会员聊天
 *
 * @好商城V4 (c) 2015-2016 33hao Inc.
 * @license    http://www.33hao.com
 * @link       交流群号：216611541
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('InShopNC') or exit('Access Invalid!');
class member_chatControl extends BaseMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * node连接参数
     */
    public function get_node_infoOp()
    {
        $this->output_data                = array('node_chat' => C('node_chat'), 'node_site_url' => C('node_site_url'), 'resource_site_url' => RESOURCE_SITE_URL);
        $model_chat                       = Model('web_chat');
        $member_id                        = $this->member_info['member_id'];
        $member_info                      = $model_chat->getMember($member_id);
        $this->output_data['member_info'] = $member_info;
        $u_id                             = intval($_GET['u_id']);
        if ($u_id > 0) {
            $member_info                    = $model_chat->getMember($u_id);
            $this->output_data['user_info'] = $member_info;
        }
        $goods_id = intval($_GET['chat_goods_id']);
        if ($goods_id > 0) {
            $goods                           = $model_chat->getGoodsInfo($goods_id);
            $this->output_data['chat_goods'] = $goods;
        }
        $this->output_data($this->output_data);
    }

    /**
     * 最近联系人
     */
    public function get_user_listOp()
    {
        $member_list = array();
        $model_chat  = Model('web_chat');

        $member_id   = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];
        $n           = intval($_POST['n']);
        if ($n < 1) {
            $n = 50;
        }

        if (intval($_POST['recent']) != 1) {
            $member_list = $model_chat->getFriendList(array('friend_frommid' => $member_id), $n, $member_list);
        }
        $add_time                   = date("Y-m-d");
        $add_time30                 = strtotime($add_time) - 60 * 60 * 24 * 30;
        $member_list                = $model_chat->getRecentList(array('f_id' => $member_id, 'add_time' => array('egt', $add_time30)), 10, $member_list);
        $member_list                = $model_chat->getRecentFromList(array('t_id' => $member_id, 'add_time' => array('egt', $add_time30)), 10, $member_list);
        $member_info                = array();
        $member_info                = $model_chat->getMember($member_id);
        $node_info                  = array();
        $node_info['node_chat']     = C('node_chat');
        $node_info['node_site_url'] = NODE_SITE_URL;
        $this->output_data(array('node_info' => $node_info, 'member_info' => $member_info, 'list' => $member_list));
    }

    /**
     * 会员信息
     *
     */
    public function get_infoOp()
    {
        $val        = '';
        $member     = array();
        $model_chat = Model('web_chat');
        $types      = array('member_id', 'member_name', 'store_id', 'member');
        $key        = $_POST['t'];
        $member_id  = intval($_POST['u_id']);
        if ($member_id > 0 && trim($key) != '' && in_array($key, $types)) {
            $member_info = $model_chat->getMember($member_id);
            $this->output_data(array('member_info' => $member_info));
        } else {
            output_error('参数错误');
        }
    }

    /**
     * 发消息
     *
     */
    public function send_msgOp()
    {
        $member      = array();
        $model_chat  = Model('web_chat');
        $member_id   = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];
        $t_id        = intval($_POST['t_id']);
        $t_name      = trim($_POST['t_name']);
        $member      = $model_chat->getMember($t_id);
        if ($t_name != $member['member_name']) {
            output_error('接收消息会员账号错误');
        }

        $msg           = array();
        $msg['f_id']   = $member_id;
        $msg['f_name'] = $member_name;
        $msg['t_id']   = $t_id;
        $msg['t_name'] = $t_name;
        $msg['t_msg']  = trim($_POST['t_msg']);

        $str             = html_entity_decode($msg['t_msg']);
        $str             = str_replace('&lt;', '<', $str);
        $str             = str_replace('&gt;', '/>', $str);
        $str             = str_replace('&quot;', '"', $str);
        $msg['t_msg']    = $str;
        $msg['goods_id'] = intval($_POST['chat_goods_id']);
        if ($msg['t_msg'] != '') {
            $chat_msg = $model_chat->addMsg($msg);
        }

        if ($chat_msg['m_id']) {
            $goods_id = intval($_POST['chat_goods_id']);
            if ($goods_id > 0) {
                $goods                  = $model_chat->getGoodsInfo($goods_id);
                $chat_msg['chat_goods'] = $goods;
            }
            $this->output_data(array('msg' => $chat_msg));
        } else {
            output_error('发送失败，请稍后重新发送');
        }
    }

    /**
     * 删除最近联系人消息
     *
     */
    public function del_msgOp()
    {
        $model_chat        = Model('web_chat');
        $member_id         = $this->member_info['member_id'];
        $t_id              = intval($_POST['t_id']);
        $condition         = array();
        $condition['f_id'] = $member_id;
        $condition['t_id'] = $t_id;
        $model_chat->delChatMsg($condition);
        $condition         = array();
        $condition['t_id'] = $member_id;
        $condition['f_id'] = $t_id;
        $model_chat->delChatMsg($condition);
        $this->output_data(1);
    }

    /**
     * 商品图片和名称
     *
     */
    public function get_goods_infoOp()
    {
        // $model_chat = Model('web_chat');
        // $goods_id   = intval($_POST['goods_id']);
        // $goods      = $model_chat->getGoodsInfo($goods_id);
        // $this->output_data(array('goods' => $goods));
        $model_chat = Model('web_chat');
        $goods_id   = intval($_GET['goods_id']);
        $goods      = $model_chat->getGoodsInfo($goods_id);
        $this->json($goods);
    }
    /**
     * jsonP
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
     * 未读消息查询
     *
     */
    public function get_msg_countOp()
    {
        $model_chat           = Model('web_chat');
        $member_id            = $this->member_info['member_id'];
        $condition            = array();
        $condition['t_id']    = $member_id;
        $condition['r_state'] = 2;
        $n                    = $model_chat->getChatMsgCount($condition);
        $this->output_data($n);
    }

    /**
     * 聊天记录查询
     *
     */
    public function get_chat_logOp()
    {
        $member_id       = $this->member_info['member_id'];
        $t_id            = intval($_POST['t_id']);
        $goods_id        = intval($_POST['goods_id']);
        $add_time_to     = date("Y-m-d");
        $time_from       = array();
        $time_from['7']  = strtotime($add_time_to) - 60 * 60 * 24 * 7;
        $time_from['15'] = strtotime($add_time_to) - 60 * 60 * 24 * 15;
        $time_from['30'] = strtotime($add_time_to) - 60 * 60 * 24 * 30;

        $key = $_POST['t'];
        if (trim($key) != '' && array_key_exists($key, $time_from)) {
            $model_chat    = Model('web_chat');
            $list          = array();
            $condition_sql = " add_time >= '" . $time_from[$key] . "' and goods_id = '" . $goods_id . "' ";
            $condition_sql .= " and ((f_id = '" . $member_id . "' and t_id = '" . $t_id . "') or (f_id = '" . $t_id . "' and t_id = '" . $member_id . "'))";
            $add_time   = date("Y-m-d");
            $add_time30 = strtotime($add_time) - 60 * 60 * 24 * 30;
            $list       = $model_chat->getLogList($condition_sql, $this->page);
            foreach ($list as $key => $value) {
                $list[$key]['add_time'] = date('H:i', $value['add_time']);
                // if (preg_match('/^<div*/', $value['t_msg'])) {

                //     $list[$key]['t_msg'] = '产品链接';
                // }
            }

            $total_page = $model_chat->gettotalpage();
            $this->output_data(array('list' => $list), $this->mobile_page($total_page));
            // $this->output_data(array('list' => $list));
        }
    }

    public function output_data($datas, $extend_data = array())
    {
        $data         = array();
        $data['code'] = 200;

        if (!empty($extend_data)) {
            $data = array_merge($data, $extend_data);
        }

        $data['datas'] = $datas;

        if (!empty($_GET['callback'])) {
            echo $_GET['callback'] . '(' . json_encode($data) . ')';die;
        } else {
            echo json_encode($data);die;
        }
    }

    public function output_error($message, $extend_data = array())
    {
        $datas = array('error' => $message);
        output_data($datas, $extend_data);
    }

    public function mobile_page($page_count)
    {
        //输出是否有下一页
        $extend_data  = array();
        $current_page = intval($_GET['curpage']);
        if ($current_page <= 0) {
            $current_page = 1;
        }
        if ($current_page >= $page_count) {
            $extend_data['hasmore'] = false;
        } else {
            $extend_data['hasmore'] = true;
        }
        $extend_data['page_total'] = $page_count;
        return $extend_data;
    }
    /**
     * 发送图片
     */

    public function uploadOp()
    {
        $upload = new UploadFile();
        $type   = trim($_GET['type']);
        if ('' == $type) {
            die(json_encode(array('status' => 0, 'msg' => '参数错误')));
        }
        $rpath = $type . '/' . date('y/m') . '/';
        if (!is_dir(BASE_UPLOAD_PATH . DS . $rpath)) {
            @mkdir(BASE_UPLOAD_PATH . DS . $rpath, 0755, true); //创建目录
        }
        $upload->set('default_dir', $rpath); //设置上传目录
        $upload->set('max_size', 1 * 1024); //设置上传文件大小
        $upload->set('fprefix', $type); //文件名前缀
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile($type);
        if (!$result) {
            die(json_encode(array('status' => 0, 'msg' => $upload->error)));
        }

        $img_path = $upload->getSysSetPath() . DS . $rpath . $upload->file_name;

        $data                 = array();
        $data['thumb_name']   = C('base_site_url') . '/data/upload/' . $img_path;
        $data['thumb_size']   = getimagesize(C('base_site_url') . '/data/upload/' . $img_path);
        $data['thumb_width']  = $data['thumb_size'][0] / 100;
        $data['thumb_height'] = $data['thumb_size'][1] / 100;
        $data['name']         = $img_path;
        $data['status']       = 1;
        die(json_encode($data));
    }
}
