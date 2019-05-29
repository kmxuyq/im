<?php
/**
 * chat
 *
 *
 *
 *

 */
defined('InShopNC') or exit('Access Invalid!');
class web_chatModel extends Model
{

    /**
     * get chat msg
     *
     * @param
     * @return array
     */
    public function getMsgList($condition = array(), $page = 10)
    {
        $result = $this->table('chat_msg')->where($condition)->page($page)->order('m_id')->select();
        return $result;
    }
    /**
     * add chat msg
     *
     * @param
     * @return array
     */
    public function addMsg($msg)
    {
        $msg['f_ip']     = getIp();
        $msg['r_state']  = '2'; //state:1--read ,2--unread
        $msg['add_time'] = time();
        $m_id            = $this->table('chat_msg')->insert($msg);
        if ($m_id > 0) {
            $msg['m_id'] = $m_id;
            unset($msg['r_state']);
            $this->table('chat_log')->insert($msg);
            $msg['m_id']     = $m_id;
            $msg['add_time'] = date('H:i', $msg['add_time']);
            $t_msg           = $msg['t_msg'];
            $goods_id        = 0;
            $goods_info      = array();
            $pattern         = '#' . SHOP_SITE_URL . '/index.php\?act=goods&amp;op=index&amp;goods_id=(\d+)$#';
            preg_match($pattern, $t_msg, $matches);
            if (intval($matches[1]) < 1) {
//伪静态
                $pattern = '#' . SHOP_SITE_URL . '/item-(\d+)\.html$#';
                preg_match($pattern, $t_msg, $matches);
            }
            $goods_id = intval($matches[1]);
            if ($goods_id >= 1) {
                $goods_info = $this->getGoodsInfo($goods_id);
                $goods_id   = intval($goods_info['goods_id']);
            }
            $msg['goods_id']   = $goods_id;
            $msg['goods_info'] = $goods_info;
            return $msg;
        } else {
            return 0;
        }
    }
    /**
     * member info
     *
     * @param
     * @return array
     */
    public function getMember($member_id)
    {
        if (intval($member_id) < 1) {
            return false;
        }
        $member = $this->getMemberInfo(array('member_id' => $member_id));
        return $member;
    }
    /**
     * update chat msg
     *
     * @param
     * @return bool
     */
    public function updateMsg($condition, $data)
    {
        $m_id = $condition['m_id'];
        if (intval($m_id) < 1) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('chat_msg')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }
    /**
     * get chat log
     *
     * @param
     * @return array
     */
    public function getLogList($condition = array(), $page = 10, $offset = 0, $is_chat = 0)
    {
        if ($is_chat == 1) {
            $list = $this->query('SELECT * FROM az_chat_log WHERE ' . $condition . ' ORDER BY m_id desc LIMIT 10 OFFSET ' . $offset . '');
            if (!empty($list)) {
                sort($list);
            }
        } else {
            $list = $this->table('chat_log')->where($condition)->page($page)->order('m_id desc')->select();
            if ($is_chat == 1 && $list) {
                sort($list);
            }

        }
        if (!empty($list) && is_array($list)) {
            foreach ($list as $k => $v) {
                $model_seller = Model('seller');
                $seller       = $model_seller->getSellerInfo(array('member_id' => $v['f_id']));
                $v['avatar']  = getMemberAvatarForID($v['f_id'], true);
                if ($seller) {

                    $v['msg_from'] = 'seller';
                } else {
                    $v['msg_from'] = 'member';
                }
                $v['time'] = date("H:i", $v['add_time']);
                $list[$k]  = $v;
            }
        }
        return $list;
    }
    /**
     * get friends
     *
     * @param
     * @return array
     */
    public function getFriendList($condition = array(), $page = 50, $member_list = array())
    {
        $list = $this->table('sns_friend')->where($condition)->page($page)->order('friend_addtime desc')->select();
        if (!empty($list) && is_array($list)) {
            foreach ($list as $k => $v) {
                $member             = array();
                $u_id               = $v['friend_tomid'];
                $member['u_id']     = $u_id;
                $member['u_name']   = $v['friend_tomname'];
                $member['avatar']   = getMemberAvatarForID($u_id, true);
                $member['friend']   = 1;
                $member_list[$u_id] = $member;
            }
        }
        return $member_list;
    }
    /**
     * 商家客服
     *
     * @param
     * @return array
     */
    public function getSellerList($condition = array(), $page = 50, $member_list = array())
    {
        $model_seller = Model('seller');
        $list         = $model_seller->getSellerList($condition, $page, 'seller_id desc');
        if (!empty($list) && is_array($list)) {
            $member_ids = array(); //会员编号数组
            foreach ($list as $k => $v) {
                $member                = array();
                $u_id                  = $v['member_id'];
                $member_ids[]          = $u_id;
                $member['u_id']        = $u_id;
                $member['u_name']      = '';
                $member['seller_id']   = $v['seller_id'];
                $member['seller_name'] = $v['seller_name'];
                $member['avatar']      = getMemberAvatarForID($u_id, true);
                $member['seller']      = 1;
                $member_list[$u_id]    = $member;
            }
            $model_member           = Model('member');
            $condition              = array();
            $condition['member_id'] = array('in', $member_ids);
            $m_list                 = $model_member->getMemberList($condition);
            if (!empty($m_list) && is_array($m_list)) {
                foreach ($m_list as $key => $value) {
                    $u_id                         = $value['member_id']; //会员编号
                    $member_list[$u_id]['u_name'] = $value['member_name'];
                }
            }
        }
        return $member_list;
    }
    /**
     * get recent msg
     *
     * @param
     * @return array
     */
    public function getRecentList($condition = array(), $limit = 5, $member_list = array())
    {
        $list = $this->getMemberRecentList($condition, '', $limit);
        if (!empty($list) && is_array($list)) {
            foreach ($list as $k => $v) {
                $member            = array();
                $u_id              = $v['t_id'];
                $new_msg           = $this->table('chat_msg')->field('t_msg')->where(array('f_id' => $v['f_id']))->order('m_id desc')->find();
                $member['new_msg'] = $new_msg['t_msg'];
                $member['u_id']    = $u_id;
                $member['u_name']  = $v['t_name'];
                $member['avatar']  = getMemberAvatarForID($u_id, true);
                $member['recent']  = 1;
                $member['time']    = date("y/m/d", $v['addtime']);
                if (empty($member_list[$u_id])) {
                    $member_list[$u_id] = $member;
                } else {
                    $member_list[$u_id]['recent'] = 1;
                    $member_list[$u_id]['t_msg']  = $new_msg['t_msg'];
                    $member_list[$u_id]['time']   = date("y/m/d", $v['addtime']);
                }
            }
        }
        return $member_list;
    }
    /**
     * get recent from msg
     *
     * @param
     * @return array
     */
    public function getRecentFromList($condition = array(), $limit = 5, $member_list = array())
    {
        $list = $this->getMemberFromList($condition, '', $limit);
        if (!empty($list) && is_array($list)) {
            foreach ($list as $k => $v) {
                $member             = array();
                $u_id               = $v['f_id'];
                $new_msg            = $this->table('chat_msg')->field('t_msg,goods_id')->where(array('f_id' => $v['f_id']))->order('m_id desc')->find();
                $member['new_msg']  = $new_msg['t_msg'];
                $member['goods_id'] = $new_msg['goods_id'];
                $member['u_id']     = $u_id;
                $member['u_name']   = $v['f_name'];
                $member['avatar']   = getMemberAvatarForID($u_id, true);
                $member['recent']   = 1;
                $member['time']     = date("y/m/d", $v['addtime']);
                if (empty($member_list[$u_id])) {
                    $member_list[$u_id] = $member;
                } else {
                    $member_list[$u_id]['recent']   = 1;
                    $member_list[$u_id]['t_msg']    = $new_msg['t_msg'];
                    $member_list[$u_id]['goods_id'] = $new_msg['goods_id'];
                    $member_list[$u_id]['time']     = date("y/m/d", $v['addtime']);
                }
            }
        }
        return $member_list;
    }
    /**
     * 收到消息的会员记录
     *
     * @param
     * @return array
     */
    public function getMemberRecentList($condition = array(), $page = '', $limit = '')
    {
        $list = array();
        $msg  = $this->table('chat_msg')->field('count(DISTINCT t_id) as count')->where($condition)->find();
        if ($msg['count'] > 0) {
            $count = intval($msg['count']);
            $list  = $this->table('chat_msg')->field('t_id,t_name,max(add_time) as addtime')->group('t_id')->where($condition)->limit($limit)->page($page, $count)->order('addtime desc')->select();
        }
        return $list;
    }
    /**
     * 发出消息的会员记录
     *
     * @param
     * @return array
     */
    public function getMemberFromList($condition = array(), $page = '', $limit = '')
    {
        $list = array();
        $msg  = $this->table('chat_msg')->field('count(DISTINCT f_id) as count')->where($condition)->find();
        if ($msg['count'] > 0) {
            $count = intval($msg['count']);
            $list  = $this->table('chat_msg')->field('f_id,f_name,max(add_time) as addtime')->group('f_id')->where($condition)->limit($limit)->page($page, $count)->order('addtime desc')->select();
        }
        return $list;
    }
    /**
     * 删除会员信息
     * @Author   xuyq
     * @DateTime 2017-02-21T14:39:22+0800
     * @param    string                   $u_id [description]
     * @return   [type]                         [description]
     */
    public function del_user_msg($u_id = '')
    {
        $data = ['r_state' => 3];
        $msg  = $this->table('chat_msg')->where(array('f_id' => $u_id))->update($data);
        if ($msg) {
            echo 1;
        }
    }
    /**
     * 标记会员信息为已读
     * @Author   xuyq
     * @DateTime 2017-02-21T14:39:22+0800
     * @param    string                   $u_id [description]
     * @return   [type]                         [description]
     */
    public function is_read_msg($u_id = '')
    {
        $data = ['r_state' => 1];
        $msg  = $this->table('chat_msg')->where(array('f_id' => $u_id))->update($data);
    }
    /**
     * 单个会员的消息记录
     *
     * @param
     * @return array
     */
    public function getLogFromList($condition = array(), $page = 10)
    {
        $list = array();
        $f_id = intval($condition['f_id']);
        if ($f_id > 0) {
            $t_id = intval($condition['t_id']);
            if ($t_id > 0) {
                $condition_sql = " ((f_id = '" . $f_id . "' and t_id = '" . $t_id . "') or (f_id = '" . $t_id . "' and t_id = '" . $f_id . "'))";
            } else {
                $condition_sql = " (f_id = '" . $f_id . "' or t_id = '" . $f_id . "')";
            }
            $add_time_from = trim($condition['add_time_from']);
            if (!empty($add_time_from)) {
                $add_time_from = strtotime($add_time_from);
                $condition_sql .= " and add_time >= '" . $add_time_from . "'";
            }
            $add_time_to = trim($condition['add_time_to']);
            if (!empty($add_time_to)) {
                $add_time_to = strtotime($add_time_to) + 60 * 60 * 24;
                $condition_sql .= " and add_time <= '" . $add_time_to . "'";
            }
            $t_msg = trim($condition['t_msg']);
            if (!empty($t_msg)) {
                $condition_sql .= " and t_msg like '%" . $t_msg . "%'";
            }

            $list = $this->getLogList($condition_sql, $page);
        }
        return $list;
    }
    /**
     * 会员相关的信息
     *
     * @param
     * @return array
     */
    public function getMemberInfo($condition)
    {
        $model_member            = Model('member');
        $member                  = $model_member->getMemberInfo($condition, 'member_id,member_name,member_avatar');
        $member['store_name']    = '';
        $member['grade_id']      = '';
        $member['member_avatar'] = getMemberAvatar($member['member_avatar']);
        $model_seller            = Model('seller');
        $seller                  = $model_seller->getSellerInfo(array('member_id' => $member['member_id']));
        if (!empty($seller) && $seller['store_id'] > 0) {
            $store_info = $this->table('store')->field('store_id,store_name,grade_id')->where(array('store_id' => $seller['store_id']))->find();
            if (is_array($store_info) && !empty($store_info)) {
                $member['store_id']     = $store_info['store_id'];
                $member['store_name']   = $store_info['store_name'];
                $member['store_avatar'] = $store_info['store_avatar'];
                if (empty($store_info['store_avatar'])) {
                    $member['store_avatar'] = 'http://wx.yimayholiday.com/data/upload/shop/common/default_user_portrait.gif';
                } else {
                    $member['store_avatar'] = $store_info['store_avatar'];
                }
                $member['seller_name'] = $seller['seller_name'];
                $member['grade_id']    = $store_info['grade_id'];
            }
        }
        return $member;
    }
    /**
     * 商品相关的信息
     *
     * @param
     * @return array
     */
    public function getGoodsInfo($goods_id)
    {
        $goods       = array();
        $model_goods = Model('goods');
        $goods_id    = intval($goods_id);
        if ($goods_id == 10086) {
            $goods             = array();
            $goods['goods_id'] = $goods_id;
        } else {

            $field = 'goods_id,store_id,goods_commonid,goods_name,goods_image,goods_price,goods_marketprice,goods_promotion_price';
            $goods = $model_goods->getGoodsInfoByID($goods_id, $field);
            if (is_array($goods) && !empty($goods)) {
                $goods['url'] = urlShop('goods', 'index', array('goods_id' => $goods['goods_id']));
                $goods['pic'] = thumb($goods, 240);
            }
        }
        return $goods;
    }
}
