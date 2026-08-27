<?php
// type 1 means sender delete for me 
// type 2 means reciver delete for me
// type 3 means delete for all in sender option
switch($DATA_OBJ->oparation_type){
    case 1: 
        $sql = " UPDATE message SET _delete_ = 1 WHERE msg_id = $DATA_OBJ->messageId";
        $result = mysqli_query($con, $sql);
        if($result){
            echo json_encode(["deleted" => true]);
        }else{
            echo false;
        }
        break;
    case 2:
        $sql = " UPDATE message SET _delete_ = 2 WHERE msg_id = $DATA_OBJ->messageId";
        $result = mysqli_query($con, $sql);
        if($result){
            echo json_encode(["deleted" => true]);
        }else{
            echo false;
        }
        break;
    case 3:
        $sql = "DELETE FROM message WHERE msg_id = $DATA_OBJ->messageId";
        $result = mysqli_query($con, $sql);
        if($result){
            echo json_encode(["deleted" => true]);
        }else{
            echo false;
        }
        break;
}
?>