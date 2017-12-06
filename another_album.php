<?php
	require_once("connect.php");
	session_start();
	
	$album_id = $_SESSION['album_id'];
	$album_name = $_SESSION['album_name'];
	$another_name=$_SESSION['another_name'];
	
?>

<html>
<head>
	<title>ALBUM</title>
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
		<h2>User : <?php echo $another_name; ?> </h2>
		<h3>Album: <?php echo $album_name; ?> </h3>
		
	
		<?php
			$sql = "select photo_name, photo_id from photo where album_id=$album_id;";
					
			$query = mysql_query($sql, $conn);
			
			while($row = mysql_fetch_array($query))
			{	
				echo "<label style='height: 210; width: 300; margin: 10;'>
						<form action='another_album.php' method='post'>
						<input type='submit' name='show_photo' value='".$row[1]."' style='display: none; height: 200; width: 290'>
						</form>
						<img src='image/".$row[0]."' alt='photo in here' style='height: 200; width: 290'>
					</label>";
			}
			if(isset($_POST["show_photo"]))
			{
				$_SESSION['photo_id']=$_POST["show_photo"];
				header("Location: photo.php");
			}
		?>
	</div>
</body>
</html>