<?php
	require_once("connect.php");
	session_start();
	ob_start();
	if (!isset($_SESSION['username'])) {
		 header('Location: login.php');
	}
	$photo_id = $_SESSION['photo_id'];
	$album_id = $_SESSION['album_id'];
	$username = $_SESSION['username'];
	
	$album_name = $_SESSION['album_name'];
	
	$sql = "select a.username from photo p, album a where p.photo_id=$photo_id and p.album_id = a.album_id;";
	$query = mysql_query($sql, $conn);
	$row = mysql_fetch_array($query);
	$own_photo = $row[0];
?>

<html>
<head>
	<title>PHOTO</title>
	<meta charset="utf-8">
	<meta name='viewport' content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="mystyle.css">
	<script type="text/script" src="detail.js"></script>
	<style>
		div#fix_nav {	
			width: 110%;
			height: 70px;
			background-color: #f9f9f9;
			display: block;
			box-shadow: 0px 2px 2px rgba(0,0,0,0.2); /*Đổ bóng cho menu*/
			position: fixed; /*Cho menu cố định 1 vị trí với top và left*/
			top: 0; /*Nằm trên cùng*/
			left: 0; /*Nằm sát bên trái*/
			z-index: 100000; /*Hiển thị lớp trên cùng*/
		}
		
		#div1
		{
			height: 10;
			width: 10;
			float: right;
			background: #eeeeee;
		}
		
		#div1 ul
		{
			list-style: none;
		}
		
		#div1 ul li a
		{
			position: ralative;
			float: right;
		}
		
		#div1 a
		{
			text-decoration: none;
			color: $666666;
			padding: .3cm 0px;
			font-size: 20px;
			
		}
		
		#div1 ul
		{
			position: absolute;
			display: none;
		}
		
		#div1:hover ul
		{
			display: block;
		}
		
		div#comment{
			background-color: #f9f9f9;
			height: 400;
			width: 270;
			overflow: auto;
		}
		#next_photo:hover #pre
		{
			display: block;
		}
		
		#close:hover
		{
			background: red;
		}
		#touched
		{
			display: none;
			margin-top: -590;
			
		}
		input#pre_next
		{
			width: 40;
			height: 100;
			color: green;
			font-size: 40;
			text-align: center;
			
		}
		#touch:hover #touched
		{
			display: block;
		}
	</style>
</head>

