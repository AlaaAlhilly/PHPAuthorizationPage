<?php

$title = 'Athintication System -Recover Password';
include_once 'partials/header.php';
include_once 'partials/recoverControl.php';

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
                <label for="passwordField">Password:</label>
                <input type="password" class="form-control" id="passwordField" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="CpasswordField">Confirm Password:</label>
                <input type="password" class="form-control" id="CpasswordField" name="confirm_password" placeholder="Password">
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