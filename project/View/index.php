<?php
	if(!isset($GLOBALS['indexMod']))
		die();
?>
<html>
	<head>
		<title>测试主页，默认页面的Mod为Main</title>
		<s:bootstrap />
		<s:ueditor />
		<meta charset="utf-8" />
	</head>
	<body>
		<form action="index.php?mod=Main" method="POST">
			<div style="width:100%">
				<script id="editor" name="text" type="text/plain" style="width:1024px;height:500px;"></script>
			</div>
			<input type="submit">
		</form>
		<s:checkcode />
		<script type="text/javascript">
			var ue = UE.getEditor('editor');
			ue.ready(function(){
				ue.setContent('<s:property value="text">');//这里可以通过Service修改$GLOBALS['text']修改其值
			});
		</script>
	</body>
</html>