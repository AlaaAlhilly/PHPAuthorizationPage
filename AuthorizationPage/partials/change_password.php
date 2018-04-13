<?php
include_once 'resources/Database.php';
include_once 'resources/utilities.php';
if(isset($_POST['changePass'],$_POST['token'])){

    if(isValidToken($_POST['token'])){
        
        $form_errors = array();

    //Form validation
    $required_fields = array('cupassword','npassword','cpassword');
    
     
    //call the function to check for empty fields and merege data with form_errors array
    $form_errors = array_merge($form_errors,check_empty_fields($required_fields));
    
    //fields that required for minimum length check
    $field_to_check_length = array('npassword'=>6,'cpassword'=>6);
    
    //call the function to check for minimum required length and merege data with form_errors array
    $form_errors = array_merge($form_errors,check_min_length($field_to_check_length));
    
    //email validation/merege data with form_errors array
    //$form_errors = array_merge($form_errors,check_email($_POST));
    if(empty($form_errors)){
        
        $encoded_id=$_POST['hidden_id'];
    
        $decoded_id=base64_decode($encoded_id);
        $user_id=explode("encodeuserid",$decoded_id);
        $id=$user_id[1];
        echo $id;
        if(search_data('users','id',$id,$db)=="pass"){
            $statement=sqlSearch('users','id',$id,$db);
            if($row=$statement->fetch()){
                $pass=$row['password'];
                $cupass=$_POST['cupassword'];
                if(password_verify($cupass,$pass)){
                    $fields = array($_POST['npassword'],$_POST['cpassword'],$id);
                    $msg = sqlUPdate_func('users',$fields,$db);
                if($msg=="pass")             
                     $result = "
                <script type=\"text/javascript\">
                        swal({   title: \"Congragulation!\",   text: \"Your password updated successfully.\",type:'success',   timer: 6000,   showConfirmButton: false });
                </script>";
                else $result = flashMessage("Password and Confirmation do not match.");
                }else{
                  $result=  "<script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"Old password is not correct.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
                }
            }
        }else{
             $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"user authintication error\",type:'error',   timer: 10000,   showConfirmButton: false });
        </script>";
           // signout();
        }
    }else if(count($form_errors)==1){
        $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"There was an error in the form.\",type:'error',   timer: 6000,   showConfirmButton: false });
        </script>";   
    }
       else{
        $er=count($form_errors);
          $result = "
        <script type=\"text/javascript\">
                swal({   title: \"OOPS!\",   text: \"There were $er error in the form.\",type:'error',   timer: 6000,   showConfirmButton: false });
        </script>";
    }
    }else{
        $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Warning!\",   text: \"This might be an attack,from someone stolen your id.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
    }
}