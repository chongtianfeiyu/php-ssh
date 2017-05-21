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
    当sql为查询时，返回值可被用于$this->db->fetch_array()的参数，否则返回是否成功。
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
    }
  ```
  
  
