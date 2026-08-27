<?php
try{				
    $info = mysqli_query($con,"SELECT * FROM users WHERE user_id = '$_SESSION[user_id]'");
    if ($profile_info = mysqli_fetch_array($info)){
        echo json_encode($profile_info);
    }
}catch(mysqli_sql_exception $e){
    echo $e->getMessage();
} 
?>