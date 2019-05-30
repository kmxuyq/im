<?php

$site = 'http://image.yimayholiday.com';

$ds = DIRECTORY_SEPARATOR;

$root     = dirname(dirname(dirname(__FILE__)));
$savepath = 'data' . $ds . 'upload' . $ds . 'images' . $ds . date('Y/m/d', time());
$data = array(
   'status' => 1,
   'list'   => array(),
);

if (isset($_POST['image']) and !empty($_POST['image']) and is_array($_POST['image'])) {
   foreach ($_POST['image'] as $source) {
      $im     = imagecreatefromstring($source);
      if (false != $im) {
         $dm = getimagesizefromstring($source);
         switch ($dm['mime']) {
         case 'image/png':
            $ext = '.png';
            break;
         case 'image/gif':
            $ext = '.gif';
         case 'image/jpg':
         case 'image/jpeg':
            $ext = '.jpg';
            break;
         default:
            $ext = '.png';
            break;
         }
         $file_name = microtime(true) . mt_rand(10000, 99999) . $ext;

         if (!file_exists($root . $ds . $savepath)) {
            if (!mkdir($root . $ds . $savepath, 0777, true)) {
               // $data['msg'] = '创建目录失败';
               break;
            }
         }
         if (!$data['msg']) {
            file_put_contents($root . $ds . $savepath . $ds . $file_name, $source);
            // $data = array(
            //    'status'   => 1,
            //    'msg'      => 'SUCCESS',
            //    'filename' => $file_name,
            //    'site'     => $site,
            //    'url'      => $site . '/' . str_replace($ds, '/', $savepath) . '/' . $file_name,
            //    'path'     => '/' . str_replace($ds, '/', $savepath) . '/' . $file_name,
            // );
            $data['list'][] = '/' . str_replace($ds, '/', $savepath) . '/' . $file_name;
         }
      } else {
         // $data['msg'] = '不是图片';
         continue;
      }
   }
} else {
   $data['msg'] = '文件为空';
}

die(json_encode($data));
