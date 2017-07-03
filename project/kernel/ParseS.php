<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.20
	*		功能：		仿java的s标签
	*		日志：		2017.5.19		实现词法分析makeTree方法，将s:标签提取出来
	*					2017.5.20		实现语法分析parse功能与工具方法getpointvalue
	*					2017.5.21		引入插件S功能，添加HookParse类监控解析过程，为Test类提供支持
	*					2017.7.03		修正了语法解析器的问题，更改使用方法
	*/
	class ParseS{
		public static function makeTree($code){//词法分析
			import("kernel.simple_html_dom");
			$resultarr=array();
			$first=true;//第一个是特例
			$all=explode("<s:", $code);
			function gindex($row){
				$xx=false;
				$instr=false;
				for($i=0;$i<strlen($row);$i++){
					switch(substr($row, $i, 1)){
						case "\\":
							$xx=true;
							break;
						case '>':
							if(!$instr)
								return $i;
							break;
						case "\"":
						case "'":
							if(!$xx)
								$instr=!$instr;
						default:
							$xx=false;
							break;
					}
				}
			}
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
				$index=gindex($row);
				$p=substr($row, 0,$index);
				$isend=false;
				if(endWith(trim($p),"/"))
					$isend=true;
				$p="<".$p.">";
				$dom=str_get_html($p);
				$type=$dom->root->children[0]->tag;
				$param=$dom->root->children[0]->attr;
				$dom->clear();
				$arr=array(
					"type"=>$type,
					"param"=>$param
				);
				array_push($resultarr,$arr);
				if($isend){
					$arr=array(
						"type"=>"/".$type,
						"param"=>array()
					);
					array_push($resultarr,$arr);
				}
				$row=substr($row, $index+1);
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
						$index=gindex($r);
						$row=$r;
						$p=substr($row, 0,$index);
						$r=array(
							"type"=>"/".$p,
							"param"=>array()
						);
						array_push($resultarr,$r);
						$r=array(
							"type"=>"text",
							"text"=>substr($row, $index+1)
						);
						array_push($resultarr,$r);
					}
				}
				$first=false;
				continue;
			}
			return $resultarr;
		}
		public static $pointvalue=array();
		public static function getpointvalue($arr,$str){
			if($str==="")
				return $arr;
			$r=explode(".", $str);
			if(count($r)==1){
				return $arr[$str];
			}
			$c=array_shift($r);
			return self::getpointvalue($arr[$c],join(".",$r));
		}
		function parse($tree,$arr){//语法分析生成目标代码
			//if语句，session调用，$arr调用
			$result="";
			$iterator_arr=array();//循环栈
			$canecho_arr=array();//条件栈
			$idarr = array();//遍历栈
			$line_stack = array();//遍历的行栈
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
						array_push($line_stack, $line);
						$params = $tree[$line]['param'];
						$value = $params['value'];
						$k = $params['k'];
						$v = $params['v'];
						if(array_key_exists($value, $iterator_arr)){
							$iterator_arr[$k] = array_keys($iterator_arr[$value][$idarr[$k]]);
							$iterator_arr[$v] = array_values($iterator_arr[$value][$idarr[$k]]);
							$idarr[$k] = 0;
							$idarr[$v] = 0;
							$hasvalue = ((count($iterator_arr[$k]) > 0) ? 1 : 0);
						}else{
							$arr = self::getpointvalue($GLOBALS,$value);
							$iterator_arr[$k] = array_keys($arr);
							$iterator_arr[$v] = array_values($arr);
							$idarr[$k] = 0;
							$idarr[$v] = 0;
							$hasvalue = ((count($arr) > 0) ? 1 : 0);
						}
						if($hasvalue)
							array_push($canecho_arr, 1);
						else
							array_push($canecho_arr, 0);
						break;
					case '/iterator':
						$l = array_pop($line_stack);
						$params = $tree[$l]['param'];
						$k = $params['k'];
						$v = $params['v'];
						if($idarr[$k] + 1 < count($iterator_arr[$k])){
							$idarr[$k]++;
							$idarr[$v]++;
							$line = $l;
							array_push($line_stack,$l);
						}else{
							array_pop($canecho_arr);
							unset($idarr[$params['k']]);
							unset($idarr[$params['v']]);
							unset($iterator_arr[$params['k']]);
							unset($iterator_arr[$params['v']]);
						}
						break;
					case 'property':
						if(!in_array(0, $canecho_arr)){
							$params = $tree[$line]['param'];
							$value = $params['value'];
							$e = explode(".", $value);
							$txt = "";
							if(array_key_exists($e[0], $iterator_arr)){
								$h=array_shift($e);
								$txt = self::getpointvalue($iterator_arr,$h.".".$idarr[$h].".".join($e,"."));
							}else
								$txt = self::getpointvalue($GLOBALS,$value);
							$result .= S::onText($txt);
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
					case 'css':
						if(!in_array(0, $canecho_arr)){
							$path="assests/css/";
							$params=$tree[$line]['param'];
							if(isset($params['src'])){
								$result.="<link href=\"".$path.str_replace(".", "/", $params['src']).".css\" rel=\"stylesheet\" type=\"text/css\">";
							}else{
								$result.="<link>";
							}
						}
						break;
					case '/css':
						$result.="</link>";
						break;
					case 'js':
						if(!in_array(0, $canecho_arr)){
							$path="assests/js/";
							$params=$tree[$line]['param'];
							if(isset($params['src']))
								$result.="<script src=\"".$path.str_replace(".", "/", $params['src']).".js\" type=\"text/javascript\">";
						}
						break;
					case '/js':
						if(!in_array(0, $canecho_arr))
							$result.="</script>";
						break;

					case 'img':
						if(!in_array(0, $canecho_arr)){
							$path="assests/images/";
							$params=$tree[$line]['param'];
							$par="";
							foreach ($params as $key => $value) {
								if($key=="src")
									$value=str_replace(".", "/", $value);
								$value=substr_replace($value, ".", strrpos($value,'/'), 1);
								$par.=$key."=\"".$value."\" ";
							}
							$result.="<img ".$par.">";
						}
						break;
					case '/img':
						if(!in_array(0, $canecho_arr))
							$result.="</img>";
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
