<html>
<head>
	<title>ALBUM_S SIGNIN</title>
	<meta charset="utf-8">
	<meta name='viewport' content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>

<body style="background: cyan;">
	<div class="container">
		<div id="header">
				<h1 align="center">Sign up</h1>
				<br>
		</div>
		
		<div id="content" class="well well-lg">
			<table align="center">
				<form action="signup.php" method="post" >					
					<tr>
						<td><input type="text" name="username" placeholder="username" class="input_text"><td>
					</tr>
					
					<tr>
						<td><input type="password" name="password" placeholder="password" class="input_text"></td>
					</tr>
					
					<tr>
						<td><input type="text" name="fullname" placeholder="fullname" class="input_text"><td>
					</tr>
					
					<tr>
						<td align="center"><input type="submit" name="signup" value="sign up" class="input_submit"><td>
					</tr>
					<tr>
						<td align="center"><a href="login.php">Have an acount? log in</td>
					</tr>
				</form>
			</table>
		</div>
	</div>
</body>
</html>

<?php
	require_once("connect.php");
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
			echo "<script> alert('đăng ký tài khoản thành công ^^ !')</script>";
		}
		
		
	}
?>
