<?php
    session_start();
    $con = mysqli_connect("localhost","root","","NexusChat");
    if(!$con){
        die("connection faild".mysqli_connect_error());
    }
   // echo json_encode($_FILES['file']);
    $error = false;
   /* $message = (object)[];
    $message->info = null;
    $message->info_type = null; 
    $profile_data = array();*/

    if(isset($_FILES['file']) && isset($_FILES['file']['name'])){
        if($_FILES['file']['error'] == 0){
            $folder = "uploads/";
            if(!file_exists($folder)){
                mkdir($folder,0777,true);
            }
           // $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $file_name = $_SESSION["user_id"]."_".$_FILES['file']['name'];
            $file_destination = $folder.$file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_destination);
        }else{
            $error = true;
        }
    }else{
        $error = true;
    } 
   // echo $file_name;
    if($error){  
        echo json_encode(['info_type' => false]);
    }else{
        try{				
            $result = mysqli_query($con,"UPDATE users SET profile = '$file_name' WHERE user_id =". $_SESSION["user_id"]);
            if ($result){
                echo json_encode(["info_type" => true]);
            }else{
                echo json_encode(["info_type" => false]);
            }
        }catch(mysqli_sql_exception $e){
            echo $e->getMessage();
        } 
    } 
?>