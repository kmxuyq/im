<?php
/**
 * 会员模型
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');
class memberModel extends Model {
	private $wx_password='123456';
	private $wx_email='admin@admin.com';
    public function __construct(){
        parent::__construct('member');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false,$order='') {
        return $this->table('member')->field($field)->where($condition)->master($master)->order($order)->find();
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*') {
        $member_info = rcache($member_id, 'member', $fields);
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id'=>$member_id),'*',true);
            wcache($member_id, $member_info, 'member');
        }
        return $member_info;
    }

    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = 0, $order = 'member_id desc', $limit = '') {
       return $this->table('member')->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition) {
        return $this->table('member')->where($condition)->count();
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            dcache($condition['member_id'], 'member');
        }
        return $update;
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($member_info = array(),$reg = false) {
        if (empty($member_info) || !is_array($member_info)) return ;

		$_SESSION['is_login']	= '1';
		$_SESSION['member_id']	= $member_info['member_id'];
		$_SESSION['member_name']= $member_info['member_name'];
		$_SESSION['member_email']= $member_info['member_email'];
		$_SESSION['is_buy']		= isset($member_info['is_buy']) ? $member_info['is_buy'] : 1;
		$_SESSION['avatar'] 	= $member_info['member_avatar'];

		$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$_SESSION['member_id']));
		$_SESSION['store_id'] = $seller_info['store_id'];

		if (trim($member_info['member_qqopenid'])){
			$_SESSION['openid']		= $member_info['member_qqopenid'];
		}
		if (trim($member_info['member_sinaopenid'])){
			$_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
		}

		if (!$reg) {
		    //添加会员积分
		    $this->addPoint($member_info);
		    //添加会员经验值
		    $this->addExppoint($member_info);		    
		}

		if(!empty($member_info['member_login_time'])) {
            $update_info	= array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip']
            );
            $this->editMember(array('member_id'=>$member_info['member_id']),$update_info);
		}
		setNcCookie('cart_goods_num','',-3600);
        if(!empty($_SESSION['member_id'])&&!empty($_SESSION['is_login'])) {
            $deb = new desceb();
            $mctoken = $deb->new_encrypt($_SESSION['member_id']);
            setNcCookie('mctoken', $mctoken, 350*3600*24, '/', '.gellefreres.com');
        }

    }
	/**
	 * 获取会员信息
	 *
	 * @param	array $param 会员条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoMember($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'member';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$member_list	= Db::select($param);
		$member_info	= $member_list[0];
		if (intval($member_info['store_id']) > 0){
	      $param	= array();
	      $param['table']	= 'store';
	      $param['field']	= 'store_id';
	      $param['value']	= $member_info['store_id'];
	      $field	= 'store_id,store_name,grade_id';
	      $store_info	= Db::getRow($param,$field);
	      if (!empty($store_info) && is_array($store_info)){
		      $member_info['store_name']	= $store_info['store_name'];
		      $member_info['grade_id']	= $store_info['grade_id'];
	      }
		}
		return $member_info;
	}

    /**
     * 注册
     */
    public function register($register_info) {
		// 注册验证
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
		array("input"=>$register_info["username"],		"require"=>"true",		"message"=>'用户名不能为空'),
		array("input"=>$register_info["password"],		"require"=>"true",		"message"=>'密码不能为空'),
		array("input"=>$register_info["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$register_info["password"],"message"=>'密码与确认密码不相同'),
		array("input"=>$register_info["email"],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
            return array('error' => $error);
		}

        // 验证用户名是否重复
		$check_member_name	= $this->getMemberInfo(array('member_name'=>$register_info['username']));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
		}

        // 验证邮箱是否重复
		$check_member_email	= $this->getMemberInfo(array('member_email'=>$register_info['email']));
		if(is_array($check_member_email) and count($check_member_email)>0) {
            return array('error' => '邮箱已存在');
		}
		// 会员添加
		$member_info	= array();
		$member_info['member_name']		= $register_info['username'];
		$member_info['member_passwd']	= $register_info['password'];
		$member_info['member_email']		= $register_info['email'];
		//添加邀请人(推荐人)会员积分 by abc.com
		//$member_info['inviter_id']		= $register_info['inviter_id'];
		$insert_id	= $this->addMember($member_info);
		if($insert_id) {
		    //添加会员积分
			if (C('points_isuse')){
				Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
				//添加邀请人(推荐人)会员积分 by abc.com
				//$inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
				//Model('points')->savePointsLog('inviter',array('pl_memberid'=>$register_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
			}

            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $this->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
		} else {
            return array('error' => '注册失败');
		}

    }

    /**
     * 注册
     */
    public function registerNotEmail($register_info) {
        // 注册验证
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
        array("input"=>$register_info["username"],      "require"=>"true",      "message"=>'用户名不能为空'),
        array("input"=>$register_info["password"],      "require"=>"true",      "message"=>'密码不能为空'),
        array("input"=>$register_info["password_confirm"],"require"=>"true",    "validator"=>"Compare","operator"=>"==","to"=>$register_info["password"],"message"=>'密码与确认密码不相同'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            return array('error' => $error);
        }

        // 验证用户名是否重复
        $check_member_name  = $this->getMemberInfo(array('member_name'=>$register_info['username']));
        if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
        }

        // 会员添加
        $member_info    = array();
        $member_info['member_name']     = $register_info['username'];
        $member_info['member_passwd']   = $register_info['password'];
        $member_info['member_email']    = 'admin@admin.com';
        //添加邀请人(推荐人)会员积分 by abc.com
        //$member_info['inviter_id']        = $register_info['inviter_id'];
        $insert_id  = $this->addMember($member_info);
        if($insert_id) {
            //添加会员积分
            if (C('points_isuse')){
                Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
                //添加邀请人(推荐人)会员积分 by abc.com
                //$inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
                //Model('points')->savePointsLog('inviter',array('pl_memberid'=>$register_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
            }

            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $this->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
        } else {
            return array('error' => '注册失败');
        }

    }

    /**
     * 手机号码注册的方式注册
     */
    public function register_by_phonenum($register_info) {
        // 注册验证
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
        array("input"=>$register_info["username"],      "require"=>"true",      "message"=>'用户名不能为空'),
        array("input"=>$register_info["password"],      "require"=>"true",      "message"=>'密码不能为空'),
        array("input"=>$register_info["password_confirm"],"require"=>"true",    "validator"=>"Compare","operator"=>"==","to"=>$register_info["password"],"message"=>'密码与确认密码不相同'),
        array("input"=>$register_info["mobile"],        "require"=>"true",      "validator"=>"mobile", "message"=>'手机号码不正确'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            return array('error' => $error);
        }

        // 验证用户名是否重复
        $check_member_name  = $this->getMemberInfo(array('member_name'=>$register_info['username']));
        if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
        }

        // 验证手机号是否重复
        $check_member_mobile = $this->getMemberInfo(array('member_mobile'=>$register_info['mobile']));
        if(is_array($check_member_mobile) and count($check_member_mobile)>0) {
            return array('error' => '手机号已存在');
        }
        // 会员添加
        $member_info    = array();
        $member_info['member_name']     = $register_info['username'];
        $member_info['member_passwd']   = $register_info['password'];
        $member_info['member_mobile']   = $register_info['mobile'];
        $member_info['member_mobile_bind'] = 1;
        $member_info['member_email']    = '';
        //添加邀请人(推荐人)会员积分 by abc.com
        $member_info['inviter_id']      = $register_info['inviter_id'];
        $insert_id  = $this->addMember($member_info);
        if($insert_id) {
            //添加会员积分
            if (C('points_isuse')){
                Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
                //添加邀请人(推荐人)会员积分 by abc.com
                $inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
                Model('points')->savePointsLog('inviter',array('pl_memberid'=>$register_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
            }

            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $this->table('sns_albumclass')->insert($insert);

            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

            return $member_info;
        } else {
            return array('error' => '注册失败');
        }

    }

	/**
	 * 注册商城会员
	 *
	 * @param	array $param 会员信息
	 * @return	array 数组格式的返回结果
	 */
	public function addMember($param) {
		if(empty($param)) {
			return false;
		}
		try {
		    $this->beginTransaction();
		    $member_info	= array();
		    $member_info['member_id']			= $param['member_id'];
		    $member_info['member_name']			= $param['member_name'];
		    $member_info['member_passwd']		= md5(trim($param['member_passwd']));
		    $member_info['member_email']		= !empty($param['member_email'])?$param['member_email']:'';
            $member_info['member_mobile']       = !empty($param['member_mobile'])?$param['member_mobile']:'';
            $member_info['member_mobile_bind']  = !empty($param['member_mobile_bind'])?$param['member_mobile_bind']:'';
		    $member_info['member_time']			= TIMESTAMP;
		    $member_info['member_login_time'] 	= TIMESTAMP;
		    $member_info['member_old_login_time'] = TIMESTAMP;
		    $member_info['member_login_ip']		= getIp();
		    $member_info['member_old_login_ip']	= $member_info['member_login_ip'];

		    $member_info['member_truename']		= $param['member_truename'];
		    $member_info['member_qq']			= $param['member_qq'];
		    $member_info['member_sex']			= $param['member_sex'];
		    $member_info['member_avatar']		= $param['member_avatar'];
		    $member_info['member_areainfo']		= $param['member_areainfo'];
		    $member_info['member_qqopenid']		= $param['member_qqopenid'];
		    $member_info['member_qqinfo']		= $param['member_qqinfo'];
		    $member_info['member_sinaopenid']	= $param['member_sinaopenid'];
		    $member_info['member_sinainfo']	= $param['member_sinainfo'];
		    $member_info['openid']	= $param['openid'];//------
		    //添加邀请人(推荐人)会员积分 by abc.com
            if(!empty($param['inviter_id'])) {
                $member_info['inviter_id']          = $param['inviter_id'];
            }
            if(!empty($param['invite_at'])) {
                $member_info['invite_at']           = $param['invite_at'];
            }
            if(!empty($param['inviter_name'])){
                $member_info['inviter_name']        = $param['inviter_name'];
            }
		    $insert_id	= $this->table('member')->insert($member_info);
		    if (!$insert_id) {
		        throw new Exception();
		    }
		    $insert = $this->addMemberCommon(array('member_id'=>$insert_id));
		    if (!$insert) {
		        throw new Exception();
		    }
		    $this->commit();
		    return $insert_id;
		} catch (Exception $e) {
		    $this->rollback();
		    return false;
		}
	}

	/**
	 * 会员登录检查
	 *
	 */
	public function checkloginMember() {
		if($_SESSION['is_login'] == '1') {
			@header("Location: /wap/");
			exit();
		}
	}

    /**
	 * 检查会员是否允许举报商品
	 *
	 */
	public function isMemberAllowInform($member_id) {
        $condition = array();
        $condition['member_id'] = $member_id;
        $member_info = $this->getMemberInfo($condition,'inform_allow');
        if(intval($member_info['inform_allow']) === 1) {
            return true;
        }
        else {
            return false;
        }
	}

	/**
	 * 取单条信息
	 * @param unknown $condition
	 * @param string $fields
	 */
	public function getMemberCommonInfo($condition = array(), $fields = '*') {
	    return $this->table('member_common')->where($condition)->field($fields)->find();
	}

	/**
	 * 插入扩展表信息
	 * @param unknown $data
	 * @return Ambigous <mixed, boolean, number, unknown, resource>
	 */
	public function addMemberCommon($data) {
	    return $this->table('member_common')->insert($data);
	}

	/**
	 * 编辑会员扩展表
	 * @param unknown $data
	 * @param unknown $condition
	 * @return Ambigous <mixed, boolean, number, unknown, resource>
	 */
	public function editMemberCommon($data,$condition) {
	    return $this->table('member_common')->where($condition)->update($data);
	}

	/**
	 * 添加会员积分
	 * @param unknown $member_info
	 */
	public function addPoint($member_info) {
	    if (!C('points_isuse') || empty($member_info)) return;
	
	    //一天内只有第一次登录赠送积分
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;

	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addPoint',$queue_content);
	}

	/**
	 * 添加会员经验值
	 * @param unknown $member_info
	 */
	public function addExppoint($member_info) {
	    if (empty($member_info)) return;

	    //一天内只有第一次登录赠送经验值
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;
	
	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addExppoint',$queue_content);
	}

	/**
	 * 取得会员安全级别
	 * @param unknown $member_info
	 */
	public function getMemberSecurityLevel($member_info = array()) {
	    $tmp_level = 0;
	    if ($member_info['member_email_bind'] == '1') {
	        $tmp_level += 1;
	    }
	    if ($member_info['member_mobile_bind'] == '1') {
	        $tmp_level += 1;
	    }
	    if ($member_info['member_paypwd'] != '') {
	        $tmp_level += 1;
	    }
	    return $tmp_level;
	}

	/**
	 * 获得会员等级
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param int $exppoints  会员经验值
	 * @param array $cur_level 会员当前等级
	 */
	public function getMemberGradeArr($show_progress = false,$exppoints = 0,$cur_level = ''){
	    $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    //处理会员等级进度
	    if ($member_grade && $show_progress){
	        $is_max = false;
	        if ($cur_level === ''){
	            $cur_gradearr = $this->getOneMemberGrade($exppoints, false, $member_grade);
	            $cur_level = $cur_gradearr['level'];
	        }
	        foreach ($member_grade as $k=>$v){
	            if ($cur_level == $v['level']){
	                $v['is_cur'] = true;
	            }
	            $member_grade[$k] = $v;
	        }
	    }
	    return $member_grade;
	}
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $conditon_array
	 * @return	string
	 */
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['member_id'] != '') {
			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";
		}
		if($conditon_array['member_name'] != '') {
			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";
		}
		if($conditon_array['member_passwd'] != '') {
			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";
		}
		//是否允许举报
		if($conditon_array['inform_allow'] != '') {
			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";
		}
		//是否允许购买
		if($conditon_array['is_buy'] != '') {
			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";
		}
		//是否允许发言
		if($conditon_array['is_allowtalk'] != '') {
			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
		}
		//是否允许登录
		if($conditon_array['member_state'] != '') {
			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";
		}
		if($conditon_array['friend_list'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";
		}
		if($conditon_array['member_email'] != '') {
			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";
		}
		if($conditon_array['no_member_id'] != '') {
			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";
		}
		if($conditon_array['like_member_name'] != '') {
			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";
		}
		if($conditon_array['like_member_email'] != '') {
			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";
		}
		if($conditon_array['like_member_truename'] != '') {
			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";
		}
		if($conditon_array['in_member_id'] != '') {
			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";
		}
		if($conditon_array['in_member_name'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";
		}
		if($conditon_array['member_qqopenid'] != '') {
			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";
		}
		if($conditon_array['member_sinaopenid'] != '') {
			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";
		}
		
		return $condition_sql;
	}
		/**
	 * 删除会员
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " member_id = '". intval($id) ."'";
			$result = Db::delete('member',$where);
			return $result;
		}else {
			return false;
		}
	}

	/**
	 * 获得某一会员等级
	 * @param int $exppoints
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param array $member_grade 会员等级
	 */
	public function getOneMemberGrade($exppoints,$show_progress = false,$member_grade = array()){
	    if (!$member_grade){
	        $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    }
	    if (empty($member_grade)){//如果会员等级设置为空
	        $grade_arr['level'] = -1;
	        $grade_arr['level_name'] = '暂无等级';
	        return $grade_arr;
	    }
	    
	    $exppoints = intval($exppoints);
	    
	    $grade_arr = array();
	    if ($member_grade){
		    foreach ($member_grade as $k=>$v){
		        if($exppoints >= $v['exppoints']){
		            $grade_arr = $v;
		        }
			}
		}
		//计算提升进度
		if ($show_progress == true){
		    if (intval($grade_arr['level']) >= (count($member_grade) - 1)){//如果已达到顶级会员
		        $grade_arr['downgrade'] = $grade_arr['level'] - 1;//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $grade_arr['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = 0;
		        $grade_arr['exppoints_rate'] = 100;
		    } else {
		        $grade_arr['downgrade'] = $grade_arr['level'];//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $member_grade[$grade_arr['level']+1]['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = $grade_arr['upgrade_exppoints'] - $exppoints;
		        $grade_arr['exppoints_rate'] = round(($exppoints - $member_grade[$grade_arr['level']]['exppoints'])/($grade_arr['upgrade_exppoints'] - $member_grade[$grade_arr['level']]['exppoints'])*100,2);
		    }
		}
		return $grade_arr;
	}
	/**
	 *  微信自动登陆，成功为1，失败为0
	 * @param  $code 微信菜单URL链接转发的code,通过code从微信获取openid,(可以为code和openid)
	 * @param  $type 来源类型，code 和openid,如果为openid则不需要从微信获取openid
	 */
	public function weixin_login_handle00($code,$source_type='code'){
		//通过code从微信获取openid
		if($source_type=='code'){
			//微信code
			$appid=$GLOBALS["setting_config"]["weixin_appid"];
			$appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appacrect}&code={$code}&grant_type=authorization_code";
			$info=curl($url);
			
			$info_arr=json_decode($info,true);
			//自动登陆
			$openid=$info_arr["openid"];
			//如果wx_member表中是否有此用户，如果没有则获取用户信息，插入用户表
		}else{
			$openid=$code;
		}
		if(!empty($openid)){
			$openid_where=array('openid'=>$openid);
			$member=Model('member');
			$wx_member=Model('wx_member');
			$wx_member_count=$wx_member->where(openid_where)->count('id');
			if($wx_member_count=='0'){
				$this->insert_wx_member($member,$wx_member,$openid,$openid_where);
			}
			//--------------------------
			log_result('login:'.$openid.'--code:'.$code.'--info:'.$info, '','weixin');
			if(!empty($openid)){
				$model_member	= $this;
				$array	= array();
				$array['openid']	= $openid;
				$member_info = $model_member->getMemberInfo($array);
				if(is_array($member_info) and !empty($member_info)) {
					if($member_info['member_state']){
						process::addprocess('login');
					}
					$model_member->createSession($member_info);
					$status=1;
				}else{
					$status=0;
				}
			}
		}
		return $status;
	}
	/**
	 * 
	 *  微信自动登陆，成功为1，失败为0
	 * @param  $code 微信菜单URL链接转发的code,通过code从微信获取openid,(可以为code和openid)
	 * @param  $type 来源类型，code 和openid,如果为openid则不需要从微信获取openid
	 * @return 登陆状态，成功1，失败0
	 */
	public function weixin_login_handle($code,$source_type='code'){
		
		//通过code从微信获取openid
		if($source_type=='code'){
			//微信code
			$appid=$GLOBALS["setting_config"]["weixin_appid"];
			$appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appacrect}&code={$code}&grant_type=authorization_code";
			$info=curl($url);
				
			$info_arr=json_decode($info,true);
			//自动登陆
			$openid=$info_arr["openid"];
			//如果wx_member表中是否有此用户，如果没有则获取用户信息，插入用户表
		}else{
			$openid=$code;
		}
		log_result('login:'.$openid.'--code:'.$code.'--info:'.$info, '','weixin');
		if(!empty($openid)){
			$openid_where=array('openid'=>$openid);
			$member=Model('member');
			$wx_member=Model('wx_member');
			$wx_member_count=$wx_member->where($openid_where)->count('id');
			$member_count=$member->where($openid_where)->count('member_id');
			if($wx_member_count=='0'||$member_count=='0'){
				$this->insert_wx_member($member,$wx_member,$openid,$openid_where,$wx_member_count,$member_count);
			}
			//--------------------------
			if(!empty($openid)){
				//登陆
				$status=$this->weixin_login($openid);
			}
		}
		return $status;
	}
	/**
	 * 如果wx_member表中是否有此用户，如果没有则获取用户信息，插入用户表
	 * @param unknown_type $member
	 * @param unknown_type $wx_member
	 * @param unknown_type $openid
	 * @param unknown_type $openid_where
	 */
	private function insert_wx_member($member,$wx_member,$openid,$openid_where,$wx_member_count,$member_count){
		//获取用户信息
		$userinfo_arr=$wx_member->get_wx_userinfo($openid);//获取用户信息
		//$member_count=$member->where($openid_where)->count('member_id');
		if($member_count=='0'){
			//微信自动注册
			$nickname=$userinfo_arr['nickname'];
			$password=$this->wx_password;
			$email=$this->wx_email;
			$member_avatar=$openid.'_weixin.jpg';
			$member_sex=$userinfo_arr['sex'];
			$member_areainfo=$userinfo_arr['country'].$userinfo_arr['province'].$userinfo_arr['city'];
			$member_info=$this->weixin_register_model($nickname,$password,$email,$member_avatar,$member_sex,$member_areainfo,$openid);
			$member_id=$member_info["member_id"];
			/* $member_d["openid"]=$openid;
			$member_d["member_name"]=$userinfo_arr['nickname'];
			$member_d["member_avatar"]=$userinfo_arr['headimgurl'];
			$member_d["member_sex"]=$userinfo_arr['sex'];
			$member_d["member_time"]=$userinfo_arr['subscribe_time'];
			$member_d["member_areainfo"]=$userinfo_arr['country'].$userinfo_arr['province'].$userinfo_arr['city'];
			$member_d["member_time"]=time();
			$member_d["member_login_time"]=time();
			$member_d["member_old_login_time"]=time();
			//$member_d["member_email"]='';
			$member_id=$member->insert($member_d);//插入用户主表 */
		}else{
			$member_id_res=$member->where($openid_where)->field('member_id')->find();
			$member_id=$member_id_res["member_id"];
		}
		if($member_count=='0'&&!empty($member_id)){
			$wx_member_d['member_id']=$member_id;
			$wx_member_d['openid']=$openid;
			$wx_member_d['reg_time']=$userinfo_arr['subscribe_time'];
			$wx_member_d['source_type']=2;
			$wx_member_d['nickname']=$userinfo_arr['nickname'];
			$wx_member_d['sex']=$userinfo_arr['sex'];
			$wx_member_d['country']=$userinfo_arr['country'];
			$wx_member_d['city']=$userinfo_arr['city'];
			$wx_member_d['province']=$userinfo_arr['province'];
			$wx_member_d['headimgurl']=$userinfo_arr['headimgurl'];
			$wx_member_d['status']='1';//已关注1/未关注0
			$wx_member_d["stock"]=5;
			//print_r($wx_member_d);
			$wx_member->insert($wx_member_d);//插入微信用户表
		}
		
	}
	
	/**
	 * 微信注册
	 */
	public function weixin_register($register_info) {
		// 会员添加
		$member_info	= array();
		$member_info['member_name']		= $register_info['username'];
		$member_info['member_passwd']	= $register_info['password'];
		$member_info['member_email']		= $register_info['email'];
		//添加邀请人(推荐人)会员积分 by abc.com
		$member_info['inviter_id']		= $register_info['inviter_id'];
		$member_info['member_avatar']		= $register_info['member_avatar'];
		$member_info['member_sex']		= $register_info['member_sex'];
		$member_info['member_areainfo']		= $register_info['member_areainfo'];
		$member_info['openid']		= $register_info['openid'];
		$member_info['is_buy'] = 1;
		$member_info['member_state'] = 1;
		$member_info['member_mobile'] =$register_info['member_mobile'];
        $member_info['inviter_id'] = 0;
        if(!empty($register_info['inviter_id'])) {
            $from_wx_id = $register_info['inviter_id'];
            $wx_member_model = Model('wx_member');
            $from_wxer = $wx_member_model->where(array('id'=>$from_wx_id))->field('member_id')->find();
            if(!empty($from_wxer)) {
                $model_member   = Model('member');
                $pinfo=$model_member->getMemberInfoByID($from_wxer['member_id'],'member_id,member_name');
                if(!empty($pinfo)) {
                    $member_info['inviter_id'] = $pinfo['member_id'];
                    $member_info['inviter_name'] = $pinfo['member_name'];
                    $member_info['invite_at'] = TIMESTAMP;
                }
            }
        }
		$insert_id	= $this->addMember($member_info);
		if($insert_id) {
			//添加会员积分
			if (C('points_isuse')){
				Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
				//添加邀请人(推荐人)会员积分 by abc.com
				//$inviter_name = Model('member')->table('member')->getfby_member_id($member_info['inviter_id'],'member_name');
				//Model('points')->savePointsLog('inviter',array('pl_memberid'=>$register_info['inviter_id'],'pl_membername'=>$inviter_name,'invited'=>$member_info['member_name']));
			}
	
			// 添加默认相册
			$insert['ac_name']      = '买家秀';
			$insert['member_id']    = $insert_id;
			$insert['ac_des']       = '买家秀默认相册';
			$insert['ac_sort']      = 1;
			$insert['is_default']   = 1;
			$insert['upload_time']  = TIMESTAMP;
			$this->table('sns_albumclass')->insert($insert);
	
			$member_info['member_id'] = $insert_id;
			$member_info['is_buy'] = 1;
	
			return $member_info;
		} else {
			return array('error' => '注册失败');
		}
	
	}
	/**
	 * 微信会员自动注册操作
	 *
	 * @param $mobile 为手机注册用户的手机号
	 * @return
	 */
	public function weixin_register_model($nickname,$password,$email,$member_avatar,$member_sex,$member_areainfo,$openid,$from_wx_id=0,$mobile='') {
		$model_member	= Model('member');
		//$model_member->checkloginMember();
		$register_info = array();
		$register_info['username'] =$nickname;//$_POST['user_name'];
		$register_info['password'] = $password;//$_POST['password'];
		$register_info['password_confirm'] = $password;;//$_POST['password_confirm'];
		$register_info['email'] = $email;//$_POST['email'];
		$register_info['member_avatar'] = $member_avatar;//$_POST['email'];
		$register_info['member_sex'] = $member_sex;//$_POST['email'];
		$register_info['member_areainfo'] = $member_areainfo;//$_POST['email'];
		$register_info['openid'] = $openid;//$_POST['email'];
		$register_info['member_mobile'] = $mobile;
		//添加奖励积分zmr>v30
        $register_info['inviter_id'] = $from_wx_id;
		$member_info = $model_member->weixin_register($register_info);
		if(!isset($member_info['error'])) {
			$model_member->createSession($member_info,true);
			process::addprocess('reg');
			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);
			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
		}
		return $member_info;
	}
/**
 * 
 * 微信自动登录操作
 * @param $openid
 * @param  $mode默认为openid
 * @return number登陆成功1，失败0
 */
	public function weixin_login($openid,$mode='openid'){
		$model_member	= Model('member');
		$array	= array();
		$array[$mode]	= $openid;//md5($_POST['password']);
		$member_info = $model_member->getMemberInfo($array);
		$model_member->createSession($member_info);
		//process::clear('login');
		// cookie中的cart存入数据库
		Model('cart')->mergecart($member_info,$_SESSION['store_id']);
		// cookie中的浏览记录存入数据库
		Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
		if(isset($_SESSION["member_id"])){
			$status=1;
		}else{
			$status=0;
		}
		return $status;
	}
}
