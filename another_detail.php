<?php
	require_once("connect.php");
	session_start();
	ob_start();
	if (!isset($_SESSION['username'])) header('Location: login.php');
	
	$another_name=$_SESSION['another_name'];
	
	if($another_name == $_SESSION['username']) header('Location: detail.php');
	
	$sql_count = "select count(album_name), u.fullname from album a, user u where a.username='$another_name' and a.username=u.username;";
	$query_count = mysql_query($sql_count, $conn);
	while($row_count = mysql_fetch_array($query_count))
	{		
		$album_count = $row_count[0];
		$fullname = $row_count[1];
	}	
	if($fullname == null) $fullname="no fullname!";
?>

<html>
<head>
	<title>ANOTHER</title>
	<meta charset="utf-8">
	<meta name='viewport' content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	
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
		.box_album{
			display: none;
			position: absolute;
			background-color: #f9f9f9;
			min-width: 160px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			padding: 12px 16px;
			z-index: 1;
		}
	</style>
</head>

<body style="background-color: #eeeeee;">

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
	
	<div id="contenter" style="margin-top: 70;">
		<?php
			
		?>
		<div class="ibox" style="height: 100%; background: cyan;">
			<br> <br> <br>
			<div class="row">
				<!--
				<div class="col-sm-4">
					anh dai dien o day
				</div>
				-->
				<div class="col-sm-8" style="margin-left: 100;">
					<p style="font-size: 20;">
						<em>username : &nbsp </em><?php echo $another_name; ?>
					</p>
					<p style="font-size: 20;"><em>fullname &nbsp : &nbsp <?php echo $fullname; ?></em></p>
					
					<em style="font-size: 20; margin-right: 40"><?php echo $album_count; ?> albums</em>
					<br><br>
					
					
					
				</div>
			</div>
				
			<br><br><br>
			<hr>
			
			
			
		
			<!------------------------------------------------>
			
			
			<div>
			<big>list albums:</big>
			<?php
				$sql_album = "select album_id, album_name from album where username='$another_name';";
				$query_album = mysql_query($sql_album, $conn);
				while($row_album = mysql_fetch_array($query_album))
				{
					echo "<div class='well well-sm' style='background-color: #eeeeee;font-size: 30;
					width: 800; height: 60; padding-left: 100'>
						<form action='another_detail.php' method='post'>
							<input type='hidden' name='album_id' value='".$row_album[0]."'>
							<input type='submit' name='album' value='".$row_album[1]."' style='border: 0; 
							padding-top: 0 ;background: #eeeeee;'>
						</form>
					</div>";
				}
				
				
				if(isset($_POST["album"]))
				{	
					 
					$_SESSION['album_id'] = $_POST['album_id'];
					$_SESSION['album_name'] = $_POST["album"];
					
					header("Location: another_album.php");
				} 
			?>
			</div>
		</div>
	</div>
		
</body>
</html>