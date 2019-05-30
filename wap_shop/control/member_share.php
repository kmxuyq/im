<?php
/**
 * 用户中心微信模板
 * @author susu
 */
defined('InShopNC') or exit('Access Invalid!');
class member_shareControl extends BaseMemberControl {
   //构造函数 
   public function __construct() {
	  parent::__construct();
	  //直接进入推客中心， 没有获取到的$_SESSION['share_store_id'] 就给 赋值
	  if(!intval($_SESSION['share_store_id'])){
	  	//优先级  $_SESSION['store_member_info_ID'] 》》  $_GET['store_id']  》》  7 
	  	$_SESSION['share_store_id'] = 
	  	   intval($_SESSION['store_member_info_ID'])?$_SESSION['store_member_info_ID'] : 
	  	   (intval($_GET['store_id'])?$_GET['store_id']: 7 ) ;
	  }
	  //同时对$_SESSION['share_shop']如果没有值，那么就赋值分销
	  if(!isset($_SESSION['share_shop'])){
	  	 $_SESSION['share_shop'] = 1; //分销
	  } 
   }
   public function indexOp() {
      $share_member = Model('share_member')->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      $total        = Model()->query('select count(*) as total from ' . DBPRE . 'share_confirm c WHERE store_id=' . $_SESSION['share_store_id'] . ' and member_id IN(select member_id from ' . DBPRE . 'share_member m WHERE m.member_id=c.member_id and m.pid=' . $_SESSION['member_id'] . ')');
      Tpl::output('share_member', $share_member);
      Tpl::output('total', empty($total) ? 0 : $total[0]['total']);
      Tpl::showpage('member_share.index');
   }
   /**
    * 我的下线
    */
   public function lower_memberOp() {
      $model             = Model('share_member');
      $share_member_info = $model->where(array('store_id' => $_SESSION['share_store_id'], 'member_id' => $_SESSION['member_id']))->find();
      if (1 != $share_member_info['isshare'] and 1 != $share_member_info['status']) {
         showMessage('无权访问', '', '', 'error');
      }
      $level    = intval($_GET['level']) == 2 ? 2 : 1;
      $lv1_list = $model->where(array('store_id' => $_SESSION['share_store_id'], 'pid' => $_SESSION['member_id']))->select();
      if (2 == $level) {
         $lv2_pids = array(-1);
         foreach ($lv1_list as $item) {
            $lv2_pids[] = $item['member_id'];
         }
         $lv2_list = $model->where(array('store_id' => $_SESSION['share_store_id'], 'pid' => array('IN', implode(',', $lv2_pids))))->select();
      }
      Tpl::output('level', $level);
      Tpl::output('list', 1 == $level ? $lv1_list : $lv2_list);
      Tpl::output('share_member_info', $share_member_info);
      Tpl::showpage('member_family');
   }

   public function rankOp() {
      $model = Model();
      $table = DBPRE . 'share_member';
      $list  = $model->query("SELECT d.*,(	SELECT count(*) FROM {$table} c WHERE c.pid=d.member_id) AS total2 FROM ( SELECT b.*, a.total FROM ( SELECT count(*) AS total, pid FROM {$table} WHERE `status` = 1 AND store_id = '{$_SESSION['share_store_id']}' AND IFNULL(pid, 0) > 0 GROUP BY pid ) a LEFT JOIN {$table} b ON a.pid = b.member_id ) d ORDER BY d.total LIMIT 5");
      Tpl::output('list', $list);
      Tpl::showpage('member_share.rank');
   }

   public function applyOp() {
      $model = Model('share_confirm');
      $info  = $model->where(array('member_id' => $_SESSION['member_id'], 'store_id' => $_SESSION['share_store_id']))->find();
      if (!empty($info) and 0 == $info['status']) {
         showMessage('已提交过申请，请耐心等待审核', '', '', 'error');
      } elseif (1 == $info['status']) {
         showMessage('你的申请已经通过，跳转到推客中心', '/wap_shop/index.php?act=member_share&op=index', '', 'succ');
      }
      if (!empty($_POST)) {
         $data = array(
            'store_id'    => $_SESSION['share_store_id'] ? $_SESSION['share_store_id'] : $_SESSION['store_id'],
            'member_id'   => $_SESSION['member_id'],
            'member_name' => $_SESSION['member_name'],
            'addtime'     => TIMESTAMP,
            'name'        => trim($_POST['name']),
            'mobile'      => trim($_POST['mobile']),
            'status'      => 0,
            'remark'      => '',
         );
         if (empty($data['name'])) {
            showMessage('请输入姓名', '', '', 'error');
         }
         if (empty($data['mobile'])) {
            showMessage('请输入电话号码', '', '', 'error');
         }
         if ($model->insert($data)) {
            showMessage('申请提交成功，请耐心等待审核', '/wap_shop/index.php?act=member&op=home', '', 'success');
         } else {
            showMessage('申请提交失败，请重试', '', '', 'error');
         }
      }
      Tpl::showpage('member_share.apply');
   }

   public function recordOp() {
      $model        = Model('share_cmm_log');
      $store_id     = $_SESSION['share_store_id'] ? $_SESSION['share_store_id'] : $_SESSION['store_id'];
      $psize        = 15;
      $page         = max(1, intval($_GET['page']));
      $offset       = ($page - 1) * $psize;
      $where        = array('store_id' => $store_id, 'mid' => $_SESSION['member_id']);
      $total        = $model->where($where)->count();
      $list         = $model->where($where)->order('addtime DESC')->page($psize, $total)->limit("{$offset}, {$psize}")->select();
      $share_member = Model('share_member')->where(array('store_id' => $store_id, 'member_id' => $_SESSION['member_id']))->find();
      Tpl::output('list', $list);
      Tpl::output('share_member', $share_member);
      Tpl::output('show_page', $model->showpage());
      Tpl::showpage('member_share.record');
   }
}
?>
