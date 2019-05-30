<?php
/**
 * 会员店铺
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class show_storeControl extends BaseStoreControl {
   public function __construct() {
      parent::__construct();
      auto_apply_cmms();
   }

   //店铺主页
   public function indexOp() {
      //setNcCookie('location_search_history','');
      $model                  = Model();
      $store_id               = $_GET['store_id'];
      $_SESSION['share_shop'] = 0; //非分销
      if ($store_id > 0) {
         $_SESSION['share_store_id'] = $store_id;
      }
      //定位用户当前位置
      if (!isset($_SESSION['location_city'])) {
          $this->_location();
      }
	  $pi = pi();
	  $r = 6367000;
      $sql   = "SELECT a.goods_id,a.goods_price,a.goods_marketprice,a.goods_salenum,a.goods_name,a.goods_jingle,a.like_num,b.goods_desc,b.goods_image,(ACOS(SIN(({$_SESSION['location_lat']} * {$pi}) / 180 ) *SIN((b.latitude * {$pi}) / 180 ) +COS(({$_SESSION['location_lat']} * {$pi}) / 180 ) * COS((b.latitude * {$pi}) / 180 ) *COS(({$_SESSION['location_lng']} * {$pi}) / 180 - (b.longitude * {$pi}) / 180 ) ) * {$r}) as distance FROM az_goods AS a LEFT JOIN az_goods_common as b ON(a.goods_commonid = b.goods_commonid)";
      $where = " WHERE  a.goods_commend = 1 AND a.goods_state = 1 AND a.goods_verify = 1 AND a.store_id = {$store_id}";
      if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
         $keywords = trim($_GET['keywords']);
         //处理关联的分类
         $or = "a.goods_name LIKE '%{$keywords}%' OR a.goods_jingle LIKE '%{$keywords}%' OR b.goods_body LIKE '%{$keywords}%' OR b.area_info LIKE '%{$keywords}%'";
         if ($goods_class = $model->table('goods_class')->field('gc_id')->where("gc_name LIKE '%$keywords%'")->select()) {
            $gc_id_in = implode(',', array_column($goods_class, 'gc_id'));
            $or .= " OR a.gc_id IN($gc_id_in)";
         }
         $where .= " AND ({$or})";
         //如果用户搜索则添加搜索记录
         $this->_add_city_history($_SESSION['location_city_id'], $_SESSION['location_city'], $keywords);
      }
      if (isset($_GET['category']) && intval($_GET['category'])) {
         $cate_id = intval($_GET['category']);
         $where .= " AND b.goods_stcids LIKE '%{$cate_id}%'";
      }
      //此处开启是城市信息筛选
       if (isset($_SESSION['location_city'])) {
         $order = " ORDER BY distance ASC";
      }
      //处理店铺baner
      $baner_img = explode(',', $this->store_info['store_slide']);
      $baner_url = explode(',', $this->store_info['store_slide_url']);
      $banner    = array();
      foreach ($baner_img as $k => $v) {
         if (!empty($v)) {
            $banner[$k]['img'] = UPLOAD_SITE_URL . DS . ATTACH_SLIDE . DS . $v;
            $banner[$k]['url'] = $baner_url[$k];
         }
      }
      //处理店铺分类
      $category_index = $model->table('store_goods_class')->field('stc_name,stc_id')->where("stc_parent_id = 0 AND store_id = {$store_id} AND stc_state = 1")->order('stc_sort ASC')->limit('0,2')->select();
      foreach ($category_index as $k => $v) {
         $child_cate                  = $model->table('store_goods_class')->field('stc_name,stc_id')->where("stc_parent_id = {$v['stc_id']} AND store_id = {$store_id} AND stc_state = 1")->order('stc_sort ASC')->select();
         $category_index[$k]['child'] = $child_cate;
         $child_cate                  = null;
      }
      #专题
      $special_info = Model('mb_special')->field('special_id')->where(['status' => 1, 'store_id' => $_SESSION['route_store_id']])->find();
      Tpl::output('special_info', $special_info);
      //商品底部广告
      $stime = time();
      $advs  = array();
      if ($adv = $model->table('share_advs')->field('title,url,thumb')->where("status = 1 AND starttime <= {$stime} AND endtime >= {$stime} AND store_id = {$store_id} AND type = 'shop_adv_a'")->order('sort ASC')->limit('0,5')->select()) {
         foreach ($adv as $k => $v) {
            $advs[$k]['img']   = UPLOAD_SITE_URL . $v['thumb'];
            $advs[$k]['url']   = $v['url'];
            $advs[$k]['title'] = $v['title'];
         }
      }
      //处理首页推荐商品
      $category                   = $this->_get_goods_category();
      $_SESSION['store_sql']      = $sql;
      $_SESSION['store_where']    = $where;
      $_SESSION['store_order']    = $order;
      $_SESSION['store_category'] = $category;
      $goods_list                 = $this->_get_goods_list();
      $store_info['store_id']     = $this->store_info['store_id'];
      $store_info['name']         = $this->store_info['store_name'];
      $store_info['keywords']     = $this->store_info['store_keywords'];
      $store_info['desccredit']   = $this->store_info['store_desccredit'];
      $recommoned_videos = Model('videos')->getVideosList(array('is_recommend'=>1),"*","videos_id asc","2");//取出两条推荐视频

      $btn_list = Model('index_menu')->where(['store_id' => $this->store_info['store_id'], 'status' => 1])->order('sort DESC')->select();
      if(!empty($btn_list)){
         $temp = [];
         foreach($btn_list as $item){
            $temp[$item['rows']][] = $item;
         }
         $btn_list = $temp;
         unset($temp);
      }
     Tpl::output('btn_list', $btn_list);

     $store_wx_info = Model('store_wxinfo')->where(['store_id' => $this->store_info['store_id']])->find();
     Tpl::output('store_wx_info', $store_wx_info);

	  Tpl::output('recommoned_videos',$recommoned_videos);
	  Tpl::output('share_shop',$_SESSION['share_shop']);
      Tpl::output('adv', $advs);
      Tpl::output('store', $store_info);
      Tpl::output('goods', $goods_list);
      Tpl::output('city', $_SESSION['location_city']);
      Tpl::output('category', $category_index);
      Tpl::output('banner', $banner);
      Tpl::showpage('index', 'null_layout');
   }


   public function shareOp() {
      //setNcCookie('location_search_history','');
      $model                  = Model();
      $store_id               = $_GET['store_id'];
      $_SESSION['share_shop'] = 1; //分销
      if ($store_id > 0) {
         $_SESSION['share_store_id'] = $store_id;
      }
      $this->unlockRelationship();
      $this->lockRelationship(intval($_GET['share_uid']));
      //定位用户当前位置
      if (!isset($_SESSION['location_city'])) {
          $this->_location();
      }
	  $pi = pi();
	  $r = 6367000;
      $sql   = "SELECT a.goods_id,a.goods_name,a.share_price,a.goods_jingle,b.goods_image,a.like_num,(ACOS(SIN(({$_SESSION['location_lat']} * {$pi}) / 180 ) *SIN((b.latitude * {$pi}) / 180 ) +COS(({$_SESSION['location_lat']} * {$pi}) / 180 ) * COS((b.latitude * {$pi}) / 180 ) *COS(({$_SESSION['location_lng']} * {$pi}) / 180 - (b.longitude * {$pi}) / 180 ) ) * {$r}) as distance FROM az_goods AS a LEFT JOIN az_goods_common as b ON(a.goods_commonid = b.goods_commonid)";
      $where = " WHERE a.goods_state = 1 AND a.goods_verify = 1 AND a.store_id = {$store_id} AND a.isshare = 1";
      if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
         $keywords = trim($_GET['keywords']);
         //处理关联的分类
         $or = "a.goods_name LIKE '%{$keywords}%' OR a.goods_jingle LIKE '%{$keywords}%' OR b.goods_body LIKE '%{$keywords}%' OR b.area_info LIKE '%{$keywords}%'";
         if ($goods_class = $model->table('goods_class')->field('gc_id')->where("gc_name LIKE '%$keywords%'")->select()) {
            $gc_id_in = implode(',', array_column($goods_class, 'gc_id'));
            $or .= " OR a.gc_id IN($gc_id_in)";
         }
         $where .= " AND ({$or})";
         //如果用户搜索则添加搜索记录
         $this->_add_city_history($_SESSION['location_city_id'], $_SESSION['location_city'], $keywords);
      }
      if (isset($_GET['category']) && intval($_GET['category'])) {
         $cate_id = intval($_GET['category']);
         $where .= " AND b.goods_stcids LIKE '%{$cate_id}%'";
      }
      //此处开启是城市信息筛选
      if (isset($_SESSION['location_city'])) {
         $order = " ORDER BY distance ASC";
      }
      //处理店铺分销baner
      $stime  = time();
      $banner = array();
      if ($baner = $model->table('share_advs')->field('title,url,thumb')->where("status = 1 AND starttime <= {$stime} AND endtime >= {$stime} AND store_id = {$store_id} AND type = 'share_top_bar'")->order('sort ASC')->limit('0,5')->select()) {
         foreach ($baner as $k => $v) {
            $banner[$k]['img']   = UPLOAD_SITE_URL . $v['thumb'];
            $banner[$k]['url']   = $v['url'];
            $banner[$k]['title'] = $v['title'];
         }
      }

      //处理店铺分类
      $category_index = $model->table('store_goods_class')->field('stc_name,stc_id')->where("stc_parent_id = 0 AND store_id = {$store_id} AND stc_state = 1")->order('stc_sort ASC')->limit('0,2')->select();
      foreach ($category_index as $k => $v) {
         $child_cate                  = $model->table('store_goods_class')->field('stc_name,stc_id')->where("stc_parent_id = {$v['stc_id']} AND store_id = {$store_id} AND stc_state = 1")->order('stc_sort ASC')->select();
         $category_index[$k]['child'] = $child_cate;
         $child_cate                  = null;
      }
	  //获取店铺微信信息
	  $store_wx_info = $model->table('store_wxinfo')->field('appsecret,appid')->where('store_id = '.store_id)->find();
	  $_SESSION['weixin_appid'] = $store_wx_info['appid'];
	  $_SESSION['weixin_appsecret'] = $store_wx_info['appsecret'];
      //处理首页推荐商品
      $category                   = $this->_get_goods_category();
      $_SESSION['store_sql']      = $sql;
      $_SESSION['store_where']    = $where;
      $_SESSION['store_order']    = $order;
      $_SESSION['store_category'] = $category;
      $goods_list                 = $this->_get_goods_list();
      $store_info['store_id']     = $this->store_info['store_id'];
      $store_info['name']         = $this->store_info['store_name'];
      $store_info['keywords']     = $this->store_info['store_keywords'];
      $store_info['desccredit']   = $this->store_info['store_desccredit'];
      //上级分销主头像
	  if(isset($_GET['share_uid']) && intval($_GET['share_uid'])){
		  $member_id = intval($_GET['share_uid']);
	  }else{
		  $member_id = $_SESSION['member_id'];
	  }

	  /*else if($first_member = $model->table('share_member')->field('pid')->where("member_id = {$_SESSION['member_id']}")->find()){
		  $member_id = $first_member['pid'];
	  }*/

      if ($member = $model->table('member')->field('member_sex,member_name,member_avatar,member_age')->where("member_id = {$member_id}")->find()) {
			 $store_info['member_id'] = $member_id;
			 $store_info['member_sex']  = $member['member_sex'];
			 $store_info['member_age']  = $member['member_age'];
			 $store_info['member_name'] = $member['member_name'];
			 if (preg_match("/^(http:\/\/|https:\/\/).*$/", $member['member_avatar'])) {
				$store_info['member_avatar'] = $member['member_avatar'];
			 } else {
				if ($store_info['member_avatar']) {
				   $store_info['member_avatar'] = UPLOAD_SITE_URL . DS . ATTACH_AVATAR . DS . $member['member_avatar'];
				} else {
				   $store_info['member_avatar'] = UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . C('default_user_portrait');
				}
			 }
		}
      $advs = array();
      if ($adv = $model->table('share_advs')->field('title,url,thumb')->where("status = 1 AND starttime <= {$stime} AND endtime >= {$stime} AND store_id = {$store_id} AND type = 'shop_adv_a'")->order('sort ASC')->limit('0,5')->select()) {
         foreach ($adv as $k => $v) {
            $advs[$k]['img']   = UPLOAD_SITE_URL . $v['thumb'];
            $advs[$k]['url']   = $v['url'];
            $advs[$k]['title'] = $v['title'];
         }
      }
	  $special_info = Model('mb_special')->field('special_id')->where(['status' => 1, 'store_id' => $_SESSION['route_store_id']])->find();
      Tpl::output('special_info', $special_info);

	  //新增VR
	  $recommoned_videos = Model('videos')->getVideosList(array('is_recommend'=>1),"*","videos_id asc","2");//取出两条推荐视频
	  Tpl::output('recommoned_videos',$recommoned_videos);

     $store_wx_info = Model('store_wxinfo')->where(['store_id' => $this->store_info['store_id']])->find();
     Tpl::output('store_wx_info', $store_wx_info);

	  Tpl::output('share_shop',$_SESSION['share_shop']);
      Tpl::output('adv', $advs);
      Tpl::output('store', $store_info);
      Tpl::output('goods', $goods_list);
      Tpl::output('city', $_SESSION['location_city']);
      Tpl::output('category', $category_index);
      Tpl::output('banner', $banner);
      Tpl::showpage('share', 'null_layout');
   }

   //处理商品函数
   public function _get_goods_list($t =null) {
      $model       = Model();
      $goods_list  = array();
      $page_count  = 0;
      $page_num    = 1; //此处控制每次分页至少输出商品条数
      $default_num = 6; //此处控制非推荐分类中商品显示条数
      if (!empty($_SESSION['store_category'])) {
         foreach ($_SESSION['store_category'] as $k => $v) {
            $sql             = $_SESSION['store_sql'] .$_SESSION['store_where']. " AND a.gc_id = {$v['gc_id']}" ." GROUP BY a.goods_commonid ". $_SESSION['store_order'] . " LIMIT 0,{$default_num}";
			$goods           = $model->query($sql);
            $v['goods_list'] = $goods;
            $goods_list[]    = $v;
            unset($_SESSION['store_category'][$k]);
            $page_count = $page_count + count($goods);
			if($page_count >= $page_num){
				break;
			}
         }
      }

      //处理商品详细信息函数
      foreach ($goods_list as $k => $v) {
         if (!empty($v['goods_list'])) {
            foreach ($v['goods_list'] as $key => $val) {
               $goods_list[$k]['goods_list'][$key]['goods_image']    = cthumb($val['goods_image'], 360);
               $goods_list[$k]['goods_list'][$key]['distance']       =  round($val['distance']/1000);//$this->_getDistance($_SESSION['location_lat'], $_SESSION['location_lng'], $val['latitude'], $val['longitude']);
			   if($_SESSION['share_shop']){
				   $goods_list[$k]['goods_list'][$key]['goods_price'] = $val['share_price'];
			   }
               $goods_list[$k]['goods_list'][$key]['goods_evaluate'] = $this->_settle_evaluate($val['goods_id']);
               if (trim($val['goods_jingle'])) {
                  $goods_list[$k]['goods_list'][$key]['goods_jingle'] = explode(',', str_replace(array(' ', '  ', '。', '，', '.'), ',', $val['goods_jingle']));
               } else {
                  $goods_list[$k]['goods_list'][$key]['goods_jingle'] = '';
               }
            }

         } else {
            unset($goods_list[$k]);
         }
      }
      return $goods_list;
   }
   //处理商品评价详细信息函数
   protected function _settle_evaluate($goods_id) {
      $comment = array();
      if ($eva = Model()->table('evaluate_goods')->field('geval_content,geval_frommemberid,geval_frommembername')->where("geval_goodsid = {$goods_id} AND geval_content != '' AND geval_state = 0")->order('geval_addtime DESC')->find()) {
         $comment['comment']     = $eva['geval_content'];
         $comment['member_id']   = $eva['geval_frommemberid'];
         $comment['member_name'] = $eva['geval_frommembername'];
         if ($member = Model()->table('member')->field('member_sex,member_avatar,member_age')->where("member_id = {$comment['member_id']}")->find()) {
            $comment['member_sex'] = $member['member_sex'];
            $comment['member_age'] = $member['member_age'];
            if (preg_match("/^(http:\/\/|https:\/\/).*$/", $member['member_avatar'])) {
               $comment['member_avatar'] = $member['member_avatar'];
            } else {
               if (file_exists(BASE_UPLOAD_PATH . DS . ATTACH_AVATAR . DS . $member['member_avatar'])) {
                  $comment['member_avatar'] = UPLOAD_SITE_URL . DS . ATTACH_AVATAR . DS . $member['member_avatar'];
               } else {
                  $comment['member_avatar'] = UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . C('default_user_portrait');
               }
            }
         }
      }
      return $comment;
   }
   //获取所有产品的分类
   public function _get_goods_category() {
      $cate   = Model()->table('goods_class')->field("gc_id,gc_name,gc_parent_id")->order("gc_sort ASC")->select();
      $id     = array_unique(array_column($cate, 'gc_parent_id'));
      $return = array();
      foreach ($cate as $k => $v) {
         if (!in_array($v['gc_id'], $id)) {
            $return[] = $v;
         }
      }
      return $return;
   }

   //当前位置定位
   public function _location() {
      require_once 'map.php';
      $map                           = map::instance();
      $ip                            = explode(',',$this->getIP());
      $address_info                  = $map->locationByIP("116.52.147.51");
      $_SESSION['location_lng']      = $address_info['lng'];
      $_SESSION['location_lat']      = $address_info['lat'];
      $_SESSION['location_province'] = $address_info['province'];
      $_SESSION['location_city']     = $address_info['city'];

      //比配系统中的地理信息
      $sys_addr                             = Model()->table('area')->field('area_id,area_name,pinyinCode')->where("area_name = '{$_SESSION['location_city']}'")->find();
      $_SESSION['location_city_id']         = $sys_addr['area_id'];
      $_SESSION['location_city_pinyinCode'] = $sys_addr['pinyinCode'];
      //更新热点城市值
      $this->_update_city($sys_addr['area_id'], $sys_addr['area_name']);
   }

   public function set_mapOp() {
      $id       = $_GET['id'];
      $sys_addr = Model()->table('area')->field('area_id,area_name,pinyinCode')->where("area_id = {$id}")->find();

      $_SESSION['location_city']            = $sys_addr['area_name'];
      $_SESSION['location_city_id']         = $sys_addr['area_id'];
      $_SESSION['location_city_pinyinCode'] = $sys_addr['pinyinCode'];
	  require_once 'map.php';
      $map                           = map::instance();
	  if($locat = $map->locationByXY($area_name)){
		  $_SESSION['location_lat'] = $locat['lat'];
		  $_SESSION['location_lng'] = $locat['lng'];
	  }
      //更新热点城市值
      $this->_update_city($sys_addr['area_id'], $sys_addr['area_name']);

      $this->_add_city_history($sys_addr['area_id'], $sys_addr['area_name']);
	  if($_SESSION['share_shop']){
		  $sql = "index.php?act=show_store&op=share&store_id={$_SESSION['route_store_id']}";
	  }else{
		  $sql ="index.php?act=show_store&op=index&store_id={$_SESSION['route_store_id']}";
	  }
      header("Location: $sql");
   }

   public function lockRelationship($pid) {
      if (!$_SESSION['member_id']) {
         unset($_SESSION['is_login']);
         $this->checkWxLogin();
         return false;
      }
      #自己不能锁定自己
      if($_SESSION['member_id'] == $pid){
         return false;
      }
      $share_model = Model('share_member');
      $member_info = $share_model->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      #上级是否存在
      // if ($pid > 0) {
      //    $parent = $share_model->where(array('member_id' => $pid, 'store_id' => $_SESSION['store_id']))->find();
      //    if (empty($parent)) {
      //       return false;
      //    }
      // }
      $share_settings = Model('share_settings')->where(array('store_id' => $_SESSION['share_store_id']))->find();
      $member_comm    = Model('member')->where(array('member_id' => $_SESSION['member_id']))->find();
      switch ($share_settings['expire']) {
      case '1':
         #如果用户已存在，则表示关系已锁定
         if (!empty($member_info)) {
            return false;
         }
         #永久锁定关系
         $res = $share_model->insert(array(
            'member_id'  => $member_comm['member_id'],
            'openid'     => (string) $member_comm['openid'],
            'reg_time'   => TIMESTAMP,
            'nickname'   => $member_comm['member_name'],
            'sex'        => $member_comm['member_sex'],
            'headimgurl' => $member_comm['member_avatar'],
            'pid'        => $pid,
            'store_id'   => $_SESSION['share_store_id'],
            'mobile'     => $member_comm['member_mobile'],
            'status'     => 1,
            'lock_parent_time' => TIMESTAMP,
         ));
         break;
      case '4':
      case '3':
      case '5':
         $days = ['4' => 7, '3' => 1, '5' => 30, '6' => 3];
         $timestamp = $days[$share_settings['expire']] * 86400;
         if(empty($member_info)){
            #新用户锁定
            $res = $share_model->insert(array(
               'member_id'  => $member_comm['member_id'],
               'openid'     => (string) $member_comm['openid'],
               'reg_time'   => TIMESTAMP,
               'nickname'   => $member_comm['member_name'],
               'sex'        => $member_comm['member_sex'],
               'headimgurl' => $member_comm['member_avatar'],
               'pid'        => $pid,
               'store_id'   => $_SESSION['share_store_id'],
               'mobile'     => $member_comm['member_mobile'],
               'status'     => 1,
               'lock_parent_time' => TIMESTAMP,
            ));
         } elseif(($member_info['lock_parent_time'] + $timestamp) <= TIMESTAMP){
            #锁定时间已过期,重新锁定
            $share_model->where(['id' => $member_info['id']])->update([
               'lock_parent_time' => TIMESTAMP,
               'pid' => $pid,
            ]);
         }
         break;
      default:
         break;
      }
      return true;
   }
   public function unlockRelationship(){
      if (!$_SESSION['member_id']) {
         return true;
      }
      $share_model = Model('share_member');
      $member_info = $share_model->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      if(empty($member_info['pid'])){
         return true;
      }
      $share_settings = Model('share_settings')->where(array('store_id' => $_SESSION['share_store_id']))->find();
      switch ($share_settings['expire']) {
      case '1':
         return true;
      case '4':
      case '3':
      case '5':
         $days = ['4' => 7, '3' => 1, '5' => 30, '6' => 3];
         $timestamp = $days[$share_settings['expire']] * 86400;
         if(($member_info['lock_parent_time'] + $timestamp) <= TIMESTAMP){
            #解除锁定
            $share_model->where(['id' => $member_info['id']])->update([
               'lock_parent_time' => 0,
               'pid' => 0,
            ]);
         }
      }
   }

   //优惠券列表
   public function lotteryOp(){
      Tpl::showpage('lottery_list');

   }

   //ajax 动态加载首页内容
   public function ajax_loadOp() {
      $goods_list = $this->_get_goods_list();

      if(!empty($goods_list)){
         Tpl::output('goods', $goods_list);
         $store_info['store_id']     = $this->store_info['store_id'];
         Tpl::output('store', $store_info);
         Tpl::showpage('push_goods_list');
      }
   }

   //更换城市页面
   public function mapOp() {
      //当前城市
      $model     = Model();
      $this_city = array(
         'city'   => $_SESSION['location_city'],
         'id'     => $_SESSION['location_city_id'],
         'pinyin' => $_SESSION['location_city_pinyinCode'],
      );
      //热点城市
      $hot_city     = $model->table('user_location_statistical')->field('city_id,city_name')->order('location_count DESC')->limit('0,5')->select();
      $history_city = array();
      if ($_SESSION['member_id']) {
         //登录用户
         //登录检查是否有最新预览的城市
         if ($history = unserialize(cookie('location_search_history'))) {
            foreach ($history as $k => $v) {
               $history[$k]['member_id']   = $_SESSION['member_id'];
               $history[$k]['member_name'] = $_SESSION['member_name'];
               $model->table('user_search_history')->insert($history[$k]);
            }
         }
         $history_city = $model->table('user_search_history')->field('city_id,city_name')->where("member_id = {$_SESSION['member_id']}")->group('city_id')->order('search_time DESC')->limit('0,5')->select();
      } else {
         if ($history = unserialize(cookie('location_search_history'))) {
            foreach ($history as $k => $v) {
               if ($k <= 5) {
                  $history_city[] = $v;
               }
            }
         }
      }

      $city = array();
      if (isset($_GET['city_key']) && !empty($_GET['city_key'])) {
         //获取查询的城市
         $citys = $model->table('area')->field('area_id,area_name,pinyinCode')->where("area_name LIKE '%{$_GET['city_key']}%' AND area_deep =2")->limit('0,10')->select();
         foreach ($citys as $k => $v) {
            $city[$v['pinyinCode']][] = $v;
         }
      } else if (isset($_GET['city_pinyin']) && !empty($_GET['city_pinyin'])) {
         //获取pinyin关键城市
         $pinyinCode                 = strtoupper($_GET['city_pinyin']);
         $citys                      = $model->table('area')->field('area_id,area_name,pinyinCode')->where("pinyinCode = '{$pinyinCode}'  AND area_deep =2")->limit('0,10')->select();
         $city[$_GET['city_pinyin']] = $citys;
      } else {
         //获取关联城市pinyin首字母的城市
         $citys                                       = $model->table('area')->field('area_id,area_name,pinyinCode')->where("pinyinCode = '{$_SESSION['location_city_pinyinCode']}'  AND area_deep =2")->limit('0,10')->select();
         $city[$_SESSION['location_city_pinyinCode']] = $citys;
      }

      Tpl::output('history_city', $this->unique_multidim_array($history_city, 'city_id'));
      Tpl::output('this_city', $this_city);
      Tpl::output('hot_city', $hot_city);
      Tpl::output('city', $city);
      Tpl::showpage('city', 'null_layout');
   }

   //添加用户search记录
   protected function _add_city_history($city_id, $city_name, $keywords = '') {
      $history = array();
      if (empty($city_name) || !intval($city_id)) {
         return;
      }

      if ($_SESSION['member_id']) {
         //登录用户直接写入表
         Model()->table('user_search_history')->insert(array(
            'member_id'   => intval($_SESSION['member_id']),
            'member_name' => $_SESSION['member_name'],
            'keywords'    => $keywords,
            'city_id'     => intval($city_id),
            'city_name'   => $city_name,
            'search_time' => time(),
         ));

      } else {
         $arr = array(
            'city_id'     => $city_id,
            'city_name'   => $city_name,
            'keywords'    => $keywords,
            'search_time' => time(),
         );

         if ($history = unserialize(cookie('location_search_history'))) {
            array_unshift($history, $arr);
            $history = $this->unique_multidim_array($history, 'city_id');
            if (count($history) > 5) {
               array_pop($history);
            }
            setNcCookie('location_search_history', serialize($history));
         } else {
            $arrs[0] = $arr;
            setNcCookie('location_search_history', serialize($arrs));
         }
      }
   }

   //更新城市热点值
   protected function _update_city($id, $city) {
      if (!intval($id) && empty($city)) {
         return;
      }

      if (Model()->table('user_location_statistical')->where("city_name = '$city' AND city_id = $id")->find()) {
         Model()->query("UPDATE az_user_location_statistical SET location_count = location_count+1 WHERE city_id = $id");
      } else {
         Model()->table('user_location_statistical')->insert(array(
            'city_id'   => $id,
            'city_name' => $city,
         ));
      }
   }

   //计算两个经纬度坐标的距离
   protected function _getDistance($lat1, $lng1, $lat2, $lng2) {
      $earthRadius = 6367000; //approximate radius of earth in meters

      $lat1 = ($lat1 * pi()) / 180;
      $lng1 = ($lng1 * pi()) / 180;

      $lat2 = ($lat2 * pi()) / 180;
      $lng2 = ($lng2 * pi()) / 180;

      $calcLongitude      = $lng2 - $lng1;
      $calcLatitude       = $lat2 - $lat1;
      $stepOne            = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
      $stepTwo            = 2 * asin(min(1, sqrt($stepOne)));
      $calculatedDistance = $earthRadius * $stepTwo;

      return round($calculatedDistance) / 1000;
   }

   //去除数组中某个重复的值
   protected function unique_multidim_array($array, $key) {
      $temp_array = array();
      $i          = 0;
      $key_array  = array();

      foreach ($array as $val) {
         if (!in_array($val[$key], $key_array)) {
            $key_array[$i]  = $val[$key];
            $temp_array[$i] = $val;
         }
         $i++;
      }
      return $temp_array;
   }

   protected function getIP() {
      static $realip;
      if (isset($_SERVER)) {
         if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
         } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
         } else {
            $realip = $_SERVER["REMOTE_ADDR"];
         }
      } else {
         if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
         } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
         } else {
            $realip = getenv("REMOTE_ADDR");
         }
      }
      return $realip;
   }

   private function getGoodsMore($goods_list1, $goods_list2 = array()) {
      if (!empty($goods_list2)) {
         $goods_list = array_merge($goods_list1, $goods_list2);
      } else {
         $goods_list = $goods_list1;
      }
      // 商品多图
      if (!empty($goods_list)) {
         $goodsid_array  = array(); // 商品id数组
         $commonid_array = array(); // 商品公共id数组
         $storeid_array  = array(); // 店铺id数组
         foreach ($goods_list as $value) {
            $goodsid_array[]  = $value['goods_id'];
            $commonid_array[] = $value['goods_commonid'];
            $storeid_array[]  = $value['store_id'];
         }
         $goodsid_array  = array_unique($goodsid_array);
         $commonid_array = array_unique($commonid_array);

         // 商品多图
         $goodsimage_more = Model('goods')->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));

         foreach ($goods_list1 as $key => $value) {
            // 商品多图
            foreach ($goodsimage_more as $v) {
               if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                  $goods_list1[$key]['image'][] = $v;
               }
            }
         }

         if (!empty($goods_list2)) {
            foreach ($goods_list2 as $key => $value) {
               // 商品多图
               foreach ($goodsimage_more as $v) {
                  if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                     $goods_list2[$key]['image'][] = $v;
                  }
               }
            }
         }
      }
      return array(1 => $goods_list1, 2 => $goods_list2);
   }

   public function show_articleOp() {
      //判断是否为导航页面
      $model_store_navigation = Model('store_navigation');
      $store_navigation_info  = $model_store_navigation->getStoreNavigationInfo(array('sn_id' => intval($_GET['sn_id'])));
      if (!empty($store_navigation_info) && is_array($store_navigation_info)) {
         Tpl::output('store_navigation_info', $store_navigation_info);
         Tpl::showpage('article');
      }
   }

   public function add_goods_likeOp() {
      $goodsid = $_GET['id'];
      Model()->query("UPDATE az_goods SET like_num = like_num +1 WHERE goods_id = $goodsid");
      echo '1';
   }
   /**
    * 全部商品
    */
   public function goods_allOp() {

      $condition             = array();
      $condition['store_id'] = $this->store_info['store_id'];
      if (trim($_GET['inkeyword']) != '') {
         $condition['goods_name'] = array('like', '%' . trim($_GET['inkeyword']) . '%');
      }

      // 排序
      $order = 1 == $_GET['order'] ? 'asc' : 'desc';
      switch (trim($_GET['key'])) {
      case '1':
         $order = 'goods_id ' . $order;
         break;
      case '2':
         $order = 'goods_promotion_price ' . $order;
         break;
      case '3':
         $order = 'goods_salenum ' . $order;
         break;
      case '4':
         $order = 'goods_collect ' . $order;
         break;
      case '5':
         $order = 'goods_click ' . $order;
         break;
      default:
         $order = 'goods_id desc';
         break;
      }

      //查询分类下的子分类
      if (intval($_GET['stc_id']) > 0) {
         $condition['goods_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
      }

      $model_goods = Model('goods');
      $fieldstr    = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count,goods_promotion_type";

      $recommended_goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, 24);
      $recommended_goods_list = $this->getGoodsMore($recommended_goods_list);
      Tpl::output('recommended_goods_list', $recommended_goods_list[1]);
      loadfunc('search');

      //输出分页
      Tpl::output('show_page', $model_goods->showpage('5'));
      $stc_class = Model('store_goods_class');
      $stc_info  = $stc_class->getStoreGoodsClassInfo(array('stc_id' => intval($_GET['stc_id'])));
      Tpl::output('stc_name', $stc_info['stc_name']);
      Tpl::output('page', 'index');

      //Tpl::showpage('goods_list');
   }
   /**
    *ajax 获取商品的PJ
    **/
   function updata_pjOp() {
      $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($_GET['goods_id']);
      echo $_GET['goods_id'];
   }
   /**
    * ajax获取动态数量
    */
   function ajax_store_trend_countOp() {
      $count = Model('store_sns_tracelog')->getStoreSnsTracelogCount(array('strace_storeid' => $this->store_info['store_id']));
      echo json_encode(array('count' => $count));exit;
   }
   /**
    * ajax 店铺流量统计入库
    */
   public function ajax_flowstat_recordOp() {
      $store_id = intval($_GET['store_id']);
      if ($store_id <= 0 || $_SESSION['store_id'] == $store_id) {
         echo json_encode(array('done' => true, 'msg' => 'done'));die;
      }
      //确定统计分表名称
      $last_num       = $store_id % 10; //获取店铺ID的末位数字
      $tablenum       = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量
      $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';
      //判断是否存在当日数据信息
      $stattime = strtotime(date('Y-m-d', time()));
      $model    = Model('stat');
      //查询店铺流量统计数据是否存在
      $store_exist = $model->getoneByFlowstat($flow_tablename, array('stattime' => $stattime, 'store_id' => $store_id, 'type' => 'sum'));
      if ('goods' == $_GET['act_param'] && 'index' == $_GET['op_param']) { //统计商品页面流量
         $goods_id = intval($_GET['goods_id']);
         if ($goods_id <= 0) {
            echo json_encode(array('done' => false, 'msg' => 'done'));die;
         }
         $goods_exist = $model->getoneByFlowstat($flow_tablename, array('stattime' => $stattime, 'goods_id' => $goods_id, 'type' => 'goods'));
      }
      //向数据库写入访问量数据
      $insert_arr = array();
      if ($store_exist) {
         $model->table($flow_tablename)->where(array('stattime' => $stattime, 'store_id' => $store_id, 'type' => 'sum'))->setInc('clicknum', 1);
      } else {
         $insert_arr[] = array('stattime' => $stattime, 'clicknum' => 1, 'store_id' => $store_id, 'type' => 'sum', 'goods_id' => 0);
      }
      if ('goods' == $_GET['act_param'] && 'index' == $_GET['op_param']) { //已经存在数据则更新
         if ($goods_exist) {
            $model->table($flow_tablename)->where(array('stattime' => $stattime, 'goods_id' => $goods_id, 'type' => 'goods'))->setInc('clicknum', 1);
         } else {
            $insert_arr[] = array('stattime' => $stattime, 'clicknum' => 1, 'store_id' => $store_id, 'type' => 'goods', 'goods_id' => $goods_id);
         }
      }
      if ($insert_arr) {
         $model->table($flow_tablename)->insertAll($insert_arr);
      }
      echo json_encode(array('done' => true, 'msg' => 'done'));
   }
}

