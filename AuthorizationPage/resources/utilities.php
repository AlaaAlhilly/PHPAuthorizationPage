<?php
/**
*@param required_field_array,an array containing n required fields
*@return array , containing all errors
*/

function check_empty_fields($required_field_array){
    //initialize an array to store any error message from the form
    $form_errors = array();
    
     //loop through the required fields array
    foreach($required_field_array as $name_of_field){
        if(!isset($_POST[$name_of_field]) || $_POST[$name_of_field]==NULL){
            $form_errors[] = $name_of_field." is a required field.";
        }
    }
    return $form_errors;
}

/**
*@param fields_to_check is an array containing the name of fields
*which is used to check the length of the fields
*like username->4,email->12,password->8
*@return array containing all errors
*/

function check_min_length($fields_to_check_length)
{
    //initilize an array for errors
    $form_errors = array();
    
    foreach($fields_to_check_length as $name_of_field => $minimum_of_length_required){
        if(strlen(trim($_POST[$name_of_field])) < $minimum_of_length_required){
            $form_errors[]=$name_of_field." is too short,must be ".$minimum_of_length_required." characters long";
        }
    }
    
    return $form_errors;
}

/**
*@param $data, store a key/data pair array where key is the name of the form control(field)
*in this case 'email' , and the value entered by user
*@return array contain all errors
*/

function check_email($data)
{
     //initilize an array for errors
    $form_errors = array();
    
    $key = 'email';
    
    //check if key email is exist with the data array
    
    if(array_key_exists($key,$data)){
        
        //check if the email has a value
        if($_POST[$key]!=null){
            
            //remove all illegal character in the email
            $key = filter_var($key,FILTER_SANITIZE_EMAIL);
            
            //check if input is valid email
            if(filter_var($_POST[$key],FILTER_VALIDATE_EMAIL)===false){
                $form_errors[] = $key." is not a valid email";
            }
        }
    }
   return $form_errors; 
}

/**
*@param @form_errors_array which contain
*all error we want to loop through
*@return a string list containing all errors
*/

function show_errors($form_errors_array)
{
    
    $errors = "<div class = 'alert alert-danger'><p><ul style='color:red;'>";
    //loop through error array and display all items in a list
    foreach($form_errors_array as $the_error){
        $errors.="<li> {$the_error} </li>";
    }
    $errors.="</ul></p>";
    return $errors;
    
}

function rememberMe($user_id){
    $encrypt_data = base64_encode("IloveYouGogobutYouAreArealBITCH{$user_id}");
    setcookie('userCookie',$encrypt_data,time()+60*60*24*100,"/");
}

function isCookieValid($db){
    $isValid = false;
    if(isset($_COOKIE['userCookie'])){
        $dec_data = base64_decode($_COOKIE['userCookie']);
        $user_id = explode("IloveYouGogobutYouAreArealBITCH",$dec_data);
        $userID=user_id[1];
        $statement = sqlSearch('users','id',$userID,$db);
        if($row=$statemnt->fetch()){
            $username=$row['username'];
            $email=$row['email'];
            $_SESSION['id']=$userID;
            $_SESSION['username']=$username;
            $isValid=true;
        }
    }
    return $isValid;
}

function signout(){
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    
    if(isset($_COOKIE['userCookie'])){
        unset($_COOKIE['userCookie']);
        setcookie('userCookie',null,-1,"/");
    }
    session_destroy();
    session_regenerate_id(true);
    goto_homepage('index');
}

