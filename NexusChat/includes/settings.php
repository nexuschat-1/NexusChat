<?php
$message = (object)[];
$message->name = null;
$message->username = null;
$message->email = null;
$message->phone = null;
$message->password = null;
$message->password_confirm = null;

$message->info = null;
$message->info_type = null;  

$error =false;
$settings_data = array();
// use id
$settings_data["user_id"] = $_SESSION["user_id"];
//full name validation
$settings_data['name'] = $DATA_OBJ->name;
if(empty($DATA_OBJ->name)){
    $error = true;
    $message->name = "! Please enter your name";
}else{
    if(strlen($DATA_OBJ->name) < 6 ||strlen($DATA_OBJ->name) > 30 ){
        $error = true;
        $message->name = "! Name must be contain characters between 6-30";
    }if(!preg_match("/^[a-z A-Z 0-9 _ .]*$/",$DATA_OBJ->name)){
        $error = true;
        $message->name = "! Special character are not allowed";
    }
}
//username validation
$settings_data['username'] = $DATA_OBJ->username;
if(empty($DATA_OBJ->username)){
    $error = true;
    $message->username = "! Please enter a valid username";
}else if(strlen($DATA_OBJ->username) < 6 || strlen($DATA_OBJ->username) > 50 ){
    $error = true;
    $message->username = "! Username must be contain characters between 3-50";
}
//email validation
$settings_data['email'] = $DATA_OBJ->email;
if(empty($DATA_OBJ->email)){
    $error = true;
    $message->email = "! Please enter a valid email";
}else{
    if(!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $DATA_OBJ->email)){
        $error = true;
        $message->email = "! Email format is not valid";
    }
}
// phone number validation
$settings_data['phone'] = $DATA_OBJ->phone;
if(empty($DATA_OBJ->phone)){
    $error = true;
    $message->phone = "! Please enter a valid phone number";
}else{
    if(strlen((string)$DATA_OBJ->phone) < 10 || strlen((string)$DATA_OBJ->phone) > 10){
        $error = true;
        $message->phone = "! Phone number must contain 10 digits";
    }
}
//password validation
$settings_data['pass_word'] = $DATA_OBJ->password;
if(empty($DATA_OBJ->password)){
    $error = true;
    $message->password = "! Please enter a valid password";
}else if(strlen($DATA_OBJ->password) < 6){
        $error = true;
        $message->password = "! Password must be contain atleast 6 characters";
}
//pasword confirmation
$password_confirm = $DATA_OBJ->password_confirm;
if($password_confirm != $DATA_OBJ->password){
    $error = true;
    $message->password_confirm = "! Retype the password correctly";
}
//gender set
$settings_data["gender"] = $DATA_OBJ->gender;

if($error){                                                       
    $message->info_type = false;
    echo json_encode($message);
}else{
    try{				
        $result = mysqli_query($con, "UPDATE users SET name = '$DATA_OBJ->name', username = '$DATA_OBJ->username', e_mail = '$DATA_OBJ->email', phone = '$DATA_OBJ->phone', pass_word = '$DATA_OBJ->password', gender = '$DATA_OBJ->gender' WHERE user_id = " . $_SESSION["user_id"]); 
        if($result){
            $message->info_type = true;
            echo json_encode($message);
        }
    }catch(PDOException $e){
        echo $e->getMessage();
    } 
}  
?>