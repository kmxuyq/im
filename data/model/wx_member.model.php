<?php 
class wx_memberModel extends Model {
	public function __construct(){
		 parent::__construct('wx_member');
	}
	
	/**
	 * 获取微信access_token
	 * $reget=1为强制重新获取token
	 */
	public function get_token($appid,$appacrect,$reget='0'){
		$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appacrect;
		//调整需要每一个token存储一个文件
		$token_file='../data/log/weixin_token_'.$_SESSION['store_id'].'.txt';
		if(!file_exists($token_file)||(filemtime($token_file)+7200)<time()||$reget=='1'){
			//token文件不存在或都文件过期
			//echo $url.'<br/>';
			$token_str=curl($url);
				
			$token_arr=json_decode($token_str);
			//echo $token_str.'<br/>';
			//echo $token_arr->access_token;
			if(($token_arr->access_token)!=''){
				file_put_contents($token_file, $token_arr->access_token);
			}
		}
		$token=file_get_contents($token_file);//此行不要删
		//判断token是否失效
	
		$getip_url="https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=";
		//echo $getip_url.$token;
		$ip_json=curl($getip_url.$token);
		$ip_json_arr=json_decode($ip_json,true);
		if($ip_json_arr["errcode"]=='40001'||$ip_json_arr["errcode"]=='41001'){
			//如果失效
			$token_str=curl($url);
			$token_arr=json_decode($token_str);
			if(($token_arr->access_token)!=''){
				file_put_contents($token_file, $token_arr->access_token);
			}
		}
		//print_r($ip_json_arr);
		$token=file_get_contents($token_file);
		return $token;
	}
	
