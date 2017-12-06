<?php
	require_once("connect.php");
	session_start();
	ob_start();
	if (!isset($_SESSION['username'])) {
		 header('Location: login.php');
	}
	
	$username=$_SESSION['username'];
	
	$sql_count = "select count(album_name), u.fullname from album a, user u where a.username='$username' and a.username=u.username;";
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
	<title>MY PROFILE</title>
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

<body style="background-color: eeeeee;">

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
		<div class="ibox" style="background-color: cyan;">
			<br> <br> <br>
			<div class="row">
				<!--
				<div class="col-sm-4">
					anh dai dien o day
				</div>
				-->
				<div class="col-sm-8" style="margin-left: 100;">
					<p style="font-size: 20;">
						<em>my username : &nbsp </em><?php echo $username; ?>
					</p>
					<p style="font-size: 20;"><em>my fullname &nbsp : &nbsp <?php echo $fullname; ?></em></p>
					
					<em style="font-size: 20; margin-right: 40"><?php echo $album_count; ?> albums</em>
					<br><br>
					
					
					<button onclick='document.getElementById("profile").style.display="block"'>Edit Profile </button>
					<div id="profile" class="box_album"> 
						<form action="detail.php" method="post">
							<input type="password" placeholder="new password" name="new_password" ><br>
							<input type="text" placeholder="new fullname" name="new_fullname" ><br>
							<input type="submit" value="Edit" name="edit_profile">
						</form>
						<button id="cancel" onclick='document.getElementById("profile").style.display="none"' style="margin-left: 170">Cancel
						</button>
						<?php
							if(isset($_POST["edit_profile"]))
							{	
								$new_password = $_POST["new_password"];
								$new_fullname = $_POST["new_fullname"];
								
								if ($new_fullname!="" )
								{
									$sql = "update user set fullname='$new_fullname' where username='$username';";
									$query = mysql_query($sql, $conn);
								}
								
								header("Location: detail.php");
							} 
						?>
					</div>
					<br><br>
					
					
				</div>
			</div>
				
			<br><br><br>
			<hr>
			
			
			
			<!--add album------------------------------------>

			<button id="add_album" onclick='document.getElementById("album").style.display="block"'>+ Add Album </button>
			<div id="album" class="box_album"> 
				<form action="detail.php" method="post">
					<input type="text" placeholder="album name" name="album_name" >
					<input type="submit" value="ADD" name="add_album">
				</form>
				<button id="cancel" onclick='document.getElementById("album").style.display="none"' style="margin-left: 170">Cancel
				</button>
			</div>
			<br>
			<br>
			<?php
				if(isset($_POST["add_album"]))
				{	
					$album_name = $_POST["album_name"];
					
					if ($album_name!="")
					{
						$sql = "insert into album(album_name, username) value('$album_name','$username');";
						$query = mysql_query($sql, $conn);
						echo "<script> alert('thêm album thành công ^^ !')</script>";
					}
					
					header("Location: detail.php");
				} 
			?>
			<!------------------------------------------------>
			
			
			<div>
			<big>list albums:</big>
			<?php
				$sql_album = "select album_id, album_name from album where username='$username';";
				$query_album = mysql_query($sql_album, $conn);
				while($row_album = mysql_fetch_array($query_album))
				{
					echo "<div class='well well-sm' style='background-color: #eeeeee;font-size: 30;
					width: 800; height: 60; padding-left: 100'>
						<form action='detail.php' method='post'>
							<input type='hidden' name='album_id' value='".$row_album[0]."'>
							<input type='submit' name='album' value='".$row_album[1]."' style='border: 0; 
							padding-top: 0 ;background: #eeeeee; color: green;'>
						</form>
					</div>";
				}
				
				
				if(isset($_POST["album"]))
				{	
					 
					$_SESSION['album_id'] = $_POST['album_id'];
					$_SESSION['album_name'] = $_POST["album"];
					
					header("Location: album.php");
				} 
			?>
			</div>
		</div>
	</div>
		
</body>
</html>