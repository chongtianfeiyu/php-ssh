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
			<form action="index.php?mod=Login" method="POST">
				<div class="row" >
					<div class="col-sm-3 .col-md-3"></div>
					<div class="col-sm-3 .col-md-3">用户名</div>
					<div class="col-sm-3 .col-md-3"><input type="text" name="username" /></div>
					<div class="col-sm-3 .col-md-3"></div>
				</div>
				<div class="row" >
					<div class="col-sm-3 .col-md-3"></div>
					<div class="col-sm-3 .col-md-3">密码</div>
					<div class="col-sm-3 .col-md-3"><input type="password" name="password" /></div>
					<div class="col-sm-3 .col-md-3"></div>
				</div>
				<div class="row" >
					<div class="col-sm-3 .col-md-3"></div>
					<div class="col-sm-3 .col-md-3">验证码</div>
					<div class="col-sm-2 .col-md-2"><input type="text" name="code" /></div>
					<div class="col-sm-1 .col-md-1"><s:checkcode /></div>
					<div class="col-sm-3 .col-md-3"></div>
				</div>
				<div class="row" >
					<div class="col-sm-3 .col-md-3"></div>
					<div class="col-sm-6 .col-md-6">
						<center><input type="submit" value="登录"></center>
					</div>
					<div class="col-sm-3 .col-md-3"></div>
				</div>
			</form>
		</div>
	</body>
</html>