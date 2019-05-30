<?php
/**
 * 店铺动态
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');
class serviceControl extends BaseGoodsControl{
	private $model;
	private $busy_time = 180; //设置忙线时间（单位秒）
	private $relation_lock_time = 0; //锁定客服与客户关系的时间（默认0,表示不限制），超过24小时系统重新分配客服
	
    public function __construct() {
        parent::__construct ();
		$this->model = Model('service');
    }
	
	/** 用户与客服聊天主页 **/
    public function chatOp(){
		$data = explode(',',$_GET['data']);
		$member_id = $data[5];
		$_SESSION['chat_from_id'] = $member_id;
		$store_id = $data[6];
		$member_name = $data[7];
		$pre_s = $this->model->get_pre_sale_service($store_id);
		$pre_member_id = array_column($pre_s,'num');
		$service_time = array_column($pre_s,'time','num');
		$service_priority = array_column($pre_s,'priority','num');
		$service_name = array_column($pre_s,'name','num');
		
		$chat_to_id = '';
		//验证最新对话
		if(!$chat_to_id = $this->check_relation($member_id,$pre_member_id)){
			//处理服务时间
			$in_service = array();
			$h_i = date("H:i");
			foreach($service_time as $k=>$v){
				$t_a = explode('-',$v);
				if($t_a[0] < $h_i && $t_a[1] > $h_i){
					$in_service[] = $k;
				}
			}
			$service_id = !empty($in_service) ? $in_service : $pre_member_id;
			//检测忙线客服
			if($busy = $this->check_busy($member_id,$service_id)){
				foreach($service_id as $k=>$v){
					if(in_array($v,$busy)){
						unset($service_id[$k]);
					}
				}
			}
			if(empty($service_id)){
				//当所有客服处于忙线，取接收消息最前着提供服务
				$chat_to_id = $busy[0];
			}else{
				//取处于非忙线，服务优先着
				foreach($service_priority as $k=>$v){
					if(!in_array($k,$service_id)){
						//移除不在服务时间的客服
						unset($service_priority[$k]);
					}
				}
				$chat_to_id = array_search(max($service_priority),$service_priority);
			}
		}
		$service_info['member_id'] = $chat_to_id;
		$service_info['name'] = $service_name[$chat_to_id];
		$service_info['headimg'] = $this->get_headimg($chat_to_id);
		$user_info['member_id'] = $member_id;
		$user_info['name'] = $member_name;
		$user_info['headimg'] = $this->get_headimg($member_id);
		$data['page'] = 0;
		$_SESSION['SERVICE_CHAT_PAGE'] = 0;
		$data['list'] = $this->model->get_chat_list($member_id,$chat_to_id,0);
		Tpl::output('chat_data',$data);
		Tpl::output('service_info',$service_info);
		Tpl::output('user_info',$user_info);
		Tpl::output('goods_info',$data);
        Tpl::showpage('service_chat');
    }
	
	//发送消息
	public function send_chat_msgOp(){
		$from_id = $_POST['from_id'];
		$from_name = $_POST['from_name'];
		$to_id = $_POST['to_id'];
		$to_name = $_POST['to_name'];
		$msg = $_POST['msg'];
		if($data = $this->model->send_msg($from_id, $from_name , $to_id, $to_name, $msg)){
			echo json_encode(array(
				'respond'=>'1',
				'data'=>array(0=>$data)
			),true);
		}else{
			echo json_encode(array(
				'respond'=>'0',
				'msg'=>'系统繁忙！请重发'
			),true);
		}
	}
	
	//前段轮询接口
	public function ajax_pollingOp(){
		$from_id = $_POST['from_id'];
		$to_id = $_POST['to_id'];
		if($this->model->check_new_msg($from_id,$to_id)){
			$_SESSION['SERVICE_CHAT_PAGE'] = 0;
			$new_msg = $this->model->get_new_msg($from_id,$to_id);
			if($new_msg){
				echo json_encode(array('respond'=>'1','data'=>$new_msg),true);
			}
		}
	}
	
	//获取头像函数
	public function get_headimg($member_id){
		$headimg = '';
		$avater = Model('member')->where(array('member_id' => $member_id))->field('member_avatar')->find();
		return $avater['member_avatar'];exit();
		/*
		$wx = Model('wx_member')->where(array('member_id' => $member_id))->field('headimgurl,openid')->find();
		if($wx['headimgurl']){
			$headimg = $wx['headimgurl'];
		}else{
			$headimg = '/data/weixin/head/'.$wx['openid'] .'_weixin.jpg';
		}
		return $headimg;*/
	}
	/** 检测忙线客服 **/
	private function check_busy($user_id, $pre_array){
		$pre_str = implode(',',$pre_array);
		$busy_sql = "AND add_time >= ".(time()-$this->busy_time);
		$sql = "SELECT * FROM az_chat_log WHERE (f_id = {$user_id} AND t_id IN ({$pre_str})) {$busy_sql} GROUP BY t_id ORDER BY add_time ASC";
		$result = Model()->query($sql);
		return !empty($result) ? array_column($result,'t_id') : '';
	}
	
	/** 检测用户最新是否与客服联系　注同时存在多个客服对话取最新对话**/
	private function check_relation($user_id,$pre_array){
		$pre_str = implode(',',$pre_array);
		$relation_lock = '';
		if($this->relation_lock_time){
			$relation_lock = "AND add_time >= ".(time()- $this->relation_lock_time);
		}
		$sql = "SELECT * FROM az_chat_log WHERE (f_id = {$user_id} AND t_id IN ({$pre_str})) {$relation_lock} GROUP BY t_id ORDER BY add_time DESC";
		$result = Model()->query($sql);
		return $result[0]['t_id'];
	}
	
	public function uploadOp(){
		$upload = new UploadFile();
        $upload->set('default_dir', '/chat/'.$_SESSION['chat_from_id'].'/');
        $upload->set('max_size', C('image_max_filesize'));
        $upload->set('thumb_width', "240");
        $upload->set('thumb_height', "240");
        $upload->set('thumb_ext', "_240");
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        if($upload->upfile('file')){
			$img_p = 'http://'.$_SERVER['SERVER_NAME'].'/data/upload/chat/'.$_SESSION['chat_from_id'].'/'. $upload->file_name;
			$_a = explode('.',$upload->file_name);
			$img_a = 'http://'.$_SERVER['SERVER_NAME'].'/data/upload/chat/'.$_SESSION['chat_from_id'].'/'.$_a[0]."_240.".$_a[1];
			$return = array(
				'yt_url'=>$img_p,
				'sl_url'=>$img_a
			);
			echo json_encode($return,true);
		}
	}
}
