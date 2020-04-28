<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name = "Sample Site">
        <title>Ensemble</title>
        <link rel="stylesheet" type="text/css" href="ensemble.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
      <!-- Top bar of the webpage, contains logo and drop down menu with links -->
      <div class = "topBar">
          <a href = "index.html">
          <img src="logo.jpeg" class = "icon"/> </a>
          <text class = "barText">Ensemble</text>
          <!-- Drop down menu of user options - from w3 schools  -->
          <div>
            <div class="menu-icon dropdown">
              <i class="fa fa-bars" style = "color:#FFD700"></i>
              <div class="dropdown-content">
                <a href="user.php" >What's new</a>
                <a href="profile.php" >My Ensemble</a>
                <a href="myMusic.php" >My Music</a>
                <a href="updateProfile.php" >Edit Profile</a>
              </div>
            </div>
         </div>
      </div>
        <!-- Main portion of this page contianing the form to sign-up -->
	<?php
		include_once("db_connect.php");
		$uid = $_SESSION['uid'];

		$query = "SELECT * FROM user WHERE uid=$uid;";
		$qRes = $db->query($query);

		if ($qRes != FALSE) {
			$row = $qRes->fetch();
			$username = $row['username'];
			$email = $row['email'];
			$bdate = $row['bdate'];
		}

	?>

        <div class = "input">
            <center>
               <div class = "form-container">
                 <h1 style = "color:#333"> Your Profile </h1>
                 <form name="updateProfile" method = "POST" action="updateProfile_f.php">
		   <input class = "inputBox" type = "text" name = "username" value = "<?php echo $username; ?>"/>
                   <br />
                   <input class = "inputBox" type = "text" name = "email" value = "<?php echo $email; ?>"/>
                   <br />
                   <input class = "inputBox" type = "text" name = "bdate" value = "<?php echo $bdate; ?>" onfocus="(this.type='date')"/>
                   <br />
		   <h2 style = "color:#333"> Change your password</h2>
		   <input class = "inputBox" type = "password" name = "currentpw" placeholder="Your current password"/>
                   <br />
		   <input class = "inputBox" type = "password" name = "newpw" placeholder="Your new password"/>
                   <br />
                   <input class ="submit-button main-button" type = "submit" value = "Update your Profile" >
                 </form>
                </div>
            </center>
        </div>
        <!-- footer -->
        <div class = "footer">
            <center>
                <text class = "copyright"> &copy; BMC inc. | 2020 </text>
            </center>
        </div>
    </body>
</html>
