<?php
	require("connect.php");
	session_start();
	ob_start();
	
?>


<html>
<head>
	<title>SEARCH</title>
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
	
	<!---------------------------------------------------------------------------------------------------------------->
	<div id="contenter" style="margin-top: 90;">
		<?php
			$text = $_SESSION["text"];
			echo "<h4 style='margin-left: 350'>Search for #".$text."</h4>";
			
			$sql = "select * from user where username like '%$text%';";
			$query = mysql_query($sql, $conn);
			
			while($row = mysql_fetch_array($query))
			{
				echo "<div class='well well-sm' style='background-color: #eeeeee; color: 666699;font-size: 30;
					width: 600; height: 60; margin-left: 350; padding-left: 100'>
						<form action='list_user.php' method='post'>
							<input type='submit' name='detail' value='".$row["username"]."' style='border: 0; 
							padding-top: 0 ;background: #eeeeee;'>
						</form>
					</div>";
			}
			if(isset($_POST["detail"]))
			{	
				$_SESSION['another_name'] = $_POST["detail"];
				
				header("Location: another_detail.php");
			} 
			
			
			
			if(isset($_POST["album_name"]))
			{	
				$_SESSION['another_name'] = $_POST["sdetail"];
				$_SESSION['album_id'] = $_POST['album_id'];
				$_SESSION['album_name'] = $_POST["album_name"];
				
				header("Location: another_album.php");
			} 
			
		?>
	</div>
	
</body>
</html>