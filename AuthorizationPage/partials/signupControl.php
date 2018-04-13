<?php
include_once('resources/Database.php');
include_once('resources/utilities.php');
include_once 'resources/send-email.php';

//process the form

if(isset($_POST['signupBtn'],$_POST['token'])){
    
    if(isValidToken($_POST['token'])){
        //array of errors
    $form_errors = array();

    //Form validation
    $required_fields = array('email','username','password');
    
     
    //call the function to check for empty fields and merege data with form_errors array
    $form_errors = array_merge($form_errors,check_empty_fields($required_fields));
    
    //fields that required for minimum length check
    $field_to_check_length = array('username'=>4,'password'=>6);
    
    //call the function to check for minimum required length and merege data with form_errors array
    $form_errors = array_merge($form_errors,check_min_length($field_to_check_length));
    
    //email validation/merege data with form_errors array
    $form_errors = array_merge($form_errors,check_email($_POST));
    
    //check if error array is empty, if yes process form data and insert record
    if(empty($form_errors)){
        $name = $_POST['username'];
        $email=$_POST['email'];
        $fields = array($_POST['username'],$_POST['email'],password_hash($_POST['password'],PASSWORD_DEFAULT));
        $msg = sqlInsert_func('users',$fields,$db);
        if($msg=="pass"){
            $user_id = $db->lastinsertId();
            $encoded_iden = base64_encode("encodeduserid{$user_id}");
            $body='
                <html>
                    <body style="background-color:#cccccc;color:#000;font-family:Arial,Helvetica,sans-serif;line-height:1.8em;">
                    
                        <h2>User Athintication System</h2>
                        <p>Dear'.$name.'<br><br>Thank you for registering,please click on the link to activate your email address</p>
                        <p><a href="http://alhilly.com/alaa/activate.php?id='.
                        $encoded_iden.'">Confirm Email</a></p>
                        <p><strong>&copy;2016 Alaa Design</strong></p>
                    </body>
                </html>
            ';
            $mail->addAddress($email,$username);
            $mail->Subject="Message from Alaa Athintication System";
            $mail->Body=$body;
            if(!$mail->Send()){
                $result="<script type=\"text/javascript\">
                swal(\"Error\",\"Email sending failed:$mail->ErrorInfo\",\"error\");</script>";
            }else{
            $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Congragulations $name!\",   text: \"You are now a member in our family.please check your email for confirmation.\",type:'success',   timer: 6000,   showConfirmButton: false });
                setTimeout(function(){window.location.href = 'index.php';},5000);
        </script>";}
        }
        else $result = flashMessage("Registeration Faild");
 }else{
      if(count($form_errors)==1)
      {
        $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"There was an error in the form.\",type:'error',   timer: 6000,   showConfirmButton: false });
        </script>";      }else{
          $er=count($form_errors);
          $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"There were $er error in the form.\",type:'error',   timer: 6000,   showConfirmButton: false });
        </script>";      }
 }
 }else{
        $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Warning!\",   text: \"This might be an attack,from someone stolen your id.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
    }
    
    
}else if(isset($_GET['id'])){
    
    $encoded_id=$_GET['id'];
    $decoded_id=base64_decode($encoded_id);
    $user_id=explode("encodeduserid",$decoded_id);
    $id=$user_id[1];
    
    $sqlUpdate = "UPDATE users SET activated=:activated WHERE id=:id AND activated='0'";
    $statement = $db->prepare($sqlUpdate);
    $statement->execute(array(':activated'=>"1",':id'=>$id));
    if($statement->rowCount()==1){
        $result = '<h2>Email Confirmed</h2>
        <p>your email activated successfully you can now <a href="login.php">Login</a> using your email and password.</p>';
    }else{
        $result="<p class='lead'>No changes have been made,please contact site manager if
        you confirmed your email before</p>";
    }
}