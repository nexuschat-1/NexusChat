<?php
    $receiver = $DATA_OBJ->reciver;
    $message = $DATA_OBJ->message;
    try{
        $sql = "INSERT INTO message (sender, reciver, message, msg_date) VALUES (".$_SESSION["user_id"].", $receiver, '$message', NOW() )";
        $result = mysqli_query($con, $sql);
        if($result){
            
        }
        exit();
    }catch(mysqli_sql_exception $e){
        echo $e->getMessage();
    } 