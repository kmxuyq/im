<?php
/**
 * 价格日历控制器
 *
 *
 *
 ***/
defined('InShopNC') or exit ('Access Invalid!');
class goods_stock_priceControl extends BaseGoodsControl {

    public function IndexOp()//普通门票线路价格日历
    {
        $commonid = $_GET["commonid"];
        $month_curent = date("m");//月
        $year_curent = date("Y");//年
        $date = date('Y-m');//数据库查询日历月份
        if ($_GET["date"] != '') {//实现上一月、下一月
            $date = $_GET["date"];
            $month_curent = substr($_GET["date"], -2);
            $year_curent = substr($_GET["date"], 0, 4);
        }

        $package =  trim($_GET['package']);
        if(!empty($package)){
            $where_stock = array('commonid' => $commonid, 'package'=> $package,'date' => $date);
            $where_stock_count = array('commonid' => $commonid, 'package'=> $package);
        }else{
            $where_stock = array('commonid' => $commonid, 'date' => $date);
            $where_stock_count = array('commonid' => $commonid);

        }
        $th_array = array('日', '一', '二', '三', '四', '五', '六');

        $stock_data = Model()->table('stock')->where($where_stock)->field('stock_info,date')->order('date',ASC)->find();
        $stock = json_decode($stock_data['stock_info'], true);//转换成数组
        $first_day_week = date('w', strtotime($stock[1]['date']));//一个月中的第一天是周几 0（表示星期天）到 6（表示星期六）
        $have_day_month = date('t', strtotime($stock_data['date']));//原始数据中这个月有多少天
        $table_num = $first_day_week + $have_day_month;//计算日历格子总个数，注意这里第一列是周日
        $rowspan =ceil($table_num/7);//一共需要多少行
        $first_array = array_fill(0, $first_day_week, array('man_price' => '', 'child_price' => '', 'man_stock' => '','child_stock'=>'','no_use' =>'1'));//组建数组

        $count_stock = Model()->table('stock')->where($where_stock_count)->count();
        $count_stock = intval($count_stock) -1;
        Tpl::output('count_stock', $count_stock);

        foreach ($stock as $k => $val) {
            $pj_day =   $k >= 10 ? $k : "0".$k;//预防后台编辑的没有date值
            $day = isset($val['date']) ? $val['date'] :$stock_data['date']."-".$pj_day;
            $man_stock = !empty($val['man_stock']) ? $val['man_stock'] : '无' ;//成人库存
            $child_stock = !empty($val['child_stock']) ? $val['child_stock'] : '无' ;//儿童库存

            $first_array[] = array('man_price' => $val['man_price'], 'child_price' => $val['child_price'], 'man_stock' =>$man_stock ,'child_stock'=>$child_stock, 'xs_stock'=>$val['xs_stock'],'xs_price'=>$val['xs_price'],'diff_price'=>$val['diff_price'],'day' => $day);
        }
            for($i=count($first_array);$i<=42;$i++){

            $first_array[$i] = array('man_price' => '', 'child_price' => '',  'man_stock' => '','child_stock'=>'','xs_stock'=>'','xs_price'=>'','no_use' =>'1');//其他格子填充

        }


        $first_array = array_chunk($first_array, 7);//把数组分成几块，7个为一组




        Tpl::output('th_array', $th_array);
        Tpl::output('rowspan', $rowspan);
        Tpl::output('first_array', $first_array);
        $date_str = $year_curent . '年' . $month_curent . '月';
        $curent_date_tmp = $date . '-01';
//
        $pre_month = date('Y-m', strtotime("$curent_date_tmp +1 month"));
        $next_month = date('Y-m', strtotime("$curent_date_tmp -1 month"));


        $url = "?act={$_GET["act"]}&commonid={$_GET["commonid"]}&calendar_type={$_GET['calendar_type']}";
        $rl["date"] = $date;
        $rl["pre_month"] = $pre_month;
        $rl["next_month"] = $next_month;
        $rl["date_str"] = $date_str;
        $rl["url"] = $url;
        $rl["package"]=$package;
        Tpl::output('is_cx', $_GET['is_cx']);
        Tpl::output('rl', $rl);
        Tpl::setDir('seller');
        Tpl::showpage('goods_pt_stock_price', 'null_layout');

    }
    //获取限时促销的信息
    /*
     * @param $ic_cx 促销标识
     * @param $cx_id 促销活动id
     * @return array 查询库存信息的条件数组 或者 不返回
     */
    public function  cx_where($is_cx,$xs_id){
        if($is_cx ==1 && $xs_id >0){ //限时促销模块,则取出促销时间区间，以及商品规格
            $xs_info = Model('p_xianshi_goods')->getXianshiGoodsInfo(['xianshi_goods_id'=>$xs_id]);
            $s_ym = date("Y-m",$xs_info['start_time']);
            $e_ym = date("Y-m",$xs_info['end_time']);
            for($s_ym ;$s_ym <= $e_ym;$s_ym++){
                $date_ym [] = $s_ym;
            }
            $map_date = implode(',',$date_ym);
            $package  = Model('goods')->getGoodsInfo(['goods_id'=>$xs_info['goods_id']],"goods_spec");
            $package = unserialize($package['goods_spec']);//商品规格值
            if(!empty($package)){
                $goods_spec=array_values($package);
                $package=$goods_spec[0];//商品规格
            }
            $package = !empty($package) ? $package : "";
            $where['date']      = array('in',$map_date);
            $where['package']   = $package;
            return $where;
        }
    }
    public function hotel_calendarOp(){//酒店日历
        $commonid = $_GET["commonid"];
        $is_cx = $_GET['is_cx'];//促销标识
        $xs_id = $_GET['xs_id'];//促销id
        $month_curent = date("m");//月
        $year_curent = date("Y");//年
        $date = date('Y-m');//数据库查询日历月份

        if ($_GET["date"] != '') {//实现上一月、下一月
            $month_curent = substr($_GET["date"], -2);
            $year_curent = substr($_GET["date"], 0, 4);
        }
        $where = $this->cx_where($is_cx,$xs_id);
        $where['commonid'] = $commonid;
        $th_array = array('日', '一', '二', '三', '四', '五', '六');
        $hotel_stock_data = Model()->table('stock')->where($where)->field('stock_info,date')->order('date',ASC)->select();
        $rowspan =array();
        foreach($hotel_stock_data as $a=>$b){

            $stock[$b['date']][] =json_decode($b['stock_info'],true);//库存信息

            $have_day_month = date('t', strtotime($b['date']));//原始数据中这个月有多少天
            $first_day_week = date('w', strtotime($b['date']));// 6 一个月中的第一天是周几 0（表示星期天）到 6（表示星期六）
            $table_num = $first_day_week + $have_day_month;//计算日历格子总个数，注意这里第一列是周日
            $rowspan[$b['date']] =ceil($table_num/7);//一共需要多少行


            foreach($stock[$b['date']] as $k =>$value){

                $first_array[$b['date']] = array_fill(0, $first_day_week, array('man_price' => '', 'stock' => '','xs_stock'=>'','xs_price'=>'','no_use' =>'1'));//组建数组

                foreach($value as $key =>$val){
                    $pj_day =   $key >= 10 ? $key : "0".$key;//预防后台编辑的没有date值
                    $day = isset($val['date']) ? $val['date'] : $b['date']."-".$pj_day;
                    $stock_one_day = !empty($val['man_stock']) ? $val['man_stock'] : '无' ;
                    $first_array[$b['date']][] = array('man_price' => $val['man_price'] ,'stock' => $stock_one_day,'xs_stock'=>$val['xs_stock'],'xs_price'=>$val['xs_price'], 'day' => $day);
                }
                for($i=count($first_array[$b['date']]);$i<=42;$i++){

                    $first_array[$b['date']][$i] = array('man_price' => '', 'stock' => '','no_use' =>'1');//其他格子填充

                }
                $first_array[$b['date']] = array_chunk($first_array[$b['date']], 7);//把数组分成几块，7个为一组

            }
        }
        Tpl::output('rowspan', $rowspan);
        Tpl::output('th_array', $th_array);
        $date_str = $year_curent . '年' . $month_curent . '月';
        Tpl::setDir('seller');
//        print_r($first_array);exit;
        Tpl::output('first_array', $first_array);
        Tpl::output('date_str', $date_str);
        Tpl::showpage('goods_hotel_stock_price', 'null_layout');
    }
    //获取到选择的数据
    public  function getDataOp(){
        $data=json_encode($_POST);
        echo $data;//返回json格式给js
    }

