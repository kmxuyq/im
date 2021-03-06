<?php
defined('InShopNC') or exit('Access Invalid!');
/**
 * @author 阿正
 * 精彩活动填写个人信息
 */

class activeControl extends BaseHomeControl{
    protected $member_info = array();   // 会员信息
    public function __construct() {
        parent::__construct();
        Language::read('active');
//         if($_GET['wx'] != '2'){
//             redirect(urlShopWap('login', 'index'));
//         }
//         if (!$_SESSION['member_id']){
//             redirect('index.php?act=login&ref_url='.urlencode(request_uri()));
//         }
    }

    /**
     * 判断用户是否已经参加过改活动
     * @param unknown $str
     * @param unknown $str2
     * @return boolean
     */
    public function is_exit($m,$g) {
//         echo $m.$g;
        $active = Model('active');
        $field = 'goods_id,member_id';
        $condition ='member_id='.$m .' AND goods_id='.$g;
        $is_exit = $active->field($field)->where($condition)->find();

        if(!empty($is_exit)&&is_array($is_exit)){
            return true;
        }else {
            return  false;
        }
    }
    /**
     * 填写个人信息
     */
    public function indexOp() {

        //POST提交
        if (chksubmit()){
            $this->thepost();
        } else {
            if(!$_SESSION['is_login']){//微信填写，如没有登录，则查询用户id
                $member_list= Model()->table('member')->field('member_id,member_name')->where(array('openid'=>$_GET['openid']))->find();
                if(!empty($member_list)) {
                    $_SESSION['member_id'] = $member_list['member_id'];
                    $_SESSION['member_name'] = $member_list['member_name'];
                    $_SESSION['is_login'] = 1;
                } else {
                    $this->checkLogin();
                    exit();
                }
            }
            $model_addr = Model('address');

            $is_wx =$_GET['wx']=='2'||$_GET['wx']=='1';  //微信扫码入口
            $page = 'act_address';

            $goods_id = $is_wx ? decrypt($_GET['goods_id']) : $_GET['goods_id'];

            if($is_wx){
                $page = 'wx_present_success';
            }
            $is_virtual =Model()->table('goods')->field('goods_id,goods_name,is_virtual')->where(array('goods_id'=>$goods_id))->find();

            //获得会员信息
            $this->member_info = $this->getMemberAndGradeInfo(true);

            if($this->member_info['member_privacy'] != ''){
                $this->member_info['member_privacy'] = unserialize($this->member_info['member_privacy']);
            } else {
                $this->member_info['member_privacy'] = array();
            }
            Tpl::output('goods_type',$is_virtual);
            Tpl::output('member_info',$this->member_info);
            Tpl::showpage($page);
        }

    }

