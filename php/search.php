<?php
	//出租搜索php
	error_reporting(E_ALL & ~E_NOTICE);
//	echo '<pre>';
//	print_r($_GET);
//	print_r($_POST);
//	print_r($_FILES);
//	echo '</pre>';
//	exit;
//	if(isset($_POST['keyword'])){//关键字搜索
//		//关键字为空
//		if($_POST['keyword'] == ""){
//			echo "<script>alert('请输入关键字');window.history.go(-1);</script>";
//			exit;
//  	}
//  }
	$keyword=$_GET['keyword'];
  
	$rent_type=$_GET['rent_type'];
	$district=$_GET['district'];
	if($district=="全国"){
	  $district="不限";
	}
	$room_type=$_GET['room_type'];
	$mysqli = new mysqli('localhost', 'root', 'root', 'demo');
	$mysqli->query("SET NAMES utf8");
	
	//$conditions=array($rent_type,$district,$subway,$room_type,$price);
	$conditions=array("district"=>$district,"room_type"=>$room_type,"rent_type"=>$rent_type);
	//$arr = array("1"=>"111","2"=>"222","3"=>"333");
	$count = 0;
	$ar = array();
	$br = array();
	foreach($conditions as $key=>$value)
	{
	  //echo $key."=>".$value."<br>";
	  //echo "********<br>";
	  if($value!="不限"){
		  //echo $key."<br>";
		  $count++;
		  $ar[] =$key;
		  $br[] =$value;
	  }
	}
  //echo $count;
  //echo "<br>";
  //print_r($br);
	if($count==0){//四个条件都是不限
		$sql="select * from house_rent where is_show = 1 and verify =1 order by id desc";
	}
	if($count==1){
		$sql="select * from house_rent where $ar[0] = '$br[0]' and is_show = 1 and verify =1 order by id desc";
		//echo $sql;
	}
	if($count==2){
		$sql="select * from house_rent where $ar[0] = '$br[0]' and $ar[1] = '$br[1]' and is_show = 1 and verify =1 order by id desc";
		//echo $sql;
	}
	if($count==3){
		$sql="select * from house_rent where $ar[0] = '$br[0]' and $ar[1] = '$br[1]' and $ar[2] = '$br[2]' and is_show = 1 and verify =1 order by id desc";
		//echo $sql;
	}
	// if($count==4){
		// $sql="select * from house_rent where $ar[0] = '$br[0]' and $ar[1] = '$br[1]' and $ar[2] = '$br[2]' and $ar[3] = '$br[3]' and is_show = 1 and verify =1 order by id desc";
		//echo $sql;
	// }
	// if($count==5){
		// $sql="select * from house_rent where $ar[0] = '$br[0]' and $ar[1] = '$br[1]' and $ar[2] = '$br[2]' and $ar[3] = '$br[3]' and $ar[4] = '$br[4]' and is_show = 1 and verify =1 order by id desc";
		//echo $sql;
	// }
	
	$rs_conditions=$mysqli->query($sql);
	$num_conditions=mysqli_num_rows($rs_conditions);
	$result=$mysqli->query($sql);
	//echo $num_conditions;

	//关键字搜索
    $keyword_content = $keyword;
	$rs_content=$mysqli->query("SELECT * FROM house_rent where (content like '%$keyword_content%' or title like '%$keyword_content%') and is_show = 1 and verify =1");
	$num_content=mysqli_num_rows($rs_content);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>水木租房子-出租搜索结果</title>
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link href="../css/main.css" rel="stylesheet">
		<link rel="stylesheet" href="../css/rent_info_main.css"><!--use rent_info css-->
		<link rel="stylesheet" href="../css/rentout_search.css">
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.more.rentout_search.js"></script>
		<script type="text/javascript" src="../js/jquery.cookie.js"></script>
		<script type="text/javascript">
		$(function(){
			var keyword=$(" input[ name='keyword_post' ] ").val();
			var rent_type=$(" input[ name='rent_type_post' ] ").val();
			var district=$(" input[ name='district_post' ] ").val();
			var room_type=$(" input[ name='room_type_post' ] ").val();
			var num_content=$(" input[ name='num_content' ] ").val();
			var num_conditions=$(" input[ name='num_conditions' ] ").val();
			
			var count=$(" input[ name='count' ] ").val();
			var a0=$(" input[ name='a0' ] ").val();
			var b0=$(" input[ name='b0' ] ").val();
			var a1=$(" input[ name='a1' ] ").val();
			var b1=$(" input[ name='b1' ] ").val();
			var a2=$(" input[ name='a2' ] ").val();
			var b2=$(" input[ name='b2' ] ").val();
			var a3=$(" input[ name='a3' ] ").val();
			var b3=$(" input[ name='b3' ] ").val();
			// var a4=$(" input[ name='a4' ] ").val();
			// var b4=$(" input[ name='b4' ] ").val();
			
			if(keyword!=""){//按关键字搜索
				//alert("rent_type为空");
				if(num_content == 0){//关键字搜索，没有搜到结果 && rent_type ==""
					var innerHTML="<a class='content-box positon-relative'>没有找到该关键字对应的群组</a><br>您可以<a href='../tools/search.html'>返回</a>重新搜索<br>或者新建一个微信群并<a href='../tools/publish.html' style='color:blue;text-decoration:underline'>发布</a>";
					document.getElementById('more').innerHTML=innerHTML; 
				}else{
					$('#more').more({'address': 'data_rentout_search.php?keyword='+keyword})
				}
			}else{//按条件搜索
				//alert("rent_type不为空");
				//alert(rent_type);
				//alert(num_conditions);
				if(num_conditions == 0){//条件搜索，没有搜到结果
					var innerHTML="<a class='content-box positon-relative'>没有找到该条件对应的群组</a><br>您可以<a href='../tools/search.html'>返回</a>重新搜索<br>或者新建一个微信群并<a href='../tools/publish.html' style='color:blue;text-decoration:underline'>发布</a>";
					document.getElementById('more').innerHTML=innerHTML; 
				}else{
					//alert("num_conditions不为0");
					$('#more').more({'address': 'data_rentout_search.php?renttype='+rent_type+'&district='+district+'&roomtype='+room_type+'&count='+count+'&a0='+a0+'&b0='+b0+'&a1='+a1+'&b1='+b1+'&a2='+a2+'&b2='+b2+'&a3='+a3+'&b3='+b3})
				}
				//var test='../php/data_rentout_search.php?renttype='+rent_type+'&district='+district+'&subway='+subway+'&room_type='+room_type+'&price='+price;
				//alert(test);
			}
			//var address_php='../php/data_user_rent.php?w_or_m='+w_or_m+'&contact='+contact+'&house_rent_id='+user_house_rent_id+'&user_want='+user_want;
			//alert(address_php);
			//$('#more').more({'address': '../php/data_rentout_search.php?keyword='+keyword+'&rent_type='+rent_type+'&district='+district+'&subway='+subway+'&room_type='+room_type})
			//alert('111');
		});
		//返回之前浏览位置
		 $(function () {
        var str = window.location.href;
        str = str.substring(str.lastIndexOf("/") + 1);
        if ($.cookie(str)) {
            $("html,body").animate({ scrollTop: $.cookie(str) }, 1000);
        }else {
        }
    })
    
    $(window).scroll(function () {
        var str = window.location.href;
        str = str.substring(str.lastIndexOf("/") + 1);
        var top = $(document).scrollTop();
        $.cookie(str, top, { path: '/' });
        return $.cookie(str);
    })
		</script>
