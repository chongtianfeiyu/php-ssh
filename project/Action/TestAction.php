<?php
	class TestAction{
		public static function doAction($param){
			$GLOBALS['g']=array("0"=>"1","2"=>"3");
			Controller::doService("TestService","doService");
			return 1;
		}
	}
?>
