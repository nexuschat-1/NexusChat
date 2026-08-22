<?php
    $username = "";
    $phone = "";
    $email = "";
    $password = "";
    $confrimpass = "";
    $gender = "not-to-say";
    $errors = array();
    $success_message = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $phone = trim($_POST["phone"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? ""); 
        $confrimpass = trim($_POST["confrimpass"] ?? "");
        $username = trim($_POST["username"] ?? "");
        $gender = trim($_POST["gender"] ?? "not-to-say");

        if (empty($phone)) {
            $errors["phone"] = "Phone number is required.";
        } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
            $errors["phone"] = "Phone number must be exactly 10 digits.";
        }

        if (empty($email)) {
            $errors["email"] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format.";
        }

        if (strlen($password) < 6) {
            $errors["password"] = "Password is required.";
        } elseif (empty($password) || empty($confrimpass)) {
            $errors["password"] = "Password must be at least 6 characters long.";
        } elseif($password != $confrimpass){
            $errors["password"] = "Password and confirm password must be same.";
        }

        if (strlen($username) < 6) {
            $errors["username"] = "Username is required.";
        } elseif (empty($username)) {
            $errors["username"] = "username must be at least 6 characters long.";
        }

        if (empty($errors)) {

            $con = mysqli_connect("localhost","root","","NexusChat");
            if(!$con){
                die("connection faild".mysqli_connect_error());
            }

            function genarate_user_id($con,$user_id,$chance){
                $result = mysqli_query($con, "SELECT user_id FROM users WHERE user_id = $user_id LIMIT 1");
                if(!$result && $chance <= 10){
                    echo "<div id='error_msg'>
                        <button id='close_error_msg'>X</button>
                        <ul>"; ?>
                        <?php
                            echo "<li>Error! sorry something went wrong</li>";
                        ?>
                    <?php echo "</ul></div>"; 
                    die();
                }
                if(mysqli_fetch_assoc($result) == null){
                    return $user_id;
                }else{
                    genarate_user_id($con, rand(10000, 99999), $chance + 1);
                }

            }
            
            $user_id = genarate_user_id($con, rand(10000, 99999), 1);

            $result = mysqli_query($con, "INSERT INTO users(user_id, username, pass_word, e_mail, phone, gender) VALUES($user_id, '$username', '$password', '$email', $phone, '$gender')"); 
            if(!$result){
                echo "<div id='error_msg'>
                    <button id='close_error_msg'>X</button>
                    <ul>"; ?>
                    <?php
                        echo "<li>Error! sorry something went wrong</li>";
                    ?>
                <?php echo "</ul></div>"; 
                die();
            }
            
            echo "<script>alert('Account created Login to continue');</script>";
        } else {
            echo "<div id='error_msg'>
                    <button id='close_error_msg'>X</button>
                    <ul>"; ?>
                    <?php
                        foreach ($errors as $value) {
                            echo "<li>$value</li>";
                        }
                    ?>
            <?php echo "</ul></div>"; 
        }
    }
?>
<script>
    document.getElementById('s_username').value = <?php echo json_encode($username); ?>;
    document.getElementById('s_password').value = <?php echo json_encode($password); ?>;
    document.getElementById('s_confrimpass').value = <?php echo json_encode($confrimpass); ?>;
    document.getElementById('s_email').value = <?php echo json_encode($email); ?>;
    document.getElementById('s_phone').value = <?php echo json_encode($phone); ?>;
</script>