<?php
include_once 'resources/Database.php';
include_once 'resources/utilities.php';
try{
    
    $sqlQuery = $db->query("SELECT * FROM trash WHERE deleted_at <= CURRENT_DATE - INTERVAL 14 DAY");
    
    while($rs=$sqlQuery->fetch()){
        $user_id=$rs['user_id'];
        
        $userRec=$db->prepare("SELECT * FROM users WHERE id= :id");
        $userRec->execute(array(':id'=>$user_id));
        
        if($row=$userRec->fetch()){
            $user=$row['username'];
            $id=$row['id'];
            
            $profImg="upload/".$user.".jpg";
            if(file_exists($proImg)){
                unlink($profImg);
            }
            
            $db->exec("DELETE FROM trash WHERE user_id= $user_id");
            $result=$db->exec("DELETE FROM users WHERE id= $id");
            echo "$result account(s) deleted";
        }
    }
}catch(PDOEXception $ex){
    echo "error".$ex->getMessage();
}
try{
    
    $sqlQuery=$db->query("SELECT id,username FROM users WHERE join_date <= CURRENT_DATE - INTERVAL 3 DAY AND activated = '0'");
    
    while($row=$sqlQuery->fetch()){
        $user=$row['username'];
        $id=$row['id'];
        if(search_data('trash','user_id',$id,$db)=="passF"){
             $proImg="upload/".$user.".jpg";
             if(file_exists($proImg)){
             unlink($proImg);
        }
            
       
        }
        
        $result=$db->exec("DELETE FROM users WHERE id= $id AND activated= '0'");
        echo "$result account(s) deleted";
        
        
    }
}catch(PDOEXception $ex){
    echo "error";
}
?>