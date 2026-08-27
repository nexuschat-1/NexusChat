<?php 
    $DATA_RAW = file_get_contents("php://input");
    $DATA_OBJ = json_decode($DATA_RAW);
    
    session_start();
    $logged_info = (object)[];
    if (!isset($_SESSION["user_id"])) { //dont forget to add ! 
        if (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "user_info") {
            $logged_info->logged_in = false;
            echo json_encode($logged_info);
            die;
        }  
    }

    $con = mysqli_connect("localhost","root","","NexusChat");
    if(!$con){
        die("connection faild".mysqli_connect_error());
    }

    if (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "user_info") {
        require("includes/user_info.php");
    } elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "logout") {
        require("includes/logout.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "contact") {
        require("includes/contact.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "settings") {
        require("includes/settings.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "search_contact") {
        require("includes/search_contact.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "get_messages") {
        require("message/get_messages.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "send_message") {
        require("message/send_message.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "delete_message") {
        require("message/delete_message.php");
    }elseif (isset($DATA_OBJ->request_type) && $DATA_OBJ->request_type == "notification") {
        require("message/notification.php");
    }
?>