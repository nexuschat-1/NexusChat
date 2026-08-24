<?php
    $name = "";
    try{
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = trim($_POST["name"] ?? "");
            $result = mysqli_query($con, "UPDATE users SET name = '$name' WHERE user_id = " . $_SESSION["user_id"]); 
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
            echo "<script>window.location.href = 'nexuschat.html';</script>";
            mysqli_close($con);
        }
    }catch(mysqli_sql_exception $e){
        echo "<script>console.log('$e');</script>";
    }
?>