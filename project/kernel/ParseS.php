<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.20
	*		功能：		仿java的s标签
	*		日志：		2017.5.19		实现词法分析makeTree方法，将s:标签提取出来
	*					2017.5.20		实现语法分析parse功能与工具方法getpointvalue
	*					2017.5.21		引入插件S功能，添加HookParse类监控解析过程，为Test类提供支持
	*/
	class ParseS{
		public static function makeTree($code){//词法分析
			$resultarr=array();
			$first=true;//第一个是特例
			$all=explode("<s:", $code);
			foreach ($all as $row) {
				if($first){//特例情况,不可能有/s:
					$first=false;
					$row=array(
						"type"=>"text",
						"text"=>$row
					);
					array_push($resultarr,$row);
					continue;
				}
				$regex="/^(.*?)>(.*?|\n*?)$/is";
				if(preg_match_all($regex, $row, $m)){
					$p=$m[1][0];
					$type="";
					$param=array();
					$isend=false;
					if(endWith(trim($p),"/"))
						$isend=true;
					$item=explode(" ", $p);
					if(count($item)>1){
						$t=array_shift($item);
						$p=join(" ",$item);
						$type=$t;
					}else{
						$type=$p;
						$p="";
					}
					$p=str_replace('""', '" "', $p);
					$p=str_replace("''", "' '", $p);
					$reg='/(.*?)="(.*?[^\\\\])"/is';
					if(preg_match_all($reg, $p, $mat)){
						for($i=0;$i<count($mat[1]);$i++)
							$param[trim($mat[1][$i])]=trim($mat[2][$i]);
						$arr=array(
							"type"=>$type,
							"param"=>$param
						);
						array_push($resultarr,$arr);
					}else{
						$arr=array(
							"type"=>$type,
							"param"=>array()
						);
						array_push($resultarr,$arr);
					}
					if($isend){
						$arr=array(
							"type"=>"/".$type,
							"param"=>array()
						);
						array_push($resultarr,$arr);
					}
					$row=$m[2][0];
					$row=explode("</s:", $row);
					$first2=true;//第一个是特例
					foreach ($row as $r) {
						if($first2){
							$first2=false;
							$r=array(
								"type"=>"text",
								"text"=>$r
							);
							array_push($resultarr,$r);
						}else{
							$regex="/^(.*?)>(.*?|\n*?)$/is";
							if(preg_match_all($regex, $r, $m)){
								$r=array(
									"type"=>"/".$m[1][0],
									"param"=>array()
								);
								array_push($resultarr,$r);
								$r=array(
									"type"=>"text",
									"text"=>$m[2][0]
								);
								array_push($resultarr,$r);
							}
						}
					}
					$first=false;
					continue;
				}
			}
			return $resultarr;
		}
		public static $pointvalue=array();
		public static function getpointvalue($arr,$str,$ref=false,$refid=false){
			if($str==="")
				return $arr;
			$r=explode(".", $str);
			if(count($r)==1){
				if(substr($str, 0, 1)=="#")
					return self::$pointvalue[$str];
				return $arr[$str];
			}
			$c=array_shift($r);
			if(substr($c,0,1)=='#'){
				self::$pointvalue[$c]=array_keys($ref)[$refid];
				return self::getpointvalue($ref[array_keys($ref)[$refid]],join(".",$r),$ref,array_keys($ref)[$refid]);
			}
			return self::getpointvalue($arr[$c],join(".",$r),$arr,$c);
		}
		function parse($tree,$arr){//语法分析生成目标代码
			//if语句，session调用，$arr调用
			$result="";
			$iterator_arr=array();//循环栈
			$canecho_arr=array();//条件栈
			$line=0;
			HookParse::$line=&$line;
			HookParse::$iterator_arr=&$iterator_arr;
			HookParse::$tree=&$tree;
			HookParse::$arr=&$arr;
			for($line=0;$line<count($tree);$line++){
				switch ($tree[$line]['type']) {
					case 'text':
						if(!in_array(0, $canecho_arr)){
							$result.=S::onText($tree[$line]['text']);
						}
						break;
					case 'iterator':
						if(count($iterator_arr)==0)
							array_push($iterator_arr, array(
								"value"=>$tree[$line]['param']['value'],
								"id"=>0,
								"line"=>$line
							));
						elseif ($iterator_arr[count($iterator_arr)-1]['line']==$line) {//当前在循环这个iterator
							continue;
						}else{
							array_push($iterator_arr, array(
								"value"=>$tree[$line]['param']['value'],
								"id"=>0,
								"line"=>$line
							));
						}
						break;
					case '/iterator':
						$a=array();
						foreach ($iterator_arr as $r) {
							array_push($a, $r['value']);
							array_push($a, $r['id']);
						}
						array_pop($a);//最后一个数组是我们要的
						if(count(self::getpointvalue($arr,join(".",$a)))==0){//遍历内容不存在
							array_pop($iterator_arr);
							continue;
						}
						if($iterator_arr[count($iterator_arr)-1]['id']==count(self::getpointvalue($arr,join(".",$a)))-1)
							array_pop($iterator_arr);
						else{
							$iterator_arr[count($iterator_arr)-1]['id']++;
							$line=$iterator_arr[count($iterator_arr)-1]['line'];
						}
						break;
					case 'property':
						if($tree[$line]['param']['type']=="item"){
							$a=array();
							foreach ($iterator_arr as $r) {
								array_push($a, $r['value']);
								array_push($a, $r['id']);
							}
							if(!in_array(0, $canecho_arr))
								$result.=S::onParamText(self::getpointvalue($arr,join(".",$a).".".$tree[$line]['param']['value']));
						}else{
							if(!in_array(0, $canecho_arr))
								$result.=S::onParamText(self::getpointvalue($arr,$tree[$line]['param']['value']));
						}
						break;
					case '/property':
						break;
					case 'if':
						$method=$tree[$line]['param']['test'];
						$params=$tree[$line]['param'];
						if(call_user_func("Test::".$method,$params))
							array_push($canecho_arr, 1);
						else
							array_push($canecho_arr, 0);
						break;
					case 'else':
						array_push($canecho_arr,!array_pop($canecho_arr));
						break;
					case '/if':
						array_pop($canecho_arr);
						break;
					default:
						try{
							$method=$tree[$line]['type'];
							$params=$tree[$line]['param'];
							$a="";
							if(substr($method, 0, 1)=='/')
								$a=call_user_func("S::_".substr($method, 1));
							else
								$a=call_user_func("S::".$method,$params);
							if(!in_array(0, $canecho_arr))
								$result.=$a;
						}catch(Exception $e){
							continue;
						}
						break;
				}
			}
			return $result;
		}
	}
?>