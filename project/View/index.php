<?php
	if(!isset($GLOBALS['indexMod']))
		die();
?>
<html>
	<head>
		<title>UEditorDemo</title>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
		<script type="text/javascript" charset="utf-8" src="Tools/Editor/ueditor.config.js"></script>
		<script type="text/javascript" charset="utf-8" src="Tools/Editor/ueditor.all.min.js"> </script>
		<script type="text/javascript" charset="utf-8" src="Tools/Editor/lang/zh-cn/zh-cn.js"></script>
	</head>
	<body>
		<form action="Controller.php?action=TestAction&method=testUE" method="POST">
			<div style="width:100%">
				<script id="editor" name="text" type="text/plain" style="width:1024px;height:500px;"></script>
			</div>
			<input type="submit">
		</form>
		<script type="text/javascript">
			var ue = UE.getEditor('editor');
			ue.ready(function(){
				ue.setContent('<s:property value="text">');
			});
		</script>
	</body>
</html>