<?php 
    session_start(); 
    require "signup-login.php";
    $request_type = "";
    $con = mysqli_connect("localhost","root","","NexusChat");
    if(!$con){
        die("connection faild".mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $request_type = $_POST["request_type"] ?? "return-to-login";
    }
    if ($request_type == "return-to-login") {
        die("Unexpected request type. something went wrong.");
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