class sortClass {
   //升序
   public static function sortArrayAsc($preData, $sortType = 'store_sort') {
      $sortData = array();
      foreach ($preData as $key_i => $value_i) {
         $price_i    = $value_i[$sortType];
         $min_key    = '';
         $sort_total = count($sortData);
         foreach ($sortData as $key_j => $value_j) {
            if ($price_i < $value_j[$sortType]) {
               $min_key = $key_j + 1;
               break;
            }
         }
         if (empty($min_key)) {
            array_push($sortData, $value_i);
         } else {
            $sortData1 = array_slice($sortData, 0, $min_key - 1);
            array_push($sortData1, $value_i);
            if (($min_key - 1) < $sort_total) {
               $sortData2 = array_slice($sortData, $min_key - 1);
               foreach ($sortData2 as $value) {
                  array_push($sortData1, $value);
               }
            }
            $sortData = $sortData1;
         }
      }
      return $sortData;
   }
   //降序
   public static function sortArrayDesc($preData, $sortType = 'store_sort') {
      $sortData = array();
      foreach ($preData as $key_i => $value_i) {
         $price_i    = $value_i[$sortType];
         $min_key    = '';
         $sort_total = count($sortData);
         foreach ($sortData as $key_j => $value_j) {
            if ($price_i > $value_j[$sortType]) {
               $min_key = $key_j + 1;
               break;
            }
         }
         if (empty($min_key)) {
            array_push($sortData, $value_i);
         } else {
            $sortData1 = array_slice($sortData, 0, $min_key - 1);
            array_push($sortData1, $value_i);
            if (($min_key - 1) < $sort_total) {
               $sortData2 = array_slice($sortData, $min_key - 1);
               foreach ($sortData2 as $value) {
                  array_push($sortData1, $value);
               }
            }
            $sortData = $sortData1;
         }
      }
      return $sortData;
   }
}

?>
