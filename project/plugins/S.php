<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.21
	*		插件名：	S标签功能插件
	*/
	class S{
		public static function onText($text){
			return $text;
		}
		public static function onParamText($text){
			return $text;
		}
		public static function g($params){
			return "gstart";
		}
		public static function _g(){
			return "gend";
		}
		public static function checkcode(){
			return "<img src='index.php?mod=CheckCode'>";
		}
		public static function _checkcode(){
			return "</img>";
		}
	}
?>