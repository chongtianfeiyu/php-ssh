<?php
	class TestAction{
		public static function doAction($param){
			$GLOBALS['g']=array("0","hehe");
			return 1;
		}
	}
?>