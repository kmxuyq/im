<?php
/**
 * @Author: root
 * @Date:   2015-03-31 17:07:50
 * @Last Modified by:   root
 * @Last Modified time: 2015-04-06 09:49:59
 */
/**
* 
*/
class DbShow
{	
	public $host;
	public $user;
	public $pwd;
	public $dbname;
	public $mysqli;
	public $config=array(
		//'TABLE_SCHEMA'=>'表所在库',
		//'TABLE_NAME'=>'表名',
		'COLUMN_NAME'=>'列名',
		//'ORDINAL_POSITION'=>'标识号',
		'COLUMN_DEFAULT'=>'默认值',
		'IS_NULLABLE'=>'是否为空',
		//'DATA_TYPE'=>'数据类型',
		'CHARACTER_MAXIMUM_LENGTH'=>'最大长度(字符)',
		'CHARACTER_OCTET_LENGTH'=>'最大长度(字节)',
		'NUMERIC_PRECISION'=>'总精度',
		'NUMERIC_SCALE'=>'小数位数',
		//'DATETIME_PRECISION'=>'时间',
		'COLUMN_TYPE'=>'数据类型',
		'COLUMN_KEY'=>'是否主键',
		'EXTRA'=>'EXTRA',
		'COLUMN_COMMENT'=>'描述'
		);
	public function __construct($host='localhost',$user='root',$pwd='',$dbname='test')
	{
		$this->host=$host;
		$this->user = $user;
		$this->pwd=$pwd;
		$this->dbname =$dbname;
		$this->mysqli = new mysqli($host,$user,$pwd,$dbname);
		if($this->mysqli->connect_error )
		{
			die ('数据库链接失败'.$this->mysqli->connect_error);		
		}
		$this->mysqli->query("SET NAMES 'utf8'");
	}


	//获取数据库所有表
	function get_tabels()
	{
		$sql = 'SHOW TABLES FROM '.$this->dbname;
		$res = $this->mysqli->query($sql);
		$tables = array();
		if($res)
		{
			if($res->num_rows>0)
			{
				while ($row = $res->fetch_array(MYSQLI_NUM)) {
					$tables[]=$row[0];
				}			
			}		
			return $tables;
		}else
		{
			return flase;
		}
	}

	//获取单张表各字段信息
	function get_tabels_fields($table)
	{
		$fields = implode(',', array_flip($this->config));
		$sql = "SELECT $fields FROM information_schema.columns WHERE table_schema = '$this->dbname' AND table_name = '$table'";
		$res = $this->mysqli->query($sql);
		$columns = array();
		if($res)
		{
			if($res->num_rows>0)
			{
				while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
					$columns[]=$row;
				}			
			}		
			return $columns;
		}else
		{
			return flase;
		}
	}
	//输出数据库简介
	function table_view()
	{
		$style=<<<STR
		<!-- 新 Bootstrap 核心 CSS 文件 -->
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">

		<!-- 可选的Bootstrap主题文件（一般不用引入） -->
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

		<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
		<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>

		<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
		<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

		<style type="text/css">
		h1{text-align:center;}
		</style>
STR;
		echo $style;
		$tables = $this->get_tabels();
		foreach ($tables as $key => $value) 
		{	
			echo '<h1>',$value,'</h1>';
			echo '<div class="table-responsive">';			
			echo "<table class='table   table-hover'>";
			$columns = $this->get_tabels_fields($value);
			echo "<tr><td>序号</td>";
			foreach ($this->config as $v) {
				echo "<td>$v</td>";
			}
			echo "</tr>";
			
			foreach ($columns as $k => $v) {
				echo "<tr><td>$k</td>";
				foreach ($v as $a=>$b) {			
					echo "<td>$b</td>";
				}		
				echo "</tr>";
			}
			
			echo "</table>";
			echo '</div>';
		}
		$this->mysqli->close();
	}
}

$db=new DbShow('192.168.1.117','ymtx','ymtx#2W1testdb','ymtx');
$db->table_view();
