<?php
//initialize variable to hold db connection info
$username = 'devAlaa';

$dsn = 'mysql:host=localhost; dbname=register2';

$password = 'Getdream980@';

try{
    //create an instance of the PDO class with the required parameter
    
    $db=new PDO($dsn,$username,$password);
    
    
    //set pdo error mode to exception
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //display success message
    
    //echo "Connected to database";
    
}catch (PDOException $ex){
    
    //display error message
    
  echo "connection faild ".$ex->getMessage();
}
?>