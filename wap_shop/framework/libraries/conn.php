<?php
class opmysqli{

	private $host ='';			//服务器地址
	private $name = '';					//登录账号
	private $pwd = '';					//登录密码
	private $dBase ='';				//数据库名称
	private $conn = '';						//数据库链接资源
	private $result = '';					//结果集
	private $msg = '';						//返回结果
	private $fields;						//返回字段
	private $fieldsNum = 0;					//返回字段数
	private $rowsNum = 0;					//返回结果数
	private $filesArray = array();			//返回字段数组
	private $rowsArray = array();			//返回结果数组
	//初始化类
	function __construct($host='',$name='',$pwd='',$dBase=''){
		if($host != '')
			$this->host = $host;
		if($name != '')
			$this->name = $name;
		if($pwd != '')
			$this->pwd = $pwd;
		if($dBase != '')
			$this->dBase = $dBase;
		$this->init_conn();
	}
	/**
	 *  链接数据库*/
	function init_conn(){	
		require_once $_SERVER["DOCUMENT_ROOT"].'/data/config/config.ini.php';
		if($this->conn == ''){
			$dbhost=$config['db']['1']['dbhost'];
			$dbname=$config['db']['1']['dbuser'];
			$dbpwd=$config['db']['1']['dbpwd'];
			$dBase=$config['db']['1']['dbname'];
			$this->conn=mysqli_connect('localhost','root','','ymjr2');
			mysqli_query($this->conn,"set names utf8");		
		}
	}
	function fetch_array($res) {
		return mysqli_fetch_array($res);
	}
	function insert_id() {		
		$this->init_conn($this->conn);
		return mysqli_insert_id($this->conn);
	}
	/**
	 *  mysqli_query执行SQL，返回$res*/
	function query_res($sql) {
		//mysqli_query("set names 'utf8'");
		$res=mysqli_query($this->conn,$sql);
		if(!$res)$this->halt("<font color=333399>SQL语句无效,可能数据库被破坏。</font><br><br><font style='font-size:12px;color:#ffffff'>".$sql);
		return $res;	
	}
	/**
	 *查询结果*/
	function mysqli_query_rst($sql){
		$this->init_conn();
		$this->result = @mysqli_query($this->conn,$sql);
	}
	/**
	 * 取得字段数 */
	function getFieldsNum($sql){
		$this->mysqli_query_rst($sql);
		$this->fieldsNum = @mysqli_num_fields($this->result);
	}
	/**
	 * 取得查询结果数*/
	function getRowsNum($sql){
		$this->mysqli_query_rst($sql);
		$this->rowsNum = @mysqli_num_rows($this->result);
		return $this->rowsNum;
	}
	/**
	 * 取得记录数组（多条记录）*/
	function getRowsArray($sql){
		$this->mysqli_query_rst($sql);
		while($row = mysqli_fetch_array($this->result,MYSQLI_ASSOC)) {
    		$this->rowsArray[] = $row;
   		}
		return $this->rowsArray;
	}
	/**
	 * 更新、删除、添加记录数*/
	function uidRst($sql){
		$this->init_conn();
		@mysqli_query($this->conn,$sql);
		$this->rowsNum = @mysqli_affected_rows($this->conn);
		return $this->rowsNum;
	}
	/**
	 * 获取对应的字段值 */
	function getFields($sql,$fields){
		$this->mysqli_query_rst($sql);
		if(mysqli_num_rows($this->result) > 0){
			$tmpfld = mysqli_fetch_row($this->result);
			$this->fields = $tmpfld[$fields];
		}
		return $this->fields;
	}
	
	/**
	 * 错误信息 */
	function msg_error(){
		if(mysqli_errno() != 0) {
			$this->msg = mysqli_error();
		}
		return $this->msg;
	}
	/**
	 * 释放结果集*/
	function close_rst(){
		if(empty($this->result))exit();
		mysqli_free_result($this->result);
		$this->msg = '';
		$this->fieldsNum = 0;
		$this->rowsNum = 0;
		$this->filesArray = '';
		$this->rowsArray = '';
	}
	/**
	 * 关闭数据库*/
	function close_conn(){
		$this->close_rst();
		mysqli_close($this->conn);
		$this->conn = '';
	}
	function halt($msg){
		echo $msg;exit;
	}

