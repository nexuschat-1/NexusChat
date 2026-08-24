<?php 
    session_start(); 
    require "signup-login.php";
    $request_type = "";
    if (!isset($_SESSION["user_id"])) {
        if (isset($request_type) && $request_type == "user_info") {
            header("Location: validation-login.php");
            die;
        }  
    }
    $con = mysqli_connect("localhost","root","","NexusChat");
    if(!$con){
        die("connection faild".mysqli_connect_error());
    }

    $DATA_RAW = file_get_contents("php://input");
    $DATA_OBJ = json_decode($DATA_RAW);

    if ($DATA_OBJ == null) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $request_type = $_POST["request_type"] ?? "return-to-login";
        }
        if ($request_type == "return-to-login") {
            header("Location: signup-login.php");
            die;
        }
    }

    if (isset($request_type) && $request_type == "signup") {
        require("includes/validate-upload.php");
    } elseif (isset($request_type) && $request_type == "login") {
        require("includes/login.php");
    } elseif (isset($request_type) && $request_type == "welcome") {
        require("includes/welcome.php");
    } 
?>
<script>
    function flip_page() {
        const card = document.getElementById('cardElement');
        card.classList.toggle('flipped');
    }
    const close_msg = document.getElementById('close_error_msg');
    close_msg.addEventListener("click", () => {
        document.getElementById('error_msg').remove();
    });
</script>