<body style="background-color: cyan;">

	<!---------------------------------------------------------------------------------------------------------------->
	<div id="fix_nav" class="row">
		<div class="col-sm-4"> 
			<label id="home"><a href="home.php">home</a></label>
		</div><!--button home------------------->
		
		<div class="col-sm-4"> 
			<form action="home.php" method="post">
				<input type="text" placeholder=" search" name="search" class="input_search">
				<input type="submit" name="search_submit" value="search">
			</form>
		</div><!--tim kiem user------------------>
		
		<div class="col-sm-4"> 
			<label id="tcn">
				<form action="home.php" method="post">
					<span class="glyphicon glyphicon-user"></span>
					<input type="submit" name="detail" value="Trang cá nhân" style="border: 0; background: #f9f9f9;">
				</form>
			</label>
			<label id="tcn">
				<a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> log out</a>
			</label>
		</div><!--button trangcanhan va log out-->
	</div><!--header:de muc cua trang-->
	
	</nav>
	

	
	<!---------------------------------------------------------------------------------------------------------------->
	<div id="contenter" style="margin-top:70;">
		<div class="ibox" style="background: #eeeeee">
			<div class='row'>
				<!--hien thi hinh anh o day----------------------------------------->
				<div class='col-sm-8'>
					<div id='touch'>
						<?php
						$sql = "select photo_name from photo where photo_id = $photo_id";
						$query = mysql_query($sql, $conn);
						$row = mysql_fetch_array($query);
						
						$photo_name = $row[0];
							
						echo 
						"<img src='image/".$row[0]."' alt='photo in here' style='height: 87%; width: 100%; margin-top: -10;'>";
					
						?>
						<div id='touched'>
							<form action='photo.php' method='post'>
								<input id='close' name='close' type='submit' value='x' style='margin-left: 0; margin-top: -100;'>
								<br><br><br><br><br><br><br><br><br><br><br><br>
								<input id='pre_next' name='pre_photo' type='submit' value='<' style='margin-left: 0;'>
								<input id='pre_next' name='next_photo' type='submit' value='>' style='margin-left: 480;'>
							</form>
						</div>
						<?php
							if(isset($_POST['close']))
							{
								if($username == $own_photo) header("Location: album.php");
								else header("Location: another_album.php");
							}
							else if(isset($_POST['pre_photo']))
							{
								$sql_pre = "select photo_id from photo where photo_id<$photo_id and album_id=$album_id order by photo_id desc limit 1;";
								$query_pre = mysql_query($sql_pre, $conn);
								$row_pre = mysql_fetch_array($query_pre);
								
								if($row_pre == 0) header("Location: photo.php");
								else
								{
									$_SESSION['photo_id'] = $row_pre[0];
									header("Location: photo.php");
								}
							}
							else if(isset($_POST['next_photo']))
							{
								$sql_next = "select photo_id from photo where photo_id>$photo_id and album_id=$album_id limit 1;";
								$query_next = mysql_query($sql_next, $conn);
								$row_next = mysql_fetch_array($query_next);
								
								if($row_next == 0) header("Location: photo.php");
								else
								{
									$_SESSION['photo_id'] = $row_next[0];
									header("Location: photo.php");
								}
							}
						?>
					</div>
				</div>
				
				<!--hien thi thông tin hinh anh, comment o day--------------------->
				<div class='col-sm-4'>
					<?php
						$sql = "select title, time from photo where photo_id = $photo_id";
						
						$query = mysql_query($sql, $conn);
						
						$row = mysql_fetch_array($query);
							
					?>
					<!--thong tin anh-->
					<div style='font-size: 18;'>
						<b><?php echo $own_photo ?></b>
						<em><?php echo $album_name ?></em>
						<label id='div1'>
							<a href='#'>...</a>
							<ul class='al'>
								<li>
								<?php
									if($username == $own_photo)
									echo"
								<form action='photo.php' method='post'>
									<input type='submit' name='xoa' value='xóa ảnh' style='margin-left: -50; font-size: 15; border: 0'>
								</form>";
									else
									echo"
								<form action='photo.php' method='post'>
									<input type='submit' name='baocao' value='báo cáo vi phạm' style='margin-left: -50; font-size: 15; border: 0'>
								</form>";
								
									if(isset($_POST['xoa']))
									{
										$file = "image/".$photo_name;
										if(file_exists($file))
										{
											unlink($file);
										}
										
										$sql1 = "delete from comment where photo_id=$photo_id;";
										$sql2 = "delete from photo where photo_id=$photo_id;";
										mysql_query($sql1, $conn);
										mysql_query($sql2, $conn);
										header("Location: album.php");
									}
									else if(isset($_POST['baocao']))
									{
										$sql1 = "update photo set vipham=1 where photo_id=$photo_id;";
										mysql_query($sql1, $conn);
										header("Location: photo.php");
									}
									
								?>
								</li>
							</ul>	
						</label>
						<br>
						<em style='font-size: 13;'><u><?php echo $row[1] ?></u></em>
						<p style='margin-top: 15;'><?php echo $row[0] ?></p>
						<hr>
						
						
					</div>
					
					<!--viet comment-->
					<div>
						<form action="photo.php" method="post">
							<input type="text" name="content" placeholder="write comment ..." style="width: 270; border: 0; height: 25;">
							<input type="submit" name="sub_comment" style="display: none;" >
						</form>
					</div>
					<?php
						if(isset($_POST['sub_comment']))
						{
							$content = $_POST['content'];
							$sql = "insert into comment(content, photo_id, username)
							value('$content', $photo_id, '$username');";		
							mysql_query($sql, $conn);
							header("Location: photo.php");
						}
					?>
					
					<!--cac comment-->
					<div id="comment">
						<?php
							$sql3 = "select username, content
							from comment where photo_id=$photo_id
							ORDER BY photo_id DESC;";
							
							$query3 = mysql_query($sql3, $conn);
							
							while($row3 = mysql_fetch_array($query3))
							{
								echo "
								<p><b>".$row3[0]."</b>
									<em>".$row3[1]."</em>
								</p>";
							}
							
						?>
					</div>
					
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>