function guard(){
    $isValid = false;
    $inactive = 60*10;
    $fingerprint = md5($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']);
    if(isset($_SESSION['fingerprint']) && ($_SESSION['fingerprint']!=$fingerprint)){
        $isValid = false;
        signout();
    }else if(isset($_SESSION['last_active']) && (time()-$_SESSION['last_active'] >$inactive)){
        $isValid=false;
        signout();
    }else{
        $isValide=true;
    }
    return $isValid;
}
function sqlInsert_func($table,$fields,$db){
    
    try{
        
        $sqlInsert = "INSERT INTO {$table} (username,email,password,join_date) VALUES(:username,:email,:password,now())";
    
        $statement = $db->prepare($sqlInsert);
    
        $statement->execute(array(':username'=>$fields[0],':email'=>$fields[1],':password'=>$fields[2]));
        
        return "pass";
    
    }catch(PDOException $ex){
        
        echo "Error connecting: ".$ex->getMessage();
        
    }
    
    return "fail";
}

function sqlSelect_func($table,$field,$value,$password,$db){
    
    $pass = $password;
    
    try{
        
        $sqlQuery = "SELECT * FROM {$table} WHERE {$field} = :{$field}";
    
        $statement = $db->prepare($sqlQuery);
    
        $statement->execute(array($field=>$value));
        if($statement->rowCount()>0){
        while($row = $statement->fetch()){
            
            if($row['activated']=='0'){
                $msg=array('',$row['id'],$row['username'],$row['activated']);
            }else{
                if(password_verify($pass,$row['password'])){
                    $msg =array('',$row['id'],$row['username'],$row['activated']);
                }else{
                    $msg = array('Wrong password',null,null,null);
                }
            }
         }
        }else{
             $msg= array('User does not exist',null,null,null);           
        }
        
  
    }catch(PDOException $ex){
        
        echo "Error Connection: ".$ex->getMessage();
        
    }
    
    
    return $msg;
    
}
function search_data($table,$field,$value,$db){
        
    try{
        
        $sqlQuery = "SELECT * FROM {$table} WHERE {$field} = :{$field}";
    
        $statement = $db->prepare($sqlQuery);
    
        $statement->execute(array($field=>$value));
        if($statement->rowCount()>0) return "pass";
    }catch(PDOException $ex){
        
        echo "Error Connection: ".$ex->getMessage();
        
    }
    return "fail";
}
function sqlSearch($table,$field,$value,$db){
        
    try{
        
        $sqlQuery = "SELECT * FROM {$table} WHERE {$field} = :{$field}";
    
        $statement = $db->prepare($sqlQuery);
    
        $statement->execute(array($field=>$value));
        if($statement->rowCount()>0) return $statement;
    }catch(PDOException $ex){
        
        echo "Error Connection: ".$ex->getMessage();
        
    }
    return null;
}
function sqlUpdate($table,$fields,$db){
        $email=$fields[0];
        $username=$fields[1];
        $id = $fields[2];
        try{  
                $sqlUpdate = "UPDATE {$table} SET email = :email,username=:username WHERE id = :id";
                $statement = $db->prepare($sqlUpdate);
            
                $statement->execute(array('email'=>$email,'username'=>$username,'id'=>$id));
            if($statement->rowCount()==1){return "pass";}
            else {return "passF";}
        }catch(PDOException $ex){
            echo "error connection: ".$ex->getMessage();
        }
    return "fail";
}
function sqlUpdate_func($table,$fields,$db){
    if($fields[0]==$fields[1]){
        $pass =password_hash($fields[0],PASSWORD_DEFAULT);
        try{
        
        $sqlUPdate = "UPDATE {$table} SET password = :password WHERE id= :id";
    
        $statement = $db->prepare($sqlUPdate);
    
        $statement->execute(array(':id'=>$fields[2],':password'=>$pass));
        
        return "pass";
    
    }catch(PDOException $ex){
        
        echo "Error connecting: ".$ex->getMessage();
        
    }
    
    }else {
        
        return "fail";
        
    }
    
}

function goto_homepage($home){
    
    header("location:{$home}.php");
}

function flashMessage($msg,$passOrFail="fail"){
    
    if($passOrFail=="pass"){
        
        $result = "<div class ='alert alert-success'>{$msg}</div>";
    }else{
        $result = "<div class ='alert alert-danger'>{$msg}</div>";
    }
    return $result;
}

function isValidImage($file){
    $form_errors=array();
    
    $part=explode(".",$file);
    $extension=end($part);
    switch(strtolower($extension)){
        case 'jpg':
        case 'png':
        case 'gif':
        case 'bmp':
            return $form_errors;
    }
    $form_errors[] =$extension." is not valid for profile picture";
    return $form_errors;
}
function uploadeImag($username)
{
    $isMoved=false;
    
   if($_FILES["avatar"]["tmp_name"]){ 
    $temp_file=$_FILES['avatar']['tmp_name'];
    $ds = DIRECTORY_SEPARATOR;
    $avatar_name=$username.".jpg";
    $path="uploads".$ds.$avatar_name;
    if(move_uploaded_file($temp_file,$path)){
        $isMoved=true;
    }
   }
    return $isMoved;
}

function _token(){
    $randomToken=base64_encode(openssl_random_pseudo_bytes(32));
    return $_SESSION['token']=$randomToken;
}

function isValidToken($token){
    if(isset($_SESSION['token']) && $token === $_SESSION['token']){
        return true;
    }
    return false;
}
?>