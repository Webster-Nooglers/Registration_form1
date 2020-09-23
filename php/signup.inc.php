<?php


if(isset($_POST['signup-submit'])) { 	

	 require 'dbh.inc.php';

	 $firstName = $_POST['firstName'];
	 $lastName = $_POST['lastName'];
	 $gender = $_POST['gender'];
	 $email = $_POST['email'];
	 $pswd = $_POST['password'];
	 $phno = $_POST['phnumber'];


	 if(empty($firstName) || empty($lastName) || empty($email) || empty($firstName) || empty($pswd) || empty($phno)){

	 	header("Location: ../index.php?error=emptyfields&firstName=".$firstName."&lastName=".$lastName."&email=".$email."&phno=".$phno);
	 	exit();
	 }
	 elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	 	header("Location: ../index.php?error=invalidEmail&firstName=".$firstName."&lastName=".$lastName."&phno=".$phno);
	 	exit();
	 }
	 else{

	 	$sql = "Select email from users_ where email=?";
	 	$stmt = mysqli_stmt_init($conn);
	 	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 		header("Location: ../index.php?error=sqlError");
	 		exit();
	 	}

	 	else {

	 		mysqli_stmt_bind_param($stmt, "s", $email);
	 		mysqli_stmt_execute($stmt);
	 		mysqli_stmt_store_result($stmt);
	 		$resultCheck = mysqli_stmt_num_rows($stmt);
	 		if($resultCheck > 0)
	 		{
	 			header("Location: ../index.php?error=emailalreadyexists");
	 			exit();
	 		}
	 		else {

	 			$sql = "INSERT into users_ (firstname, lastname, gender, email, pswd, phno) VALUES (?, ?, ?, ?, ?, ?)";
	 			$stmt = mysqli_stmt_init($conn);
	 			if(!mysqli_stmt_prepare($stmt, $sql)){ 
	 				header("Location: ../index.php?error=sqlError");
	 				exit();
	 			}
	 			else {
	 				$hashpwd = password_hash($pswd, PASSWORD_DEFAULT);
	 				mysqli_stmt_bind_param($stmt, "ssssss", $firstName, $lastName, $gender, $email, $hashpwd, $phno);
	 				mysqli_stmt_execute($stmt);
	 				header("Location: ../index.php?signup=success");
	 				exit();
	 			}

	 		}
	 	}
	 }

	 mysqli_stmt_close($stmt);
	 mysqli_stmt_close($conn);

}

else {
	header("Location: ../signup.php");
	exit();
}