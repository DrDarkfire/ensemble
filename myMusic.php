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
        <!-- User what's new page contianing what their friends have been listening to -->
        <div class = "music-grid">
          <div class = "music-container">
            <center>
                <h1> Your playlists </h1>
              <select onchange="changeInfo()" id ="trackSelect">
              <option value ="none" disabled selected = "selected"> please select one </option>
              <?php

                $qStr = "SELECT name, pid FROM Playlist WHERE owner = 1;";
                $qRes = $db->query($qStr);

                if($qRes != FALSE) {

                  //display all rows from user

                  while($row = $qRes->fetch()) {
                    $name = $row['name'];
                    $pid = $row['pid'];
                    $str = "<option value ='$pid'>$name</option>\n";
                    print $str;
                  }
                }
                ?>
                </select>
                <center>
                  <?php
                      $currID = $_GET['id'];
                      $qStr = "SELECT name FROM Playlist WHERE pid = $currID";
                      $qRes = $db->query($qStr);
                      if($qRes != FALSE) {
                        $row = $qRes->fetch();
                        $name = $row['name'];
                        print "<h1> $name </h1>";
                      }
                  ?>
                <table class = "music-table" cellspacing ="5" cellpadding="10">
                  <?php
                    $currID = $_GET['id'];
                    $qStr = "SELECT Name FROM Tracks NATURAL JOIN pid_tid WHERE pid = $currID";
                    $qRes = $db->query($qStr);
                    if($qRes != FALSE) {

                      //display all rows from user

                      while($row = $qRes->fetch()) {
                        $name = $row['Name'];
                        $str = "<TR><TD>$name</TD></TR>\n";
                        print $str;
                        }
                    }
                    ?>
                </table>
              </center>
            </center>
          </div>
          <div id = "addPlaylist" class = "music-container" >
            <center>
                <h1> Add playlists </h1>
              <form name="" method = "POST" action="myMusic.php">
                <input class = "inputBox" type = "text" name = "name" placeholder = "name">
                <br />
                <input type="radio" name="public" value="public">
                <label for="male">Public</label><br>
                <input type="radio"  name="public" value="private">
                <label for="female">Private</label><br>
                <input style = "width: 80%;" class ="submit-button main-button" type = "submit" value = "Add playlist ">
              </form>
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
        function changeInfo() {
          var currID = document.getElementById("trackSelect");
          var value = currID.options[currID.selectedIndex].value;
          var str = "&id=" + value;
          window.location.search += str;
        }
    </script>
</html>
