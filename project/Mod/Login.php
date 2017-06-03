<?php
	/*		作者：		similing4
	*		创作时间：	2017.6.3
	*		插件名：	登录Mod
	*/
	if(!isset($GLOBALS['indexMod']))
		die();
	Controller::doAction("LoginAction","login");
?>