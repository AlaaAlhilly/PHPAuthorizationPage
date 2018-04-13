<?php

include_once 'resources/session.php';

include_once 'resources/utilities.php';

include_once 'resources/database.php';

if(isset($_POST['loginBtn']) && isset($_POST['token'])){
    
    if(isValidToken($_POST['token'])){
    $form_errors = array();
    $required_fields = array('username','password');
    
    $form_errors = array_merge($form_errors,check_empty_fields($required_fields));
    
    $required_length = array('username'=>4,'password'=>6);
    
    $form_errors = array_merge($form_errors,check_min_length($required_length));
    
    if(empty($form_errors)){
        
        isset($_POST['remember']) ?$remember = "yes":$remember="";
        $msg = sqlSelect_func('users','username',$_POST['username'],$_POST['password'],$db);
        
        if($msg[0]!="") $result = flashMessage($msg[0]);
        else{
                if($msg[3]=='0'){
                    $usId=$msg[1];
                    if(search_data('trash','user_id',$usId,$db)=="pass"){
                        try{
                            $db->exec("UPDATE USERS SET activated= '1' WHERE id= $usId");
                            $db->exec("DELETE FROM trash WHERE user_id= $usId");
                            $_SESSION['id'] = $msg[1];
                            $_SESSION['username'] = $msg[2];

                            $fingerprint=md5($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']);
                            $_SESSION['fingerprint']=$fingerprint;
                            $_SESSION['last_active'] = time();

                            if($remember === "yes"){
                                rememberMe($msg[1]);
                            }

                        //message ajax
                        $result = "
                                <script type=\"text/javascript\">
                                        swal({   title: \"Welcome back $msg[2]!\",   text: \"You are Loged in again.\",type:'success',   timer: 6000,   showConfirmButton: false });
                                        setTimeout(function(){window.location.href = 'index.php';},5000);
                                </script>";
                        }catch(PDOException $ex){
                            echo "error: ".$ex->getMessage();
                        }
                    }else{
                        $result = "
                                <script type=\"text/javascript\">
                                        swal({   title: \"Warning $msg[2]!\",   text: \"pleas confirm your email\",type:'success',   timer: 6000,   showConfirmButton: false });
                                </script>";
                    }
                }else{
                $_SESSION['id'] = $msg[1];
                $_SESSION['username'] = $msg[2];
                
                $fingerprint=md5($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']);
                $_SESSION['fingerprint']=$fingerprint;
                $_SESSION['last_active'] = time();
            
                if($remember === "yes"){
                    rememberMe($msg[1]);
                }
            
            //message ajax
            $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Welcome back $msg[2]!\",   text: \"You are Loged in again.\",type:'success',   timer: 6000,   showConfirmButton: false });
                setTimeout(function(){window.location.href = 'index.php';},5000);
        </script>";
               
                
        } 
    }
    }else if(count($form_errors)==1){
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

    }else{
        $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Warning!\",   text: \"This might be an attack,from someone stolen your id.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
    }
    
}