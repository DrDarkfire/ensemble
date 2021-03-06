<?php
//Author: Chase Tiberi
session_start();
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
          if(!empty($_GET['id'])) {
            $pid = $_GET['id'];
            $query = "UPDATE Playlist SET plays = plays + 1 WHERE  pid = $pid;";
            $result = $db->query($query);
          }
          if(!empty($_POST['name'])) {
            $public = 0;
            if($_POST['public'] == "public") {
              $public = 1;
            }
            $uid = $_SESSION['uid'];
            $pname = $_POST['name'];
            $queryUser = "INSERT INTO Playlist VALUES (null ,$uid, '$pname', $public, 0, null);";
          	$resultUser = $db->query($queryUser);
          }
          else if(!empty($_POST['tname'])) {
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
          else if(!empty($_POST['tid'])) {
            $tid = $_POST['tid'];
            $pid = $_POST['pid'];
            $queryUser = "DELETE FROM pid_tid WHERE pid = $pid AND tid = $tid;";
            $resultUser = $db->query($queryUser);
          }
          else if(!empty($_POST['dpid'])) {
            $pid = $_POST['dpid'];
            $user = $_SESSION['uid'];
            $checkQuery = "SELECT owner FROM Playlist WHERE pid = $pid";
            $resultCheck = $db->query($checkQuery);
            $row = $resultCheck->fetch();
            $owner = $row['owner'];
            if($user == $owner) {
              $queryOne = "DELETE FROM pid_tid WHERE pid = $pid;";
              $queryTwo = "DELETE FROM Playlist WHERE pid = $pid;";
              $resultOne = $db->query($queryOne);
              $resultTwo = $db->query($queryTwo);
            }
            else {
              $queryOne = "DELETE FROM followplist WHERE pid = $pid AND uid = $user;";
              $resultOne = $db->query($queryOne);
            }

          }
          else if(!empty($_POST['newList'])) {
            $public = 0;
            if($_POST['public'] == "public") {
              $public = 1;
            }
            $uid = $_SESSION['uid'];
            $pname = $_POST['newList'];
            $queryUser = "INSERT INTO Playlist VALUES (null ,$uid, '$pname', $public,0, null);";
          	$resultUser = $db->query($queryUser);
            $autoQuery = "SHOW TABLE STATUS LIKE 'Playlist'";
            $result = $db->query($autoQuery);
            $row = $result->fetch();
            $next_increment = $row['Auto_increment'] - 1;
            $p1 = $_POST['p1'];
            $p1query = "SELECT tid FROM pid_tid WHERE pid = $p1";
            $p1result = $db->query($p1query);
            while($row = $p1result->fetch()) {
              $currtid = $row['tid'];
              $trackQuery = "INSERT INTO pid_tid VALUES ($next_increment, $currtid)";
              $trackQuery = $db->query($trackQuery);
            }
            $p2 = $_POST['p2'];
            $p2query = "SELECT tid FROM pid_tid WHERE pid = $p2";
            $p2result = $db->query($p2query);
            while($row = $p2result->fetch()) {
              $currtid = $row['tid'];
              $trackQuery = "INSERT INTO pid_tid VALUES ($next_increment, $currtid)";
              $trackQuery = $db->query($trackQuery);
            }
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
                  <a href="profile.php" >My Ensemble</a>
                  <a href="myMusic.php" >My Music</a>
                  <a href="updateProfile.php" >Edit Profile</a>
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
            <label for="public">Public</label><br>
            <input type="radio"  name="public" value="private">
            <label for="private">Private</label><br>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Add playlist ">
          </form>
        </center>
        </div>
        </div>
        <!-- modal box for add track -->
        <div class = "modal" id ="trackBox" onclick = "closeTrackBox(event)">
          <div class = "music-container" id = "trackForm" style = "margin-left:auto; margin-right:auto; height: 450px;">
            <center>
          <h1> Add a song </h1>
          <form name="" method = "POST" action="myMusic.php">
            <input class = "inputBox" type = "text" name = "tname" placeholder = "Song name">
            <br />
            <div>
            </div>
            <input class = "inputBox" type = "text" name = "link" placeholder = "link">
            <br />
            <?php
            $currID = $_GET['id'];
            print "<input type = 'text' id ='currpid' name = 'pid' value = '$currID' style = 'display:none'>";
            ?>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Add Song">
          </form>

          <button class = "main-button" style = "display:inline" onClick = "showYou()">Click for youtube instructions</button>
          <button class = "main-button" style = "display:inline" onClick = "showCloud()">Click for soundcloud instructions</button>
          <div style = "display:none" id = "youIns">
           </br>
            <p>1. Go to the youtube video you want to add and click share</p>
            </br>
            <p>2. From there click on "embed" and copy the link located after src and between the two "</p>
            </br>
            <p>3. paste this link into the link input box above</p>
            </br>
            <p>4. add ?autoplay=1 to the end of your link.</p>
          </div>
          <div style = "display:none" id = "cloudIns">
           </br>
            <p>1. Go to the soundcloud song you want to add and click share</p>
            </br>
            <p>2. From there click on "embed" and copy the link located after src and between the two "</p>
            </br>
            <p>3. paste this link into the link input box above</p>
            </br>
            <p>4. change the autoplay=false to autoplay=true</p>
          </div>
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
        <!-- modal box for delete playlist -->
        <div class = "modal" id ="playDelete" onclick = "closePlayBox(event)">
          <div class = "music-container" id = "playFormD" style = "margin-left:auto; margin-right:auto; height: 200px;">
            <center>
          <h1> Delete this playlist? </h1>
          <form name="" method = "POST" action="myMusic.php">
            <input type = 'text' name = 'dpid' id ="deleteList" value = '' style = 'display:none'>;
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Delete Playlist">
          </form>
        </center>
        </div>
        </div>
        <!-- modal box for merge playlists -->
        <div class = "modal" id ="playMerge" onclick = "closeMergeBox(event)">
          <div class = "music-container" id = "mergeForm" style = "margin-left:auto; margin-right:auto; height: 350px;">
            <center>
          <h1> Merge Playlists </h1>
          <form name="" method = "POST" action="myMusic.php">
            <select name ="p1" style = "margin: 20px;">
            <?php
              $owner =  $_SESSION['uid'];
              $qStr = "SELECT name, pid FROM Playlist WHERE owner = $owner;";
              $qRes = $db->query($qStr);

              if($qRes != FALSE) {

                //display all rows from user

                while($row = $qRes->fetch()) {
                  $name = $row['name'];
                  $pid = $row['pid'];
                  $str = "<option value = '$pid'>$name</option>";
                  print $str;
                }
              }

              $qStr = "SELECT name, pid FROM Playlist NATURAL JOIN followplist WHERE uid = $owner;";
              $qRes = $db->query($qStr);

              if($qRes != FALSE) {

                //display all rows from user

                while($row = $qRes->fetch()) {
                  $name = $row['name'];
                  $pid = $row['pid'];
                  $str = "<option value = '$pid'>$name</option>";
                  print $str;
                }
              }
              ?>
            </select> </br>
            <select name ="p2">
            <?php
              $owner =  $_SESSION['uid'];
              $qStr = "SELECT name, pid FROM Playlist WHERE owner = $owner;";
              $qRes = $db->query($qStr);

              if($qRes != FALSE) {

                //display all rows from user

                while($row = $qRes->fetch()) {
                  $name = $row['name'];
                  $pid = $row['pid'];
                  $str = "<option value = '$pid'>$name</option>";
                  print $str;
                }
              }

              $qStr = "SELECT name, pid FROM Playlist NATURAL JOIN followplist WHERE uid = $owner;";
              $qRes = $db->query($qStr);

              if($qRes != FALSE) {

                //display all rows from user

                while($row = $qRes->fetch()) {
                  $name = $row['name'];
                  $pid = $row['pid'];
                  $str = "<option value = '$pid'>$name</option>";
                  print $str;
                }
              }
              ?>
            </select> </br>
            <input type = 'text' name = 'newList' class = "inputBox" placeholder ="Merged Playlist name"/>
            </br>
            <input type="radio" name="public" value="public">
            <label for="public">Public</label><br>
            <input type="radio"  name="public" value="private">
            <label for="private">Private</label><br>
            <input style = "width: 50%;" class ="submit-button main-button" type = "submit" value = "Merge Playlists">
          </form>
        </center>
        </div>
        </div>
        <!-- Users lists of playlists  -->
        <div class = "playlist-grid">
          <div class = "music-container">
            <center>
                <h1 style = "display: inline;"> Playlists </h1>
                <i  style = 'display:inline; margin:5px; cursor:pointer' class='fa fa-plus' onclick='showAdd()'></i>
                <i class = 'fa fa-exchange' style = 'display:inline; margin:5px; cursor:pointer' onclick = 'openMerge()'></i>
                <table class = "music-table" cellspacing ="5" cellpadding="10">
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
                    $str = "<TR><TD><a class = 'playlist-link' href ='$link'>$name</a></TD><TD><i  style = 'cursor:pointer' class='fa fa-trash-o' onclick='deletePlaylist($pid)'></TD></TR>";
                    print $str;
                  }
                }

                $qStr = "SELECT name, pid FROM Playlist NATURAL JOIN followplist WHERE uid = $owner;";
                $qRes = $db->query($qStr);

                if($qRes != FALSE) {

                  //display all rows from user

                  while($row = $qRes->fetch()) {
                    $name = $row['name'];
                    $pid = $row['pid'];
                    $link = "myMusic.php?&id=" . $pid;
                    $str = "<TR><TD><a class = 'playlist-link' href ='$link'>$name</a></TD><TD><i  style = 'cursor:pointer' class='fa fa-trash-o' onclick='deletePlaylist($pid)'></TD></TR>";
                    print $str;
                  }
                }
                ?>
                </table>
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

                            <i class = 'fa fa-play' style = 'display:inline; margin:20px; cursor:pointer' onclick = 'openVideoPlayer()'></i>";

                            $user = $_SESSION['uid'];
                            $pid = $_GET['id'];
                            $checkQuery = "SELECT owner FROM Playlist WHERE pid = $pid";
                            $resultCheck = $db->query($checkQuery);
                            $row = $resultCheck->fetch();
                            $owner = $row['owner'];
                            if($user == $owner) {
                            print " <p style = 'display: inline; font-size: 20px; cursor:pointer;' onclick = 'showTrack()'> + </p>";
                            }
                    print " </br><i class = 'fa fa-backward trackController' id ='prevTrack' style = 'display: none' onclick = 'changeTrack(0)'> </i>
                            <i class = 'fa fa-forward trackController' id = 'nextTrack' style = 'display: none' onclick = 'changeTrack(1)'> </i>";
                  }
                  else {
                    print "<h1> Select a playlist </h1>";
                  }
              ?>
            <table class = "music-table" cellspacing ="5" cellpadding="10">
              <?php
                $currID = $_GET['id'];
                $qStr = "SELECT Name, tid, Link FROM Tracks NATURAL JOIN pid_tid WHERE pid = $currID";
                $qRes = $db->query($qStr);
                if($qRes != FALSE) {

                  //display all rows from user
                  $currSong = 1;
                  while($row = $qRes->fetch()) {
                    $name = $row['Name'];
                    $tid = $row['tid'];
                    $link = $row['Link'];
                    $songID = "song" . $currSong;
                    $songLinkID = "songL" . $currSong;
                    $songTidID = "songID" . $currSong;
                    $str = "<TR><TD id ='$songID'>$name</TD><TD style ='display:none' id ='$songLinkID'>$link</TD><TD style ='display:none' id ='$songTidID'>$tid</TD><TD><i  style = 'cursor:pointer' class='fa fa-trash-o' onclick='deleteSong($tid)'></i></TD></TR>\n";
                    print $str;
                    $currSong++;
                    }
                }
                ?>
            </table>
          </div>
          <div id = "iframeDiv" style = "display:none; font-size: 30px">
            <center>
            <iframe id = "framePlayer" allow="autoplay" src = ""></iframe>
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
        function showAdd() {
          document.getElementById("addBox").style.display = "block";
        }
        /*hide the add playlist modal */
        function closeBox(e) {
          if(e.target.id == "addBox") {
            document.getElementById("addBox").style.display = "none";
          }
        }
        /*show the modal to add a track */
        function showTrack() {
          document.getElementById("trackBox").style.display = "block";
        }
        /*hide the modal to add a track  */
        function closeTrackBox(e) {
          if(e.target.id == "trackBox") {
            document.getElementById("trackBox").style.display = "none";
          }
        }
        /*show the modal to delete a song*/
        function deleteSong(tid) {
          document.getElementById("deleteID").value = tid;
          document.getElementById("trackBoxD").style.display = "block";
        }
        /*hide the modal to delete a song  */
        function closeTrackBoxD(e) {
          if(e.target.id == "trackBoxD") {
            document.getElementById("trackBoxD").style.display = "none";
          }
        }
        /*open delete playlist modal */
        function deletePlaylist(pid) {
          document.getElementById("deleteList").value = pid;
          document.getElementById("playDelete").style.display = "block";
        }
        /*hide delete playlist modal */
        function closeTrackBoxD(e) {
          if(e.target.id == "playDelete") {
            document.getElementById("playDelete").style.display = "none";
          }
        }
        /*open merge playlist modal */
        function openMerge() {
          document.getElementById("playMerge").style.display = "block";
        }
        /*hide delete playlist modal */
        function closeMergeBox(e) {
          if(e.target.id == "playMerge") {
            document.getElementById("playMerge").style.display = "none";
          }
        }
        /*keep track of track playing */
        var currentTrack = 1;
        /*open the video player on play  */
        function openVideoPlayer() {
          var songId = "songL" + currentTrack;
          var firstSrc = document.getElementById(songId).innerHTML;
          document.getElementById("framePlayer").src = firstSrc;
          document.getElementById("iframeDiv").style.display = "block";
          updateVideoPlayer();
        }
        /* adds necessary icons */
        function updateVideoPlayer() {
          var prevTrack = "song" + (currentTrack -1);
          var nextTrack = "song" + (currentTrack + 1);
          var myPrevTrack = document.getElementById(prevTrack);
          var myNextTrack = document.getElementById(nextTrack);
          if(myPrevTrack ){
            document.getElementById("prevTrack").style.display = "inline";
          }
          else {
            if (document.getElementById("prevTrack").style.display == "inline") {
               document.getElementById("prevTrack").style.display = "none";
            }
          }
          if(myNextTrack ){
            document.getElementById("nextTrack").style.display = "inline";
          }
          else {
            if (document.getElementById("nextTrack").style.display == "inline") {
               document.getElementById("nextTrack").style.display = "none";
            }
          }
        }

        /*moves foward or back based on click */
        function changeTrack(flag) {
          if(flag == 0) {
            currentTrack -= 1;
          }
          else {
            currentTrack += 1;
          }
          var songId = "songL" + (currentTrack);
          var firstSrc = document.getElementById(songId).innerHTML;
          document.getElementById("framePlayer").src = firstSrc;
          document.getElementById("framePlayer").src =   document.getElementById("framePlayer").src;
          updateVideoPlayer();
        }

        /* functions to show instructions*/
        /*  show youtube instructions */
        function showYou() {
          document.getElementById("youIns").style.display = "block";
          document.getElementById("cloudIns").style.display = "none";
        }
        /*show soundcloud instructions */
        function showCloud() {
          document.getElementById("youIns").style.display = "none";
          document.getElementById("cloudIns").style.display = "block";
        }

    </script>
</html>
