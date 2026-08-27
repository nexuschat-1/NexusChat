<?php                                //id = "contact_user_$user_chat_id" this is the id of each contact
$contact_list = "";
$users_data = (object)[];

 try{
    $list_no = "list_1";				
    $result = mysqli_query($con,"SELECT * FROM users");
    if($result){
        while ($row = mysqli_fetch_array($result)){
            if(empty($row["profile"])){
                if($row["gender"] == "male"){
                    $pic_url = "src_img/male676.png";
                    $on_click = "show_profile_recent('src_img/male676.png')";
                }else if ($row["gender"] == "female") {
                    $pic_url = "src_img/female1673.png";
                    $on_click = "show_profile_recent('src_img/female1673.png')";
                }else{
                    $pic_url = "src_img/not-to-say.png";
                    $on_click = "show_profile_recent('src_img/not-to-say.png')";
                } 
            }else{

                $pic_url = "includes/uploads/".$row["profile"];
                $on_click = "show_profile_recent('$pic_url')";
            }
            
            $user_chat_id = $row["user_id"];
            // in html onclick function with an argument of string containing spaces in it can't be passed to javascript, so replace it with special character @
            $user_full_name = str_replace(' ','@',$row["name"]);
            $user_date = str_replace(' ','@',$row["login_date"]);
            
            $load_contact_chat = "load_contact_chat($user_chat_id,'recent_contact','$pic_url','$user_full_name','$user_date')";
            if($user_chat_id == $_SESSION["user_id"]){
                $contact_list .= "<div class='recent_contact_list' id='contact_user_".$user_chat_id."'> <button class='recent_contact_profile' onclick = $on_click> <img src = '$pic_url' class='recent_contact_profile_img' style='height: 44px; width: 44px;'> </button> <div onclick = $load_contact_chat class='recent-inner-1_recent-inner-2_container'> <div class='recent-inner-1'> <label class='name' style='color: #fff; font-size: 15px; text-align: left;'><span id='user_notification_".$user_chat_id."' style='width: 5px; border-radius: 30px; background-color: #0ba100; color: #fff; margin-right: 5px; font-size: 12px; padding-left: 2px; padding-right: 2px; aspect-ratio: 1 / 1; margin-left: 0; visibility: hidden;'></span>".$row["name"]." : ( You ) </label> </div> <div class='recent-inner-2'> <label class='visited' style='color: #aaa0a0; font-size: 10px;'><span>Last visited on</span><br>".$row["login_date"]."</label> </div> </div> </div>";
            }else{
                $contact_list .= "<div class='recent_contact_list' id='contact_user_".$user_chat_id."'> <button class='recent_contact_profile' onclick = $on_click> <img src = '$pic_url' class='recent_contact_profile_img' style='height: 44px; width: 44px;'> </button> <div onclick = $load_contact_chat class='recent-inner-1_recent-inner-2_container'> <div class='recent-inner-1'> <label class='name' style='color: #fff; font-size: 15px; text-align: left;'><span id='user_notification_".$user_chat_id."' style='width: 5px; border-radius: 30px; background-color: #0ba100; color: #fff; margin-right: 5px; font-size: 12px; padding-left: 2px; padding-right: 2px; aspect-ratio: 1 / 1; margin-left: 0; visibility: hidden;'></span>".$row["name"]."</label> </div> <div class='recent-inner-2'> <label class='visited' style='color: #aaa0a0; font-size: 10px;'><span>Last visited on</span><br>".$row["login_date"]."</label> </div> </div> </div>";
               
            }
        }
        $users_data->list_code = $contact_list; 
        $users_data->request_type = "contact";
        echo json_encode($users_data); 
    }else{
        echo "No recent contacts"; // need improvement
    }
}catch(mysqli_sql_exception $e){
    echo $e->getMessage();
} 
?>

