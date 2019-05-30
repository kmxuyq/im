<?php

function output_data($datas, $extend_data = array())
{
    $data         = array();
    $data['code'] = 200;

    if (!empty($extend_data)) {
        $data = array_merge($data, $extend_data);
    }

    $data['datas'] = $datas;

    if (!empty($_GET['callback'])) {
        echo $_GET['callback'] . '(' . json_encode($data) . ')';die;
    } else {
        echo json_encode($data);die;
    }
}

function output_error($message, $extend_data = array())
{
    $datas = array('error' => $message);
    output_data($datas, $extend_data);
}
/**
 *
 * @param $file文件名前缀
 * @param $word文件内容
 */
function log_result($word, $file)
{
    $filename  = $_SERVER["DOCUMENT_ROOT"] . "/data/log/pay/" . date('Ymd') . ".log"; //文件名
    $file_name = md5('dxj.1301' . date('Y-m-d')) . $file . date('Y-m-d') . '.log';
    $path      = $_SERVER["DOCUMENT_ROOT"] . '/data/log/pay/';
    $file      = $path . $file_name;
    $fp        = fopen($file, "a");
    flock($fp, LOCK_EX);
    //$word="\n".date('Y-m-d H:i:s')."\n".base64_encode($word);
    fwrite($fp, date('Y-m-d H:i:s', time()) . "\n" . $word . "\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}
/**
 * curl获取远程网址内容,替代file_get_contents
 * @param  $url
 * @param  $type方式默认为GET,有post和get两种方式，获取POST数据$_POST['']
 * @param  $post_data,post方式的数组
 */
function curl($url, $type = 'get', $post_data = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($type == 'post') {
        curl_setopt($ch, CURLOPT_POST, 1); //开启POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //POST数据
    }
    $output = curl_exec($ch);
    curl_close($ch);
    return $output; //返回或者显示结果
}
/**
 * 提交表单
 * @param  $arr 数组
 * @param  $action地址
 */
function submit_post($arr, $action)
{
    echo '<form name="form1" method="post" action="' . $action . '">';
    foreach ($arr as $k => $v) {
        echo "<input name='{$k}' value='{$v}' type='hidden'/>";
    }
    echo "<script>document.form1.submit();</script></form>";

}
function mobile_page($page_count)
{
    //输出是否有下一页
    $extend_data  = array();
    $current_page = intval($_GET['curpage']);
    if ($current_page <= 0) {
        $current_page = 1;
    }
    if ($current_page >= $page_count) {
        $extend_data['hasmore'] = false;
    } else {
        $extend_data['hasmore'] = true;
    }
    $extend_data['page_total'] = $page_count;
    return $extend_data;
}
