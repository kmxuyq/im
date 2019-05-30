<?php
defined ( 'InShopNC' ) or exit ( 'Access Invalid!' );
/**
 * @author 
 * 抽奖活动个人信息
 */
class activity_personControl extends BaseHomeControl {
	public function __construct() {
		parent::__construct ();
		Language::read('active');
		//重置layout
		Tpl::setLayout('null_layout');
		//重置目录
		Tpl::setDir('draw');
	}
	/**
	 * 会员签到页面
	 */
	public function pastOp() {
 		// 会员免费登录
		if (!$_SESSION['member_id']) {
			unset($_SESSION['is_login']);
			$this->checkWxLogin();
			return false;
		} else {
			// 免登成功
			//1、校验参数
			if (empty($_GET['activity_id']) || empty($_GET ['store_id'] )) {
				showMessage(Language::get('para_error' ),'','html','error' );
			}			
			$activity_id = intval($_GET['activity_id']);
			$store_id    = intval($_GET['store_id']);
// 			$activity_id = 3;
// 			$store_id    = 7;
			//活动是否还在		
			$activity = Model('activity')->getOneById ($activity_id);
			//只查询类型:抽奖，状态：开启 ,活动有效
			if (empty ( $activity ) || $activity ['activity_type'] != '3' || $activity ['activity_state'] != 1 
					  || $activity ['activity_start_date'] > time () || $activity ['activity_end_date'] < time ()) {
				showMessage(Language::get('activity_index_activity_not_exists' ),'','html','error' ); // '指定活动并不存在'
			}
			Tpl::output('activity_info',array('activity_id'=>$activity_id,'store_id'=>$store_id));
			
            //得到当前人的信息全部从session里获取
// 			$member_info =array('member_id'     =>'76676',
// 					            'member_name'   =>'阮红勇',
// 								'member_avatar' =>'ocuxlxMyvoq4tyHhEbRtQ0QzxcFk_weixin.jpg');
		    $member_info =array('member_id'     =>$_SESSION['member_id'],
		    		            'member_name'   =>$_SESSION['member_name'],
		    		            'member_avatar' => $_SESSION['avatar']);
		    Tpl::output('member_info',$member_info);
            //对应的抽奖人数
			$personModel = Model('activity_person');
			$return = $personModel->getGetJoinPersonList(array(
						'activity_id'=>$activity_id,
						'store_id'=>$store_id
			),'');
			//针对时间的特殊调整
			for($k= 0;$k<count($return);$k++){
				$return[$k]['addtimeStr'] = $this->time_tranx($return[$k]['addtime']);
			}			
			Tpl::output('pesonList' ,$return);
			//校验该人员是否已经签到
			$checkDate=$personModel->getOneByCondition(
				 array('activity_id'=>$activity_id,'store_id'=>$store_id,
				 		'member_id'=>$member_info['member_id']));
			if($checkDate){
				Tpl::output('existflag' ,true);
			}else{
				Tpl::output('existflag' ,false);
			}
			Tpl::output('html_title','签到');
			Tpl::showpage('activity_past' );
		}
	}
	
	public function  getPersonDataOp(){
		if (empty($_POST['activity_id']) || empty($_POST['store_id'] )) {
			exit(json_encode(array('state'=>0,'msg'=>'请传递正确的参数')));
		}
		$activity_id = intval($_POST['activity_id']);
		$store_id    = intval($_POST['store_id']);
		//对应的抽奖人数
		$personModel = Model('activity_person');
		$return = $personModel->getGetJoinPersonList(array(
				'activity_id'=>$activity_id,
				'store_id'=>$store_id
		),'');
	    if(empty($return)){
	    	exit(json_encode(array('state'=>0,'msg'=>'没有找到数据')));
	    }else{
	        foreach($return as $k => $value){
	        	$arr[$k]['member_name']    = $value['member_name'];
	        	$arr[$k]['addtimeStr']     = $this->time_tranx($value['addtime']);
	        	$arr[$k]['member_avatar']  = getMemberAvatar($value['member_avatar']);
	        }
	        exit(json_encode(array('state'=>1,'msg'=>$arr)));
	    }
	}
	
