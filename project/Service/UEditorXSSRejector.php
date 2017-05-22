<?php
	class UEditorXSSRejector{
		public static function parse($uedata){
			$allowParams=array(
				"p"=>array("class","style"),
				"h1"=>array("class","style"),
				"h2"=>array("class","style"),
				"h3"=>array("class","style"),
				"h4"=>array("class","style"),
				"h5"=>array("class","style"),
				"hr"=>array(),
				"br"=>array(),
				"img"=>array("src"),
				"table"=>array(),
				"tr"=>array("class"),
				"td"=>array("width","valign","rowspan","colspan"),
				"sup"=>array(),
				"sub"=>array(),
				"del"=>array(),
				"span"=>array("class","style"),
				"em"=>array(),
				"strong"=>array(),
				"blockquote"=>array(),
				"ol"=>array("class","style"),
				"ul"=>array("class","style"),
				"li"=>array("class","style"),
				"a"=>array("href","tag","title"),
				"pre"=>array("class")
			);
			//为了服务器安全，禁止使用视频上传。如果UEditor来源是可信的，可以不使用本Rejector
			return preg_replace_callback("/<(.*?)>/is",function($match){
				$match=$match[1];
				$tag=explode(" ", $match)[0];
				$t=explode(" ", $match);
				array_shift($t);
				$params=join(" ",$t);
				if(substr($tag,0,1)=='/')
					return "<".$tag.">";
				if(!in_array($tag, array_keys($allowParams)));
					return "";
				$params=str_replace('""', '" "', $params);
				$params=str_replace("''", "' '", $params);
				$param=preg_replace_callback("/(.*?)=['\"](.*?[^\\\\])['\"]/is",function($m){
					if(!in_array($m[1], $allowParams[$tag]))
						return "";
					if($m[1]=="href"&&substr($m[2], 0, 10)=="javascript")
						return "href=\"#\"";
					return $m[0];
				},$params);
				return "<".$tag." ".$params.">");
			},$uedata);
		}
	}
?>