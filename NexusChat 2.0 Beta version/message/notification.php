<?php
$notification  = array();
$sql = "SELECT sender FROM message WHERE reciver = ".$_SESSION["user_id"]." AND  sender <> ".$_SESSION["user_id"]." AND seen = 1";
$result = mysqli_query($con, $sql);
if($result){
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            if(array_key_exists($row["sender"],$notification)){
                $notification[$row["sender"]] += 1;
            }else {
                $notification[$row["sender"]] = 1;
            }
        }
        echo json_encode($notification);
    }else{
        echo false;
    }
}
echo false;

?>