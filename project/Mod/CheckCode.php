<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.21
	*		插件名：	验证码Mod
	*/
	if(!isset($GLOBALS['indexMod']))
		die();
	Controller::doAction("CheckCodeAction","makeCode");
?>