<?php
	require_once("connect.php");
	session_start();
	ob_start();
	
	$album_id = $_SESSION['album_id'];
	$album_name = $_SESSION['album_name'];
	$username=$_SESSION['username'];
	
?>

<html>
<head>
	<title>MY ALBUM</title>
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
		<h2>Album: <?php echo $album_name; ?> </h2>
		
		<!--add album------------------------------------>

		<button id="add_album" onclick='document.getElementById("album").style.display="block"'> Chỉnh sửa </button>
		<div id="album" class="box_album"> 
			<form action="album.php" method="post">
				<input type="text" placeholder="tên album mới" name="album_name" >
				<input type="submit" value="đổi tên" name="doiten"><br><br>
				<input type="submit" value="xóa album" name="xoa" style="margin-left: 170">
			</form>
			<button id="cancel" onclick='document.getElementById("album").style.display="none"' style="margin-left: 170">Cancel
			</button>
		</div>
		<br>
		<br>
		<?php
			if(isset($_POST["doiten"]))
			{	
				$album_name2 = $_POST["album_name"];
				
				if ($album_name!="")
				{
					$sql = "update album set album_name='$album_name2' where album_id=$album_id;";
					$query = mysql_query($sql, $conn);
					$_SESSION['album_name']=$album_name2;
					header("Location: album.php");
				}				
			} 
			if(isset($_POST['xoa']))
			{
				//xoa comment
				$sql1 = "select photo_id, photo_name from photo where album_id=$album_id;";
				$query1 = mysql_query($sql1, $conn);
				while($row1 = mysql_fetch_array($query1))
				{
					$photo_name = $row1[1];
					$file = "image/".$photo_name;
					if(file_exists($file))
					{
						unlink($file);
					}
					
					$photo_id = $row1[0];
					$sql11 = "delete from comment where photo_id=$photo_id;";
					mysql_query($sql11, $conn);
				}
				
				//xoa anh
				$sql2 = "delete from photo where album_id=$album_id;";
				mysql_query($sql2, $conn);
				
				//xoa album
				$sql3 = "delete from album where album_id=$album_id;";
				mysql_query($sql3, $conn);
				header("Location: detail.php");
			}
		?>
		<!------------------------------------------------>
		
		<div style='margin-left: 20;'>
			<form action="album.php" method="post" enctype="multipart/form-data">
			   Thêm ảnh: <input type="file" name="uploadFile"><br>
			   <input type="text" name="title" placeholder="Tiêu đề ảnh">
			   <input type="submit" value="Tải lên">
			</form>
		</div>
		<?php
			if($_FILES['uploadFile']['name'] != NULL){ // Đã chọn file
				//Kiểm tra định dạng tệp tin
				if($_FILES['uploadFile']['type'] == "image/jpeg" || $_FILES['uploadFile']['type'] == "image/png" || $_FILES['uploadFile']['type'] == "image/gif"){
					//Tiếp tục kiểm tra dung lượng
					$maxFileSize = 10 * 1000 * 1000; //MB
					if($_FILES['uploadFile']['size'] > ($maxFileSize * 1000 * 1000)){
						echo 'Tập tin không được vượt quá: '.$maxFileSize.' MB';
					} else {
						//Hợp lệ tiếp tục xử lý Upload
						$path = 'image/'; //Lưu trữ tập tin vào thư mục: image
						$tmp_name = $_FILES['uploadFile']['tmp_name']; //duong dan toi fileupload o client
						$name = $_FILES['uploadFile']['name']; //=ten fileupload
						
						
						//lay du lieu thoi gian hien tai
						date_default_timezone_set('Asia/Ho_Chi_Minh');
						$time = date('Y-m-d H:i:s');
						
						$title = $_POST['title'];
						//Them vao csdl
						$photo_name = $name;
						$sql = "insert into photo(photo_name, album_id, title, time) value('$photo_name',$album_id,'$title', '$time');";
						$query = mysql_query($sql, $conn);
						
						$sql1 = "select photo_id from photo where time='$time';";
						$query1 = mysql_query($sql1, $conn);
						$row1 = mysql_fetch_array($query1);
						
						$name2 = $row1[0]."_".$name;
						
						mysql_query("update photo set photo_name='$name2' where time='$time';", $conn);
					
						//Upload file
						move_uploaded_file($tmp_name,$path.$name2);
						
						echo "<script> alert('Đã tải ảnh lên thành công !');</script>";
						header("Location: album.php");
					}
				} else {
					echo "<script> alert('Tập tin phải là hình ảnh !');</script>";
				}
			}
		?>
	
		<?php
			$sql = "select photo_name, photo_id from photo where album_id=$album_id;";
					
			$query = mysql_query($sql, $conn);
			
			while($row = mysql_fetch_array($query))
			{	
				echo "<label style='height: 210; width: 300; margin: 10;'>
						<form action='album.php' method='post'>
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