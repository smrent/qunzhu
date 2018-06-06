<?php
//发布出租php
echo '<pre>';
print_r($_POST);
print_r($_FILES);
echo '</pre>';
//$ip=getRealIp();//获取当前IP
//echo $ip;
exit;
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set(PRC);  
$mysqli = new mysqli('localhost', 'root', 'root', 'demo');

$mysqli->query("SET NAMES utf8");

$t_link=$_POST['t_link'];
$about=$_POST['about'];

$content=$about;
//$title=mb_substr($about,0,26,'utf-8');

$time=time(); //获取当前时间
$ip=getRealIp();//获取当前IP
//信息为空的post过来了
if ($about == ""){//$rs为true才去取
	echo "<script>alert('请输入想广而告之的内容( ^3^ )');window.location.href='./tools/publish.html';</script>";
	exit;
}
//ip被封禁
$rs=$mysqli->query("SELECT * FROM `forbid_ip` WHERE ip = '$ip'");
if ($rs){//$rs为true才去取
	$numrows_ip_forbid=mysqli_num_rows($rs);
	if($numrows_ip_forbid > 0){
		echo "<script>alert('IP被封禁，请联系公众号区块链帮助');window.history.go(-1);</script>";
		exit;
    }
}

//内容重复
$rs=$mysqli->query("SELECT * FROM `nnbw` WHERE content = '$content'");
if ($rs){//$rs为true才去取
	$numrows_content=mysqli_num_rows($rs);
	if($numrows_content > 0){
		echo "<script>alert('感谢留言重复');window.history.go(-1);</script>";
		exit;
    }
}


    $rs0=$mysqli->query("select max(ID) from `nnbw`");
	$row0=$rs0->fetch_assoc();
	$maxid=$row0['max(ID)'];
	$maxid++;
	
	$s = rand(1,11);// $s 为返回1到15之间的随机数
	//echo $s;
	$headimg="images/head_img/".$s.".jpg";

	
	//该IP没发布过出租
	$sql="INSERT INTO nnbw (id,ip,content,headimg,t_link,addtime) VALUES ($maxid,'$ip','$content','$headimg','$t_link','$time')";
	if($result = $mysqli->query($sql)){
	    echo "<script>alert('提交成功！');location.href = '../index.php'</script>";
	}else{
	    echo "<script>alert('提交失败！请重新提价！');window.history.go(-1);</script>";
	}

	
function getRealIp(){
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
//echo getRealIp();

exit;
?>