    //高尔夫价格日历
    public  function golf_calendarOp(){
        $commonid = $_GET["commonid"];
        $month_curent = date("m");//月
        $year_curent = date("Y");//年
        $date = date('Y-m');//数据库查询日历月份

        if ($_GET["date"] != '') {//实现上一月、下一月
            $date = $_GET["date"];
            $month_curent = substr($_GET["date"], -2);
            $year_curent = substr($_GET["date"], 0, 4);
        }
        $th_array = array('日', '一', '二', '三', '四', '五', '六');

        $first_day_week = date('w', strtotime($date));//一个月中的第一天是周几 0（表示星期天）到 6（表示星期六）
        $have_day_month = date('t', strtotime($date));//原始数据中这个月有多少天
        $table_num = $first_day_week + $have_day_month;//计算日历格子总个数，注意这里第一列是周日
        $rowspan =ceil($table_num/7);//一共需要多少行

        $first_array = array_fill(0, $first_day_week, array('date' =>'','no_use' =>'1','day' =>''));//组建数组

        for($i=1;$i<=$have_day_month ;$i++){
            $day =   $i >= 10 ? $i : "0".$i;
            $first_array[] =array('date'=>$i,'day'=>$date."-".$day);
        }

        for($i=count($first_array);$i<=42;$i++){

            $first_array[$i] = array('date'=>'','no_use' =>'1','day' =>'');//其他格子填充

        }

        $first_array = array_chunk($first_array, 7);//把数组分成几块，7个为一组
        Tpl::output('th_array', $th_array);
        Tpl::output('rowspan', $rowspan);
        Tpl::output('first_array', $first_array);
        $date_str = $year_curent . '年' . $month_curent . '月';
        $curent_date_tmp = $date . '-01';
//
        $pre_month = date('Y-m', strtotime("$curent_date_tmp +1 month"));
        $next_month = date('Y-m', strtotime("$curent_date_tmp -1 month"));

        $op ="golf_calendar";
        $url = "?act={$_GET["act"]}&op={$op}&commonid={$_GET["commonid"]}&calendar_type={$_GET['calendar_type']}";
        $rl["date"] = $date;
        $rl["pre_month"] = $pre_month;
        $rl["next_month"] = $next_month;
        $rl["date_str"] = $date_str;
        $rl["url"] = $url;

        Tpl::output('rl', $rl);
        Tpl::setDir('seller');
        Tpl::showpage('goods_golf_stock_price', 'null_layout');

    }
    /**
     * 价格日历选择规格后AJAX获取价格 /wap_shop/?act=goods_stock_price&op=get_goods_price&commonid=100568&package_name=套餐三&date2016-11-09
     */
    public function get_goods_priceOp() {
        if (!empty($_GET["commonid"]) && !empty($_GET["package_name"]) && !empty($_GET["date"])) {
            $goods_commonid = intval($_GET["commonid"]);
            $package_name     = trim($_GET["package_name"]);
            $date = trim($_GET['date']);
            $date_month = substr($date,0,7);
            $date_day =intval(substr($date,-2));//取得最后一天
            $where = array('commonid' => $goods_commonid, 'package' => $package_name,'date'=>$date_month);
            $price_arr      = Model()->table('stock')->where($where)->field('date,stock_info')->find();
            $stock_info = json_decode($price_arr['stock_info'],true);
            if(!empty($stock_info)){
                echo json_encode($stock_info[$date_day]);
            }else{
                echo json_encode(array('done' => false, 'msg' => "此套餐没库存"));

            }
        }
    }


    /**
     * 选择时段时判断此场次是否可以预定
     * @param $commonid商品ID
     * @param $date2015-10-01 年月日
     * @param $golf_minute 00 10 20 30 40 50 分钟
     * @param $golf_hour 8 9 10 11 小时
     * @return $stock_state，2为预订锁定，0为支付成功,1为可以预定
     */
    public  function check_stockOp($commonid='100463',$date='2015-11-26',$golf_minute="10",$golf_hour='8'){
        $where_stock=array('commonid'=>$commonid,'date'=>$date);
        $stock_info_res=Model()->table('golf_stock')->where($where_stock)->field('stock_info')->find();//先查出stock_info信息
        $stock_info=unserialize($stock_info_res["stock_info"]);//格式化
        print_r($golf_minute);
        $stock_state = $stock_info[$golf_hour][$golf_minute]['stock'];
        if($stock_state ==1){
            echo json_encode(array('state'=>'1','message'=>'可以预定'));
        }else{
            echo json_encode(array('state'=>'0','message'=>'您选择的场次已被预定，请重新选择时段'));
        }
    }
}