	/**
	 * 会员报名，签到 save校验
	 */
	public function pastSaveOp(){
		//校验参数
		if(empty($_POST['activity_id']) || empty($_POST['store_id']) || empty($_POST['member_id'])) {
			exit(json_encode(array('state'=>0,'msg'=>Language::get('para_error'))));
		}
		$activity_id =$_POST['activity_id'] ;
		$store_id    =$_POST['store_id'];
		$member_id   =$_POST['member_id'];
		//校验
		$activity_model	= Model('activity');
		$activity_info	= $activity_model->getOneById($activity_id);
		//1、类型抽奖,没有关闭 ,进行中
		if(empty($activity_info) || $activity_info['activity_type'] != '3' || $activity_info['activity_state'] != 1 || $activity_info['activity_start_date']>time() || $activity_info['activity_end_date']<time()){
			exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_index_activity_not_exists'))));
		}
		//2、校验活动规则
		$rule_model	= Model('activity_rule');
		$ruleinfo = $rule_model->getRuleByCondtion(array('activity_id'=>$activity_id,'store_id'=>$store_id));
		if(empty($ruleinfo)){
			exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_activity_rule_err'))));
		}
		//2、1 是否校验
		if($ruleinfo['is_check']){
		    //需要校验 2、1、1 校验是否收藏店铺
		    if($ruleinfo['is_collect']){
		    	$favorites = Model('favorites')->getOneFavorites(array('member_id'=>$member_id,'fav_type'=>'store','fav_id'=>$store_id));
		    	if(empty($favorites)){
		    		exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_activity_rule_err_collect'))));
		    	}
		    }
		    //2、1、2 校验购买过本店商品
		    if($ruleinfo['is_buy']){
		    	 $order = Model('order')->getOrderInfo(array('buyer_id'=>$member_id,'store_id'=>$store_id,'order_state'=>'20'));
		    	 if(empty($order)){
		    	 	exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_activity_rule_err_buy'))));
		    	 }
		    }
		}
	    $personModel = Model('activity_person');
	    $return = $personModel->getOneByCondition(array(
	    		'activity_id'=>$activity_id,
	    		'store_id'	 =>$store_id,
	    		'member_id'  =>$member_id
	    ));
		if($return){
			//已经报名，签到
			exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_activity_person_exists'))));
		}else{
		   //塞入
			$addReturn= $personModel->add(
					array('activity_id'=>$activity_id,'store_id'=>$store_id,
						  'member_id'=>$member_id,'addtime' => TIMESTAMP	
					));
			if($addReturn){
				exit(json_encode(array('state'=>1,'msg'=>Language::get('activity_activity_past_suc'))));
			}else{
				exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_activity_person_exists'))));
			}
		}
	}
	/**
	 * 展示奖品
	 */
	public function showPrizeOp(){
		//校验参数
		if(empty($_GET['activity_id']) || empty($_GET['store_id']) || empty($_GET['member_id']) ) {
			showMessage(Language::get('para_error'),'','html','error');
		}
		$activity_id =$_GET['activity_id'];
		$store_id    =$_GET['store_id'];
		$member_id   =$_GET['member_id'];
		//得到activity
		$activity_model	= Model('activity');
		$activity_info	= $activity_model->getOneById($activity_id);
		Tpl::output ('activity_info',$activity_info);
		//得到rule规则
		$ruleModel = Model('activity_rule');
	    $ruleData = $ruleModel->getRuleByCondtion(array('activity_id'=>$activity_id,'store_id'=>$store_id));
		if(empty($ruleData)){
			showMessage(Language::get('activity_activity_rule_err'),'','html','error' );//没有配置规则
		}
		Tpl::output ('ruleData',$ruleData);
		//没有到时间
	    if($ruleData['activity_time'] > TIMESTAMP){
	    	$activitytime  =doubleval($ruleData['activity_time']);
	    	//小时
	    	$h=floor(($activitytime-TIMESTAMP)/3600);
	    	$m=floor((($activitytime-TIMESTAMP)%3600)/60);
	    	$s=($activitytime-TIMESTAMP)%60;
	    	Tpl::output ('moreflag',true);
	    	Tpl::output ('h',$h);
	    	Tpl::output ('i',$m);
	    	Tpl::output ('s',$s);
	    //已经到了
	    }else{
	    	Tpl::output ('moreflag',false);
	    }
	    
		//查询活动内容信息
		$list = Model ('activity_detail')->getDetailAndDetailPerson(array (
					   'activity_detail.activity_id' =>"$activity_id",
					   'activity_detail.store_id'    =>$store_id
		));
		
		//查询活动内容信息 按照奖项groupby
		$list = Model ('activity_detail')->getGoodsListByGroup(array(
				'activity_detail.activity_id' =>"$activity_id",
				'activity_detail.store_id'    =>$store_id
		));
		
		Tpl::output('member_id',$member_id);
		Tpl::output ('list', $list);
		Tpl::output ('html_title','抽奖奖品');
		Tpl::showpage ('showPrize');
	}
	/**
	 * 获取倒计时
	 */
	public function getCountdownOp(){
		$activitytime  =doubleval($_POST['activitytime']);
		if($activitytime >= TIMESTAMP){
			//小时
			$h=floor(($activitytime-TIMESTAMP)/3600);
			$m=floor((($activitytime-TIMESTAMP)%3600)/60);
			$s=($activitytime-TIMESTAMP)%60;
			exit(json_encode(array('state'=>'1','h'=>sprintf("%02d", $h),'m'=>sprintf("%02d", $m),'s'=>sprintf("%02d", $s))));
		}else{
			exit(json_encode(array('state'=>'2')));
		}
	}
	/**
	 * 抽奖页面
	 */
	public function  myDrawPrizeOp(){
		//校验参数
		if(empty($_GET['activity_id']) || empty($_GET['store_id']) || empty($_GET['activity_detail_sort'])) {
			showMessage(Language::get('para_error'),'','html','error');
		}
		$activity_id   			=$_GET['activity_id'];
		$store_id      			=$_GET['store_id'];
		$activity_detail_sort   =$_GET['activity_detail_sort'];
	  
		Tpl::output ('activity_detail_sort',$activity_detail_sort);
		
		//得到rule规则
		$ruleData = Model('activity_rule')->getRuleByCondtion(
				  array('activity_id'=>$activity_id,'store_id'=>$store_id));
		if(empty($ruleData)){
			showMessage(Language::get('activity_activity_rule_err'),'','html','error' );//没有配置规则
		}
		//校验时间
		if($ruleData['activity_time'] > TIMESTAMP){
			Tpl::output ('ruleData',$ruleData);
			Tpl::output ('html_title','等待开始时间');
		    Tpl::showpage ('showPrize_null');
		    exit();
		}
		Tpl::output('ruleData',  $ruleData);
		
        //得到该项奖品的中间人
		$giftpersoninfo =Model('activity_person')->getGetGiftPersonList
		                (array('activity_id'=>$activity_id,'store_id'=>$store_id,
		              		   'activity_detail_sort'=>$activity_detail_sort),'');
        //得到所有detail奖品信息
        $detailinfo  =Model('activity_detail')->getGoodsList(
                 		array('activity_id' =>$activity_id,
                 			  'store_id'    =>$store_id,
                 			  'activity_detail_sort'=>$activity_detail_sort
        ),'');
		if(count($giftpersoninfo) > count($detailinfo)){
			showMessage(Language::get('activity_gift_more_err'),'','html','error' );//中奖的人员比奖品数量多
		}else if(count($giftpersoninfo) == count($detailinfo)){
			Tpl::output('drawflag',true);
			Tpl::output('giftPerson',$giftpersoninfo);
		}else {
			Tpl::output('drawflag',false);
			Tpl::output('giftPerson',$giftpersoninfo);
		}
		//找到参与抽奖的人
		$personinfo  =Model('activity_person')->getGetJoinPersonList
		               (array('activity_id'=>$activity_id,'store_id'=>$store_id,'activity_state'=>'0'),'');
		Tpl::output('personinfo',$personinfo);
		
		Tpl::output ('html_title','抽奖');
		Tpl::showpage ('myDrawPrize');
	}
	/**
	 * 
	 */
	public function  getDrawPersonOp(){	
		//校验参数
		if(empty($_POST['activity_id']) || empty($_POST['store_id']) || empty($_POST['activity_detail_sort'])
			|| !isset($_POST['member_ids'])	) {
			exit(json_encode(array('state'=>0,'msg'=>'传递正确的参数')));	
		}
		$activity_id 			=$_POST['activity_id'];
		$store_id    			=$_POST['store_id'];
		$activity_detail_sort   =$_POST['activity_detail_sort'];
		$member_ids   			=$_POST['member_ids'];
		//得到该项奖品的中间人
		$giftpersoninfo =Model('activity_person')->getGetGiftPersonList
		(array('activity_id'=>$activity_id,'store_id'=>$store_id,
			   'activity_detail_sort'=>$activity_detail_sort
		),'');
		//得到所有detail奖品信息
		$detailinfo  =Model('activity_detail')->getGoodsList(
				array('activity_id' =>$activity_id,
					  'store_id'  =>$store_id,
					  'activity_detail_sort'=>$activity_detail_sort
				),'');
		if(count($giftpersoninfo)>count($detailinfo)){
			exit(json_encode(array('state'=>0,'msg'=>Language::get('activity_gift_more_err'))));
		}else{
			$condition = array('activity_id'=>$activity_id,
					           'store_id'=>$store_id,
					'activity_detail_sort'=>$activity_detail_sort,
					'order' => 'activity_person.active_time desc'
			);
			//处理传递来的member_ids数组
			if(!empty($member_ids)){
				//去除
				$member_ids = substr($member_ids,0,strlen($member_ids)-1);
				$condition['member_id_not_in'] =$member_ids;				
			}
			$last = Model('activity_person')->getGetGiftPersonList($condition,'');
			if(!empty($last)){
				if(count($giftpersoninfo) == count($detailinfo)){
					exit(json_encode(array('state'=>1,'state1'=>'1',
						'mid'=>$last[0]['member_id'],
				        'msg'=>$last[0]['member_name'],
						'img'=>getMemberAvatar($last[0]['member_avatar']),
						'pesontime'=>date('m月d日  H:i',$last[0]['active_time']))));
				}else{
					exit(json_encode(array('state'=>2,'state1'=>'1',
					    'mid'=>$last[0]['member_id'],
						'msg'=>$last[0]['member_name'],
						'img'=>getMemberAvatar($last[0]['member_avatar']),
						'pesontime'=>date('m月d日  H:i',$last[0]['active_time']))));
				} 
			}else{
				if(count($giftpersoninfo) == count($detailinfo)){
					exit(json_encode(array('state'=>1,'state1'=>'2','msg'=>'抽奖结束，请关注下次开奖')));
				}else{
					exit(json_encode(array('state'=>2,'state1'=>'2','msg'=>'还存在')));
				}
			}
		}
	}
	/**
	 * 时间转换
	 * @param unknown $the_time
	 * @return unknown|string
	 */
	private function time_tranx($the_time){
			$now_time   = TIMESTAMP;
			$dur = $now_time - $the_time;
			//小于
			if($dur < 0){
				return '0秒前';
			}
			//秒
			if($dur < 60){
				return $dur.'秒前';
			}
			//分
			if($dur < 3600){
				return floor($dur/60).'分钟前';
			}
			//小时
			if($dur < 86400){
				return floor($dur/3600).'小时前';
			}
			//天
			if($dur < 604800){
				return floor($dur/86400).'天前';
			}
			//星期
			if($dur < 2592000){
				return floor($dur/604800).'星期前';
			}
			//月
			if($dur < 31104000){
				return floor($dur/2592000).'月前';
		    //年
			}else{
				return floor($dur/31104000).'年前';
			}
		}
}
?>
