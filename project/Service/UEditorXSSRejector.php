<?php
	class UEditorXSSRejector{
		public static function parse($uedata){
			$allowParams=array(//白名单
				'a'=>array('target','href','title','class','style'),
				'abbr'=>array('title','class','style'),
				'address' =>array('class','style'),
				'area' =>array('shape','coords','href','alt'),
				'article' =>array(),
				'aside' =>array(),
				'audio' =>array('autoplay','controls','loop','preload','src','class','style'),
				'b' =>array('class','style'),
				'bdi' =>array('dir'),
				'bdo' =>array('dir'),
				'big' =>array(),
				'blockquote' =>array('cite','class','style'),
				'br' =>array(),
				'caption' =>array('class','style'),
				'center' =>array(),
				'cite' =>array(),
				'code' =>array('class','style'),
				'col' =>array('align','valign','span','width','class','style'),
				'colgroup' =>array('align','valign','span','width','class','style'),
				'dd' =>array('class','style'),
				'del' =>array('datetime'),
				'details' =>array('open'),
				'div' =>array('class','style'),
				'dl' =>array('class','style'),
				'dt' =>array('class','style'),
				'em' =>array('class','style'),
				'font' =>array('color','size','face'),
				'footer' =>array(),
				'h1' =>array('class','style'),
				'h2' =>array('class','style'),
				'h3' =>array('class','style'),
				'h4' =>array('class','style'),
				'h5' =>array('class','style'),
				'h6' =>array('class','style'),
				'header' =>array(),
				'hr' =>array(),
				'i' =>array('class','style'),
				'img' =>array('src','alt','title','width','height','id','_src','loadingclass','class','data-latex'),
				'ins' =>array('datetime'),
				'li' =>array('class','style'),
				'mark' =>array(),
				'nav' =>array(),
				'ol' =>array('class','style'),
				'p' =>array('class','style'),
				'pre' =>array('class','style'),
				's' =>array(),
				'section' =>array(),
				'small' =>array(),
				'span' =>array('class','style'),
				'sub' =>array('class','style'),
				'sup' =>array('class','style'),
				'strong' =>array('class','style'),
				'table' =>array('width','border','align','valign','class','style'),
				'tbody' =>array('align','valign','class','style'),
				'td' =>array('width','rowspan','colspan','align','valign','class','style'),
				'tfoot' =>array('align','valign','class','style'),
				'th' =>array('width','rowspan','colspan','align','valign','class','style'),
				'thead' =>array('align','valign','class','style'),
				'tr' =>array('rowspan','align','valign','class','style'),
				'tt' =>array(),
				'u' =>array(),
				'ul' =>array('class','style'),
				'video' =>array('autoplay','controls','loop','preload','src','height','width','class','style')
			);
			return preg_replace_callback("/<(.*?)>/is",function($match){
				$match=$match[1];
				$tag=explode(" ", $match);
				$tag=$tag[0];
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
				return "<".$tag." ".$params.">";
			},$uedata);
		}
	}
?>