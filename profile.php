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

        <!-- Main portion of this page containing user information -->
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
                 <h1 style = "color:#333"> <?php echo "Welcome back, ".$username." !" ?> </h1>
                </div>
            </center>
        </div>
        <!-- Showing user information  -->
	    <div class = "music-container">
		<h2> Your Account Information </h2>
		      <table class = "music-table" cellspacing ="5" cellpadding="10">
			<TR><TD>Your Username:</TD><TD><?php echo $username?></TD></TR>
			<TR><TD>Your Email:</TD><TD><?php echo $email?></TD></TR>
			<TR><TD>Your Birthday</TD><TD><?php echo $bdate?></TD></TR>
		      </table>
	    </div>

        <!-- Showing users whom user follows -->
	    <div class = "music-container">
	      <h2> Following </h2>

	      <table class = "music-table" cellspacing ="5" cellpadding="10">
	      <!-- retrieve accs user are following-->
	      <?php
		$qStr = "SELECT * FROM user WHERE uid IN (SELECT fid FROM follow WHERE uid = $uid);";
		$qRes = $db->query($qStr);
		if ($qRes != FALSE)
		{
		  while ($row = $qRes->fetch())
		  {
		    $username = $row['username'];
		    $follow_id = $row['uid'];
		    $str = "<TR><TD>$username</TD><TD><button id='unfollowBtn' onclick='unfollowUser($follow_id);'>Unfollow</button></TD>
<TD><button id='viewProfBtn' onclick='viewProfile($follow_id);'>View Profile</button></TD></TR>";
		    print($str);
		  }
		}
	      ?>
	      </table>

	    <!-- hidden form -->
            <form name="unfollow_form" id="unfollow_form" method ="POST" action="unfollow_f.php">
		  <p id='unfollow_ms' style="display:none;" >Unfollow user ?</p>
                  <input type = 'text' name = 'unfollow_id' id ='unfollow_id' value = 'text' style='display:none;'>
		  <input class ="submit-button main-button" id='unfollow_submit' style="display:none;" type = "submit" value = "Unfollow" >
            </form>
	    </div>

	    <div class = "music-container">
		<h2>  Followers </h2>
		      <table class = "music-table" cellspacing ="5" cellpadding="10">
			<?php
			$qStr = "SELECT * FROM user WHERE uid IN (SELECT uid FROM follow WHERE fid = $uid);";
			$qRes = $db->query($qStr);

			if ($qRes != FALSE) {
				while ($row = $qRes->fetch()) {
					$username = $row['username'];
					$str = "<TR><TD>$username</TD></TR>";
					print($str);
				}
			}
		       ?>
		      </table>
	    </div>

        <!-- footer -->
        <div class = "footer">
            <center>
                <text class = "copyright"> &copy; BMC inc. | 2020 </text>
            </center>
        </div>
    </body>

    <script>
    function unfollowUser(id) {
	    console.log(id);
	    document.getElementById("unfollow_id").value = id;
	    document.getElementById("unfollow_ms").style.display = 'block';
	    document.getElementById("unfollow_submit").style.display = 'block';
    }

    function viewProfile(id) {

    }
    </script>
</html>