	function show_pager($pageurl,$curpage,$totpage,$totnum){
		echo "<table><tr><td>";
		//showarticle.php?act=&uid=".$_GET['uid']."&artid=".$_GET['artid']."&curpage=".($curpage-1)."//原URL地址
		$prepage=$pageurl."&curpage=".($curpage-1);//preg_replace("/curpage=(\d+)/sm", "curpage=".($curpage-1), $_SERVER[REQUEST_URI]);//使用正则表达式替换URL参数
		$nextpage=$pageurl."&curpage=".($curpage+1);//preg_replace("/curpage=(\d+)/sm", "curpage=".($curpage+1), $_SERVER[REQUEST_URI]);
		$firstpage=$pageurl."&curpage=1";//preg_replace("/curpage=(\d+)/sm", "curpage=1", $_SERVER[REQUEST_URI]);
		$endpage=$pageurl."&curpage=".$totpage;//preg_replace("/curpage=(\d+)/sm", "curpage=".$totpage, $_SERVER[REQUEST_URI]);
		echo ($curpage == 1)?"首页&nbsp;":"<a href=\"{$firstpage}\">首页</a>&nbsp;";
		echo ($curpage==1)?"上一页&nbsp;":"<a href=\"{$prepage}\">上一页</a>&nbsp;";
		echo ($curpage == $totpage)?'下一页&nbsp;':"<a href=\"{$nextpage}\">下一页</a>&nbsp;";
		echo ($curpage==$totpage)?"尾页&nbsp;":"<a href=\"{$endpage}\">尾页</a>&nbsp;";
		echo "当前是第".$curpage."页/共".$totpage."页&nbsp;".$totnum."条记录  跳转到：";
		//echo "<select id=\"jump\" name=\"jump\"	onchange=\"javascript:window.location=('showarticle.php?act=see&uid={$_GET['uid']}&artid={$_GET['artid']}&curpage='+this.options[this.selectedIndex].value);\">";
		echo "<select id=\"jump\" name=\"jump\"	onchange=\"javascript:window.location=('{$pageurl}&curpage='+this.options[this.selectedIndex].value);\">";
				
		if ($totpage <= 20) {
			for($i = 1; $i <= $totpage; $i ++) {
				if ($i == $curpage)
					echo "<option value=" . $i . " selected>" . $i . "</option>";
				else
					echo "<option value=" . $i . ">" . $i . "</option>";
			}
	} else {
		if ($curpage >= 15) {
			$endpage = 1;
			if ($curpage + 10 <= $totpage) {
				$endpage = $curpage + 10;
			} else {
				$endpage = $totpage;
			}
			for($i = $curpage - 10; $i <= $endpage; $i ++) {
				if ($i == $curpage)
					echo "<option value=" . $i . " selected>" . $i . "</option>";
				else
					echo "<option value=" . $i . ">" . $i . "</option>";
			}
		} else {
			for($i = 1; $i <= 20; $i ++) {
				if ($i == $curpage)
					echo "<option value=" . $i . " selected>" . $i . "</option>";
				else
					echo "<option value=" . $i . ">" . $i . "</option>";
			}
		}
	
	}
	echo "</select>页</td></tr></table>";
	}
}
class dbmysqli{
	private $host =hostname;			//服务器地址
	private $name = username;					//登录账号
	private $pwd = password;					//登录密码
	private $dBase =database;				//数据库名称
	private $conn;
	//初始化类
	function __construct($host='',$name='',$pwd='',$dBase=''){
		if($host != '')
			$this->host = $host;
		if($name != '')
			$this->name = $name;
		if($pwd != '')
			$this->pwd = $pwd;
		if($dBase != '')
			$this->dBase = $dBase;
		$this->init_conn();
	}
	/**
	 *  链接数据库*/
	function init_conn(){
		if(empty($this->conn)){
			$this->conn=new mysqli($this->host,$this->name,$this->pwd,$this->dBase);
			mysqli_query($this->conn,"set names utf8");
		}
		
	}
	function multi_query($sqls){//批量执行SQL语句
		if($this->conn == ''){
			$this->init_conn();
		}
		return $this->conn->multi_query($sqls);
	}
	
}
$conne = new opmysqli();
?>