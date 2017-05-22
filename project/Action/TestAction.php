<?php
	class TestAction{
		public static function doAction($param){
			$GLOBALS['text']="呵呵";
			Controller::doService("TestService","doService");
			return 1;
		}
		public static function testUE(){
			$result=$_POST['text'];
			$result=Controller::doService("UEditorXSSRejector","parse",$_POST['text']);
			die($result);
		}
	}
?>
