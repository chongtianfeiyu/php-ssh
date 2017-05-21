<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.21
	*		插件名：	主页Mod
	*/
	if(!isset($indexMod))
		die();
	Controller::doAction("TestAction","doAction",array("1"));
	Controller::showView("index");
?>