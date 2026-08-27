<?php
    if(isset($_SESSION["user_id"])){
        session_unset();
    }
    echo json_encode("logged out");
?>
 