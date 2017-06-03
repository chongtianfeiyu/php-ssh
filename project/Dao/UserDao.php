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
		function getUserByLogin($username,$password){
			$r=$this->db->select_arr("users",array(
				"username"=>$username,
				"password"=>md5($password)
			));
			if(empty($r))
				return false;
			return $r[0];
		}
	}
?>