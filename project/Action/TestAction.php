<?php
	class TestAction{
		public static function doAction($param){
			$GLOBALS['g']=array("0"=>"1","2"=>"3");
			return 1;
		}
	}
?>