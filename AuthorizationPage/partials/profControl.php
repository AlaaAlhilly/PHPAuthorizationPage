<?php
include_once 'resources/Database.php';
include_once 'resources/utilities.php';
$d=1;
if((isset($_SESSION['id'])||isset($_GET['user_id']))&&!isset($_POST['updateBtn'])){
    if(isset($_GET['user_id'])){
        $url_id = $_GET['user_id'];
        $decoded_id=base64_decode($url_id);
        $user_id=explode("encodeuserid",$decoded_id);
        $id = $user_id[1];
    }else{
    $id = $_SESSION['id'];
    }
    
    $statement = sqlSearch('users','id',$id,$db);
    while($rs=$statement->fetch()){
        $username = $rs['username'];
        $email = $rs['email'];
        
        $date_joined = strftime("%b %d %Y",strtotime($rs['join_date']));
    }
    $user_pic="uploads/".$username.".jpg";
    $default ="uploads/default.jpg";
    if(file_exists($user_pic)){
        $prof_pic=$user_pic;
        
    }else {$prof_pic=$default;}
    $encode_id = base64_encode("encodeuserid{$id}");
}else if(isset($_POST['updateBtn'],$_POST['token'])){
    
       if(isValidToken($_POST['token'])){
                $form_errors = array();
                $req_fields = array('email','username');
                $form_errors = array_merge($form_errors,check_empty_fields($req_fields));

                $req_len=array('username'=>4);
                $form_errors = array_merge($form_errors,check_min_length($req_len));

                $form_errors = array_merge($form_errors,check_email($_POST));
                isset($_FILES["avatar"]["name"]) ? $avatar =$_FILES["avatar"]["name"] : $avatar = null;
                if($avatar!=null)
                    {
                    $form_errors = array_merge($form_errors,isValidImage($avatar));
                    }

                if(empty($form_errors)){
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    if(isset($_POST['hidden_id']))$id=$_POST['hidden_id'];
                    if(sqlUpdate('users',array($email,$username,$id),$db)=="pass" || uploadeImag($username)){
                        $_SESSION['username']=$username;
                        $_SESSION['email']=$email;

                         $result = "
                    <script type=\"text/javascript\">
                            swal({   title: \"Congragulations!\",   text: \"Profile information updated successfuly.\",type:'success',   timer: 5000,   showConfirmButton: false });

                    </script>";
                    }else if(sqlUpdate('users',array($email,$username,$id),$db)=="passF"){
                        $result = "
                    <script type=\"text/javascript\">
                            swal({   title: \"NOTHING HAPPENED!\",   text: \"You did not make any change.\",type:'success',   timer: 3000,   showConfirmButton: false });

                    </script>";
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