<?php

class Log_
{
	// 打印log
	/**
	 * 
	 * @param $file文件名前缀
	 * @param $word文件内容
	 */
	// 打印log
/* 	function  log_result($file,$word) 
	{
	    $fp = fopen($file,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	} */
	
	function  log_result($word,$file)
	{
		$filename=$_SERVER["DOCUMENT_ROOT"]."/data/log/pay/".date('Ymd').".log";//文件名
		$file_name = md5('dxj.1301'.date('Y-m-d')).$file.date('Y-m-d').'.log';
		$path      = $_SERVER["DOCUMENT_ROOT"].'/data/log/pay/';
		$file      = $path.$file_name;
		$fp        = fopen($file, "a");
		flock($fp, LOCK_EX);
		//$word="\n".date('Y-m-d H:i:s')."\n".base64_encode($word);
		fwrite($fp, date('Y-m-d H:i:s',time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}

?>