	/**
	 * 获取微信access_token
	 * $reget=1为强制重新获取token
	 */
	public function get_token00($appid,$appacrect,$reget='0') {
	    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
	    $data = json_decode(file_get_contents("access_token.json"));
	    if ($data->expire_time < time()) {
	      // 如果是企业号用以下URL获取access_token
	      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
	      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appacrect}";
	      $res = json_decode($this->httpGet($url));
	      $access_token = $res->access_token;
	      if ($access_token) {
	        $data->expire_time = time() + 7000;
	        $data->access_token = $access_token;
	        $fp = fopen("access_token.json", "w");
	        fwrite($fp, json_encode($data));
	        fclose($fp);
	      }
	    } else {
	        $access_token = $data->access_token;
	    }
	    return $access_token;
	}
	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}
	/**
	 * 通过access_token和openid获取微信端用户信息获取微信用户信息
	 */
	public function get_wx_userinfo($openid,$appid = '',$appacrect= ''){
		if(empty($appid))     $appid    =$GLOBALS["setting_config"]["weixin_appid"];
		if(empty($appacrect)) $appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		$access_token=Model('wx_member')->get_token($appid,$appacrect);
		$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
		$json_str=curl($url);
		$userinfo_arr=json_decode($json_str,true);
		return $userinfo_arr;
	}
	/**
	 * 判断以往满足赠送礼券的微信用户礼品表中是否存在此用户礼券记录，没有则插入礼品表礼券记录
	 * @param unknown_type $member_id
	 */
	public function member_voucher($member_id){
		//判断以往满足赠送礼券的微信用户礼品表中是否存在此用户礼券记录，没有则插入礼品表礼券记录
		$wx_member_res=Model('wx_member')->where(array('member_id'=>$member_id))->field('lower_member,openid')->order('id desc')->limit(1)->find();
		//print_r($wx_member_res);
		//echo $wx_member_res["lower_member"];
		$member_lower_num=count(explode(',', @substr($wx_member_res["lower_member"],0,-1)));
		$wx_active_rule=Model('wx_active_rule');
		$new_lower_num_arr=$wx_active_rule->where("(goods_id='0' OR goods_id is Null OR goods_id='') and voucher_t_id>0 and lower_num>0")->order('lower_num desc')->field('*')->find();
		//print_r($new_lower_num_arr);
		if(!empty($new_lower_num_arr)){
			$new_lower_num=$new_lower_num_arr["lower_num"];
			//echo $member_lower_num.'--'.$new_lower_num;
			if($member_lower_num>=$new_lower_num_arr["lower_num"]){
				//判断礼品表中有满足赠送礼券的记录中用户礼券表中是否存在赠送记录，没有则礼券表中插入用户礼券
			
				$voucher=Model()->table('voucher');
				$voucher_num=$new_lower_num_arr["voucher_num"];
				$voucher_t_id=$new_lower_num_arr["voucher_t_id"];
				$voucher_time=$new_lower_num_arr["voucher_days"]*86400+time();//代金券有效期
				$voucher_t_res=Model()->table('voucher_template')->where(array('voucher_t_id'=>$voucher_t_id))->find();
				//print_r($voucher_t_res);
				$member_voucher_count=$voucher->where(array('voucher_owner_id'=>$member_id,'voucher_t_id'=>$new_lower_num_arr["voucher_t_id"]))->count('voucher_id');
				//echo '--member_voucher_count:'.$member_voucher_count;
				if($member_voucher_count=='0'){
					for($i=0;$i<$voucher_num;$i++){
						$voucher_d["voucher_code"]='0';
						$voucher_d["voucher_t_id"]=$voucher_t_id;
						$voucher_d["voucher_title"]=$voucher_t_res["voucher_t_title"];
						$voucher_d["voucher_desc"]=$voucher_t_res["voucher_t_desc"];
						$voucher_d["voucher_start_date"]=time();
						$voucher_d["voucher_end_date"]=$voucher_time;
						$voucher_d["voucher_price"]=$voucher_t_res["voucher_t_price"];
						$voucher_d["voucher_limit"]=$voucher_t_res["voucher_t_limit"];
						$voucher_d["voucher_store_id"]=$voucher_t_res["voucher_t_store_id"];
						$voucher_d["voucher_state"]=1;
						$voucher_d["voucher_active_date"]=time();
						$voucher_d["voucher_type"]='0';
						$voucher_d["voucher_owner_id"]=$member_id;
						$voucher_d["voucher_owner_name"]=$_SESSION["member_name"];
						$voucher_d["voucher_order_id"]='0';
						//log_result('voucher_d:'.json_encode($voucher_d),'','weixin');
						$voucher_id=$voucher->insert($voucher_d);//插入表
						$voucher_code=substr(rand(100000, 999999).time().$voucher_id,-18);
						$voucher->where(array('voucher_id'=>$voucher_id))->update(array('voucher_code'=>$voucher_code));//更新CODE
					}
				}
				//echo 'voucher_id:'.$voucher_id;
				$present_member_count=Model('wx_present_member')->where(array('member_id'=>$member_id,'lower_num'=>$new_lower_num))->count('id');
				if($present_member_count=='0'){
					$wx_present_member=Model('wx_present_member');
					$present_member_d_status=0;
					if(!empty($voucher_id)&&$voucher_id>0)$present_member_d_status=1;
					$present_member_data=array('title'=>$new_lower_num_arr["title"],'openid'=>$wx_member_res["openid"],'member_id'=>$member_id,'lower_num'=>$new_lower_num,'add_time'=>time(),'pre_openid'=>'','source_type'=>2,'voucher_id'=>$voucher_id,'voucher_num'=>$voucher_num,'status'=>$present_member_d_status);
					//插入礼品表
					$present_member_id=$wx_present_member->insert($present_member_data);
					//echo '--present_member_id:'.$present_member_id;
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * 生成微信发送的图片,返回图片地址
	 * @param  $face_path
	 * @param  $wx_img_path
	 * @param  $openid
	 * @param  $nickname
	 * @param  $scene_id=类别ID+wx_member表ID
	 * @param  $reg_time_path 用户注册时间,图片路径,格式：(2015/10/01/)
	 */
	public function creater_wx_image($face_path,$wx_img_path,$openid,$nickname,$scene_id,$reg_time_path){		
		$face_url=$face_path.$reg_time_path.$openid.'_thum.jpg';
		//log_result("\n creater_wx_image:".$openid.'--'.$nickname.'--'.$scene_id.'--'.$face_url, '---','weixin');
		$image_tool=new imagetool();
		$img=$_SERVER["DOCUMENT_ROOT"].'/weixin/image/wxbg.jpg';
		@mkdir($wx_img_path.$reg_time_path,0777,true);//创建文件夹
		$wx_img_filename=$wx_img_path.$reg_time_path.$openid.'.jpg';//新文件路径
		//如果二维码图片不存在或修改时间小于三天的（关注时就调用此方法生成了图片）
		if(!file_exists($wx_img_filename)||(filemtime($wx_img_filename)+86400*3)<=time()){
			copy($img, $wx_img_filename);//复制文件
		
			$water_code=BASE_SITE_URL.'/wap_shop/index.php?act=weixin&op=creater_wx_code&scene_id='.$scene_id;//$_SERVER["DOCUMENT_ROOT"].'/weixin/image/w1.jpg';
			//log_result("\n wx_img_filename:".$wx_img_filename.'--'.$water_code.'--'.$face_url.'--'.$nickname, '---','weixin');
			$text="以下是{$nickname}的迷之大使\n           专属二维码";
			$image_tool->wx_water_face($wx_img_filename, $face_url,110,34);//头像
			$image_tool->wx_water_code($wx_img_filename, $water_code,0,-132);//二维码
			$image_tool->wx_water_text($wx_img_filename,$text,10,'',180,53);//妮称
		}
		return $wx_img_filename;
	}
	/**
	 * 带参二维码的参数由$code_type.$scene_id构成，$scene_id是wx_member表的ID
	 * 用户关注后自动生成用户专属二维码,返回图片media_id
	 * @param $openid
	 * @param $nickname
	 * @param $scene_id带参二维码的参数
	 * @param $code_type 带参二维码的参数类型（用户来源），10为裂变活动，20是分销系统
	 * @param $reg_time_path 用户注册时间路径，格式date('Y/m/d/',$reg_time_res["reg_time"])
	 */
	public function creater_wx_user_code_img($face_path,$wx_img_path,$openid, $nickname, $scene_id,$code_type='10',$reg_time_path=''){
		if($reg_time_path==''){
			$reg_time_res=Model('wx_member')->where(array('openid'=>$openid))->field('reg_time')->find();
			$reg_time_path=date('Y/m/d/',$reg_time_res["reg_time"]);
		}
		$code_path=$wx_img_path.$reg_time_path;//二维码路径
		$code_filename=$code_path.$openid.'.jpg';
		//临时二维码有效期7天
		$media_id='';
		if(!file_exists($code_filename)||(filemtime($code_filename)+86400*7)<=time()){
			//生成图片
			//log_result('creater_wx_user_code_img2:'.$face_path.'---'.$wx_img_path.'---'.$openid.'---'.$nickname.'---'.$code_type.'---'.$scene_id.'---'.$reg_time_path, '---','weixin');
			$image_file=$this->creater_wx_image($face_path,$wx_img_path,$openid, $nickname, $code_type.$scene_id,$reg_time_path);
			if($image_file!=''){
				//生成media_id
				$media_id=$this->up_wx_image($image_file);
				if($media_id!=''){
					Model('wx_member')->where(array('openid'=>$openid))->update(array('media_id'=>$media_id));
				}
			}
		}elseif(empty($media_id)){
			$image_file=$this->creater_wx_image($face_path,$wx_img_path,$openid, $nickname, $code_type.$scene_id,$reg_time_path);
			if($image_file!=''){
				//生成media_id
				$media_id=$this->up_wx_image($image_file);
				if($media_id!=''){
					Model('wx_member')->where(array('openid'=>$openid))->update(array('media_id'=>$media_id));
				}
			}
		}
		return $media_id;
	}
	/**
	 * 上传微信图片(临时素材)，返回media_id
	 */
	public function up_wx_image($image_file){
		$appid=$GLOBALS["setting_config"]["weixin_appid"];
		$appacrect=$GLOBALS["setting_config"]["weixin_appsecret"];
		//log_result("\n up_wx_image1:".$image_file, '','weixin');
		$token=Model('wx_member')->get_token($appid,$appacrect);
		//log_result("\n token:".$token, '','weixin');
		$url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";
		$media_id_json=$this->upload_curl_pic($url, $image_file);
		$media_id_arr=json_decode($media_id_json);
		//log_result("\n up_wx_image2:".$media_id_json, '','weixin');
		return $media_id_arr->media_id;
	}
	/**
	 * curl 上传图片
	 * @param $url上传到的处理地址
	 * @param $file 要上传的本地文件，带本地上传盘符和路径e:\1.jpg
	 * @param $type post
	 * @return mixed
	 */
	public function upload_curl_pic($url,$file,$type='post', $second='30')
	{
		$fields['media'] = '@'.$file;
		// $fields['type'] = 'image';
		$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);//
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//
		//设置header
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if($type=='post'){
			curl_setopt($ch, CURLOPT_POST, 1);//开启POST
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);//POST数据
		}
		$output = curl_exec($ch);
		//curl_close($ch);
		//返回结果
		if( $output){
			@curl_close($ch);
			return $output;
		}
		else{
			$error = curl_errno($ch);
			@curl_close($ch);
			return false;
		}
		//if ($error = curl_error($ch) ) {
		//       die($error);
		//} else {
		//    var_dump($data);
		//}
		//curl_close($ch);
	}
	/**
	 * 获取二维码专用的ticket,通过ticket获取二维码，返回的地址即为二维码的url地址
	 * @param $scene_id带参二维码图片场景ID
	 */
	public function get_weixin_code_url($scene_id){
		$appid=C('weixin_appid');
		$appsecret=C('weixin_appsecret');
		$accestoken=$this->get_token($appid, $appsecret);
		$data='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
		$ticket_json=curl('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accestoken,'post',$data);
	
		$ticket_arr=json_decode($ticket_json,true);
		//$ticket=$ticket_arr["ticket"];
		//return "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
		$url=$ticket_arr["url"];
		return $url;
		
	}
}
?>