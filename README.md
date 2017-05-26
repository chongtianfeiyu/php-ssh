# 简介
本框架主要是模仿jsp的ssh的php框架
# 版权
辽宁工程技术大学新起点工作室版权所有，项目地址：https://github.com/similing4/php-ssh 转载请注明版权与出处！
# 框架流程
本框架入口文件为index.php，调用过程如图：
![image](https://github.com/similing4/php-ssh/blob/master/php-ssh_lct.png)
# 使用方法
## 调用mod：
index.php?mod=Mod名称<br>
Mod文件格式：放在Mod文件夹下，文件名要求大写并能表名Mod功能<br>
```php
<?php
  /*	作者：		  XXX
  *		创作时间：	XXXX.XX.XX
  *		Mod名：	  名称
  */
  if(!isset($indexMod))//防止其他文件调用。
    die();
  Controller::doAction("Action名","Action方法名",要传给Action的唯一参数);
  ...多个Action为一个Mod提供服务
  Controller::showView("加载View中指定名称的view");//如果Controller指定了就不用
?>
```
## 调用Action
Controller::doAction("Action类名","Action方法名",封装后的参数);<br>
Controller.php?action=Action类名&method=Action方法名<br>
注：调用Action限定只在mod或URL中调用，您可以将URL调用关闭，关闭方法：将Controller.php中<br>
```php
  if(isset($_GET['action'])&&isset($_GET['method'])){
    Controller::init();
    Controller::doAction($_GET['action'],$_GET['method']);
  }
```
段注释掉<br>
Action文件格式：Action是一个静态类，类名要求规则与Mod相同。不过Action要求放在Action文件夹下且Action名与类名要求一致：<br>
```php
  <?php
    //Action/Action名.php
    class Action名{
      public static function 方法名($param){
        if(Filter::拦截器方法名(拦截器参数))//调用Filter.php内的方法判断是否执行Service
          Controller::doService("Service名","Service方法名",要传给Service的唯一参数);
        ...多个Service为一个Action提供服务
        return 1;//返回1表示根据Mod决定前台文件，返回其它值则根据Controller的$action属性决定跳转到的Mod。
      }
    }
  ?>
```
## 调用Service
Controller::doService("Service类名","Service方法名",封装后的参数);<br>
注：调用Service限定只在Action中调用<br>
Service文件格式：Service同Action一样也是一个静态类，类名要求规则与Action相同。不过Service要求放在Service文件夹下：<br>
```php
  <?php
    //Service/Service名.php
    class Service名{
      public static function 方法名($param){
        /*
          可对$GLOBALS变量操作，最后前台可以通过s标签获取$GLOBALS内的内容
          可引入Dao文件夹下的自定义的任何DaoObj类，类命名方式与Action一致，不过这里的类可以是动态类或静态类。
          调用方法：import("Dao.类名");，详见DB类解释
          可以在本类内任意方法内对业务逻辑进行处理。
        */
        return 1;//返回内容给Action使用
      }
    }
  ?>
```
## DB类
  本类主要用于调用数据库。<br>
  在Dao文件夹中创建任意DaoObj类，命名要求与Action相同（同Java，类名需与文件名一致），方便在Service中import("Dao.".类名);调用。<br>
  调用方法：
```php
	import("Dao.类名");
	$变量=new 类名();
	$变量返回=$变量->方法名();
	$变量->close();
```
  DaoObj定义类结构如下：
```php
	<?php
		class 类名{
			private $db;
			function __construct(){
				  $this->db=new DB();
			}
			function close(){
				  $this->db->close();
			}
			function 方法名(参数列表){//正常定义普通方法
				  $返回值=$this->db->DB类的方法(参数);
				  //各种处理
				  return $返回值;
			}
		}
	?>
```
### DB类使用方法如下
#### DB类设置
  属性：<br>
    $host="localhost";<br>
	$name="数据库用户";<br>
	$pass="数据库密码";<br>
	$table="数据库名";<br>
	$ut='utf8';
#### query($sql);
  参数：<br>
    $sql 要执行的语句<br>
  返回值：<br>
    当sql为查询时，返回值可被用于$this->db->fetch_array()的参数，否则返回是否成功。<br>
  提示：<br>
    除该方法需对参数进行防注入处理外其余增删改查方法已对单引号替换，不需要防注入。
#### fetch_array($query);
  参数：<br>
    $query $this->db->query($sql)的返回值<br>
  返回值：<br>
    如果查询结果为空或全部查完了返回false，否则返回查询的一行数据。
  使用方法：
```php
	$sql="select * from users";
	$query=$this->db->query($sql);
	while($row=$this->db->fetch_array($query)){
	//...这里写处理
	}
```
#### select_arr($name,$req = array(),$like=false);
  参数：<br>
    $name 表名<br>
    $req 条件数组<br>
    $like 条件是否用like<br>
  返回值：<br>
    返回查询结果数组。<br>
  使用方法：<br>
```php
	$result=$this->db->select_arr("users");
	foreach($result as $row){
		//这里写处理，$row为每一行的数据，如$row['username']为改行username列的值。
		//注意，$row中除了有列名为主键的之外还有数组下标为主键的值，因此需要处理后才可以json_encode
	}
```
#### select_first($name,$req);
  参数：<br>
    $name 表名<br>
    $req 条件数组<br>
  返回值：<br>
    返回查询结果的第一条，没有则返回false。<br>
  使用方法：<br>
```php
	$result=$this->db->select_first("users",array(
		"uid"=>1
	));//查询uid为1的记录
	if($result)
		;//有查询结果的情况
```
#### update($table,$list,$tiao);
  参数：<br>
    $table 表名<br>
    $list 要更改的值<br>
    $tiao 条件数组<br>
  返回值：<br>
  	query结果<br>
  使用方法：<br>
```php
	$this->db->update("users",array(
		"password"=>md5("admin")
	),array(
		"uid"=>1,
		"username"=>"admin"
	));//更新uid为1且username为admin的用户的password列为md5("admin");
```
#### fn_del($table,$c);
  参数：<br>
    $table 表名<br>
    $c 条件数组<br>
  返回值：<br>
    query结果<br>
  使用方法：<br>
```php
	$this->db->fn_del("users",array(
		"uid"=>1
	));//删除uid为1的用户
```
#### fn_insert($table,$name,$arr);
  参数：<br>
    $table 表名<br>
    $name 插入数据列名（可以根据该参数进行排序，如果没有可以填写""，需要则按数组顺序填写元素，如array("uid","username")）<br>
    $arr 插入行的数据<br>
  返回值：<br>
    query结果<br>
  使用方法：<br>
```php
	$this->db->fn_insert("users","",array(
		"null",
		"admin",
		"adminp"
	));//插入一个第一列自增的第二列为admin，第三列为adminp的行，数据库语句内的null在数组里用字符串null表示。
```
#### affected_rows();
  返回值：影响行数
#### insert_id();
  返回值：AUTO_INCREASE列插入的id
# View中的S标签
## 简介：
View内放置的是前台的网页内容，所有资源应放置在res文件夹下供view调用。view输出在index.php入口文件下，因此相对路径就是入口文件所在位置。
View中可以嵌入<?php?>，但不建议如此。为了解决前后端分离的问题我们加入了s标签。但在实际使用时依然需要在View前加入如下代码：
```php
	<?php
		if(!isset($indexMod))
			die();
	?>
```
主要是为了防止非法访问问题。
## S标签简介
我们提供了以下标签内容：<br>
```html
	<s:iterator value="arr">
	</s:iterator>
	<s:property type="item" value="username"/>
	<s:property value="username"/>
	<s:if test="f">
	<s:else />
	</s:if>
```
另外您可以自定义s标签，详见plugins下的S类。<br>
解析类详见kernel下的ParseS类。
### 功能介绍
#### s:iterator 标签
属性：<br>
value 对应全局变量$GLOBALS内的指定值，要求获取的值是php的Array类型以用于遍历。<br>
范例：value="user.uid" 对应 $GLOBALS\['user']\['uid'];<br>
您也可获取session中的内容，获取方法：value="session.username"<br>

#### s:property 标签
属性：<br>
value 同s:iterator的value，不过要求获取的值是php的字符串或数字等可直接输出的类型。<br>
范例：value="user.uid" 对应 $GLOBALS\['user']\['uid'];
type 指定为item时，必须与s:iterator搭配，表示该iterator中的循环元素。
范例：
```html
	<s:iterator value="users">
		a
		<s:property type="item" value="user.uid" />
		b
		<s:property type="item" value="user.username" />
		c
	</s:iterator>
```
对应php语句为
```php
	foreach($GLOBALS['users'] as $item){
		echo "a";
		echo $item['user']['uid'];
		echo "b";
		echo $item['user']['username'];
		echo "c";
	}
```

如果需要对属性进行遍历则需要如下写法：
```html
	<s:iterator value="pro">
		<s:iterator value="#sheng">
			<s:property value="#sheng" />=><s:property value="" />
		</s:iterator>
	</s:iterator>
```
对应php语句为
```php
	foreach($GLOBALS['pro'] as $sheng=>$shi){
		echo $sheng;
		echo "=>";
		echo $shi;//当property的value为空时其值为为当前遍历key=>value里的value。
	}
```
#### s:if 标签
属性：<br>
test 调用Test.php中Test类的静态方法，其它属性值作为参数传入（不能在属性中嵌入s标签），当前运行状态可以通过kernel.HookParse类获取<br>
范例：
```html
	<s:if test="test" v="a">
		<s:property value="user.username" />
	<s:else />
		<s:property value="user.password" />
	</s:if>
```
等价于：
```php
	if(Test::test(array("test"=>"test","v"=>"a")))
		echo $GLOBALS['user']['username'];
	else
		echo $GLOBALS['user']['password'];
```
#### s:css 标签
属性：<br>
src css文件的名字
范例：
```html
	<s:css src="m.index" />
```
等价于
```html
	<link href="assests/css/m/index.css"  rel="stylesheet" type="text/css" />
```
#### s:js 标签
属性：<br>
src js文件的名字
范例：
```html
	<s:js src="m.index" />
```
等价于
```html
	<script src="assests/m/index.js"  type="text/javascript" />
```
#### s:img 标签
属性：<br>
src img文件的带后缀名字(可以加其他参数)
范例：
```html
	<s:img src="m.logo.png" width="100%"/>
```
等价于
```html
	<img src="assests/images/m/logo.png" width="100%" />
```
## 自定义标签
### S类
#### 简介
S类为自定义s标签插件类。当遇到不认识的s标签时，parse解释器会调用S插件内的以参数命名的方法执行其功能。每要输出一条数据时，index都会调用onText或onParamText两个参数中的一个。如果是普通文本则调用onText否则是s标签生成的就调用另一个。
![image](https://github.com/similing4/php-ssh/blob/master/parse.png)
#### S类方法命名
```html
<s:g a="b">XXX</s:g>
```
这段代码用S类拦截需要定义两个标签：
```php
public static function g($param){//<s:g>时执行的内容，$param是属性键值对的array
	$a=$param["a"];
	//...
	return "输出内容";
}
public static function _g(){//</s:g>时执行的内容
	//...
	return "输出内容";
}
```
如果需要对内部的内容进行控制，则需要在onText内对文本进行处理。您可以通过设置静态变量标志判断该text是不是标签内的内容。如果需要对内容内的标签进行处理，请使用kernel.HookParse类
