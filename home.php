<?php
	require_once("connect.php");
	session_start();
	ob_start();
	//tiến hành kiểm tra là người dùng đã đăng nhập hay chưa
	//nếu chưa, chuyển hướng người dùng ra lại trang đăng nhập
	if (!isset($_SESSION['username'])) {
		 header('Location: login.php');
	}
?>

<html>
<head>
	<title>HOME</title>
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
	<div id="contenter" style="margin-top: 70;">
		
		<?php
		

			
			
			if(isset($_POST["search_submit"]))
			{
				$text = $_POST["search"];
				if($text == "") $text='!';
				//luu $text vao session de tim kiem trong file list_user.php
				$_SESSION['text'] = $text;
				
				//chuyen huong web toi trang list_user.php
				header("Location: list_user.php");
				
			}
			
			else if(isset($_POST["detail"])) 
			{
				header('Location: detail.php');
			}
			
			else
			{	
				$sql = "select a.username as username, a.album_name as album_name,
				p.title as title, p.time as time, p.photo_name as photo_name, a.album_id
				from album a, photo p 
				where a.album_id=p.album_id 
				ORDER BY photo_id DESC;";
				
				$query = mysql_query($sql, $conn);
				
				
				while($row = mysql_fetch_array($query))
				{	
					echo "<div class='well' style='background: #eeeeee; border-color: green;
					margin-left: 350; margin-right: 351; margin-bottom: 50;'>
						<form action='home.php' method='post'>
							<input type='submit' name='another_detail' value='".$row[0]."' style='border: 0; 
							background: #eeeeee; padding-left: 0; font-weight: bold; color: blue;'>
							<input type='submit' name='album_name' value='".$row[1]."' style='border: 0; 
							background: #eeeeee; color: green;'>
							<input type='hidden' name='album_id' value='".$row[5]."'>
							<input type='hidden' name='sdetail' value='".$row[0]."'>
							<br>
							<em>".$row[3]."</em>
							<p>".$row[2]."</p>
						</form>
						
						
						<img src='image/".$row[4]."' alt='photo in here' style='margin-left: -19; margin-bottom: -20; width: 583'>
						</div>";
				}
				if(isset($_POST["another_detail"]))
				{	
					$_SESSION['another_name'] = $_POST["another_detail"];
					
					header("Location: another_detail.php");
				}
				
				if(isset($_POST["album_name"]))
				{	
					$_SESSION['another_name'] = $_POST["sdetail"];
					$_SESSION['album_id'] = $_POST['album_id'];
					$_SESSION['album_name'] = $_POST["album_name"];
					
					header("Location: another_album.php");
				} 
			}
			
		?>
		
	</div>
	
	
	<!---------------------------------------------------------------------------------------------------------------->
</body>
</html>