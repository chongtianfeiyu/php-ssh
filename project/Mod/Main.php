<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.21
	*		插件名：	主页Mod
	*/
	if(!isset($GLOBALS['indexMod']))
		die();
	//这里通过Controller::doAction调用Action的method，没有可以不写
	Controller::showView("index");
?>