<?php
    $errors = array();
    $username = "";
    $password = "";
    try{
        if ($_SERVER["REQUEST_METHOD"] === "POST"){
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            if (empty($password)) {
                $errors["password"] = "Password is required.";
            }elseif (strlen($password) < 6) {
                $errors["username"] = "username must be at least 6 characters long.";
            }

            if (empty($username)) {
                $errors["username"] = "Username is required.";
            } elseif (strlen($username) < 6) {
                $errors["username"] = "username must be at least 6 characters long.";
            }

            if (empty($errors)) {
                $logs = mysqli_query($con,"SELECT pass_word, user_id, name FROM users WHERE username='$username'");
                if ($row = mysqli_fetch_assoc($logs)){
                    if($password == $row['pass_word']){
                        $_SESSION['user_id'] = $row['user_id'];
                        $login_date_save = mysqli_query($con, "UPDATE users SET login_date = NOW() WHERE user_id = " . $_SESSION["user_id"]);
                        if ($login_date_save) {
                            echo "<script>alert('User ID: " . $_SESSION["user_id"] ."');</script>";
                            if ($row['name'] == "login_welcome_6591") {
                                echo "<script>window.location.href = 'welcome.html';</script>";
                            } else {
                                echo "<script>window.location.href = 'nexuschat.html';</script>";
                            }
                        }else {
                            echo "<script>alert('Error saving login date.');</script>";
                        }
                        mysqli_close($con);
                        exit();
                    }else{
                        echo "<div id='error_msg'>
                            <button id='close_error_msg'>X</button>
                            <ul>"; ?>
                                <?php echo "<li>Invalid username or password.</li>"; ?>
                            <?php echo "</ul>
                        </div>"; 
                        echo "<script>document.getElementById('cardElement').classList.toggle('flipped');
                            let screenWidth = window.innerWidth;
                            if (screenWidth < 768) {
                                document.getElementById('error_msg').style.left = 'calc(50%  - 150px)';
                            } else {
                                document.getElementById('error_msg').style.left = '30%';
                            }
                        </script>";
                    }
                }
            }else {
                echo "<div id='error_msg'>
                        <button id='close_error_msg'>X</button>
                        <ul>"; ?>
                        <?php
                            foreach ($errors as $value) {
                                echo "<li>$value</li>";
                            }
                        ?>
                <?php echo "</ul></div>"; 
                echo "<script>document.getElementById('cardElement').classList.toggle('flipped');
                    let screenWidth = window.innerWidth;
                    if (screenWidth < 768) {
                        document.getElementById('error_msg').style.left = 'calc(50%  - 150px)';
                    } else {
                        document.getElementById('error_msg').style.left = '30%';
                    }
                </script>";
            }
        }
    }catch(mysqli_sql_exception $e){
        echo "<script>console.log('$e');</script>";
    }
?>
<script>
    document.getElementById('l_username').value = <?php echo json_encode($username); ?>;
    document.getElementById('l_password').value = <?php echo json_encode($password); ?>;
</script>