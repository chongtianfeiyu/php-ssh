<?php
	class Core{}
	function U2T($u,$format='Y-m-d H:i:s'){
		$u+=8*60*60;
		$obj = new DateTime("@".$u);
		return $obj->format($format);
	}
	function T2U($t){
		$obj = new DateTime($t);
		return $obj->format("U");
	}
	function getIp(){
		$cip = "";
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else if(!empty($_SERVER["REMOTE_ADDR"])){
			$cip = $_SERVER["REMOTE_ADDR"];
		}else{
			$cip = '';
		}
		preg_match("/[\d\.]{7,15}/", $cip, $cips);
		$cip = isset($cips[0]) ? $cips[0] : 'unknown';
		unset($cips);
		return $cip;
	}
?>
