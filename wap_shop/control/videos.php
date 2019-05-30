<?php
/**
 * Created by PhpStorm.
 *视频列表控制器
 *
 * 现在做写死的视频列表
 * Date: 2017-1-12
 * Time: 20:42
 */

defined('InShopNC') or exit('Access Invalid!');
class videosControl extends BaseHomeControl{
    public function __construct() {
        parent::__construct();
        Language::read('active');
    }
    public function indexOp(){
//        $this->inset_data();//插入视频数据
        $videos_arr = array();
        $videos_info = Model('videos')->getVideosList('1');
        if(!empty($videos_info)){
            foreach($videos_info as $v){
                $videos_arr[$v['videos_id']]['id']           = $v['videos_id'];
                $videos_arr[$v['videos_id']]['name']         = $v['title'];
                $videos_arr[$v['videos_id']]['click_num']    = $v['click_num'] > 0 ? $v['click_num'] : 0;
                $videos_arr[$v['videos_id']]['img_url']      = "..".DS.$v['thumb'];//图片路径
                $videos_arr[$v['videos_id']]['url']          = $v['videos_url'];
            }
        }
        Tpl::output('html_title', "视频列表-". C('site_name'));
        Tpl::output('videos_list',$videos_arr);
        Tpl::showpage("videos_index","null_layout");

    }
    public function setInc_click_numOp(){
        $id = trim($_GET['id']);
        if($id){
//            $id = str_pad($id, 2, '0', STR_PAD_LEFT);
            $videosModel = Model('videos');
            if ($videosModel->setIncVideosClick(array('videos_id'=>$id))) {
                die ('success');
            } else {
                die('failed');
            }
        }
    }
    public function inset_data(){
        $ids    ="02 04 07 11 13 16 17 19 22 23 33 35 38 39";//视频ids
        $names  ="香格里拉,雨崩村,滇池：重生的眼泪,罗平:色彩的天堂,西双版纳-奔着有阳光的地方,云之南,云南民族风情巡礼,虎跳峡,昆明的一天,千古大理情,心境泸沽,初见凉山,梦中丽江,昆明世界园艺博览园";///视频名字
        $ids_array = explode(' ',$ids);
        $names_array = explode(',',$names);
        $arr = array_combine($ids_array,$names_array);
        $sql = "INSERT INTO `az_videos`(videos_id,store_id,title,thumb,videos_url)VALUES";
        foreach($arr as $k => $v){
            $img_url = DIR_UPLOAD."/video_imgs/video_".$k.".jpg";
            $videos_url = "http://m.eschervr.com/share_gt.html?id=".$k."&from=singlemessage&isappinstalled=2";
            $sql .= "('{$k}','7','{$v}','{$img_url}','{$videos_url}'),";
        }
        $sql = substr($sql,0,-1);
        $re =  Model()->query($sql);
        return $re;
    }
}