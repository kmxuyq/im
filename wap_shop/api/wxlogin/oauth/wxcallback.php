<?php 

require_once(dirname(__FILE__).'/'.'../comm/config.php');

require_once(dirname(__FILE__).'/'.'../comm/utils.php');


function wx_callback(){

    if($_REQUEST['state'] == $_SESSION['state']){

        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code"
           . "&appid=" . $_SESSION["appid"]
		   . "&secret=" . $_SESSION["appkey"]
		   . "&code=" . $_REQUEST["code"];


        $response = get_url_contents($token_url);

            $msg = json_decode($response);

            if (isset($msg->errcode)){

                echo "<h3>error:</h3>" . $msg->errcode;

                echo "<h3>msg  :</h3>" . $msg->errmsg;

                exit;

            }       


        $_SESSION["access_token"] =$msg->access_token; //$params["access_token"];

		$_SESSION["openid"] = $msg->openid;

		if(isset($msg->unionid)){

			$_SESSION["unionid"] = $msg->unionid;

		}else{
			$_SESSION["unionid"] = $msg->openid;
		}

    }else{

        echo("The state does not match. You may be a victim of CSRF.");

    }

}

	//登录成功后的回调地址,主要保存access token he openid

	wx_callback();
	//
	$model_member	= Model('member');
	$array	= array();
	$array['member_wxopenid']	= $_SESSION['openid'];
	$member_info = $model_member->getMemberInfo($array);
	if (empty($member_info)){
		//
		require_once (dirname(__FILE__).'/../comm/get_user_info.php');
		$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);

		//处理wx账号信息
		$qquser_info['nickname'] = trim($qquser_info['nickname']);
		$user_passwd = 'wx'.rand(100000, 999999);
		/**
		* 会员添加
		*/
		$user_array	= array();
		$user_array['member_name']		= $qquser_info['nickname'];
		$user_array['member_passwd']	= md5($user_passwd);
		$user_array['member_email']		= '';
		$user_array['member_wxopenid']	= $_SESSION["openid"];//wx openid
		$user_array['member_wxinfo']	= serialize($qquser_info);//wx 信息
		$user_array['member_wxunionid']	= $_SESSION['unionid'];
		$user_array['member_avatar_url']	= $qquser_info['headimgurl'];
		$rand = rand(100, 899);
		if(strlen($user_array['member_name']) < 3) {
			$user_array['member_name']		= $qquser_info['nickname'].$rand;
		}
		$check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
		$result	= 0;
		if(empty($check_member_name)) {
			//$result	= $model_member->addMember($user_array);
			$result	= Model()->table('member')->insert($user_array);
		}else {
			$user_array['member_name'] = trim($qquser_info['nickname']).$rand;
			$check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
			if(empty($check_member_name)) {
				//$result	= $model_member->addMember($user_array);
				$result	= Model()->table('member')->insert($user_array);
			}else {
				for ($i	= 1;$i < 999999;$i++) {
					$rand = $rand+$i;
					$user_array['member_name'] = trim($qquser_info['nickname']).$rand;
					$check_member_name	= $model_member->getMemberInfo(array('member_name'=>trim($user_array['member_name'])));
					if(empty($check_member_name)) {
						//$result	= $model_member->addMember($user_array);
						$result	= Model()->table('member')->insert($user_array);
						break;
					}
				}
			}
		}

		if($result) {
			$_SESSION['member_id'] = $result;
			$_SESSION['member_name'] = $user_array['member_name'];
		}
	}else{
		$_SESSION['member_id'] = $member_info['member_id'];
		$_SESSION['member_name'] = $member_info['member_name'];
	}

	//

	@header('location: /yydb/index.php');

	exit;


?>


