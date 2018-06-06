<?php
//出租搜索结果页rentout_search.php的数据文件data_rentout_search.php
//require_once('connect.php');
error_reporting(E_ALL & ~E_NOTICE);
$mysqli = new mysqli('localhost', 'root', 'root', 'demo');

$last = $_POST['last'];
$amount = $_POST['amount'];
//echo "<script>alert('111');</script>";
$user = array('demo1','demo2','demo3','demo3','demo4');
$keyword=$_GET['keyword'];

$keyword_get=$_GET['keyword'];
$rent_type_get=$_GET['renttype'];//带下划线rent_type用get方式传递不过来
$district_get=$_GET['district'];
$subway_get=$_GET['subway'];
$room_type_get=$_GET['roomtype'];
$price_get=$_GET['price'];

$count=$_GET['count'];
$a0=$_GET['a0'];
$b0=$_GET['b0'];
$a1=$_GET['a1'];
$b1=$_GET['b1'];
$a2=$_GET['a2'];
$b2=$_GET['b2'];
$a3=$_GET['a3'];
$b3=$_GET['b3'];
$a4=$_GET['a4'];
$b4=$_GET['b4'];

if($rent_type_get == ""){//按关键字搜索，已禁止空关键字搜索
	//关键字是编号
	$c=substr($keyword, 0, 1); 
	$c=strtolower($c);
	$keyword_id = substr($keyword,1);
	//首字母得是c或C
	if ($c== "c" && is_numeric($keyword_id)) {//不是数字的话 如果搜 18年 截取后搜 8年 id为8的就符合了
		$rs_id=$mysqli->query("SELECT * FROM house_rent where id='$keyword_id'");
		if ($rs_id){//$rs为true才去取
			$num_id=mysqli_num_rows($rs_id);
		}
		if ($num_id > 0){
			//$sql="SELECT *,house_rent.id AS id_h, house_rent.contact AS contact_h FROM house_rent LEFT JOIN verify ON house_rent.contact=verify.contact where house_rent.id = '$keyword_id' order by verify.addtime desc, house_rent.addtime desc limit $last,$amount";
			$sql="select * from house_rent where id = '$keyword_id' and is_show = 1 order by id desc limit $last,$amount";
			$bianhao="<span style='color:red;'>C".$keyword_id."</span>";
		}
	}

	//关键字是微信号或手机号
	$keyword_contact = $keyword;
	$rs_contact=$mysqli->query("SELECT * FROM house_rent where contact='$keyword_contact' and is_show = 1");
	if ($rs_contact){//$rs为true才去取
		$num_contact=mysqli_num_rows($rs_contact);
	}
	if ($num_contact > 0){
		//$sql="select * from house_rent where contact = '$keyword_contact' order by id desc limit $last,$amount";
		$sql="select * from house_rent where contact = '$keyword_contact' and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
		//$bianhao="<span style='color:red;'>C".$keyword."</span>";
	}

	//关键字是content
	$keyword_content = $keyword;
	$rs_content=$mysqli->query("SELECT * FROM house_rent where (content like '%$keyword_content%' or title like '%$keyword_content%') and is_show = 1");
	if ($rs_content){//$rs为true才去取
		$num_content=mysqli_num_rows($rs_content);
	}
	if ($num_content > 0){
		//$row_content=$rs_content->fetch_assoc();
		//$content=$row_content['content'];//发布过的出租的id
		//$sql="select * from house_rent where content like '%$keyword_content%' order by id desc limit $last,$amount";
		//$sql="SELECT *,house_rent.id AS id_h, house_rent.contact AS contact_h FROM house_rent LEFT JOIN verify ON house_rent.contact=verify.contact where house_rent.content like '%$keyword_content%' order by verify.addtime desc, house_rent.addtime desc limit $last,$amount";
		$sql="select * from house_rent where (content like '%$keyword_content%' or title like '%$keyword_content%') and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
		//$bianhao="<span style='color:red;'>C".$keyword."</span>";
	}
	
	
	//关键字是普通关键字
}else{//按条件搜索,rentout_search.php已处理按条件搜索无结果情况
	//$sql="select * from house_rent WHERE rent_type = '$rent_type' order by (set_top = 1) desc, addtime desc limit $last,$amount";
	//$sql="select * from house_rent WHERE rent_type ".$rent_type_operator." '$rent_type' or district ".$district_operator." '$district' or subway ".$subway_operator." '$subway' or room_type ".$room_type_operator." '$room_type' or price ".$price_operator." '$price' order by id desc limit $last,$amount";
	//$conditions=array("rent_type"=>$rent_type,"district"=>$district,"subway"=>$subway,"room_type"=>$room_type,"price"=>$price);
	
	
	
  if($count==0){//5个条件都是不限
		$sql="select * from house_rent where is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
	}
	if($count==1){//1个条件不是不限
		$sql="select * from house_rent where $a0 = '$b0' and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
	}
	if($count==2){//2个条件不是不限
		$sql="select * from house_rent where $a0 = '$b0' and $a1 = '$b1' and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
		//echo $sql;
	}
	if($count==3){//3个条件不是不限
		$sql="select * from house_rent where $a0 = '$b0' and $a1 = '$b1' and $a2 = '$b2' and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
		//echo $sql;
	}
	if($count==4){//4个条件不是不限
		$sql="select * from house_rent where $a0 = '$b0' and $a1 = '$b1' and $a2 = '$b2' and $a3 = '$b3' and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
		//echo $sql;
	}
	if($count==5){//5个条件不是不限
		$sql="select * from house_rent where $a0 = '$b0' and $a1 = '$b1' and $a2 = '$b2' and $a3 = '$b3' and $a4= '$b4' and is_show = 1 order by (set_top = 1) desc, addtime desc limit $last,$amount";
		//echo $sql;
	}
	//$sql="select * from house_rent WHERE rent_type ".$rent_type_operator." '$rent_type' and district ".$district_operator." '$district' and subway ".$subway_operator." '$subway' and room_type ".$room_type_operator." '$room_type' and price ".$price_operator." '$price' order by id desc limit $last,$amount";
	//$sql="select * from house_rent where rent_type = '$rent_type' order by id desc limit $last,$amount";
	
}
//$sql="select * from house_rent where rent_type = '$keyword' order by id desc limit $last,$amount";
//$sql="select * from house_rent where rent_type = '$rent_type' order by id desc limit $last,$amount";
//$sql="select * from house_rent where rent_type = '$rent_type' order by id desc limit $last,$amount";
//$sql="select * from house_rent where id = '$keyword_id' order by id desc limit $last,$amount";
$result=$mysqli->query($sql);
while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
	$house_rent_id=$row['id'];
	
	//$contact=$row['contact'];
	