    public function thepost() {
        if (chksubmit()){
            $member_id=$_POST['member_id']?:$_SESSION['member_id'];
            if(empty($member_id)) {
                echo json_encode(array('done'=>false,'msg'=>'no member id'));
                exit();
            }
            //验证表单信息
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["true_name"],"require"=>"true","message"=>Language::get('cart_step1_input_receiver')),
                array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('cart_step1_choose_area')),
                array("input"=>$_POST["address"],"require"=>"true","message"=>Language::get('cart_step1_input_address'))
            );

            $error = $obj_validate->validate();
            if ($error != ''){
                exit(json_encode(array('state'=>false,'msg'=>'错误')));
            }

            if ($_POST['wx'] == '2' || $_POST['wx']=='1') {//2微信扫码,1微信领取
                $is_true = true;
            } else {
                $is_true = false;
            }

            $_is_exit = $this->is_exit($member_id,$_POST['goods_id']);
            if($_is_exit){//判断活动表里是否有该用户
                if($is_true){
                    echo json_encode(array('done'=>false,'code'=>9,'msg'=>Language::get('az_aready','UTF-8')));
                    die;
                }else{
                    $url = "index.php?act=goods&op=index&type=appaly_goods&wx={$_POST['wx']}&cate_id={$_POST['cate_id']}&goods_id={$_POST['goods_id']}";
                    callmsg(Language::get('az_aready'), $url);
                }
            }else{

                $data = array();
                $data['member_id'] = $member_id;
                $data['true_name'] = $_POST['true_name'];
                $data['area_id'] = intval($_POST['area_id']);
                $data['city_id'] = intval($_POST['city_id']);
                $data['area_info'] = $_POST['area_info'];
                $data['address'] = $_POST['address'];
                $data['mob_phone'] = $_POST['mob_phone'];
                $data['member_sex'] = $_POST['member_sex'];
                $data['content'] = $_POST['content'];
                $data['member_birthday'] = $_POST['member_birthday'];
                $data['goods_id'] = $_POST['goods_id'];
                $data['follow'] = $_POST['follow'];
                $model_addr = Model('address');
                $insert_id = $model_addr->addAddress($data);

                //活动和申领分类
                if (intval($insert_id)>0){
                    $condition =array();
                    $condition['goods_id'] = $data['goods_id'];
                    $condition['address_id'] = $insert_id;
                    $condition['member_id'] = $member_id;
                    $condition['type'] = $_POST['type']=='appaly_goods' ? 0 : 1;//状态：1 精彩活动，0 免费申领
                    $condition['az_active_state'] = $is_true ? '1' : '0';    //更新活动状态
                    $condition['az_active_chick_state'] = $is_true ? '1' : '0';//如果是微信扫码，则直接跳过管理员审核
                    $condition['az_active_addtime'] = TIMESTAMP;
                    $condition['az_active_chick_time'] = $is_true ? TIMESTAMP : '0'; //如果是微信扫码，则直接跳过管理员审核
                    $condition['wx']  = $_POST['wx'];  //流量入口，0: 商城 ; 1、2: 微信入口
                    $condition['az_code'] =$_POST['az_code'];    //兑换码
                    $condition['code_usetime']='0'; //默认兑换码时间

                    Model()->table('active')->insert($condition,true);
                    $az = Model()->table('goods')->field('goods_id,store_id,goods_name,appaly_tatol_person,active_tatol_person')->where(array('goods_id'=>$condition['goods_id']))->find();

                    //微信活动
                    if($is_true){//生成订单
                        $this->gotoorder($insert_id, $member_id, $data['city_id'], $data['goods_id']);
                    }
                    if($_POST['type']=='appaly_goods'){
                        Model()->table('goods')
                        ->where(array('goods_id'=>$condition['goods_id']))
                        ->update(array('appaly_tatol_person'=>$az['appaly_tatol_person']+1));

                        $url = urlShopWAP('goods',
                                    'index',
                                    array(
                                        'type'=>'appaly_goods',
                                        'wx'=>$condition['wx'],
                                        'cate_id'=>$_POST['cate_id'],
                                        'goods_id'=>$_POST['goods_id']
                                    )
                                );
                        $jsonResult = Language::get('wx_prize_message').$post['goods_name'].Language::get('wx_com_massage');
                        if($is_true){
                            echo json_encode(array('done'=>true,'msg'=>$jsonResult));
                        }else {
                            @header("location: {$url}");die;
                        }
                    }else {
                        Model()->table('goods')->where(array('goods_id'=>$condition['goods_id']))->update(array('active_tatol_person'=>$az['active_tatol_person']+1));
                        $url = urlShopWAP('goods','index',array('type'=>"act_goods",'goods_id'=>$_POST['goods_id']));
                        @header("location: {$url}");die;
                    }
                } else {
                        echo json_encode(array('msg'=>'no address'.json_encode($data).'address:'.json_encode($insert_id)));
                        exit();
                }
            }
        }
    }

    public function gotoorder($insert_id, $member_id, $city_id, $goods_id) {
        if(intval($insert_id)<=0) {
            echo json_encode(array('done'=>false, 'msg'=> 'no insert_id'));
            exit();
        }
        if(empty($member_id)) {
            echo json_encode(array('done'=>false, 'msg'=> 'no member id'));
            exit();
        }
        if(empty($city_id)) {
            echo json_encode(array('done'=>false, 'msg'=> 'no city_id'));
            exit();
        }
        if(empty($goods_id)) {
            echo json_encode(array('done'=>false, 'msg'=> 'no goods_id'));
            exit();
        }
        $data['city_id'] = $city_id;
        $condition['goods_id'] = $goods_id;
        $condition['address_id'] = $insert_id;
        $member = Model()->table('member')
                ->where(array('member_id'=>$member_id))
                ->find();
        $az = Model()->table('goods')
            ->field('goods_id,store_id,goods_name,appaly_tatol_person,active_tatol_person')
            ->where(array('goods_id'=>$goods_id))
            ->find();

        $logic_buy = Logic('buy');

        $result = $logic_buy->buyStep1(array('0'=>$goods_id.'|1'), false, $member['member_id'], 0);

        $az_ok = $logic_buy->changeAddr(
            $result['freight_list'],
            $data['city_id'],
            $insert_id,
            $member['member_id']
        );

        $post = array();
        //cartidd
        $post['cart_id'][] = $goods_id.'|1';
        $post['pay_message'][7] = '';
        $post['ifcart'] = '';
        $post['pay_name'] = 'online';
        $post['vat_hash'] = $result['data']['vat_hash'];
        $post['address_id'] = $condition['address_id'];
        $post['buy_city_id'] = $data['city_id'];
        $post['allow_offpay'] =  $az_ok['allow_offpay'];
        $post['allow_offpay_batch'] = '7:0';
        $post['offpay_hash'] = $az_ok['offpay_hash'];
        $post['offpay_hash_batch'] =  $az_ok['offpay_hash_batch'];
        $post['invoice_id'] = '';
        $post['ref_url'] = '';
        $result = $logic_buy->weixinbuyStep2($post, $member['member_id'], $member['member_name'], $member['member_email']);
        if(!empty($result)) { 
            if($result['state']&&!empty($result['data'])) {
                Model()->table('wx_present_member')->where(array('id'=>$_POST['present_member_id']))->update(array('goods_id'=>$goods_id,'status'=>'1'));
            } else {
                echo json_encode($result);
                exit();
            }
        } else {
            echo json_encode(array('done'=>false,'msg'=>'not buy'));
            exit();
        }
    }
}
