<?php
  session_start();
  $_SESSION['uid'] = 1;
?>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name = "Sample Site">
        <title>Ensemble</title>
        <link rel="stylesheet" type="text/css" href="ensemble.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
      <?php include_once("db_connect.php");
        if(!empty($_POST['pid'])) {
          $user = $_SESSION['uid'];
          $pid = $_POST['pid'];
          $query = "INSERT INTO followplist VALUES ($user, $pid);";
          $result = $db->query($query);
        }
        ?>
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
                  <a href="#" >My Ensemble</a>
                  <a href="myMusic.php" >My Music</a>
                  <a href="updateProfile.php" >Edit Profile</a>
                </div>
              </div>
           </div>
        </div>
        <!-- modal box for add playlist -->
        <div class = "modal" id ="playAdd" onclick = "closeBox(event)">
          <div class = "music-container" id = "playForm" style = "margin-left:auto; margin-right:auto; height: 200px;">
            <center>
          <h1> Add this to My Music? </h1>
          <form name="" method = "POST" action="user.php">
            <input type = 'text' id ="pidinput" name = 'pid' value = '' style = 'display:none'>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Add to My Music ">
          </form>
        </center>
        </div>
        </div>
        <!-- User what's new page contianing what their friends have been listening to -->
        <center style ="color: #333">
          <h1> See what everyone is listening to! </h1>
        </center>
        <div class = "music-grid">
          <div class = "music-container">
            <center>
              <h2> Popular on Ensemble </h2>
              <table class = "music-table" cellspacing ="5" cellpadding="10">
                <?php
                  $user = $_SESSION['uid'];
                  $query = "SELECT Playlist.name, ensemble.name as user, plays, pid
                            FROM Playlist JOIN ensemble ON owner = uid
                            WHERE uid <> $user AND public = 1
                            ORDER BY plays DESC;";
                  $result = $db->query($query);

                  if($result != FALSE) {

                    //display all rows from user
                    print "<tr><th>Playlist</th><th>Owner</th><th>plays</th></tr>";
                    while($row = $result->fetch()) {
                      $name = $row['name'];
                      $own = $row['user'];
                      $plays = $row['plays'];
                      $pid = $row['pid'];
                      $str = "<tr><td>$name</td><td>$own</td><td>$plays</td><td><i  style = 'cursor:pointer' class='fa fa-plus' onclick='addPlaylist($pid)'></i></td></tr>";
                      print $str;
                    }
                  }
                ?>
              </table>
            </center>
          </div>
          <div class = "music-container">
            <center>
            <h2> Popular with your friends </h2>
            <table class = "music-table" cellspacing ="5" cellpadding="10">
              <?php
                $user = $_SESSION['uid'];
                $query = "SELECT Playlist.name, ensemble.name as user, plays, pid
                          FROM (SELECT fid
	                              FROM friends
	                              WHERE uid = $user) AS A
                          JOIN Playlist on fid = owner
                          JOIN ensemble on fid = uid
                          WHERE public = $user
                          ORDER BY plays DESC;";
                $result = $db->query($query);

                if($result != FALSE) {

                  //display all rows from user
                  print "<tr><th>Playlist</th><th>Owner</th><th>plays</th></tr>";
                  while($row = $result->fetch()) {
                    $name = $row['name'];
                    $own = $row['user'];
                    $plays = $row['plays'];
                    $pid = $row['pid'];
                    $str = "<tr><td>$name</td><td>$own</td><td>$plays</td><td><i  style = 'cursor:pointer' class='fa fa-plus' onclick='addPlaylist($pid)'></td></tr>";
                    print $str;
                  }
                }
              ?>
            </table>
            </center>
          </div>
          <div class = "music-container">
            <center>
            <h2> Friends newly created playlists </h2>
            <table class = "music-table" cellspacing ="5" cellpadding="10">
              <?php
                $user = $_SESSION['uid'];
                $query = "SELECT Playlist.name, ensemble.name as user, pid
                          FROM (SELECT fid
                                FROM friends
                                WHERE uid = $user) AS A
                          JOIN Playlist on fid = owner
                          JOIN ensemble on fid = uid
                          WHERE public = $user
                          ORDER BY created DESC;";
                $result = $db->query($query);

                if($result != FALSE) {

                  //display all rows from user
                  print "<tr><th>Playlist</th><th>Owner</th></tr>";
                  while($row = $result->fetch()) {
                    $name = $row['name'];
                    $own = $row['user'];
                    $pid = $row['pid'];
                    $str = "<tr><td>$name</td><td>$own</td><td><i  style = 'cursor:pointer' class='fa fa-plus' onclick='addPlaylist($pid)'></td></tr>";
                    print $str;
                  }
                }
              ?>
            </table>
            </center>
          </div>
          <div class = "music-container">
            <center>
            <h2> Music you might like </h2>
            <table class = "music-table" cellspacing ="5" cellpadding="10">
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
              <TR><TD>Fake Song</TD></TR>
            </table>
            </center>
          </div>

        </div>
        <!-- footer -->
        <div class = "footer">
            <center>
                <text class = "copyright"> &copy; BMC inc. | 2020 </text>
            </center>
        </div>
    </body>
    <script>
    /*show the add playlist modal */
    function addPlaylist(tid) {
      document.getElementById("playAdd").style.display = "block";
      document.getElementById("pidinput").value = tid;
    }
    function closeBox(e) {
      if(e.target.id == "playAdd") {
        document.getElementById("playAdd").style.display = "none";
      }
    }
    </script>
</html>
