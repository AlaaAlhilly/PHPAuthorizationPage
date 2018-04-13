<?php include_once 'resources/session.php';?>
<?php include_once 'resources/utilities.php';?>
<?php include_once 'resources/Database.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
      
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/costum.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/sweetalert.css">
        <script src="js/sweetalert.min.js"></script>
        <title><?php if(isset($title)) echo $title;?></title>
      </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Athintication System</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
              <?php if((isset($_SESSION['username']))||isCookieValid($db)):?>
            <li><a href="logout.php">LogOut</a></li>
            <li><a href="profile.php?id=<?php 
                $txt="encodeduserid";
                if(isset($_SESSION['user_id'])){
                $encoded_id=base64_encode($txt.$_SESSION['id']); 
                echo $encoded_id;
                }
                ?>">My Profile</a></li>
              <?php else: ?>
            <li><a href="login.php">LogIn</a></li>
            <li><a href="signup.php">SignUp</a></li>
              <?php endif ?>
              <i class="hide">
              <?php $n= guard();?></i>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
