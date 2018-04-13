<?php

$title = 'Athintication System -Reset Password';
include_once 'partials/header.php';
include_once 'partials/resetpasswordControl.php';

?>

     <div class="container">
      <section class="col-lg-7">
        <h1>User Authentication System</h1><hr>
        
        <h2>Reset Password</h2>
        
        <?php if(isset($result)) echo $result;?>
        <?php if(!empty($form_errors)) {echo show_errors($form_errors);echo "</div>";}?>
        <div class="clearFix"></div>
         <form method="post">
             <div class="form-group">
                <label for="emailField">Email:</label>
                <input type="email" class="form-control" id="emailField" name="email" placeholder="Username">
            </div>
             <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token();?>">
             <button type="submit" name="resetBtn" class="btn btn-default pull-right">Submit</button>
            
            <p><a href="index.php">Back</a></p>
        </form>
     </section>
    </div>

    <?php include_once 'partials/footer.php'; ?>
    </body>
</html>