//	$sql2="select * from verify where contact = '$contact'";	
//	$result2=$mysqli->query($sql2);
//  $row2=$result2->fetch_assoc();
//	$star_sum=$row2['star_sum'];
	
	$rs_rent_want=$mysqli->query("SELECT * FROM `rent_want` WHERE house_rent_id = '$house_rent_id'");
	if ($rs_rent_want){//$rs为true才去取
		$numrows_rent_want=mysqli_num_rows($rs_rent_want);
	}
	if ($num_id == 0){//没有搜编号或者搜编号没有搜到
		$bianhao='C'.$row['id'];
	}

	$cover_img=$row['cover_img'];
	$district=$row['district'];
	if($cover_img == ""){
		 $cover_img="../../images/cover_img/".$district.".jpg";
	}

	$set_top=$row['set_top'];
	$content=$row['content'];
	$title=$row['title'];
	//$content26=mb_substr($content,0,26,'utf-8');
	//搜索的标题最多只能容纳17个字
	
	if($set_top== 1){//如果置顶
		$title=mb_substr($title,0,17,'utf-8');//标题限制17
		$content=mb_substr($content,0,74,'utf-8');//有标题，搜索的正文最多只能容纳74个字
	}else{//如果未置顶
		//$title=mb_substr($content,0,17,'utf-8');
		$title="";//未置顶 没有特写标题 则标题不显示
		$content=mb_substr($content,0,72,'utf-8');//标题为空，搜索的正文容纳100个字
	}

	//$content=$content."…";
//	$subway=$row['subway'];
//	if($subway == "暂无"){
//		 $subway="<span style='color:blue;'>地铁未填</span>";
//	}
	//$content=$content."更多";
	if($rent_type_get == ""){//关键字搜素
		//正文任何情况下 关键字都标红
		$content  = preg_replace("/($keyword_content)/","<font color='red'>\$1</font>",$content);
		if($set_top == 1){//置顶 标题含有的关键字标红
			$title  = preg_replace("/($keyword_content)/","<font color='red'>\$1</font>",$title);
		}else{//未置顶 标题为空
			$title="";
		}
	}
	
	if($set_top == 1){//置顶 标题加置顶标识符
		//$content="<i class='fa fa-arrow-up' aria-hidden='true' style='color:red;'></i><span>".$content."</span>";
		$title="<i class='fa fa-arrow-up' aria-hidden='true' style='color:red;'></i><span>".$title."</span>";	
	}else{//未置顶 标题为空
		  $title="";
	}
	//$time=time();
	$district=$row['district']."/".$row['room_type']."/".$row['rent_type']."/合租/合租";
	$time0=$row['addtime'];
	$time1=format_date($time0);
	$passtime=$time1;
	if($row['subway']=="暂无"){
		$subway="地铁暂无";
	}else{
		$subway=$row['subway'];
	}
	$sayList[] = array(
		'content-box'=>$row['id'],//house_rent_id
		'house_rent_id'=>$bianhao,
		'district'=>$district,
		//'district'=>$district,
		//'rent_type'=>$row['rent_type'],
		//'rent_type'=>$rent_type,
		//'room_type'=>$row['room_type'],
		//'room_type'=>$room_type,
		'price'=>$row['price'],
		//'price'=>$price,
		'want'=>$numrows_rent_want,
		'title'=>$title,
		'content'=>$content,
		//'room-pic'=>$row['cover_img'],
		//'star_sum'=>$row2['star_sum'],
		//'set_top'=>$row['set_top'],//防止别人用已验证手机号发帖，所以搜索和出租列表，都以set_top是否以1来区分是否验证
		'subway'=>$subway,
		//'yanzheng'=>$yanzheng,
		'pic-wrapper-float'=>$cover_img,
		//'author'=>$user[$row['user_id']],
		'passtime'=>$passtime,
		'keyword_get'=>$keyword_get,
		'rent_type_get'=>$rent_type_get,
		'district_get'=>$district_get,
		'subway_get'=>$subway_get,
		'room_type_get'=>$room_type_get,
		'price_get'=>$price_get,
		'date'=>date('m-d H:i',$row['addtime'])
      );
}
echo json_encode($sayList);

function format_date($time){
		if(!is_numeric($time)){
			$time=strtotime($time);
		}
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            //return '<span class="pink">'.$c.'</span>'.$v.'前';//&nbsp;
            return $c.$v.'前';//&nbsp;
        }
    }
}
?>