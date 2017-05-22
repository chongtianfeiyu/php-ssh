<?php
	class TestAction{
		public static function doAction($param){
			Controller::doService("TestService","doService");
			return 1;
		}
	}
?>
