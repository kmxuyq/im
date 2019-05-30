<?php 
class weixin_spreadControl extends  BaseHomeControl{
    private $money = 280;// money xianzhi
    private $erweima_exprie_time = 604800;
    private $_dir = "/yunshangma/";
    private $_tail = "_ysm.jpg";
    private $wx_member = null;

    public function indexOp()
    {
        $this->checkLogin();
        $satisfy = $this->is_satisfy();
        if($satisfy) {
            $wx_member_id = $this->getWxMemberId();
            if($wx_member_id) {
                $erweima = $this->getErweimaUrl();
                if($erweima) {
                    Tpl::output('title', '我的云商码');
                    Tpl::output('erweima', $erweima);
                    Tpl::showpage('weixin_spread.satisfy');
                } else {
                    echo 'Abnormal system';
                }
            } else {
                Tpl::output('title', '我的云商码');
                Tpl::showpage('weixin_spread.notweixinuser');
            }
        } else {
            Tpl::output('title', '我的云商码');
            Tpl::output('money', $this->money);
            Tpl::showpage('weixin_spread.notsatisfy');
        }
    }

    public function settingOp()
    {
        $this->checkLogin();
        if($_SESSION['member_id']) {
            $model_member = Model('member');
            $condition['member_id'] = $_SESSION['member_id'];
            if (chksubmit()){
                $obj_validate = new Validate();
                $obj_validate->validateparam = array(
                    array("input"=>$_POST["alipay_account"], "require"=>"true","message"=>'支付宝账号必填'),
                    array("input"=>$_POST["alipay_account_name"], "require"=>"true","message"=>'真实姓名必填'),
                    array("input"=>$_POST["alipay_account_phone"], "require"=>"true","message"=>'手机号必填')
                );
                $error = $obj_validate->validate();
                if ($error != ''){
                showMessage($error);
                }else {
                    $update_array = array();
                    $update_array['alipay_account']   = $_POST['alipay_account'];
                    $update_array['alipay_account_name'] = $_POST['alipay_account_name'];
                    $update_array['alipay_account_phone']   = $_POST['alipay_account_phone'];
                    $result = $model_member->where($condition)->update($update_array);
                    echo "{status:1}";
                    exit;
                }
            }
            
            $alipay_info = $model_member->where($condition)
                         ->field('alipay_account,alipay_account_name,alipay_account_phone')
                         ->find();
            Tpl::output('title', '账号设置');
            Tpl::output('alipay_info', $alipay_info);
            Tpl::showpage('weixin_spread.setting');
        }
    }

    private function is_satisfy()
    {
        $model_order = Model('order');
        $condition['order_state'] = array('in',ORDER_STATE_PAY.','.ORDER_STATE_SEND.','.ORDER_STATE_SUCCESS);
        $condition['buyer_id'] = $_SESSION['member_id'];
        $order_list = $model_order->getOrderList($condition, 1, 'goods_amount', 'goods_amount desc','1', array());
        if(!empty($order_list[0]['goods_amount'])){
            if($order_list[0]['goods_amount'] >= $this->money){
                return true;
            }
        }
        return false;
    }
    private function getWxMemberId()
    {
        $model_member = Model('member');
        $model_wx_member = Model('wx_member');
        $condition['member_id'] = $_SESSION['member_id'];
        $member = $model_member->where($condition)->field('openid')->find();
        $wx_member = $model_wx_member->where($condition)->find();
        if(!empty($member)&&!empty($wx_member)) {
            $this->wx_member = $wx_member;
            return $wx_member['id'];
        } else {
            return false;
        }
    }
    private function fix_id($str) {
        $n = strlen(OLDEVENT_FAZHI);
        if(OLDEVENT_FAZHI>$str){
            return sprintf("%0{$n}s", $str);
        } else {
            return $str;
        }
    }
    private function getErweimaUrl() {
        $wx_member = $this->wx_member;
        if(empty($wx_member)) {
            return false;
        }
        $erweima_path = BASE_UPLOAD_PATH
        . $this->_dir
        . date('Y/m/d/',$wx_member["reg_time"])
        . $wx_member['openid'].$this->_tail;

        if( file_exists($erweima_path)
            && (filemtime($erweima_path) + $this->erweima_exprie_time) > time()
        ){
            return UPLOAD_SITE_URL
             . $this->_dir
             . date('Y/m/d/',$wx_member["reg_time"])
             . $wx_member['openid']
             . $this->_tail;
        } else {
            $result = $this->getQrCode();
            if($result) {
                $this->getErweimaUrl();
            } else {
                return false;
            }
        }
        return false;
    }
    /**
     *
     */
    private function getQrCode(){
        $appid = $GLOBALS["setting_config"]["weixin_appid"];
        $appacrect = $GLOBALS["setting_config"]["weixin_appsecret"];

        if( empty($appid) || empty($appacrect)) {
            return false;
        }
        $token = Model('wx_member')->get_token($appid, $appacrect);

        $wx_member = $this->wx_member;
        if(empty($wx_member)) {
            return false;
        }
        $logo = BASE_ROOT_PATH.'/data/weixin/head/'
            . date('Y/m/d/',$wx_member["reg_time"])
            . $wx_member['openid'].'_thum.jpg';
        if(!file_exists($logo)) {
            $logo = SHOP_TEMPLATES_URL.'/images/gfloge.jpg';
            if(!file_exists($log)) {
                return false;
            }
        }

        $erweima_path = BASE_UPLOAD_PATH
        . $this->_dir
        . date('Y/m/d/',$wx_member["reg_time"]);

        if(!is_dir($erweima_path)) {
            $this->mkDirs($erweima_path);
        }

        $scene_id = '20'.$this->fix_id($wx_member['id']);

        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
        $data='{"expire_seconds": '.$this->erweima_exprie_time.', "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": "'.$scene_id.'"}}}';
        $ticket_json = curl($url,'post',$data);
        $code_url_arr = json_decode($ticket_json,true);
        $code_url = $code_url_arr["url"];
        loadfunc('phpqrcode');

        $erweima_file = $erweima_path . $wx_member['openid'] . $this->_tail;

        QRcode::png($code_url, $erweima_file, 0, 4.5,1); 

        $QR = $erweima_file;//已经生成的原始二维码图   

        if ($logo !== FALSE) {   
            $QR = imagecreatefromstring(file_get_contents($QR));   
            $logo = imagecreatefromstring(file_get_contents($logo));   
            $QR_width = imagesx($QR);//二维码图片宽度   
            $QR_height = imagesy($QR);//二维码图片高度   
            $logo_width = imagesx($logo);//logo图片宽度   
            $logo_height = imagesy($logo);//logo图片高度   
            $logo_qr_width = $QR_width / 5;   
            $scale = $logo_width/$logo_qr_width;   
            $logo_qr_height = $logo_height/$scale;   
            $from_width = ($QR_width - $logo_qr_width) / 2;   
            //重新组合图片并调整大小   
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,   
            $logo_qr_height, $logo_width, $logo_height);   
        }   
        //输出图片   
        imagepng($QR, $erweima_file);  
        return UPLOAD_SITE_URL 
        . $this->_dir
        . date('Y/m/d/',$wx_member["reg_time"])
        . $wx_member['openid']
        . $this->_tail; 
    }

    private function mkDirs($dir){
        if(!is_dir($dir)){
            if(!$this->mkDirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }
}