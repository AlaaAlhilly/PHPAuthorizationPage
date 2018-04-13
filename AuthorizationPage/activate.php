<?php

$title = 'Athintication System -SignUp';
include_once 'partials/header.php';
include_once 'partials/signupControl.php';
?>

<div class="container">

    <div class="flag">
    
        <?php if(isset($result)) echo $result;?>
    </div>

</div>