</head>
<body>

<header>
    <div class="nav-btn nav-return">
			    	<a href='../tools/search.html'>
            <i class="fa fa-chevron-left fa-2x" aria-hidden="true"></i>
            <div class="return">返回</div>
        </a>
    </div>
    <div class="phone-info">
    <?php
    if($keyword != ""){
    	echo "<span>关键字搜索:</span><span style='color:red;'>".$keyword."</span>";
    }else{
    	echo "<span style='color:red;'>";//$district_condition.$rent_type_condition.$room_type_condition.$subway_condition.$price_condition.
			
			if($count==0){
				echo "全部已验证群组";
			}
			if($count==1){
				echo $br[0];
			}
			if($count==2){
				echo $br[0]."+".$br[1];
			}
			if($count==3){
				echo $br[0]."+".$br[1]."+".$br[2];
			}
    	echo "</span>";
    	//echo "<br>";
    	//echo "<span style='color:blue;'>";
    	//echo "参考均价：".$num_conditions."k";
    	//echo "</span>";
    }
    ?>
	  </div>
</header>
<br>
<div class=" body">
    <!--租房信息-->
    <div id="more">
        <div class="single_item"><!--onclick="alert(this.innerHTML);"-->
					<!--<a href="pages/rent_info.html" class="content-box">-->
					<?php echo "<input type='hidden' value='$keyword' name='keyword_post'/>"; ?>
					<?php echo "<input type='hidden' value='$rent_type' name='rent_type_post'/>"; ?>
					<?php echo "<input type='hidden' value='$district' name='district_post'/>"; ?>
					<?php echo "<input type='hidden' value='$subway' name='subway_post'/>"; ?>
					<?php echo "<input type='hidden' value='$room_type' name='room_type_post'/>"; ?>
					<?php echo "<input type='hidden' value='$price' name='price_post'/>"; ?>
					<?php echo "<input type='hidden' value='$num_content' name='num_content'/>"; ?>
					<?php echo "<input type='hidden' value='$num_conditions' name='num_conditions'/>"; ?>
					
					<?php echo "<input type='hidden' value='$count' name='count'/>"; ?>
					<?php echo "<input type='hidden' value='$ar[0]' name='a0'/>"; ?>
					<?php echo "<input type='hidden' value='$br[0]' name='b0'/>"; ?>
					<?php echo "<input type='hidden' value='$ar[1]' name='a1'/>"; ?>
					<?php echo "<input type='hidden' value='$br[1]' name='b1'/>"; ?>
					<?php echo "<input type='hidden' value='$ar[2]' name='a2'/>"; ?>
					<?php echo "<input type='hidden' value='$br[2]' name='b2'/>"; ?>
					<?php echo "<input type='hidden' value='$ar[3]' name='a3'/>"; ?>
					<?php echo "<input type='hidden' value='$br[3]' name='b3'/>"; ?>
					<a class="content-box positon-relative">
					<!--<a class="content-box">-->
						<!--房间属性简介_水平排列-->
			
            
						<!--<h4><div class="title" style="float:right;">科技大学老师，校内大两居寻靠谱租客</div></h4>-->
            
            <!--使用h5标签基本可以容纳18个字,如果需要更小的字体,直接改为h6就可以了-->
						<!--房间图片 float-->
						<!--
		        <div class="pic-wrapper float-left">
		            <img class="room-pic" alt="">
		        </div>
		        -->
		        <div class="pic-wrapper-float" ></div>
		        <!--房源描述 float-->
            <div class="room-desc-abs">
            	<h5><span class="title"></span></h5>
                <p class="content"></p>
            </div>
            <div class="bottom-abstract">
			
			    <span class="house-type">
					<span class="district"></span>
				</span>	
				
                <ul class="room-info">
                    <!--房源状态描述-->
                    <li>
                        <span class="house_rent_id"></span>
                    </li>
                    <li>
                        <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                        <span class="helped"></span>
                    </li>
                    <li>
                        <!--<span class="set_top"></span>-->
                        <span class="passtime"></span>
                    </li>
                </ul>
            </div>
					<!--</a>-->
					</a>
					<hr>
        </div>
        <a href="javascript:;" class="get_more" style="color:#007bc4/*#424242*/; text-decoration:none;outline: none;">::more::</a>
  	</div> 
    
    


</div>
</body>
</html>