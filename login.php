<?php
	session_start();
	if (isset($_SESSION['username'])) {
		header('Location: home.php');
	}
?>

<html>
<head>
	<title>ALBUM_S LOGIN</title>
	<link ref="shortcut icon" href="favicon.ico">
	<meta charset="utf-8">
	<meta name='viewport' content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>

<body style="background: cyan;">
	<div class="container">
		<div id="header">
				<h1 align="center">Login</h1>
				<br>
		</div>
		
		<div id="content" class="well well-lg">
			<table align="center">
				<form action="login.php" method="post" >
					<tr>
						<td><input type="text" name="username" placeholder="username" class="input_text"><td>
					</tr>
					
					<tr>
						<td><input type="password" name="password" placeholder="password" class="input_text"></td>
					</tr>
					
					<tr>
						<td align="center"><input type="submit" name="login" value="login" class="input_submit"><td>
					</tr>
					<tr>
						<td align="center"><a href="signup.php">Sign up an account</td>
					</tr>
				</form>
			</table>
		</div>
	</div>
</body>
</html>

<?php
	require_once("connect.php");
	if(isset($_POST["login"]))
	{
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
		
		if($problem == 1) echo "<script> alert('username hoặc password không đúng !')</script>";
		else
		{
			$sql = "select * from user where username = '$username' and password = '$password';";
			$query = mysql_query($sql, $conn);
			$row = mysql_fetch_array($query);
			$time_unlock = $row[4];
			if($row == 0) echo "<script> alert('username hoặc password không đúng !')</script>";
			else
			{
				//luu username vao session de tien xu ly sau nay
				$_SESSION['username'] = $username;
				
				//chuyen huong web toi trang chu nguoi dung or admin
				if($row[2]=="admin")
				{
					header("Location: admin.php");
				}
				else 
				{
					$sql1 = "SELECT DATEDIFF(now(),'$time_unlock');"; 
					$query1 = mysql_query($sql1, $conn);
					$row1 = mysql_fetch_array($query1);
					if($row1[0] < 0) echo "<script> alert('tài khoản đang bị khóa !')</script>";
					else header("Location: home.php");
				}
			}
			
		}
		
		
	}
?>
