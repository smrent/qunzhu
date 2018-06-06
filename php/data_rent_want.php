<?php
//require_once('connect.php');
error_reporting(E_ALL & ~E_NOTICE);
$mysqli = new mysqli('localhost', 'root', '674jdEddCF', 'demo');
$last = $_POST['last'];
$amount = $_POST['amount'];
//echo "<script>alert('111');</script>";
$user = array('demo1','demo2','demo3','demo3','demo4');
$house_rent_id=$_GET['house_rent_id'];
//$rs=mysql_query("SELECT count(*) FROM `rent_want` WHERE house_rent_id = '$house_rent_id'");
//if ($rs){//$rs为true才去取
//	$myrow = mysql_fetch_array($rs);
//	$numrows=$myrow[0];
//}


//$rs0=mysql_query("select max(ID) from pic_sheji");
//$maxid = mysql_fetch_array($rs0);
//我也想租 想租列表 是需要在rent_want表里预先知道 添加的联系方式是否做过验证的 否则无法验证优先排序
//我也想租 想租列表 是需要在rent_want表里预先知道 添加的联系方式如果做过验证 学业信息 性别信息的
//上述两点 都要在 我也想租做操作AddRentWant.php里处理 以上两点是因为想租列表跟出租人联系方式不一样
//验证用户联系方式，要跟新想租列表和关注列表rent_want和seek_follow
//$sql="select * from rent_want where house_rent_id = '$house_rent_id' order by (star_sum > 0) desc, addtime desc limit $last,$amount";
//rent_want和verify连表查询，这样，所有信息都从verify里取
$sql="SELECT *, rent_want.w_or_m AS w_or_m_r, rent_want.contact AS contact_r FROM rent_want LEFT JOIN verify ON rent_want.contact=verify.contact where house_rent_id = '$house_rent_id' order by verify.addtime desc, rent_want.addtime desc limit $last,$amount";
$result=$mysqli->query($sql);
while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
	$w_or_m=$row['w_or_m_r'];
	$contact=$row['contact_r'];
	//$star_sum=$row['star_sum'];

	$sql2="select * from verify where contact = '$contact'";	
	$result2=$mysqli->query($sql2);
	$row2=$result2->fetch_assoc();
	$xueye=$row2['xueye'];
	$gender=$row2['gender'];
	if($xueye == ""){//大学优先级高
 		$xueye="<span style='color:red'>未填写</span>";
  }
  if($gender == "男" ){
		$his_rent="他的出租：";
		$his_seek="他的求租：";
	}else if($gender == "女" ){
		$his_rent="她的出租：";
		$his_seek="她的求租：";
	}else{
		$his_rent="Ta的出租：";
		$his_seek="Ta的求租：";
	}
  $star_sum=$row2['star_sum'];
  if($star_sum == ""){//star_sum在联合查询的时候可能是null
 		$star_sum=0;
  }
	if($star_sum >0 ){
		$yanzheng_rent_want="<a style='color:blue;'>已验证</a>";
		$pic_rent_want="../images/yz_rent_want.jpg";
	}else{
		$yanzheng_rent_want="<a style='color:red;'>未验证</a>";
		$pic_rent_want="../images/wyz_rent_want.jpg";
	}
	

	$contact_show="<small style='color:#999;'>".$w_or_m."码：</small><small class='smID'>$contact</small>";

	//$star_sum=$row['star_sum'];
	//$house_rent_id=$row['house_rent_id'];
	$date=date('m-d H:i',$row['addtime']);
	
	

	//Get user rent house numbers from table house_rent
	//只确认联系方式 不需要确认手机号还是微信号 我只认号 有可能用户误操作填错 但也算验证过
	$rs=$mysqli->query("SELECT * FROM `house_rent` WHERE contact = '$contact'");
	if ($rs){//$rs为true才去取
		$numrows_user_rent=mysqli_num_rows($rs);
	}
	//Get user seek house numbers from table house_seek
	$rs2=$mysqli->query("SELECT * FROM `house_seek` WHERE contact = '$contact'");
	if ($rs2){//$rs为true才去取
		$numrows_user_seek=mysqli_num_rows($rs2);
	}
	
	
//	//先去house_seek里找，用户发布求租时是否填写了学业信息
//	if($user_wechat != ""){
//		$sql2="select * from house_seek where user_wechat = '$user_wechat' order by addtime desc";	
//	}else if($user_mobile != ""){
//		$sql2="select * from house_seek where user_mobile = '$user_mobile' order by addtime desc";	
//	}
//	$result2=$mysqli->query($sql2);
//	while ($row2=$result2->fetch_array(MYSQLI_ASSOC)) {
//		 $university2=$row2['university'];
//		 $industry2=$row2['industry'];
//		 if($university2 != ""){//大学优先级高
//		 		$xueye=$university2;
//		 }else if($industry2 != ""){
//		 		$xueye=$industry2;
//		 }else{
//		 	  $xueye="未填写";
//		 }
//	}
//	
//	//如果house_seek里，用户发布求租时未填写学业信息，去验证里找,当然验证的优先级更高了，所以覆盖原来
//  if($user_wechat != ""){
//		$sql3="select * from verify where user_wechat = '$user_wechat' order by addtime desc";	
//	}else if($user_mobile != ""){
//		$sql3="select * from verify where user_mobile = '$user_mobile' order by addtime desc";	
//	}
//	$result3=$mysqli->query($sql3);
//	while ($row3=$result3->fetch_array(MYSQLI_ASSOC)) {
//		 $university3=$row3['university'];
//		 $industry3=$row3['industry'];
//		 if($university3 != ""){//大学优先级高
//		 		$xueye=$university3;
//		 }else if($industry != ""){
//		 		$xueye=$industry3;
//		 }else{
//		 	  $xueye="未填写";
//		 } 
//	}

	$sayList[] = array(
	  'w_or_m'=>$row['w_or_m_r'],
	  'contact'=>$row['contact_r'],
		'contact_show'=>$contact_show,
		//'university'=>$university,
	  'user_rent_num'=>$numrows_user_rent,
	  'user_seek_num'=>$numrows_user_seek,
	  //'star_sum2'=>$star_sum,
	  'star_sum'=>$star_sum,
	  'gender'=>$gender,
	  'his_rent'=>$his_rent,
	  'his_seek'=>$his_seek,
	  'yanzheng_rent_want'=>$yanzheng_rent_want,
	  'pic_rent_want'=>$pic_rent_want,
	  'house_rent_id'=>$house_rent_id,
	  'xueye'=>$xueye,
	  'date'=>$house_rent_id
      );
}
echo json_encode($sayList);
?>