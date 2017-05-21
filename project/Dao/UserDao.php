<?php
	class UserDao{
		private $db;
		function __construct(){
			$this->db=new DB();
		}
		function close(){
			$this->db->close();
		}
		function getUserByUID($uid){
			$r=$this->db->select_arr("users",array("uid"=>$uid));
			if(empty($r))
				return false;
			return $r[0];
		}
	}
?>