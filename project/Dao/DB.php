<?php
	/*      作者：     similing4
	*       创作时间： 2016.9.10
	*       功能：     用于MySql调用数据库进行操作。
	*/
	class DB{
		private $host="localhost";
		private $name="root";
		private $pass="xinqidian";
		private $table="homework";
		private $ut='utf8';
		function __construct(){
			$this->connect();
		}
		function query($sql, $type = ''){
			if(!($query = mysql_query($sql)))
				$this->show("error:",$sql);
			return $query;
		}
		function fetch_array($query){
			return mysql_fetch_array($query);
		}
		function select_arr($name,$req = array(),$like=false){
			$name=str_replace("'","",str_replace("`","",$name));
			if(empty($req)){
				$arr=array();
				$data = $this->query("SELECT * FROM `$name`");
				while ($rows=$this->fetch_array($data)){
					array_push($arr, $rows);
				}
				return $arr;
			}else{
				$t=array();
				foreach ($req as $key => $value){
					array_push($t, "`".$key."`".($like?" like ":"=")."'".str_replace("'","''",$value)."'");
				}
				$t2=join(' and ',$t);
				$arr=array();
				$data = $this->query("SELECT * FROM `$name` where ".$t2);
				while ($rows=$this->fetch_array($data)){
					array_push($arr, $rows);
				}
				return $arr;
			}
		}
		function select_first($name,$req){
			$name=str_replace("`","",$name);
			$t=array();
			foreach ($req as $key => $value){
				array_push($t, "`".$key."`='".$value."'");
			}
			$t2=join(' and ',$name=str_replace("'","''",$t));
			return $this->fetch_array($this->query("SELECT * FROM `$name` where ".$t2));
		}
		function update($table,$list,$tiao){
			$lie="";
			foreach($list as $key=>$value){
				$lie=$lie."`".$key."`='".str_replace("'","''",$value)."',";
			}
			$lie=substr($lie,0,strlen($lie)-1);
			if(empty($tiao))
				return $this->query("update $table set $lie");
			else{
				$tiao2=array();
				foreach ($tiao as $key=>$value){
					array_push($tiao2, "`".$key."`='".str_replace("'","''",$value)."'");
				}
				return $this->query("update $table set $lie where ".join(" and ",$tiao2));
			}
		}
		function close(){
			return mysql_close();
		}
		function fn_insert($table,$name,$arr){
			foreach ($arr as $key=>$value){
				$arr[$key]=str_replace("--", "- -", str_replace("'", "''", $value));
			}
			if($name=="")
				return $this->query("INSERT INTO `$table` VALUES ".str_replace("'null'", "null", "('".join("','",$arr)."')"));
			else{
				return $this->query("INSERT INTO `$table` $name VALUES ".str_replace("'null'", "null", "('".join("','",$arr)."')"));
			}
		}
		function fn_delete($table,$id,$value){
			return $this->query("delete from `$table` where $id='".str_replace("'", "''", $value)."'");
		}
		function fn_del($table,$c){
			if(empty($c))
				$this->query("delete from `$table`");
			else{
				$tiao2=array();
				foreach ($c as $key=>$value){
					array_push($tiao2, $key."='".str_replace("'","''",$value)."'");
				}
				return $this->query("delete from `$table` where ".join(" and ",$tiao2));
			}
		}
		function createtable($name,$table){
			$table2="";
			foreach($table as $value){
				$table2=$table2."".$value.",";
			}
			$table2=substr($table2,0,strlen($table2)-1);
			$this->query("CREATE TABLE ".$name."(".$table2.")");
		}
		function droptable($name){
			$this->query("drop table ".$name);
		}
		function insertfromtable($table,$table2){
			$this->query("insert into $table select * from $table2");
		}
		function getcode(){
			return sprintf('%x',crc32(microtime()));
		}
		function connect(){
			$link=mysql_connect($this->host,$this->name,$this->pass) or die(mysql_error($sql));
			mysql_select_db($this->table,$link) or die("没该数据库：".$this->table);
			mysql_query("SET NAMES '$this->ut'");
		}
		function show($message = '', $sql = ''){
			if(!$sql)
				echo $message;
			else
				echo $message.' '.$sql."".mysql_errno()."".mysql_error().'<br />';
		}
		function affected_rows(){
			return mysql_affected_rows();
		}
		function result($query, $row){
			return mysql_result($query, $row);
		}
		function num_rows($query){
			return @mysql_num_rows($query);
		}
		function num_fields($query){
			return mysql_num_fields($query);
		}
		function free_result($query){
			return mysql_free_result($query);
		}
		function insert_id(){
			return mysql_insert_id();
		}
		function fetch_row($query){
			return mysql_fetch_row($query);
		}
		function version(){
			return mysql_get_server_info();
		}
	}
?>