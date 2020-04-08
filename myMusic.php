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
        <?php
          include_once("db_connect.php");
          if(!empty($_POST['name'])) {
            $public = 0;
            if($_POST['public'] == "public") {
              $public = 1;
            }
            $pname = $_POST['name'];
            $queryUser = "INSERT INTO Playlist VALUES (null ,1, '$pname', $public);";
          	$resultUser = $db->query($queryUser);
          }
          if(!empty($_POST['tname'])) {
            /* Add the track and then update the table connecting tracks to playlists*/
            $tname = $_POST['tname'];
            $link = $_POST['link'];
            $queryUser = "INSERT INTO Tracks VALUES (null ,'$tname', '$link');";
            $resultUser = $db->query($queryUser);
            $autoQuery = "SHOW TABLE STATUS LIKE 'Tracks'";
            $result = $db->query($autoQuery);
            $row = $result->fetch();
            $next_increment = $row['Auto_increment'] - 1;
            $pid = $_POST['pid'];
            $queryUser = "INSERT INTO pid_tid VALUES($pid, $next_increment);";
            $resultUser = $db->query($queryUser);

          }
          if(!empty($_POST['tid'])) {
            $tid = $_POST['tid'];
            $pid = $_POST['pid'];
            $queryUser = "DELETE FROM pid_tid WHERE pid = $pid AND tid = $tid;";
            $resultUser = $db->query($queryUser);
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
                  <a href="user.html" >What's new</a>
                  <a href="#" >My Ensemble</a>
                  <a href="myMusic.php" >My Music</a>
                  <a href="updateProfile.html" >Edit Profile</a>
                </div>
              </div>
           </div>
        </div>
        <!-- modal box for add playlist -->
        <div class = "modal" id ="addBox" onclick = "closeBox(event)">
          <div class = "music-container" id = "addForm" style = "margin-left:auto; margin-right:auto">
            <center>
          <h1> Add playlist </h1>
          <form name="" method = "POST" action="myMusic.php">
            <input class = "inputBox" type = "text" name = "name" placeholder = "name">
            <br />
            <input type="radio" name="public" value="public">
            <label for="male">Public</label><br>
            <input type="radio"  name="public" value="private">
            <label for="female">Private</label><br>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Add playlist ">
          </form>
        </center>
        </div>
        </div>
        <!-- modal box for add track -->
        <div class = "modal" id ="trackBox" onclick = "closeTrackBox(event)">
          <div class = "music-container" id = "trackForm" style = "margin-left:auto; margin-right:auto">
            <center>
          <h1> Add a song </h1>
          <form name="" method = "POST" action="myMusic.php">
            <input class = "inputBox" type = "text" name = "tname" placeholder = "Song name">
            <br />
            <input class = "inputBox" type = "text" name = "link" placeholder = "link">
            <br />
            <?php
            $currID = $_GET['id'];
            print "<input type = 'text' name = 'pid' value = '$currID' style = 'display:none'>";
            ?>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Add Song">
          </form>
        </center>
        </div>
        </div>
        <!-- modal box for delete track -->
        <div class = "modal" id ="trackBoxD" onclick = "closeTrackBoxD(event)">
          <div class = "music-container" id = "trackFormD" style = "margin-left:auto; margin-right:auto; height: 200px;">
            <center>
          <h1> Delete this song? </h1>
          <form name="" method = "POST" action="myMusic.php">
            <input id = "deleteID" class = "inputBox" type = "text" name = "tid" value ="" style = "display:none">
            <?php
            $currID = $_GET['id'];
            print "<input type = 'text' name = 'pid' value = '$currID' style = 'display:none'>";
            ?>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Delete Song">
          </form>
        </center>
        </div>
        </div>
        <!-- Users lists of playlists  -->
        <div class = "playlist-grid">
          <div class = "music-container">
            <center>
                <h1 style = "display: inline;"> Playlists </h1>
                <p style = "display: inline; font-size: 20px; cursor:pointer;" onclick = "showAdd()"> + </p>
              <?php
                $owner =  $_SESSION['uid'];
                $qStr = "SELECT name, pid FROM Playlist WHERE owner = $owner;";
                $qRes = $db->query($qStr);

                if($qRes != FALSE) {

                  //display all rows from user

                  while($row = $qRes->fetch()) {
                    $name = $row['name'];
                    $pid = $row['pid'];
                    $link = "myMusic.php?&id=" . $pid;
                    $str = "<a class = 'playlist-link' href ='$link'>$name</a></br>";
                    print $str;
                  }
                }
                ?>
              </center>
            </center>
          </div>
          <div class = "music-container">
            <center>
              <?php
                  $currID = $_GET['id'];
                  $qStr = "SELECT name FROM Playlist WHERE pid = $currID";
                  $qRes = $db->query($qStr);
                  if($qRes != FALSE) {
                    $row = $qRes->fetch();
                    $name = $row['name'];
                    print " <h1 style = 'display: inline;'> $name</h1>
                            <p style = 'display: inline; font-size: 20px; cursor:pointer;' onclick = 'showTrack()'> + </p></br>";
                  }
                  else {
                    print "<h1> Select a playlist </h1>";
                  }
              ?>
            <table class = "music-table" cellspacing ="5" cellpadding="10">
              <?php
                $currID = $_GET['id'];
                $qStr = "SELECT Name, tid FROM Tracks NATURAL JOIN pid_tid WHERE pid = $currID";
                $qRes = $db->query($qStr);
                if($qRes != FALSE) {

                  //display all rows from user

                  while($row = $qRes->fetch()) {
                    $name = $row['Name'];
                    $tid = $row['tid'];
                    $str = "<TR><TD>$name</TD><TD><i  style = 'cursor:pointer' class='fa fa-trash-o' onclick='deleteSong($tid)'></i></TD></TR>\n";
                    print $str;
                    }
                }
                ?>
            </table>
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
        function showAdd() {
          document.getElementById("addBox").style.display = "block";
        }
        function closeBox(e) {
          if(e.target.id == "addBox") {
            document.getElementById("addBox").style.display = "none";
          }
        }
        function showTrack() {
          document.getElementById("trackBox").style.display = "block";
        }
        function closeTrackBox(e) {
          if(e.target.id == "trackBox") {
            document.getElementById("trackBox").style.display = "none";
          }
        }
        function deleteSong(tid) {
          document.getElementById("deleteID").value = tid;
          document.getElementById("trackBoxD").style.display = "block";
        }
        function closeTrackBoxD(e) {
          if(e.target.id == "trackBoxD") {
            document.getElementById("trackBoxD").style.display = "none";
          }
        }
    </script>
</html>
