<?php
// add a new user to the user table + add the user's username
// into the unverified table 
function addUser($db, $username, $pass, $email, $bdate) {

	$hashedPass = md5($pass);

	$queryUser = "INSERT INTO user (username, hashPass, email, bdate) VALUES ('$username','$pass','$email',$bdate);";
	$resultUser = $db->query($queryUser);

	$queryUnve = "INSERT INTO unverified VALUE ('$username');";
	$resultUnve = $db->query($queryUnve);

	if ($resultUser != FALSE AND $resultUnve != FALSE) {
		print ("<P>Your account has been created with username $username, check your email address $email to verify your new account.</P>");
	}
}

function checkUser($db, $username, $pass) {
	$hashedPass = md5($pass); 

	$qStrUser = "SELECT * FROM user WHERE username='$username';";
	$qStrUnve = "SELECT * FROM unverified WHERE username='$username';";
	$qStrPass = "SELECT passHash FROM user WHERE username='$username';";

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

// remove the given username from unverified table 
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

// update user username, email and bday in profile
function updateProfile($username, $email, $bdate) {

	$query = "SELECT * FROM user WHERE username=$username";
	$res = $db->query($query);

	if ($res->rowCount() == 0) {
		print("<P>Your username is incorrect / doesn't exist.</P>");
		return -1;
	}
	else {
		// when uid is 
		$queryUpdate = "UPDATE user SET username = $username, email = $email, bdate = $bdate WHERE uid=2;"; //hard-coded uid for now
		$resultUpdate = $db->query($queryUpdate);

		$queryCheck = "SELECT username, email, bdate IN user WHERE uid=2";
		$resultCheck = $db->query($queryCheck);

		$result = $resultCheck->fetch();

		if ($username == $result['username'] AND $email == $result['email'] AND $bdate == $result['bdate']) {
			print("<P>Your information has been updated.</P>");
		}
		else {
			print("<P>Error. Try again.</P>");
		}
	}
}
?>
