<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.21
	*		功能：		用于s标签if语句的判断功能
	*/
	class Test{
		public static function hasSession($params){
			return isset($_SESSION[$params['key']]);
		}
	}
?>