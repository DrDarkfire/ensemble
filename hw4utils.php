<?php
// add a new user to the user table + add the user's login
// into the unverified table 
function addUser($db, $username, $pass, $email, $bdate) {

	$hashedPass = md5($pass);

	$queryUser = "INSERT INTO user VALUE ('$db','$username','$hashedPass','$email',$bdate);";
	$resultUser = $db->query($queryUser);

	$queryUnve = "INSERT INTO unverified VALUE ('$username');";
	$resultUnve = $db->query($queryUnve);

	if ($resultUser != FALSE AND $resultUnve != FALSE) {
		print ("<P>Your account has been created with login $login, check your email address $email to verify your new account.</P>");
	}
}

function checkUser($db, $username, $pass) {
	$hashedPass = md5($pass); 

	$qStrUser = "SELECT * FROM user WHERE login='$username';";
	$qStrUnve = "SELECT * FROM unverified WHERE username='$username';";
	$qStrPass = "SELECT passHash FROM user WHERE login='$username';";

	$qResUser = $db->query($qStrUser);
	$qResPass = $db->query($qStrPass);
	$qResUnve = $db->query($qStrUnve);
	

	// if the user doesnt exists
	if ($qResUser->rowCount() == 0) {
		return -1;
	}
	else {
		$dbPass = $qResPass->fetch();
		if ($hashedPass !== $dbPass['passHash']) {
			return -3;
		}
		else {
			if ($qResUnve->rowCount() != 0) {
				return -2;
			}
			else {
				return 1;
			}
		}
	}
}

// register a user 
function registerUser($db, $input) {
	$username = $input['username'];
	$pass  = $input['password'];
	$email = $input['email'];
	$bdate = $input['bdate'];

	// if user exists 
	if (checkUser($db, $username, $pass) != -1) {
		return false;
	}
	// if user doesnt exist -> create new user
	else {
		addUser($db, $username, $pass, $email, $bdate);
		// compose a link
		$subject = "Verify Email Address";
		$link = "http://www.cs.gettysburg.edu/~trinma01/cs360/hw4/verify.php?uid=$username"; 
		$msg = "Your account has been created. Please verify your email by clicking on the link: $link";
		mail($email, $subject, $msg);
	}
	return true; 
}

// remove the given login from unverified table 
function verifyEmail($db, $username) {
	//print("verifyEmail function called");
	$query = "DELETE FROM unverified WHERE username='$username';";
	$result = $db->query($query);

	$check_query = "SELECT * FROM unverified WHERE username='$username';";
	$check_result = $db->query($check_query);

	if ($check_result->rowCount() == 0) {
		print ("<P>User $username is now verified. </P>");
	}
}
?>
