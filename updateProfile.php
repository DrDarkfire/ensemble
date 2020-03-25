<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name = "Sample Site">
        <title>Ensemble</title>
        <link rel="stylesheet" type="text/css" href="ensemble.css">

    </head>
    <body>
        <!-- Top bar of the webpage, contains logo -->
        <div class = "topBar">
            <a href = "index.html">
            <img src="logo.jpeg" class = "icon"/> </a>
            <text class = "barText">Ensemble</text>
        </div>
        <!-- Main portion of this page contianing the form to sign-up -->
        <div class = "input">
            <center>
               <div class = "form-container">
                 <h1 style = "color:#333"> Create an account </h1>
                 <form name="sign-in" method = "POST" action="">
                   <input class = "inputBox"type = "text" name ="username" placeholder = "username">
                   <br />
                   <input class = "inputBox" type = "password" name = "password" placeholder = "password">
                   <br />
                   <input class = "inputBox"type = "password" name ="re-password" placeholder = "re-enter password">
                   <br />
                   <input class = "inputBox" type = "text" name = "email" placeholder = "email">
                   <br />
                   <input class = "inputBox" type = "text" name = "bdate" placeholder = "Birth Day" onfocus="(this.type='date')">
                   <br />
                   <input class ="submit-button main-button" type = "submit" value = "Join the Ensemble">
                 </form>
                 <hr class = "form-divider">
                 <div class = "link-container">
                    <a class = "input-link" href = "login.html">Already have an account? Click here to sign in! </a>
                 </div>
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
<?php
?>
