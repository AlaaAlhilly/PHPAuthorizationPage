<?php
include_once('resources/Database.php');
include_once('resources/utilities.php');


//process the form

if(isset($_POST['resetBtn'],$_POST['token'])){
    
    if(isValidToken($_POST['token'])){
         //array of errors
            $form_errors = array();

            //Form validation
            $required_fields = array('password','confirm_password');


            //call the function to check for empty fields and merege data with form_errors array
            $form_errors = array_merge($form_errors,check_empty_fields($required_fields));

            //fields that required for minimum length check
            $field_to_check_length = array('password'=>6,'confirm_password'=>6);

            //call the function to check for minimum required length and merege data with form_errors array
            $form_errors = array_merge($form_errors,check_min_length($field_to_check_length));

            //email validation/merege data with form_errors array
            //$form_errors = array_merge($form_errors,check_email($_POST));

            //check if error array is empty, if yes process form data and insert record
            if(empty($form_errors)){

                if(isset($_GET['id'])){
                    $encoded_id=$_GET['id'];
                    $decoded_id=base64_decode($encoded_id);
                    $user_id=explode("encodeduserid",$decoded_id);
                    $id=$user_id[1];
                    $fields = array($_POST['password'],$_POST['confirm_password'],$id);
                    $msg = sqlUPdate_func('users',$fields,$db);
                if($msg=="pass")             
                     $result = "
                <script type=\"text/javascript\">
                        swal({   title: \"Congragulation!\",   text: \"Your password updated successfully.\",type:'success',   timer: 6000,   showConfirmButton: false });
                        setTimeout(function(){window.location.href = 'index.php';},5000);
                </script>";
                else $result = flashMessage("Password and Confirmation do not match.");
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
   