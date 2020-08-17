<?php 
	error_reporting(0);
	session_start();
	$host = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "vmprovisioner";
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);
	$msg="";
	if(isset($_POST['login'])){
		$username =  $_POST['username'];
		$unhashedpass = $_POST['password'];
		$password = sha1($unhashedpass);
		$role = $_POST['role'];

		$sql = "SELECT * FROM users WHERE username=? AND password=? AND role=?";
		$stmt=$conn->prepare($sql);
		$stmt->bind_param("sss",$username,$password,$role);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		session_regenerate_id();
		$_SESSION['username'] = $row['username'];
		$_SESSION['role'] = $row['role'];
		session_write_close();

		if($result->num_rows==1 && $_SESSION['role']=="Employee"){
			header("location:employeedashboard.php");
		}
		else if($result->num_rows==1 && $_SESSION['role']=="Engineer"){
			header("location:engineerdashboard.php");
		}
		else if($result->num_rows==1 && $_SESSION['role']=="Admin"){
			header("location:admindashboard.php");
		}
		else{
			$msg = "Username or Password is Incorrect";
		}
	}
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Hayden">
	<link rel="title icon" href="images/Virtual Machine Provisioner.png">
	<meta http-equiv="x-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="wodth=device-width,initial-scale=1, shrink-to-fit=no">
	<title> VMProvisioner Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<body class="bg-dark">
<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-5 bg-light mt-5 px-0">
			<h3 class="text-center text-light bg-danger p-3">Login</h3>
			<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="p-4">
				<div class="form-group">
					<input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
				</div>
				<div class="form-group lead">
					<label for="role">Role:</label>
					<input type="radio" name="role" value="Employee" class="custom-radio" required>&nbsp;Employee |
					<input type="radio" name="role" value="Engineer" class="custom-radio" required>&nbsp;Engineer |
					<input type="radio" name="role" value="Admin" class="custom-radio" required>&nbsp;Admin
				</div>
				<div class="form-group">
					<input type="submit" name="login" class="btn btn-danger btn-block">
				</div>
				<h5 class="text-danger text-center"><?= $msg; ?></h5>
			</form>
		</div>
	</div>
</div>
</body>
</html>