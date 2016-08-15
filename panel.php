<?php
error_reporting(0);
session_start();

$display = [];

$URL = [];
$URL['classmate'] = "http://219.216.96.105:8080/neumobile//student/getClassMate?callbackparam=";
$URL['roommate'] = "http://219.216.96.105:8080/neumobile//student/getRoomMate?callbackparam=";
$URL['info'] = "http://219.216.96.105:8080/neumobile//student/getUserInfoFromMobile?callbackparam=";
$H1['classmate'] = "查同学";
$H1['roommate'] = "查室友";
$H1['info'] = "个人信息";

if(!isset($_SESSION['login_user']))
{
    $_SESSION['error'] = "你需要登陆才能查看信息";
    header("Location:index.php");
}

if(!isset($_GET['type']) || ($_GET['type'] != "classmate" && $_GET['type'] != "roommate" && $_GET['type'] != "info") )
{
    $_SESSION['error'] = "参数错误";
    header("Location:index.php");
}

$type = $_GET['type'];


$handle = curl_init($URL[$type]);
curl_setopt($handle, CURLOPT_COOKIE, $_SESSION['jessid_cookie']);
curl_setopt($handle, CURLOPT_HTTPGET, true);
curl_setopt($handle, CURLOPT_TIMEOUT, 5);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($handle);

//Check the validity of the information
//

if($data[0] == '(' && $data[strlen($data) - 2] == ')')
{?>
<!DOCTYPE html>
<html>
<head>
<?php require_once('header.php'); ?>
</head>
<body>
<?php require_once('nav.php') ?>
<div class="container">
<?php
    echo "<div class='page-header'><h1>".$H1[$type]."</h1><span><a href='index.php'>返回</a></div>";
    $json_data = substr($data, 1, strlen($data) - 3);
    $json_data = json_decode($json_data);
    //var_dump($json_data);
    display_data($type, $json_data);
}
else
{
    $_SESSION['error'] = "信息不存在或者链接超时(5s),可以尝试重试一下";
    header("Location:index.php");
}

function display_data($type, $json_data)
{
    if($type == 'classmate')
    {?>
<table class="table table-striped">
<thead>
    <th>学号</th>
    <th>姓名</th>
    <th>电话</th>
</thead>
<tbody>
<?php
        $json_data = $json_data->RES;
        for($i = 0; $i < sizeof($json_data); $i++)
        {
            $classmate = $json_data[$i];
            ?>
<tr>
<td><?php echo $classmate->STUID ;?></td>
<td><?php echo $classmate->STUNAME; ?></td>
<td><?php echo $classmate->TEL; ?></td>
</tr>
<?php
        }?>
</tbody>
</table>
<?php
    }
    elseif($type == 'roommate')
    {
        $json_data = $json_data->RES;
        ?>
<table class="table table-striped">
<thead>
    <th>学号</th>
    <th>姓名</th>
    <th>电话</th>
    <th>床号</th>
</thead>

<?php
        for($i = 0; $i < sizeof($json_data); $i++)
        {
            $roommate = $json_data[$i];
        ?>
<tr>
<td><?php echo $roommate->STUID ;?></td>
<td><?php echo $roommate->STUNAME; ?></td>
<td><?php echo $roommate->TEL; ?></td>
<td><?php echo $roommate->BED; ?></td>
</tr>
<?php
        }?>
</table>
<?php
    }
    else
    {
        $json_data = $json_data->USERINFO;
        ?>
<div class="jumbotron">
<h2>姓名: <?php echo $json_data->name; ?></h2>
<h2>宿舍: <?php echo $json_data->buildingName; ?> <?php echo $json_data->room; ?>, <?php echo $json_data->bed; ?></h2>
<h2>学院: <?php echo $json_data->collegeName; ?></h2>
<h2>专业: <?php echo $json_data->majorName; ?></h2>
<h2>校区: <?php echo $json_data->schoolareaName; ?></h2>
</div>
<?php
    }
}?>
<?php require_once('footer.php'); ?>
</div>
</body>
</html>
