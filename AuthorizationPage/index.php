<?php

$title = "Athintication System -Homepage";
include_once 'partials/header.php';
?>
<div class="container">

      <div class="flag">
          
        <h1>User Authentication System</h1>
        <p class="lead">A System created to serve your need for Login and registration system<br> with all security and activation provided and required in the market.</p>
          <?php if(!isset($_SESSION['username'])):?>
        <p class="lead">You are currently not signing in <a href="login.php">Login</a> Not a member? <a href="signup.php">Signup</a></p>
        <?php else:?>
        <p class="lead">You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username'];?>
            <a href="logout.php"> Logout.</a></p>
        <?php endif?>
      </div>

    </div>
    <?php include_once 'partials/footer.php';?>
    </body>
</html>