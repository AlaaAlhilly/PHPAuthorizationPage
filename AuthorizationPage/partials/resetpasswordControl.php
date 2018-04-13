<?php
include_once('resources/Database.php');
include_once('resources/utilities.php');
include_once 'resources/send-email.php';

//process the form

if(isset($_POST['resetBtn'],$_POST['token'])){
    
    if(isValidToken($_POST['token'])){
        //array of errors
    $form_errors = array();

    //Form validation
    $required_fields = array('email');
    
     
    //call the function to check for empty fields and merege data with form_errors array
    $form_errors = array_merge($form_errors,check_empty_fields($required_fields));
    
    //fields that required for minimum length check
    //$field_to_check_length = array('password'=>6,'confirm_password'=>6);
    
    //call the function to check for minimum required length and merege data with form_errors array
    //$form_errors = array_merge($form_errors,check_min_length($field_to_check_length));
    
    //email validation/merege data with form_errors array
    $form_errors = array_merge($form_errors,check_email($_POST));
    
    //check if error array is empty, if yes process form data and insert record
    if(empty($form_errors)){
        if(search_data('users','email',$_POST['email'],$db)=="pass"){
            $statement = sqlSearch('users','email',$_POST['email'],$db);
            if($row=$statement->fetch()){
                $id=$row['id'];
                $name=$row['username'];
                $email=$row['email'];
                $encoded_iden=base64_encode("encodeduserid{$id}");
                            $body='
                                <html>
                                    <body style="background-color:#cccccc;color:#000;font-family:Arial,Helvetica,sans-serif;line-height:1.8em;">

                                        <h2>User Athintication System</h2>
                                        <p>Dear'.$name.'<br><br>You request a link to reset your password,please click on the link to reset the password.</p>
                                        <p><a href="http://alhilly.com/alaa/recover_password.php?id='.
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
                                swal({   title: \"Confirm!\",   text: \"An reset link sent back to your email check your inbox.\",type:'success',   timer: 6000,   showConfirmButton: false });
                                setTimeout(function(){window.location.href = 'index.php';},5000);
                        </script>";
                        }
                    }
        }else{
            $result = FlashMessage("Email doesn't belong to any user.");
        }
 }else{
      if(count($form_errors)==1)
      {
           $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"There was an error in the form.\",type:'error',   timer: 6000,   showConfirmButton: false });
        </script>";
      }else{
          $er=count($form_errors);
          $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"There were $er error in the form.\",type:'error',   timer: 6000,   showConfirmButton: false });
        </script>";
      }
 }
    }else{
         $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Warning!\",   text: \"This might be an attack,from someone stolen your id.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
    }
}