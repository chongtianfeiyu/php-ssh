<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.20
	*		功能：		入口文件
	*/
	session_start();
	$GLOBALS['indexMod'] = '防mod被直接调用';
	function endWith($haystack, $needle) {
		$length = strlen($needle);
		if($length == 0)
			return true;
		return (substr($haystack, -$length) === $needle);
	}
	if(!isset($_GET['mod']))
		$_GET['mod']="Main";
	if(!preg_match('/^[_0-9a-zA-Z]*$/i',$mod))
		die("Mod Name Error");
	require("kernel/Use.php");
	import("Controller");
	import("plugins.S");
	import("kernel.HookParse");
	import("kernel.ParseS");
	import("kernel.Core");
	import("Test");
	ob_start();
	Controller::init();
	ob_clean();
	ob_start();
	import("Mod.".$_GET['mod']);
	$code=ob_get_contents();
	ob_clean();
	$GLOBALS['session']=$_SESSION;
	echo ParseS::parse(ParseS::makeTree($code),$GLOBALS);
?>