<?php
session_start();
$_SESSION['uid'] = 1;
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
                <i class="fa fa-bars" style ="color:#FFD700"></i>
                <div class="dropdown-content">
                  <a href="user.php" >What's new</a>
                  <a href="profile.php" >My Ensemble</a>
                  <a href="myMusic.php" >My Music</a>
                  <a href="updateProfile.php" >Edit Profile</a>
                </div>
              </div>
           </div>
        </div>
	<?php
	include_once("db_connect.php");

	$fid = $_GET['fid'];
	$qStr = "SELECT DISTINCT username FROM user WHERE uid=$fid;";
	$qRes = $db->query($qStr);

	if ($qRes != FALSE) {
		$qRow = $qRes->fetch();
		$username = $qRow['username'];
	}
	?>


        <div>
          <div class = "other-profile">
            <img class = "other-profile-pic" src = "https://pbs.twimg.com/profile_images/1155645244563742721/tuCu6BT-_400x400.jpg">
            <p class = "other-profile-name"> <?php echo $username; ?> </p>
	           <input type='button' class ='follow-button' id='follow_btn' value = 'Follow' onclick='window.location.href='follow_f.php''>
          </div>

          <hr style = "border: 2px solid black; width: 90%">
          <div class = "music-grid">
            <div class = "music-container">
              <center>
                <h2> Playlists <?php echo $username; ?> listens to </h2>
              <table class = "music-table" cellspacing ="5" cellpadding="10">
                <?php
                $qStr = "SELECT name, username FROM Playlist NATURAL JOIN followplist JOIN user on user.uid = Playlist.owner WHERE followplist.uid = $fid;";
                $qRes = $db->query($qStr);

                if($qRes != FALSE) {

                  //display all rows from user
                  print "<tr><th>Playlist</th><th>Owner</th></tr>";
                  while($row = $qRes->fetch()) {
                    $name = $row['name'];
                    $uname = $row['username'];
                    $str = "<TR><TD>$name</TD><TD>$uname</TD></TR>";
                    print $str;
                  }
                }
                ?>
              </table>
              </center>
            </div>
            <div class = "music-container">
              <center>
                <h2> <?php echo $username; ?> follows </h2>
              <table class = "music-table" cellspacing ="5" cellpadding="10">
                <?php
   $query = "SELECT * FROM follow where uid=$fid;";
   $result = $db->query($query);
   if ($result != FALSE) {
     while ($row = $result->fetch()) {
       $uid = $row['fid'];
       $pStr = "SELECT * FROM user WHERE uid=$uid;";
       $pRes = $db->query($pStr);
       $row = $pRes->fetch();
       $follower = $row['username'];
       $str = "<TR><TD><img class = 'table-pro-pic' src = 'https://pbs.twimg.com/profile_images/1155645244563742721/tuCu6BT-_400x400.jpg'/></TD><TD>$follower</TD></TR>\n";
       print $str;
       }
   }
   ?>
              </table>
              </center>
            </div>
            <div class = "music-container">
              <center>
                <h2> <?php echo $username; ?>'s followers </h2>
              <table class = "music-table" cellspacing ="5" cellpadding="10">
                <?php
		$query = "SELECT * FROM follow where fid=$fid;";
		$result = $db->query($query);
		if ($result != FALSE) {
			while ($row = $result->fetch()) {
				$uid = $row['uid'];
				$pStr = "SELECT * FROM user WHERE uid=$uid;";
				$pRes = $db->query($pStr);
				$row = $pRes->fetch();
				$follower = $row['username'];
				$str = "<TR><TD><img class = 'table-pro-pic' src = 'https://pbs.twimg.com/profile_images/1155645244563742721/tuCu6BT-_400x400.jpg'/></TD><TD>$follower</TD></TR>\n";
				print $str;
				}
		}
		?>

              </table>
              </center>
            </div>
          <div class = "music-container">
            <center>
              <h2> Playlists <?php echo $username; ?> has made </h2>
            <table class = "music-table" cellspacing ="5" cellpadding="10">
              <?php
                $user = $_SESSION['uid'];
                $query = "SELECT name
                          FROM Playlist
                          WHERE owner = $fid;";
                $result = $db->query($query);

                if($result != FALSE) {

                  //display all rows from user
                  while($row = $result->fetch()) {
                    $name = $row['name'];

                    $str = "<tr><td>$name</td></tr>";
                    print $str;
                  }
                }
              ?>
            </table>
            </center>
          </div>
          </div>
        </div>

        <!-- footer -->
        <div class = "footer">
            <center>
                <text class = "copyright"> &copy; BMC inc. | 2020 </text>
            </center>
        </div>
    </body>
</html>
