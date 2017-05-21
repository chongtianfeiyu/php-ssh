<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.21
	*		功能：		仿java的import功能
	*/
	function import($name){
		global $indexMod;
		$arr=explode(".", $name);
		$class_name=$arr[count($arr)-1];
		if(!class_exists($class_name,false))
			require_once(dirname(__FILE__)."/../".join("/",$arr).".php");
	}
?>