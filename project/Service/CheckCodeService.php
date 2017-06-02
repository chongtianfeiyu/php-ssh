<?php
	class CheckCodeService{
		public static function makeCode(){
			useModel('ValidateCode');
			$_vc = new ValidateCode();
			$_vc->doimg();
			$_SESSION['check_code'] = $_vc->getCode();
			return 1;
		}
		public static function checkCode($code){
			$b=false;
			if(strcasecmp($_SESSION['check_code'],$code)==0)
				$b=true;
			unset($_SESSION['check_code']);
			return $b;
		}
	}
?>