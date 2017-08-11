<?php
	/*		作者：		similing4
	*		创作时间：	2017.5.1
	*		功能：		仿ssh框架的struts2.xml
	*		日志：		2017.5.1		添加doAction与doService
	*					2017.5.2		添加了非法参数拦截正则
	*					2017.5.3		添加了$GLOBALS['error']弹窗功能
	*					2017.5.19		添加了showView功能
	*					2017.5.20		所有require改用import
	*/
	class Controller{
		public static function init(){
			if(!isset($_SESSION))
				session_start();
			import("Dao.DB");
			import("Filter");
		}
		public static function doAction($ActionName,$method="doAction",$param=array()){
			//传入类名执行，务必不能直接或间接通过用户请求控制本参数
			if(!preg_match('/^[_0-9a-zA-Z\.]*$/i',$ActionName)||!preg_match('/^[_0-9a-zA-Z\.]*$/i',$method))
				die("error");
			if(!file_exists(dirname(__FILE__)."/Action/".$ActionName.".php"))
				die("error");
			if(!class_exists($ActionName,false))
				import("Action.".$ActionName);
			$result = call_user_func($ActionName."::".$method,$param);
			if($result!=1){
				if(!isset($GLOBALS['error']))
					header("Location:index.php?mod=".$result);
				else{
					Controller::alertErr();
					echo "<script>location.href='index.php?mod=".$result."';</script>";
				}
				die();
			}else{
				if(isset($GLOBALS['error']))
					Controller::alertErr();
			}
		}
		public static function doService($ServiceName,$method="doService",$param=array()){
			//传入类名执行，务必不能直接或间接通过用户请求控制本参数
			if(!preg_match('/^[_0-9a-zA-Z\.]*$/i',$ServiceName)||!preg_match('/^[_0-9a-zA-Z\.]*$/i',$method))
				die("error");
			if(!file_exists(dirname(__FILE__)."/Service/".$ServiceName.".php"))
				die("error");
			if(!class_exists($ServiceName,false))
				import("Service.".$ServiceName);
			$result = call_user_func($ServiceName."::".$method,$param);
			return $result;
		}
		public static function showView($viewname){
			//传入View名执行，务必不能直接或间接通过用户请求控制本参数
			header("Content-type: text/html; charset=utf-8");
			if(!file_exists(dirname(__FILE__)."/View/".str_replace(".", "/", $viewname).".php"))
				die("error");
			import("View.".$viewname);
		}
		public static function alertErr(){
			header("Content-type: text/html; charset=utf-8");
			if(isset($GLOBALS['error']))
				echo "<script>alert('".$GLOBALS['error']."');</script>";
		}
	}/*
	if(isset($_GET['action'])&&isset($_GET['method'])){
		require("kernel/Use.php");
		Controller::init();
		Controller::doAction($_GET['action'],$_GET['method']);
	}
	/*
		本页调用方法：
		import("Controller");
		Controller::init();
		Controller::doAction("Action的名字","Action的方法");
		Action中所有参数全部使用$GLOBALS传递
		import调用方法只能用于Mod中。
	*/

	/*
		Action中需要将处理转到Service中处理。
		提交请求到本页面需要将请求提交到Controller.php?action=ActionName&method=MethodName
	*/

?>
