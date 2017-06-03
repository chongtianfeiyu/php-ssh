<?php
	class LoginService{
		public static function login(){
			if(!isset($_POST['username'])||!isset($_POST['password'])){
				$GLOBALS['error']="参数不全";
				return false;
			}
			if($_POST['username']==""||$_POST['password']==""){
				$GLOBALS['error']="用户名或密码为空";
				return false;
			}
			$username=$_POST['username'];
			$password=$_POST['password'];
			import("Dao.UserDao");
			$user=new UserDao();
			$result=$user->getUserByLogin($username,$password);
			$user->close();
			if($result){
				$_SESSION['uid']=$result['uid'];
				$_SESSION['username']=$result['username'];
				return true;
			}
			$GLOBALS['error']="用户名或密码错误";
			return false;
		}
	}
?>