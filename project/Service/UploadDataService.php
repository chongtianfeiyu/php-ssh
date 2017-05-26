<?php
	class UploadDataService{
		public static function upload($allow_types,$after=""){//return the uploaded file name or false
			function customError($errno, $errstr){
				echo "<b>Error:</b> [$errno] $errstr";
			}
			set_error_handler("customError");//查错
			if(!isset($_FILES['file']))
				return false;
			if ($_FILES["file"]["error"] > 0)
				return false;
			$uploaddir = dirname(__FILE__)."/../files/"; //文件保存目录
			$type = $allow_types; //允许上传文件的类型,如array("doc","zip");
			function fileext($filename) {
				return substr(strrchr($filename, '.') , 1);
			}
			$a = strtolower(fileext($_FILES['file']['name']));
			if (!in_array(strtolower(fileext($_FILES['file']['name'])) , $type)) {
				$text = implode(",", $type);
				$_GLOBALS['error']="请上传".$text."格式的文档,谢谢<br />";
				return false;
			}else{
				$name = time().$after;
				do {
					$name++;
					$uploadfile = str_replace("\\", "/", dirname(__FILE__)) . $uploaddir . $name . "." . fileext($_FILES['file']['name']);
				}while(file_exists($uploadfile));
				if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
					return $name.".".fileext($_FILES['file']['name']);
				}
			}
			return false;
		}
	}
?>