<?php


define("QQDEBUG", false);

if (defined("QQDEBUG") && QQDEBUG)

{

    @ini_set("error_reporting", E_ALL);

    @ini_set("display_errors", TRUE);

}


//申请到的appid

$_SESSION["appid"]    ="wxcd21bb7c0246f914";


//申请到的appkey

$_SESSION["appkey"]   = "cbdc538ba1d407c526ca448bfed84f77";


//登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。

//$_SESSION["callback"] = "http://hd.test.jiuxiulvxing.com/yydb/index.php?act=index&op=wxback";
$_SESSION["callback"] = "http://hd.jiuxiulvxing.com/yydb/index.php?act=index&op=wxback";

//$_SESSION["ucallback"] = "http://hd.test.jiuxiulvxing.com/yydb/index.php?act=index&op=wxuback";
$_SESSION["ucallback"] = "http://hd.jiuxiulvxing.com/yydb/index.php?act=index&op=wxuback";

//$_SESSION["dcallback"] = "http://hd.test.jiuxiulvxing.com/yydb/index.php?act=index&op=wxdback";
$_SESSION["dcallback"] = "http://hd.jiuxiulvxing.com/yydb/index.php?act=index&op=wxdback";



?>


