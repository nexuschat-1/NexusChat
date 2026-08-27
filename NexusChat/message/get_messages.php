<?php 
    $reffernce_msg_id = 0;
    $chat_message = (object)[];
    $chat = "";
    $row = array();
    $user = $_SESSION["user_id"];
    $now_chatting_with = $DATA_OBJ->now_chatting_with;
    if($DATA_OBJ->type == "new"){
        if($now_chatting_with != $user){
            $sql = "SELECT * FROM message WHERE sender = $now_chatting_with AND reciver = $user AND seen = 1";
            $result = mysqli_query($con, $sql);
            if($result){
                if(mysqli_num_rows($result) > 0){
                    $sql = "SELECT * FROM message WHERE sender = $user AND 	reciver = $now_chatting_with OR sender = $now_chatting_with AND reciver = $user ";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row["sender"] == $_SESSION["user_id"] && $row["_delete_"] != 1){
                            $do_see = "";
                            if($row["seen"] == 0 || $row["seen"] == 2){         //$row["_delete_"] == 1 means delete for me for user, $row["_delete_"] == 2 means delete for me for now chatting with
                                $do_see ="&#10003;";                
                                if($row["seen"] == 0){         
                                    $update_seen = mysqli_query($con,"UPDATE message SET seen = 2 WHERE msg_id = '".$row["msg_id"]."'");
                                }
                            }
                            $chat .= "<div class='chat_message_sent' id='msg".$row["msg_id"]."'>
                                        <div class='active_msg_txt_container_sent'>".$row["message"]."
                                            <div class='seen_date_conform_container_sent'>".$row["msg_date"]."&nbsp;&nbsp;&nbsp;
                                                <div class='seen_mark_conform' id='seen".$row["msg_id"]."'>&#10003;".$do_see."
                                                </div>&nbsp;&nbsp;
                                                <button class='msg_menu_sent_option_' onclick='option_btn_sent_(".$row["msg_id"].")'>:::</button>
                                            </div>
                                        </div>
                                        <div class='cone_point2'></div>
                                    </div><br>";
                        } else {
                            if ($row["_delete_"] != 2){
                                $chat .= "<div class='chat_message_recived' id='msg".$row["msg_id"]."'>
                                            <div class='cone_point1'></div>
                                            <div class='active_msg_txt_container_recived'>".$row["message"]."
                                                <div class='seen_date_conform_container_recived'>".$row["msg_date"]."&nbsp;&nbsp;
                                                    <button class='msg_menu_recived_option_' onclick='option_btn_recived_(".$row["msg_id"].")'>:::</button>
                                                </div>
                                            </div>
                                        </div><br>";
                                if($row["seen"] == 1){
                                    $update_seen = mysqli_query($con,"UPDATE message SET seen = 0 WHERE msg_id = '".$row["msg_id"]."'");
                                }
                            }
                        }
                        $reffernce_msg_id = $row["msg_id"];
                    }
                    $chat_message->final_chat_id = $reffernce_msg_id;
                    $chat_message->type = "all";
                    $chat_message->chat = $chat;
                    echo json_encode($chat_message);
                }
            }else{
                $chat_message->error = "something went wrong database connection lost";
                echo json_encode($chat_message);
            }
            exit();
        }
       echo (false);
    }else{
        $sql = "SELECT * FROM message WHERE sender = $user AND 	reciver = $now_chatting_with OR sender = $now_chatting_with AND reciver = $user ";
        $result = mysqli_query($con, $sql);
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row["sender"] == $_SESSION["user_id"] && $row["_delete_"] != 1){
                    $do_see = "";
                    if($row["seen"] == 0 || $row["seen"] == 2){         //$row["_delete_"] == 1 means delete for me for user, $row["_delete_"] == 2 means delete for me for now chatting with
                        $do_see ="&#10003;";                
                        if($row["seen"] == 0){         
                            $update_seen = mysqli_query($con,"UPDATE message SET seen = 2 WHERE msg_id = '".$row["msg_id"]."'");
                        }
                    }
                    $chat .= "<div class='chat_message_sent' id='msg".$row["msg_id"]."'>
                                <div class='active_msg_txt_container_sent'>".$row["message"]."
                                    <div class='seen_date_conform_container_sent'>".$row["msg_date"]."&nbsp;&nbsp;&nbsp;
                                        <div class='seen_mark_conform' id='seen".$row["msg_id"]."'>&#10003;".$do_see."
                                        </div>&nbsp;&nbsp;
                                        <button class='msg_menu_sent_option_' onclick='option_btn_sent_(".$row["msg_id"].")'>:::</button>
                                    </div>
                                </div>
                                <div class='cone_point2'></div>
                            </div><br>";
                } else {
                    if ($row["_delete_"] != 2){
                        $chat .= "<div class='chat_message_recived' id='msg".$row["msg_id"]."'>
                                    <div class='cone_point1'></div>
                                    <div class='active_msg_txt_container_recived'>".$row["message"]."
                                        <div class='seen_date_conform_container_recived'>".$row["msg_date"]."&nbsp;&nbsp;
                                            <button class='msg_menu_recived_option_' onclick='option_btn_recived_(".$row["msg_id"].")'>:::</button>
                                        </div>
                                    </div>
                                </div><br>";
                        if($row["seen"] == 1){
                            $update_seen = mysqli_query($con,"UPDATE message SET seen = 0 WHERE msg_id = '".$row["msg_id"]."'");
                        }
                    }
                }
                $reffernce_msg_id = $row["msg_id"];
            }
            $chat_message->final_chat_id = $reffernce_msg_id;
            $chat_message->type = "all";
            $chat_message->chat = $chat;
            echo json_encode($chat_message);
        }
        exit();
    }
    $chat_message->type = "null";
    echo json_encode($chat_message); 
?>