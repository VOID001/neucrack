<?php
// A program to get the students info from NEUMobile
// -- A Rubbish App
//
error_reporting(0);
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // This is the main function
    regist_stu();
}
else
{
    ?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('header.php'); ?>
</head>
<body>
<!--
<nav class="navbar navbar-default" style="padding:0px; margin-bottom:1px"> 
	<div class="container">
		<div class="navbar-header">
			<a href="/neucrack/" class="navbar-brand">NEU Crack</a> 
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="/">By VOID001</a></li>
			</ul>
		</div>
	</div>
</nav>
!-->
<?php require_once('nav.php'); ?>
<div class="jumbotron" style="padding-top: 0px; padding-bottom: 10px">
	<div class="container">
		<div class="row">
			<div class="col-md-2"></div>
<img src="shana.jpeg" style="width: 49px; height: 49px"/>
			<div class="col-md-8">
				<div class="well">
					<div class="page-header" style="margin-bottom:-2px; margin-top:0px">
<?php check_for_16(); ?>	
						<p style="margin-bottom: 0px">
替代掌上东大的Web端查询,省去下载App的麻烦= =
						</p>
					</div>
					<p style="margin-bottom: 0px; margin-top: 0px"> By <a href="https://neup-net-depart.github.io/Welcome-to-NEUP-Net-Depart/">先锋网 网络部 NEUP Net Depart</a><br/><a href="https://voidisprogramer.com/webdev/%E7%9C%8B%E7%9D%80%E4%B8%8D%E7%88%BD-%E6%96%B0%E7%94%9F%E4%B8%8D%E9%9C%80%E8%A6%81%E6%8E%8C%E4%B8%8A%E4%B8%9C%E5%A4%A7%E4%B9%9F%E5%8F%AF%E4%BB%A5%E6%9F%A5%E8%AF%A2%E5%88%B0%E5%AF%9D%E5%AE%A4.html">使用说明</a></p>
				</div>
<?php if(isset($_SESSION	['error'])){?>
					<h4>
						<div id="errors" class="label label-danger">
    							<?php echo $_SESSION['error']; ?>
    							<?php unset($_SESSION['error']); ?>
						</div>
					</h4>
<?php }?>
<?php if(isset($_SESSION	['login_user'])) {?>
					<h4>
						<div id="loginOK" class="alert alert-success">
    						<?php echo "欢迎".$_SESSION['login_user']."您已登陆"; ?>
    					<span><a href="logout.php"><button class="btn btn-danger">注销</button></a><span>
						</div>
					</h4>
    <?php } ?>
<?php if(!isset($_SESSION['login_user'])) { ?>
<form action="index.php" method="POST">
<table>
<tr>
<td>
    输入学号:
</td>
<td>
<input name="stuid" class="form-control" type="text"/>
</td>
    <br/>
</tr>
<tr>
<td>
    输入密码(初始为身份证号):
</td>
<td>
<input name="pass" class="form-control" type="password"/>
</td>
</tr>
<tr>
<td>
<input type="reset" class="btn btn-danger" value="清空"/>
</td>
<td>
<input type="submit" class="btn btn-primary" value="登陆"/>
</td>
</tr>
</table>
</form>

<?php }else {?>

<div class="text-center"><a href="panel.php?type=classmate"><button class="btn btn-primary btn-lg">同学信息</button></a></div>
<div class="text-center"><a href="panel.php?type=roommate"><button class="btn btn-info btn-lg">室友信息</button></a></div>
<div class="text-center"><a href="panel.php?type=info"><button class="btn btn-success btn-lg">个人信息</button></a></div>

<?php }?>

<?php
}

function regist_stu()
{
    $username = $_POST['stuid'];
    $password = $_POST['pass'];
    if($username == "" || $password == "")
    {
        $_SESSION['error'] = "用户名和密码均不能为空!";
        header("Location:index.php");
    }
    else
    {
        // Do the curl action
        $handle = curl_init();
        $header = [];
        $header["User-Agent"] = "Mozilla/5.0 (Linux; Android 5.1.1; KIW-AL10 Build/HONORKIW-AL10; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Mobile Safari/537.36";
        $header["X-Requested-With"] = "com.tzsoft.neu";
        $params = "?user.loginType=3&user.username=".$username."&user.password=".$password."&callbackparam=";
        curl_setopt($handle, CURLOPT_URL, "http://219.216.96.105:8080/neumobile//doLogin4mobile".$params);
        curl_setopt($handle, CURLOPT_HTTPGET, true);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($handle);
        curl_close($handle);
        $cookie = get_cookie($data);
        if($cookie)
        {
            $_SESSION['login_user'] = $username;
            $_SESSION['jessid_cookie'] = $cookie;
            header("Location:index.php");
        }
        else
        {
            $_SESSION['error'] = "用户名或者密码错误";
            header("Location:index.php");
        }
    }
}

function get_cookie($data)
{
    $pos = strpos($data, "Set-Cookie");
    if($pos != false)
    {
        $cookie = substr($data, $pos + strlen("Set-Cookie") + 2, 44);
        return $cookie;
    }
    return false;
}

function check_for_16()
{
    // here is a username & password from 2016 grade student
    $username = "********";
    $password = "******************";
    $handle = curl_init();
    $header = [];
    $header["User-Agent"] = "Mozilla/5.0 (Linux; Android 5.1.1; KIW-AL10 Build/HONORKIW-AL10; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Mobile Safari/537.36";
    $header["X-Requested-With"] = "com.tzsoft.neu";
    $params = "?user.loginType=3&user.username=".$username."&user.password=".$password."&callbackparam=";
    curl_setopt($handle, CURLOPT_URL, "http://219.216.96.105:8080/neumobile//doLogin4mobile".$params);
    curl_setopt($handle, CURLOPT_HTTPGET, true);
    curl_setopt($handle, CURLOPT_HEADER, true);
    curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($handle);
    curl_close($handle);
    $cookie = get_cookie($data);
    if($cookie)
    {
        echo "<div class='alert alert-success'>";
        echo "16级状态:可用";
        echo "</div>";
    }
    else
    {
        echo "<div class='alert alert-danger'>";
        //echo "因为掌上东大数据库还没有添加16级的信息,所以暂时16级不可用哦,当16级可用的时候这里会第一时间更新的";
        echo "16级状态:不可用";
        echo "</div>";
    }
}
?>
</div>
<div class="col-md-2"></div>
</div>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>
