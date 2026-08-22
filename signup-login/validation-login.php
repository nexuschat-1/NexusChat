<?php
$errors = array();
$username = "";
$password = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (strlen($password) < 6) {
            $errors["password"] = "Password is required.";
        }elseif (empty($password)) {
            $errors["username"] = "username must be at least 6 characters long.";
        }
        if (strlen($username) < 6) {
            $errors["username"] = "Username is required.";
        } elseif (empty($username)) {
            $errors["username"] = "username must be at least 6 characters long.";
        }
        if (empty($errors)) {

            $conn = mysqli_connect("localhost","app_user","hallo5678","login");
            if(!$conn){
                die("Connection failed:".mysqli_connect_error());
            }
            $username = strtolower(trim($_POST['username']));
            $password = strtolower(trim($_POST['password']));
            $logus=mysqli_query($conn,"select password from users where username='$username'");
            if ($row=mysqli_fetch_assoc($logus)){
                if($password === $row['password']){
                    $_SESSION['user'] = $username;
                    mysqli_close($conn);
                    header("Location: validation-login.php");
                    exit();
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
        }
    }
?>