<?php
	class CheckCodeAction{
		public static function makeCode(){
			Controller::doService("CheckCodeService","makeCode");
			return 1;
		}
	}
?>