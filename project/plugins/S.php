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
		public static function checkcode($params){
			return "<img src='index.php?mod=CheckCode'>";
		}
		public static function _checkcode(){
			return "</img>";
		}
		public static function jquery($params){
			return '<script src="assests/js/jquery.min.js">';
		}
		public static function _jquery(){
			return '</script>';
		}
		public static function tether($params){
			return '<script src="assests/js/tether.js">';
		}
		public static function _tether(){
			return '</script>';
		}
		public static function bootstrap($params){
			if($params['req']=='false')
				return '<link rel="stylesheet" href="assests/css/bootstrap.min.css" />'.
					'<script src="assests/js/bootstrap.min.js">';
			return self::jquery().self::_jquery().self::tether().self::_tether().'<link rel="stylesheet" href="assests/css/bootstrap.min.css" />'.
				'<script src="assests/js/bootstrap.min.js">';
		}
		public static function _bootstrap(){
			return '</script>';
		}
		public static function ueditor($params){
			return '
				<script type="text/javascript" charset="utf-8" src="Tools/Editor/ueditor.config.js"></script>
				<script type="text/javascript" charset="utf-8" src="Tools/Editor/ueditor.all.min.js"></script>
				<script type="text/javascript" charset="utf-8" src="Tools/Editor/lang/zh-cn/zh-cn.js"></script>
			';
		}
		public static function _ueditor(){
			return "";
		}
	}
?>