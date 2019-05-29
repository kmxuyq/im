<?php
/**
 * 视频模型
 *
 *
 *
 *

 */
defined('InShopNC') or exit('Access Invalid!');
class videosModel extends Model
{
    public function __construct()
    {
        parent::__construct('videos');
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getVideosInfo($condition, $field = '*', $master = false)
    {
        return $this->table('videos')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 视频列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getVideosInfoByID($id, $fields = '*'){
        $info = $this->getVideosInfo(array('id' => $id), '*', true);
        return $info;
    }
    public function getVideosList($condition, $fields = '*',$order,$limit){
        return $this->table('videos')->field($fields)->where($condition)->order($order)->limit($limit)->select();
    }
    public function setIncVideosClick($condition){
        return $this->table('videos')->where($condition)->setInc('click_num',1);
    }

}
