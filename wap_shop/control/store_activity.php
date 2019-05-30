<?php
/**
 * 卖家活动管理
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');
class store_activityControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    public function indexOp() {
        $this->store_activityOp();
    }
    /**
     * 随机生成字符串
     * @param unknown $length
     * @return Ambigous <NULL, string>
     */
  	private function getRandChar($length){
	  	$str = null;
	   	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	   	$max = strlen($strPol)-1;
	   	for($i=0;$i<$length;$i++){
	    	$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	   	}
	    return $str;
  	}

 	/**
	 * 活动管理
	 */
	public function store_activityOp(){
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		$activity	= Model('activity');
		//活动为商品活动，并且为开启状态   
		//modify 商品活动，或者抽奖
		$list	= $activity->getList(array('activity_type'=>array(1,3),'opening'=>true,'order'=>'activity.activity_sort asc'),$page);
		/**
		 * 页面输出
		 */
		Tpl::output('list',$list);
		Tpl::output('show_page',$page->show());
		self::profile_menu('store_activity');
		Tpl::showpage('store_activity.list');
	}
	/**
	 * 参与活动
	 */
	public function activity_applyOp(){
		//根据活动编号查询活动信息
		$activity_id = intval($_GET['activity_id']);
		if($activity_id <= 0){
			showMessage(Language::get('para_error'),'index.php?act=store_activity&op=store_activity','html','error');
		}
		$activity_model	= Model('activity');
		$activity_info	= $activity_model->getOneById($activity_id);
		//活动类型必须是商品,抽奖并且活动没有关闭并且活动进行中
		if(empty($activity_info) || $activity_info['activity_type'] == '2' || $activity_info['activity_state'] != 1 || $activity_info['activity_start_date']>time() || $activity_info['activity_end_date']<time()){
			showMessage(Language::get('store_activity_not_exists'),'index.php?act=store_activity&op=store_activity','html','error');
		}
		Tpl::output('activity_info',$activity_info);
		$list	= array();//声明存放活动细节的数组
		//查询商品分类列表
		$gc	= Model('goods_class');
		$gc_list	= $gc->getTreeClassList(3);
		foreach($gc_list as $k=>$gc){
			$gc_list[$k]['gc_name']	= '';
			$gc_list[$k]['gc_name']	= str_repeat("&nbsp;",$gc['deep']*2).$gc['gc_name'];
		}
		Tpl::output('gc_list',$gc_list);
		//查询品牌列表
		$brand	= Model('brand');
		$brand_list	= $brand->getBrandList(array());
		Tpl::output('brand_list',$brand_list);
		//查询活动细节信息
		$activity_detail_model	= Model('activity_detail');
		$list	= $activity_detail_model->getGoodsJoinList(array('activity_id'=>"$activity_id",'store_id'=>"{$_SESSION['store_id']}",'activity_detail_state_in'=>"'0','1','3'",'order'=>'activity_detail_sort'));
		//抽奖活动的奖品是可以重复选择的
		if($activity_info['activity_type'] != '3'){
			//构造通过与审核中商品的编号数组,以便在下方待选列表中,不显示这些内容
			$item_ids	= array();
			if(is_array($list) and !empty($list)){
				foreach($list as $k=>$v){
					$item_ids[]	= $v['item_id'];
				}
			}	
		}
		Tpl::output('list',$list);
		
		//查询活动规则
		$activity_rule_model	= Model('activity_rule');
		$activity_rule = $activity_rule_model->getRuleByCondtion(array('activity_id'=>$activity_id,'store_id'=>"{$_SESSION['store_id']}"));
		Tpl::output('activity_rule',$activity_rule);
		
		//根据查询条件查询商品列表
		$condition	= array();
		if($_GET['gc_id']!=''){
			$condition['gc_id']	= intval($_GET['gc_id']);
		}
		if($_GET['brand_id']!=''){
			$condition['brand_id']	= intval($_GET['brand_id']);
		}
		if(trim($_GET['name'])!=''){
			$condition['goods_name'] = array('like' ,'%'.trim($_GET['name']).'%');
		}
		$condition['store_id']		= $_SESSION['store_id'];
		if (!empty($item_ids)){
			$condition['goods_id']	= array('not in', $item_ids);
		}
		$model_goods	= Model('goods');
		$goods_list	= $model_goods->getGoodsOnlineList($condition,'*', 10);
		Tpl::output('goods_list',$goods_list);
		Tpl::output('show_page',$model_goods->showpage());
		Tpl::output('search',$_GET);
		/**
		 * 页面输出
		 */
		self::profile_menu('store_activity');
		Tpl::showpage('store_activity.apply');
	}

 	/**
	 * 活动申请保存
	 */
	public function activity_apply_saveOp(){
		//判断页面参数
		if(empty($_POST['item_id'])){
			showMessage(Language::get('store_activity_choose_goods'),'index.php?act=store_activity&op=store_activity','html','error');
		}
		$activity_id = intval($_POST['activity_id']);
		if($activity_id <= 0){
			showMessage(Language::get('para_error'),'index.php?act=store_activity&op=store_activity','html','error');
		}
		//根据页面参数查询活动内容信息，如果不存在则添加，存在则根据状态进行修改
		$activity_model	= Model('activity');
		$activity	= $activity_model->getOneByid($activity_id);
		//活动类型必须是商品,抽奖并且活动没有关闭并且活动进行中
		if(empty($activity) || $activity['activity_type'] == '2' || $activity['activity_state'] != '1' || $activity['activity_start_date']>time() || $activity['activity_end_date']<time()){
			showMessage(Language::get('store_activity_not_exists'),'index.php?act=store_activity&op=store_activity','html','error');
		}
		$activity_detail	= Model('activity_detail');
		$list	= $activity_detail->getList(array('store_id'=>"{$_SESSION['store_id']}",'activity_id'=>"$activity_id"));
		$ids	= array();    //已经存在的活动内容编号	
		$ids_state2	= array();//已经存在的被拒绝的活动编号
		if(is_array($list) and !empty($list)){
			foreach ($list as $ad){
				$ids[]	= $ad['item_id'];
			    //不存在审核不通过的商品,该功能注释掉
// 				if($ad['activity_detail_state']=='2'){
// 					$ids_state2[]	= $ad['item_id'];
// 				}
			}
		}
		//根据查询条件查询商品列表
		foreach ($_POST['item_id'] as $item_id){
			$item_id = intval($item_id);
			//抽奖活动的奖品是可以重复选择的
			$flag = $activity['activity_type'] == '3'?true:!in_array($item_id,$ids);
			if($flag){
				$input	= array();
				$input['activity_id']	= $activity_id;
				$goods	= Model('goods');
				$item	= $goods->getGoodsOnlineInfoByID($item_id, 'goods_name,store_id,store_name');
				if(empty($item) || $item['store_id'] != $_SESSION['store_id']){
					continue;
				}
				$input['item_name']	= $item['goods_name'];
				$input['item_id']	= $item_id;
				$input['store_id']	= $item['store_id'];
				$input['store_name']= $item['store_name'];
				//添加就审核通过
				$input['activity_detail_state']='1';
				$activity_detail->add($input);
			}
			//不存在审核不通过的商品,该功能注释掉
// 			elseif(in_array($item_id,$ids_state2)){
// 				$input	= array();
// 				//直接改为审核通过
// 				$input['activity_detail_state']= '1';//将重新审核状态去除
// 				$activity_detail->updateList($input,array('item_id'=>$item_id));
// 			}
		}
		showMessage(Language::get('store_activity_submitted'));
	}
    
	/**
	 *  异步存储信息
	 */
	public function activity_detail_saveOp(){
		//判断页面参数
		if(empty($_POST['activity_detail_id']) || !isset($_POST['activity_detail_sort'])){
			exit(json_encode(array('state'=>0 ,'msg'=>'请传递正确的参数')));
		}
		$detail_id = intval($_POST['activity_detail_id']);
		$activity_detai = Model('activity_detail');
		$detailDate     = $activity_detai->getDetail(array('activity_detail_id'=>$detail_id));
		if(empty($detailDate)){
			exit(json_encode(array('state'=>0 ,'msg'=>'找不到detail信息')));
		}else{
// 			//特殊判断一下
// 		    if(isset($_POST['detail_sum'])){
// 		    	$conditon['detail_sum'] = intval($_POST['detail_sum']);
// 		    	if($conditon['detail_sum'] <= intval($detailDate['detail_user'])){
// 		    		exit(json_encode(array('state'=>0 ,'msg'=>'输入的奖品数量太少,不能比已使用'.intval($detailDate['detail_user']).'数量小')));
// 		    	}
// 		    }
		    if (isset($_POST['activity_detail_sort'])){
		    	$conditon['activity_detail_sort'] = intval($_POST['activity_detail_sort']);
		    }
		    if (empty($conditon)){
		    	exit(json_encode(array('state'=>0 ,'msg'=>'请传递正确的参数')));
		    }
		    $return = $activity_detai->update($conditon,$detail_id);
		    if($return){
		    	exit(json_encode(array('state'=>1 ,'msg'=>'修改成功')));
		    }else{
		    	exit(json_encode(array('state'=>0 ,'msg'=>mysql_error())));
		    }
		}
	}
    
    public function activity_detail_delOp(){
    	//判断页面参数
    	if(empty($_GET['activity_detail_id']) || empty($_GET['activity_id'])){
    		showMessage(Language::get('store_storeinfo_error'),'index.php?act=store_activity&op=store_activity','html','error');
    	}
        $detail_id  = intval($_GET['activity_detail_id']);
        $activity_id  = intval($_GET['activity_id']);
        //校验是否有中奖
        $activityperson= Model('activity_person');
        $condition = array('activity_person.activity_id'=>$activity_id,
        		           'activity_person.store_id'=>"{$_SESSION['store_id']}",
        		           'detail_id'=>$detail_id);
        $personDate = $activityperson ->getList($condition);
        if(!empty($personDate)){
        	showMessage(Language::get('store_activity_person_getgift'),'','html','error');
        }
        $return  = Model('activity_detail')->del($detail_id);
        if($return){
        	showMessage(Language::get('store_theme_congfig_success'));
        }else{
        	showMessage(Language::get('store_theme_error'),'','html','error');
        }
    }
    
    public function  activity_rule_saveOp(){
    	$activity_id = intval($_POST['activity_id']);
    	if($activity_id <= 0){
    		showMessage(Language::get('para_error'),'index.php?act=store_activity&op=store_activity','html','error');
    	}
    	//存储规则 活动类型是抽奖 begin
    	if(!empty($_POST['activity_type']) && $_POST['activity_type'] == '3' ){
    		$activity_rule_model  = Model('activity_rule');
    		$return = $activity_rule_model->getRuleByCondtion(array('store_id'=>"{$_SESSION['store_id']}",'activity_id'=>"$activity_id"));
    		if(empty($return)){
    			$ruleData = array('store_id'=>"{$_SESSION['store_id']}",'activity_id'=>"$activity_id",
    			'store_name'=>"{$_SESSION['store_name']}",'is_check'=>$_POST['is_check'],'activity_desc'=>$_POST['activity_desc'],
    			'is_collect'=>$_POST['is_collect'],'is_buy'=>$_POST['is_buy'],'activity_time' =>strtotime($_POST['activity_time']));
    			$return  = $activity_rule_model ->addRule($ruleData);
    			if(!$return['state']){
    				showMessage(Language::get('store_activity_rule_insert'),'index.php?act=store_activity&op=store_activity','html','error');
    			}
    		}else{
    			$return =$activity_rule_model->updateRule(
    					array('is_check'=>$_POST['is_check'],'is_collect'=>$_POST['is_collect'],'is_buy'=>$_POST['is_buy'],'activity_desc'=>$_POST['activity_desc'],'activity_time' =>strtotime($_POST['activity_time'])),
    					array('store_id'=>"{$_SESSION['store_id']}",'activity_id'=>"$activity_id")
    			);
    			if(!$return['state']){
    				showMessage(Language::get('store_activity_rule_insert'),'index.php?act=store_activity&op=store_activity','html','error');
    			}
    		}
    	
    	}
    	showMessage(Language::get('store_activity_rule_submitted'));
    }
    
    public function  store_activity_resultOp(){
    	$page = new Page ();
		$page->setEachNum ( 10 );
		$page->setStyle ('admin');
		$activity = Model('activity');
		//活动为抽奖活动，开启状态,detail表审核通过
		$condition = array (
				//detail表审核通过,本店的
				'activity_id'   => array("in (select t.activity_id from ".DBPRE."activity_detail t where t.store_id='{$_SESSION['store_id']}' and t.activity_detail_state = '1' )"),
				'activity_type' => array (3),
				'opening'       => true,
				'order'         => 'activity.activity_sort asc'
		);
		$list = $activity->getList($condition,$page);
		//查询到对应store_id的detail信息
		$detailList = Model('activity_detail')->getDetailAndDetailPerson(
				    array('activity_detail.store_id'=>$_SESSION['store_id']));
		//做特殊处理 名称按照sort来归类
		$sortNum = array('1' =>1,'2' =>1,'3' =>1,'4' =>1,'5' =>1,'6' =>1,'7' =>1 );
        $curtSort;
        //做匹配
	    for ($k = 0;$k<count($list);$k++){
	        for($j=0;$j<count($detailList);$j++){
	        	 if($list[$k]['activity_id'] ==  $detailList[$j]['activity_id']){
	        	 	$curtSort = $detailList[$j]['activity_detail_sort'];
	        	    //商品名增加序列号
	        	 	$detailList[$j]['goods_name'] = $detailList[$j]['goods_name'].'-'.$sortNum[$curtSort];
	        	 	$sortNum[$curtSort]=$sortNum[$curtSort]+1;
	        	 	//塞入子集
	        	 	$list[$k]['detailItem'][] = $detailList[$j];
	        	 }else{
	        	 	continue;
	        	 }
	        }	 
	    }
		/**
		 * 页面输出
		 */
		Tpl::output ( 'list',$list);
		Tpl::output ( 'show_page',$page->show ());
		self::profile_menu ( 'store_activity_rusult' );
		Tpl::showpage ( 'store_activity.result' );
    }
    /**
     * 中奖人员，参与人员
     */
    public function joinPersonOp(){	
    	$activity_id = intval($_GET['activity_id']);
    	if($activity_id <= 0){
    		showMessage(Language::get('para_error'),'index.php?act=store_activity&op=store_activity_result','html','errro');
    	}
    	if(!isset($_GET['giftFlag'])){
    		showMessage(Language::get('para_error'),'index.php?act=store_activity&op=store_activity_result','html','errro');
    	}
    	$giftFlag = intval($_GET['giftFlag']);
    	$page = new Page ();
    	$page->setEachNum(10);
    	$page->setStyle('admin');
    	//活动人员
    	$activityperson= Model('activity_person');
    	$condition = array('activity_id'=>$activity_id,
    			           'store_id'=>"{$_SESSION['store_id']}");
    	if($giftFlag){
    		//加上奖品信息
    		$condition['detail_id'] = $_GET['detail_id'];
    		$list = $activityperson->getGetGiftPersonList($condition,'');
    		//页面输出
    		Tpl::output('list',$list);
    		self::profile_menu('store_activity_rusult');
    		Tpl::showpage('store_activity.gift');
    	}else{
    		//本次活动的参与人员
    		$list = $activityperson->getGetJoinPersonList($condition,$page);
    		//页面输出
    		Tpl::output ( 'list',$list);
    		Tpl::output ( 'show_page',$page->show());
    		self::profile_menu ('store_activity_rusult');
    		Tpl::showpage ('store_activity.join');
    	}
    }
    /**
     * 生成活动链接， 以及二维码
     */
    public function activityUrlOp(){
    	//活动ID
    	$activity_id = intval($_GET['activity_id']);
    	if($activity_id <= 0){
    		showMessage(Language::get('para_error'),'index.php?act=store_activity&op=store_activity_result','html','errro');
    	}
    	$activity_model	= Model('activity');
    	$activity	    = $activity_model->getOneByid($activity_id);
    	//类型抽奖,没有关闭,进行中
    	if(empty($activity) || $activity['activity_type'] != '3' || $activity['activity_state'] != '1' || $activity['activity_start_date']>time() || $activity['activity_end_date']<time()){
    		showMessage(Language::get('store_activity_not_exists'),'index.php?act=store_activity&op=store_activity_result','html','errro');
    	}
    	Tpl::output('activity_info',$activity);
    	//店铺ID
    	$store_id=intval($_SESSION['store_id']);
    	$rootDir    = BASE_UPLOAD_PATH.DS.'activity'.DS;
    	//开始生成链接 写死
    	$valueUrl   = 'http://wx.yimayholiday.com/wap_shop/index.php?act=activity_person&op=past&activity_id='.$activity_id.'&store_id='.$store_id; 
    	//店铺号_活动号_code.png
    	$pngTempName=$store_id."_".$activity_id."_code.png";
    	if(!file_exists($rootDir.$pngTempName)){
    		// 生成商品二维码
    		require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
    		$PhpQRCode = new PhpQRCode();
    		//根目录
    		$PhpQRCode->set('pngTempDir',$rootDir);
    		// 生成商品二维码
    		$PhpQRCode->set('date',$valueUrl);
    		$PhpQRCode->set('pngTempName',$pngTempName);
    		$PhpQRCode->init();
    	}
    	//输出模板
    	Tpl::output('codeurl',$valueUrl);
    	//上传需要改
    	$rootDir =UPLOAD_SITE_URL.'/activity/';
    	Tpl::output('codeimg',$rootDir.$pngTempName);
    	Tpl::output('html_title','二维码生成');
    	self::profile_menu ( 'store_activity_rusult');
    	Tpl::showpage('store_activity.qrcode');
    }
    /**
     * 抽奖
     */
    public function drawprizeOp(){
    	if(!isset($_GET['detail_id']) || !isset($_GET['activity_id'])){
    		showMessage(Language::get('para_error'),'','html','error');
    	}
    	//活动ID
    	$activity_id = intval($_GET['activity_id']);
    	//店铺ID
    	$store_id=intval($_SESSION['store_id']);
    	//detailId
    	$detailId= intval($_GET['detail_id']);
    	//activity Model
    	$activity_model	= Model('activity');
    	$activity	    = $activity_model->getOneByid($activity_id);
    	//类型抽奖,没有关闭,进行中
    	if(empty($activity) || $activity['activity_type'] != '3' || $activity['activity_state'] != '1' 
    	  || $activity['activity_start_date']>time() || $activity['activity_end_date']<time()){
    		showMessage(Language::get('store_activity_not_exists'),'','html','error');
    	}
    	Tpl::output('activity_info',$activity);
    	
    	//奖品
    	$detailList = Model('activity_detail')->getGoodsList(array('activity_detail_id' =>$detailId));
    	if(empty($detailList)){
    		showMessage(Language::get('store_activity_detail_no_record'),'','html','error');
    	}
    	Tpl::output('detail_info',$detailList[0]);
    	//满足要求的人员
    	$personList = Model('activity_person')->getGetJoinPersonList(array(
    			 'activity_id'=>$activity_id,
    			 'store_id'   =>$store_id,
    			 //只是报名，没有获奖
    			 'activity_state'=>'0'
    	),'');
    	if(empty($personList)){
    		showMessage(Language::get('store_activity_person_no_record'),'','html','error');
    	}
    	Tpl::output('personList',$personList);
    	//输出页面
    	Tpl::output('html_title','抽奖');
    	self::profile_menu ('store_activity_rusult' );
    	Tpl::showpage('store_activity.draw');
    }
    
    /**
     * 保存抽奖人员
     */
    public function drawprizeSaveOp(){
    	if(empty($_POST['person_id']) || empty($_POST['detail_id'])){
    		exit(json_encode(array('state'=>true,'msg'=>Language::get('para_error'))));
    	}
    	//活动人员表ID
    	$acPersonId = intval($_POST['person_id']);
    	//活动详情detailID
    	$detail_id  = intval($_POST['detail_id']);
    	//检查是否有人已经获得改奖项
        $checkPerson = Model('activity_person')->getList(array('detail_id'=>$detail_id));
    	if(!empty($checkPerson)){
    		exit(json_encode(array('state'=>false,'msg'=>Language::get('store_activity_person_getgift'))));
    	}
    	$updatePerson = Model('activity_person')->update(array(
    			 'activity_state'=>'1','active_time'=>TIMESTAMP,'detail_id'=>$detail_id,
    			 'az_code' =>$this->getRandChar(18)
    	),$acPersonId);
        if($updatePerson){
        	exit(json_encode(array('state'=>true,'msg'=>Language::get('store_activity_person_gift_succ'))));
        }else{
        	exit(json_encode(array('state'=>false,'msg'=>mysql_error())));
        }
    } 
    
    /**
     * 用户中心右边，小导航
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
    	Language::read('member_layout');
    	$menu_array = array(
    			1=>array('menu_key'=>'store_activity',       'menu_name'=>Language::get('nc_member_path_activity_list'),       'menu_url'=>'index.php?act=store_activity&op=store_activity'),
    			2=>array('menu_key'=>'store_activity_rusult','menu_name'=>Language::get('nc_member_path_activity_list_result'),'menu_url'=>'index.php?act=store_activity&op=store_activity_result')
    	);
//     	if($menu_key == 'store_activity') {
//     		unset($menu_array[2]);
//     	}
    	Tpl::output('member_menu',$menu_array);
    	Tpl::output('menu_key',$menu_key);
    }
}
?>