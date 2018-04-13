<?php
$title = "Athintication System -Profile page";
include_once 'partials/header.php';
include_once 'partials/profControl.php';
?>

<div class="container">
    <div>
        <h1>Profile</h1>
         <div class="row col-lg-3">
            <img src="<?php if(isset($prof_pic)) echo $prof_pic;?>" class="img img-rounded" width="200" style="margin-bottom:10px">
        </div>
        <div class="clearfix"></div>
       <div>
             <?php if(!isset($_SESSION['username'])):?>
        <p class="lead">You are not authorized to view this page <a href="login.php">Login </a>Not yet a member
            <a href="signup.php">Sign up</a></p>
      
        <?php else: ?>

        <section class="col col-lg-7">
            <table class="table table-bordered table-condensed">
                <tr><th style="width:20%">Username: </th><td><?php if(isset($username)) echo $username;?></td></tr>
                <tr><th>Email: </th><td><?php if(isset($email)) echo $email;?></td></tr>
                <tr><th>Join date: </th><td><?php if(isset($date_joined)) echo $date_joined;?></td></tr>
                <tr>
                    <th></th>
                    <td><a class="pull-right" href="edit-profile.php?user_identification=<?php if(isset($encode_id)) echo $encode_id;?>">
                    <span class="glyphicon glyphicon-edit">Edit_profile</span>
                                                         </a>
                                                         </td>
                                                         </tr>
                
            </table>
            <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token();?>">
        </section>
        <?php endif ?>
       </div>
    </div>
</div>
</body>
</html>