<?php
	class LoginAction{
		public static function login(){
			if(Controller::doService("LoginService","login"))
				return "Home";
			else
				return "Main";
		}
	}
?>