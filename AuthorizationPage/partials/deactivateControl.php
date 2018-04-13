<?php
include_once 'resources/Database.php';
include_once 'resources/utilities.php';
include_once 'resources/send-email.php';

if(isset($_POST['deleteAccountBtn'],$_POST['token'])){
    if(isValidToken($_POST['token'])){
        
        $encoded_id=$_POST['hidden_id'];
        $decoded_id=base64_decode($encoded_id);
        
        $user_id=explode("encodeuserid",$decoded_id);
        $id=$user_id[1];
        try{
            $statement=sqlSearch('users','id',$id,$db);
            if($row=$statement->fetch()){
                $username=$row['username'];
                $email=$row['email'];
                $usrId=$row['id'];
                
                $updateQery="UPDATE users SET activated= :activated WHERE id= :id";
                $update=$db->prepare($updateQery);
                $update->execute(array(':activated'=>'0',':id'=>$usrId));
                if($update->rowCount()==1){
                     $sqlIns="INSERT INTO trash(user_id,deleted_at) VALUES(:user_id,now())";
                     $ins=$db->prepare($sqlIns);
                     $ins->execute(array(':user_id'=>$usrId));
                     if($ins->rowCount()==1){
                         $result = "
                            <script type=\"text/javascript\">
                                    swal({   title: \"SAD!\",   text: \"Successfully deactivated the account,we will keep
                                    your information for 14 days if you feel like your like to get back to us just log in normally otherwise we will permenantly delete the account, Thank you.\",type:'error',   timer: 3000,   showConfirmButton: false });
                            </script>";
                         $body='
                                <html>
                                    <body style="background-color:#cccccc;color:#000;font-family:Arial,Helvetica,sans-serif;line-height:1.8em;">

                                        <h2>User Athintication System</h2>
                                        <p>Dear'.$username.'<br><br>You just deactivate your account with us,and we feel so sad for that, but we will keep your information for 14 days in case you want to get back with us otherwise we will delete the account permenantly,thank you.</p>
                                        <p><a href="http://alhilly.com/alaa/login.php">LogIn</a></p>
                                        <p><strong>&copy;2016 Alaa Design</strong></p>
                                    </body>
                                </html>
                                setTimeout(function(){window.location.href = 'logout.php';},5000);
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
                                swal({   title: \"Confirm!\",   text: \"re-activate link sent back to your email check your inbox,in case you want to get back to our family.\",type:'success',   timer: 6000,   showConfirmButton: false });
                                setTimeout(function(){window.location.href = 'index.php';},5000);
                        </script>";
                        }
                     }
                }else{
                     $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Error!\",   text: \"something wrong happened when trying to deactivate the account pleas try again later.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
                }
            }
            
        }catch(PDOException $ex){
            echo "error connection: ".$ex->getMessage();
        }
    }else{
        $result = "
        <script type=\"text/javascript\">
                swal({   title: \"Warning!\",   text: \"This might be an attack,from someone stolen your id.\",type:'error',   timer: 3000,   showConfirmButton: false });
        </script>";
    }
}