<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.20
	*		功能：		用于监控ParseS的功能与执行情况，可根据hookv获取当前位置的遍历的值或非type=item的值。
	*/
	class HookParse{
		public static $tree;
		public static $line;
		public static $iterator_arr;
		public static $arr;
		public static function hookv($val,$item=false){
			$a=array();
			if($item){
				foreach (self::$iterator_arr as $r) {
					array_push($a, $r['value']);
					array_push($a, $r['id']);
				}
			}
			if(!$item)
				return getpointvalue(self::$arr,$val);
			else
				return getpointvalue(self::$arr,join(".",$a).".".$val);
		}
	}
?>