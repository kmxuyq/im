<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class stationControl extends BaseMemberControl
{

    protected $_city;
    protected $_city_id;
    protected $_date;

    public function __construct()
    {
        parent::__construct();
        Tpl::setDir('station');
        Tpl::setLayout('train_layout');
        $this->_city    = $_SESSION['location_city'];
        $this->_date    = date("Y-m-d");
        $this->_city_id = $_SESSION['location_city_id'];
    }

    //车票搜索主页
    public function ticket_searchOp()
    {
        $default['time']  = $this->_date;
        $default['start'] = "起始站";
        $default['end']   = "终点站";
        $search['time']   = isset($_SESSION['station_time']) ? $_SESSION['station_time'] : $this->_date;
        $search['start']  = $_SESSION['station_start'];
        $search['end']    = $_SESSION['station_end'];
        Tpl::output('default_data', $default);
        Tpl::output('search_data', $search);

        //add by xuyq 在线客服
        $store    = Model('store');
        $store_id = trim($_GET['store_id']);
        if ($store_id) {
            $kefu = $store->where(array('store_id' => $store_id))->find();
            $kefu = unserialize($kefu['store_presales']);
            $kefu = $this->my_sort($kefu, 'priority', SORT_DESC, SORT_NUMERIC);
            foreach ($kefu as $key => $value) {

                $cate_id = explode('_', $value['cate_id']);
                if (in_array('1129', $cate_id)) {
                    Tpl::output('kefu_id', $value['num']);
                    break;
                }
            }
        }

        //end adds

        if (!empty($search['start'])) {
            //处理推荐(按照用户选择起点来推荐)

            $time_map    = "UNIX_TIMESTAMP(concat(DATE_FORMAT(c.date,'%Y-%m-%d'),' ',a.departure_time)) > UNIX_TIMESTAMP()"; //取出未发车 车次
            $sql         = "SELECT a.goods_commonid,a.start_station,a.end_station,a.goods_price,a.departure_time,b.goods_salenum,b.goods_id,b.goods_storage,c.storage,c.date FROM az_goods_common AS a JOIN az_goods AS b ON(a.goods_commonid = b.goods_commonid) JOIN az_cart_operate_date AS c ON(b.goods_id = c.goods_id) WHERE b.goods_state = 1 AND b.goods_verify = 1 AND a.area_info LIKE '%{$search['start']}%'  AND  {$time_map} ORDER BY c.date ASC ,c.storage DESC,a.departure_time ASC LIMIT 8";
            $recommended = Model()->query($sql);
            $url         = "index.php?act=buy_virtual&op=buy_step1&quantity=1&buynum=1&is_hare=0&calendar_type=4";
            Tpl::output('recommended', $recommended);
            Tpl::output('url', $url);
        }
        Tpl::showpage('ticket_search');
    }

    //二维数组排序
    public function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
    {
        if (is_array($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }
    public function ticket_listOp()
    {
        $sql  = "SELECT a.goods_commonid,a.start_station,a.end_station,a.goods_price,a.departure_time,b.goods_salenum,b.goods_id,b.goods_salenum,b.goods_storage,c.storage,c.date FROM az_goods_common AS a JOIN az_goods AS b ON(a.goods_commonid = b.goods_commonid) JOIN az_cart_operate_date AS c ON(b.goods_id = c.goods_id) WHERE b.goods_state = 1 AND b.goods_verify = 1 AND a.start_station = '{$_GET['start_station']}' AND a.end_station = '{$_GET['end_station']}' AND c.date = '{$_GET['search_time']}' ORDER BY c.storage DESC,a.departure_time ASC";
        $data = Model()->query($sql);
        Tpl::output('data', $data);
        Tpl::showpage('ticket_list');
    }

    public function station_locationOp()
    {
        $type         = $_GET['type'];
        $type_id      = ($_GET['type'] == 'start') ? 1 : 2;
        $title        = ($type == 'start') ? '起始站' : '终点站';
        $search_title = !empty($_GET['key']) ? $_GET['key'] : (!empty($_GET['pinyin']) ? $_GET['pinyin'] : '推荐站点');
        $sql          = "SELECT * FROM az_cart_station WHERE ";
        if (!empty($_GET['key'])) {
            $sql .= "address LIKE '%{$_GET['key']}%' AND type = {$type_id} ";
        } else if (!empty($_GET['pinyin'])) {
            $sql .= "pinyin = '{$_GET['pinyin']}' AND type = {$type_id} ";
        } else {
            $sql .= "city_id = {$this->_city_id} AND type = {$type_id} ";
        }
        $sql .= "ORDER BY pinyin  LIMIT 10";
        $data = Model()->query($sql);
		if($type == 'start'){
			foreach($data as $k=>$v){
				if($v['address'] == $_SESSION['station_end']){
					unset($data[$k]);
				}
			}
		}
		
		if($type == 'end'){
			foreach($data as $k=>$v){
				if($v['address'] == $_SESSION['station_start']){
					unset($data[$k]);
				}
			}
		}
		
        Tpl::output('data', $data);
        Tpl::output('type', $type);
        Tpl::output('title', $title);
        Tpl::output('search_title', $search_title);
        Tpl::showpage('station_location');
    }

    public function set_station_locationOp()
    {
        if ($_GET['type'] == 'start') {
            $_SESSION['station_start'] = $_GET['address'];
        } elseif ($_GET['type'] == 'end') {
            $_SESSION['station_end'] = $_GET['address'];
        }
        if ($_GET['station_time']) {
            $_SESSION['station_time'] = trim($_GET['station_time']);
        }
        header("Location: index.php?act=station&op=ticket_search");
    }

    public function BookingTicketsOp()
    {
        $ymdate   = $_GET['date'];
        $goods_id = $_GET['goods_id'];
        if (!empty($ymdate) && !empty($goods_id)) {
            $time_map = "UNIX_TIMESTAMP(concat(DATE_FORMAT(c.date,'%Y-%m-%d'),' ',a.departure_time)) > UNIX_TIMESTAMP()"; //取出未发车 车次
            $sql      = "SELECT a.goods_commonid,a.start_station,a.end_station,a.goods_price,a.departure_time,b.goods_salenum,b.goods_id,b.goods_storage,c.storage,c.date
				FROM az_goods_common AS a JOIN az_goods AS b ON(a.goods_commonid = b.goods_commonid)
				JOIN az_cart_operate_date AS c ON(b.goods_id = c.goods_id)
				WHERE b.goods_state = 1 AND b.goods_verify = 1 AND b.goods_id = {$goods_id} AND c.date = '{$ymdate}'
				AND  {$time_map} ";
            $station_data = Model()->query($sql);
            if ($station_data[0]) {
                $date = $station_data[0]['date'] . " " . $station_data[0]['departure_time'];
                $url  = "index.php?act=buy_virtual&op=buy_step1&quantity=1&buynum=1&is_hare=0&calendar_type=4&date=" . $date . "&goods_storage=" . $station_data[0]['storage'] . "&goods_id=" . $station_data[0]['goods_id'] . "commonid=" . $station_data[0]['goods_commonid'];
                exit(json_encode(array('status' => 1, 'url' => $url)));
            } else {
                exit(json_encode(array('status' => 0, 'msg' => '暂无车票，请稍后重试')));
            }

        } else {
            exit(json_encode(array('status' => 0, 'msg' => '参数错误，请重试')));
        }
    }

}
