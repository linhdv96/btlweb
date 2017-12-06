<?php
	require_once("connect.php");
	session_start();
	ob_start();
	if (!isset($_SESSION['username'])) {
		 header('Location: login.php');
	}
?>

<html>
<head>
	<title>USERS</title>
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
		#edit
		{
			display: none;
			position: absolute;
			background-color: #f9f9f9;
			min-width: 160px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
			margin-top: -20;
			z-index: 1;
		}
		
		#div1
		{
			height: 10;			
		}
		
		#div1 ul
		{
			list-style: none;
		}
		
		#div1 ul li
		{
			margin-top: -24;
			margin-left: 60;
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
	<div id="contenter" style="margin-top: 120;" align='center'>
		<table border='1'>
			<tr>
				<form action='quanly.php' method='post'>
					<td><input name='username' placeholder='username'></td>
					<td><input name='password' placeholder='password'></td>
					<td><input name='fullname' placeholder='fullname'</td>
					<td><input type="submit" name="signup" value="add user"><td>
				</form>
			</tr>
			
				<?php
					$sql = "select * from user where position='user';";
					$query = mysql_query($sql, $conn);
					
					while($row = mysql_fetch_array($query))
					{
						echo "
						<tr style='height: 30;'>
							<td>".$row[0]."
								<div id='".$row[0]."' style='position: absolute; display: none; background-color: #f9f9f9; margin-top: -20; '>
									<form method='post' action='quanly.php'>
										<input type='hidden' name='username' value='".$row[0]."'>
										<input name='new_username' value='".$row[0]."'>
										<input name='password' value='".$row[1]."'>
										<input name='fullname' value='".$row[3]."'>
										<input type='submit' name='edit' value='sửa'>
										<button type='reset' onclick=document.getElementById('".$row[0]."').style.display='none' >cancel</button>
									</form>
								</div>
							</td>
							<td>".$row[1]."</td>
							<td>".$row[3]."</td>
							<td>
								<label id='div1'>
									<a href='#' style='margin: left: -100;'>...</a>
									<ul class='al'>
										<li>
											<form action='quanly.php' method='post'>
												<input type='hidden' name='username' value='".$row[0]."'>
												<input type='submit' name='xoa' value='xóa' style='margin-left: -100; font-size: 15;'>
												<button type='reset' onclick=document.getElementById('".$row[0]."').style.display='block'>sửa</button>
												<input type='submit' name='quyenadmin' value='cấp quyền admin' >
											</form>
										</li>
									</ul>	
								</label>
							</td>
						</tr>
						";
					}
					
				?>
			
		</table>
	</div>
	<?php
		if(isset($_POST["signup"]))
		{
			$fullname = $_POST["fullname"];
			$username = $_POST["username"];
			$password = $_POST["password"];
			
			$n = strlen($username);
			$m = Strlen($password);
			
			
			$problem = 0; //kiem tra tinh hop le cua username, password
			
			//kiem tra do dai username, password
			if ($n<5 || $n>50 || $m<5 || $m>50)
			{
				$problem = 1;
			}
			
			//kiem tra ky tu dac biet trong username
			for($i=0; $i<$n; $i++)
			{	
				if( !($username[$i]>='a' && $username[$i]<='z') && !($username[$i]>='0' && $username[$i]<='9'))
				{
					$problem = 1;
					break;
				}
			}
			
			//kiem tra ky tu dac biet trong password
			for($i=0; $i<$m; $i++)
			{	
				if( !($password[$i]>='a' && $password[$i]<='z') && !($password[$i]>='0' && $password[$i]<='9'))
				{
					$problem = 1;
					break;
				}
			}
			
			$search = "select * from user where username = '$username'"; //tim kt xem username da duoc sd chua
			$result = mysql_query($search, $conn);
			$row = mysql_fetch_array($result);
			
			if($problem == 1) echo "<script> alert('username hoặc password không hợp lệ !')</script>";
			else if($row != 0) echo "<script> alert('username đã được sử dụng!')</script>";
			else
			{
				$sql = "insert into user(username, password, position, fullname) value('$username', '$password', 'user', '$fullname');";
				$query = mysql_query($sql, $conn);
				header("Location: quanly.php");
			}
			
			
		}
		
		if(isset($_POST["quyenadmin"]))
		{
			$name = $_POST["username"];
			$sql = "update user set position='admin' where username='$name';";
			mysql_query($sql, $conn);
			header("Location: quanly.php");
		}
		
		if(isset($_POST["xoa"]))
		{
			$name = $_POST['username'];
							
			$sql = "select album_id from album where username='$name';";
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
			$sql4 = "delete from user where username='$name';";
			mysql_query($sql4, $conn);
										
			header("Location: quanly.php");
		}
		
		if(isset($_POST["edit"]))
		{
			$old_username = $_POST["username"];
			$new_password = $_POST["password"];
			$new_fullname = $_POST["fullname"];
			
			$sql = "update user set password='$new_password', fullname='$new_fullname' where username='$old_username';";
			mysql_query($sql, $conn);
			header("Location: quanly.php");
		}
	?>
</body>
</html>