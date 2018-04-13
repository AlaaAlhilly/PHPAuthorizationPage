<?php
$title = "User Athintication - Edit profile page";
include_once 'partials/header.php';
include_once 'partials/profControl.php';
include_once 'partials/change_password.php';
include_once 'partials/deactivateControl.php';
if(function_exists('_token')){
    $token=_token();
}
?>

<div class="container">
    <section class="col col-lg-7">
        <h2>Edit Profile</h2>
        <div>
            
            <?php if(isset($result)) echo $result;?>
            <?php if(!empty($form_errors)) echo show_errors($form_errors);?>
           
        </div>
        <div class="clearfix"></div>
        <?php if(!isset($_SESSION['username'])):?>
            <p class="lead">You are not athurized to view this page <a href="index.php">LogIn </a> NOt a member <a href="signup.php">Signup.</a></p>
        <?php else:?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="emailField">Email:</label>
                    <input type="email" name="email" class="form-control" id="emailField" placeholder="Email address"
                           value="<?php if(isset($email)) echo $email;?>">
                </div>
                 <div class="form-group">
                    <label for="usernameField">Username:</label>
                    <input type="text" name="username" class="form-control" id="usernameField" placeholder="Username"
                            value="<?php if(isset($username)) echo $username;?>">
                </div>
                
                <div class="form-group">
                    <label for="profilePicture">avatar</label>
                    <input type="file" name="avatar" id="profilePicture">
                </div>
                <input type="hidden" name="hidden_id" value="<?php if(isset($_GET['user_identification'])) echo $_GET['user_identification'];?>">
                <input type="hidden" name="token" value="<?php if(isset($token)) echo $token;?>">
                <button type="submit" name="updateBtn" class="btn btn-primary pull-right">Update Profile</button>
                
            </form>
            <?php endif ?>
            <br><br>
            <h3>Password Management</h3>
            <hr>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cuPassword">Current Password:</label>
                    <input type="password" name="cupassword" class="form-control" id="cuPassword" placeholder="current password">
                </div>
                 <div class="form-group">
                    <label for="nPassword">New password:</label>
                    <input type="password" name="npassword" class="form-control" id="nPassword" placeholder="new password">
                </div>
                
                <div class="form-group">
                    <label for="cPassword">Confirm new password:</label>
                    <input type="password" name="cpassword" class="form-control" id="cPassword" placeholder="confirm new password">
                </div>
                <input type="hidden" name="hidden_id" value="<?php if(isset($_GET['user_identification'])) echo $_GET['user_identification'];?>">
                <input type="hidden" name="token" value="<?php if(isset($token)) echo $token;?>">
                <button type="submit" name="changePass" class="btn btn-primary pull-right">Change Password</button><br>
                
            </form>  
            <hr>
            <form method="post" action="" enctype="multipart/form-data" >
                
                <input type="hidden" name="hidden_id" value="<?php if(isset($_GET['user_identification'])) echo $_GET['user_identification'];?>">
                <input type="hidden" name="token" value="<?php if(isset($token)) echo $token;?>">
                <button type="submit" name="deleteAccountBtn" onclick="return confirm('do you realy want to deactivate this account?')" class="btn btn-danger btn-block pull-right">Delete Account</button><br>
                
            </form>  
        
        <br><p><a style="font-size:32px"href="profile.php">Back</a></p>
    </section>
    
    
</div>
<?php include_once 'partials/footer.php';?>
</body>
</html>