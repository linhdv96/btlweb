<?php
	require_once("connect.php");
	session_start();
	if (!isset($_SESSION['username'])) {
		 header('Location: login.php');
	}
?>

<html>
<head>
	<title>ADMIN</title>
	<meta charset="utf-8">
	<meta name='viewport' content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="mystyle.css">
	<script type="text/script" src="detail.js"></script>
	<style>
		div#fix_nav {	
			width: 110%;
			height: 70px;
			background-color: cyan;
			display: block;
			box-shadow: 0px 2px 2px rgba(0,0,0,0.2); /*Đổ bóng cho menu*/
			position: fixed; /*Cho menu cố định 1 vị trí với top và left*/
			top: 0; /*Nằm trên cùng*/
			left: 0; /*Nằm sát bên trái*/
			z-index: 100000; /*Hiển thị lớp trên cùng*/
		}
	</style>
</head>

<body style="background-color: #eeeeee;">

	<!---------------------------------------------------------------------------------------------------------------->
	<div id="fix_nav" class="row">
		<div class="col-sm-7"> 
			<label id="home"><a href="admin.php">admin</a></label>
		</div><!--button home------------------->
		
		<div class="col-sm-5"> 
			<label id='tcn' style='margin-left: 80'>
				<a href="quanly.php" style="margin-left: 70;">Quản lý user</a>
			</label>
		
			<label id="tcn">
				<a href="logout.php" ><span class="glyphicon glyphicon-log-in"></span> log out</a>
			</label>
		</div><!--button trangcanhan va log out-->
	</div><!--header:de muc cua trang-->
	
	

	
	<!---------------------------------------------------------------------------------------------------------------->
	<div id="contenter" style="margin-top: 70;">
		<?php
			$sql = "select p.photo_id, p.photo_name, a.username from photo p, album a where a.album_id=p.album_id and p.vipham=1;";
			$query = mysql_query($sql, $conn);
			
			while($row = mysql_fetch_array($query))
			{
				echo "<div class='well'>
							<div class='row'>
								<div id='photo' class='col-sm-9'>
									<img src='image/".$row[1]."' alt='photo in here' style='margin-left: 80; margin-bottom: -20;'>
								</div>
								<div class='col-sm-3'>
									<form action='admin.php' method='post'>
										Xóa ảnh :<input type='checkbox' name='xoaanh' value='".$row[0]."'>
										<input type='hidden' name='ten_anh' value='".$row[1]."'>
										<hr>
										Hình phạt: <br>
										Khóa 3 ngày:<input type='checkbox' name='bangay' value='3'><br>
										Khóa 7 ngày:<input type='checkbox' name='bayngay' value='7'><br>
										Xóa tài khoản này:<input type='checkbox' name='xoa' value='".$row[2]."'>
										<input type='hidden' name='username' value='".$row[2]."'>
										<br><br>
										<input type='submit' value='next' name='next'>
									</form>
								</div>
							</div>
					</div>";
			}
			if(isset($_POST['next']))
			{
				$ten_anh=$_POST['ten_anh'];
				if(isset($_POST['xoaanh']))
				{
					if(isset($_POST['bangay']) && isset($_POST['bayngay'])){}
					else
					{
						$username = $_POST['username'];
						if(isset($_POST['xoa']))
						{
							$username = $_POST['xoa'];
							
							$sql = "select album_id from album where username='$username';";
							$query = mysql_query($sql, $conn);
							
							while($row = mysql_fetch_array($query))
							{
								//xoa comment
								$album_id = $row[0];
								$sql1 = "select photo_id, photo_name from photo where album_id=$album_id;";
								$query1 = mysql_query($sql1, $conn);
								while($row1 = mysql_fetch_array($query1))
								{
									//xoa anh trong o cung
									$photo_name = $row1[1];
									$file = "image/".$photo_name;
									if(file_exists($file))
									{
										unlink($file);
									}
									
									//xoa comment trong anh
									$photo_id = $row1[0];
									$sql11 = "delete from comment where photo_id=$photo_id;";
									mysql_query($sql11, $conn);
								}
								//xoa anh trong csdl
								$sql2 = "delete from photo where album_id=$album_id;";
								mysql_query($sql2, $conn);
								//xoa album
								$sql3 = "delete from album where album_id=$album_id;";
								mysql_query($sql3, $conn);
							}
							//xoa album
							$sql4 = "delete from user where username='$username';";
							mysql_query($sql4, $conn);
														
							header("Location: admin.php");
						}
						else if(isset($_POST['bangay']))
						{
							$photo_name = $_POST['ten_anh'];
							$file = "image/".$photo_name;
							if(file_exists($file))
							{
								unlink($file);
							}
							
							$photo_id = $_POST['xoaanh'];
							
							$sql1 = "delete from comment where photo_id=$photo_id;";
							$sql2 = "delete from photo where photo_id=$photo_id;";
							mysql_query($sql1, $conn);
							mysql_query($sql2, $conn);
							
							$sql3 = "update user set time_unlock=ADDDATE(NOW(), INTERVAL 3 DAY) where username='$username';"; 
							mysql_query($sql3, $conn);
							header("Location: admin.php");
						}
						else if(isset($_POST['bayngay']))
						{
							//xoa anh trong o cung va csdl
							$photo_name = $_POST['ten_anh'];
							$file = "image/".$photo_name;
							if(file_exists($file))
							{
								unlink($file);
							}
							
							$photo_id = $_POST['xoaanh'];
							
							$sql1 = "delete from comment where photo_id=$photo_id;";
							$sql2 = "delete from photo where photo_id=$photo_id;";
							mysql_query($sql1, $conn);
							mysql_query($sql2, $conn);
							
							$sql3 = "update user set time_unlock=ADDDATE(NOW(), INTERVAL 7 DAY) where username='$username';"; 
							mysql_query($sql3, $conn);
							header("Location: admin.php");
						}
					}
				}else{
					$sql="update photo set vipham=0 where photo_name='$ten_anh';";
					mysql_query($sql, $conn);
					header("Location: admin.php");
				}
				
			}
		?>
	</div>
</body>
</html>