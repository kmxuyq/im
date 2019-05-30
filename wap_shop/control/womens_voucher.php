<?php

/**
 * 代金券活动
 * @author susu
 *
 */
class womens_voucherControl extends BaseHomeControl {
   private $voucher_t_id_str = '30,31'; //(30,31)';//顺序是30元，50元
   public function indexOp() {
      Tpl::showpage('womens_voucher', 'null_layout');
   }
   public function testOp() {
      if (!isset($_SESSION['member_id']) or empty($_SESSION['member_id'])) {
         die(json_encode(['state' => 'login', 'msg' => '请先登录！']));
      }
      $tid = intval($_GET['tid']);
      if(empty($tid)){
         die(json_encode(['state' => 0, 'msg' => '无效代金券']));
      }
      $store_id = intval($_SESSION['route_store_id']);
      $model_template = Model('voucher_template');
      $model_voucher  = Model()->table('voucher');
      $template       = $model_template->where('voucher_t_id= ' . $tid . ' and voucher_t_store_id=' . $store_id . ' and voucher_t_state=1 and voucher_t_end_date>' . TIMESTAMP . ' and voucher_t_used<voucher_t_limit')->find();
      if (empty($template)) {
         die(json_encode(['state' => 'false', 'msg' => '没有代金券']));
      }
      if ($template['voucher_t_eachlimit'] > 0) {
         $total = $model_voucher->where(['voucher_t_id' => $template['voucher_t_id'], 'voucher_store_id' => $store_id, 'voucher_owner_id' => $_SESSION['member_id']])->count();
         if ($total > $template['voucher_t_eachlimit']) {
            die(json_encode(['state' => 'false', 'msg' => '领取代金券超过限制']));
         }
      }
      $data = [
         'voucher_code'        => $this->get_voucher_code($_SESSION['member_id']),
         'voucher_t_id'        => $template['voucher_t_id'],
         'voucher_title'       => $template['voucher_t_title'],
         'voucher_desc'        => $template['voucher_t_desc'],
         'voucher_start_date'  => TIMESTAMP,
         'voucher_end_date'    => $template['voucher_t_end_date'],
         'voucher_price'       => $template['voucher_t_price'],
         'voucher_limit'       => $template['voucher_t_limit'],
         'voucher_store_id'    => $template['voucher_t_store_id'],
         'voucher_state'       => 1,
         'voucher_active_date' => TIMESTAMP,
         'voucher_owner_id'    => $_SESSION["member_id"],
         'voucher_owner_name'  => $_SESSION["member_name"],
      ];
      if ($model_voucher->insert($data)) {
         die(json_encode(['state' => 'true', 'msg' => '成功领取“' . $template['voucher_t_title'] . '”']));
      } else {
         die(json_encode(['state' => 'false', 'msg' => '提交失败！']));
      }
   }
   //领取代金券
   public function voucherOp() {

      if (empty($_GET["type"])) {
         exit();
      }

      $voucher_t_id_arr = explode(',', $this->voucher_t_id_str);
      $voucher_price    = decrypt($_GET["type"]);
      $voucher_t_id     = str_replace(array('a30', 'a50'), array($voucher_t_id_arr[0], $voucher_t_id_arr[1]), 'a' . $voucher_price);
      //写入代金券
      if (isset($_SESSION["member_id"])) {
         //查看是否领取过
         $is_voucher = $this->check_is_voucher($voucher_t_id);
         if (!$is_voucher) {
            //添加代金券信息
            $template_info                     = Model('voucher_template')->where(array('voucher_t_id' => $voucher_t_id))->find();
            $insert_arr                        = array();
            $insert_arr['voucher_code']        = $this->get_voucher_code($_SESSION["member_id"]);
            $insert_arr['voucher_t_id']        = $template_info['voucher_t_id'];
            $insert_arr['voucher_title']       = $template_info['voucher_t_title'];
            $insert_arr['voucher_desc']        = $template_info['voucher_t_desc'];
            $insert_arr['voucher_start_date']  = strtotime('2016-03-18');
            $insert_arr['voucher_end_date']    = $template_info['voucher_t_end_date']; //time()+365*86400;//
            $insert_arr['voucher_price']       = $template_info['voucher_t_price'];
            $insert_arr['voucher_limit']       = $template_info['voucher_t_limit'];
            $insert_arr['voucher_store_id']    = $template_info['voucher_t_store_id'];
            $insert_arr['voucher_state']       = 1;
            $insert_arr['voucher_active_date'] = time();
            $insert_arr['voucher_owner_id']    = $_SESSION["member_id"];
            $insert_arr['voucher_owner_name']  = $_SESSION["member_name"];
            $result                            = Model()->table('voucher')->insert($insert_arr);
            if ($result) {
               exit(json_encode(array('state' => 'true', 'msg' => '领取成功！')));
               //callmsg('红包领取成功！','?act=member_voucher');
            } else {
               exit(json_encode(array('state' => 'false', 'msg' => '提交失败！')));
            }
         } else {
            exit(json_encode(array('state' => 'false', 'msg' => '您已经领取过了！')));
         }
      } else {
         exit(json_encode(array('state' => 'login', 'msg' => '请先登录！')));
      }
   }
   //代金券领取校验，是否登陆和是否领取过
   public function voucher_checkOp() {
      if (isset($_SESSION["member_id"])) {
         //查看是否领取过
         $voucher_count                 = count(explode(',', $this->voucher_t_id_str));
         $condition['voucher_owner_id'] = $_SESSION["member_id"];
         $condition['voucher_t_id']     = array('in', $this->voucher_t_id_str);
         $is_voucher_count              = Model()->table('voucher')->where($condition)->count('voucher_id');
         if ($is_voucher_count == $voucher_count) {
            exit(json_encode(array('state' => 'false', 'msg' => '您已经领取过了！')));
         } else {
            exit(json_encode(array('state' => 'true', 'msg' => '')));
         }
      } else {
         exit(json_encode(array('state' => 'login', 'msg' => '请先登录！')));
      }
   }
   /**
    * 返回1为已领取过，0为可以领取
    */
   private function check_is_voucher($voucher_t_id) {
      $condition['voucher_owner_id'] = $_SESSION["member_id"];
      $condition['voucher_t_id']     = $voucher_t_id;

      //$condition["voucher_active_date"]=array('gt'=>strtotime(date('Y').'-03-01'),'lt'=>strtotime(date('Y').'-03-30'));
      $is_voucher = Model()->table('voucher')->where($condition)->count('voucher_id');
      return $is_voucher;
   }
   /**
    * 获取代金券编码
    */
   private function get_voucher_code($member_id) {
      return mt_rand(10, 99)
      . sprintf('%010d', time() - 946656000)
      . sprintf('%03d', (float) microtime() * 1000)
      . sprintf('%03d', (int) $member_id % 1000);
   }
   // public function testOp() {
   //    echo "<meta charset='utf-8'/>";
   //    echo "30:" . encrypt(30) . "，50:" . encrypt(50);
   //    //30:gPgFgODF9JYwgLhCIPyg8QQ，50:TofeDodAhWmAsRmSGQoMOVO
   // }
   //代金券领取校验，是否登陆和是否领取过
   public function voucher_check_testOp() {
      if (isset($_SESSION["member_id"])) {
         //查看是否领取过
         $voucher_count                 = count(explode(',', $this->voucher_t_id_str));
         $condition['voucher_owner_id'] = $_SESSION["member_id"];
         $condition['voucher_t_id']     = array('in', $this->voucher_t_id_str);
         $is_voucher_count              = Model()->table('voucher')->where($condition)->count('voucher_id');
         if ($is_voucher_count == $voucher_count) {
            //exit('no');
            exit('false|您已经领取过了！');
         } else {
            //exit('yes');
            exit('true|');
         }
      } else {
         //exit('login');
         exit('login|请先登录！');
      }
   }
}
