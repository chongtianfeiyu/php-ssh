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
		<div class="container">
			<div class="row" >
				<div class="col-xs-6 col-sm-3 .col-md-3">
					<form action="index.php?mod=Login" method="POST">
						<input type="text" name="username" />
						<input type="password" name="password" />
						<input type="submit" value="登录">
					</form>
				</div>
		    </div>
		</div>
	</body>
</html>