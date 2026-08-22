<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Chat</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div class="card-container">
        <div class="card" id="cardElement">
            <!-- Registration Form View -->
            <div class="form-view signup-view">
                <nav>
                    <div class="logo">
                        <span>NexusChat</span>
                    </div>
                    <div class="nav-links">
                        <a href="#">Home</a>
                        <a href="#">info</a>
                        <a href="#">Contact</a>
                        <a href="#">Privacy and policy</a>
                    </div>
                </nav>
                <div class="form">
                    <div class="subtitle">Welcome to NexusChat</div>
                    <h1>Create new account</h1>
                    <div class="login-link">Already A Member? <button type="button" class="link-btn" onclick="flip_page()">Log In</button></div>
                    <form action="signup-login.php" method="POST">
                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" placeholder="Username" name="username" id="s_username">
                        </div>
                        <div class="form-row">
                            <div class="input-group">
                                <label>Password</label>
                                <input type="password" placeholder="Password" name="password" id="s_password">
                            </div>
                            <div class="input-group">
                                <label>Confirm Password</label>
                                <input type="password" placeholder="Confirm Password" name="confrimpass" id="s_confrimpass">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>Email</label>
                            <input type="email" placeholder="Email" name="email" id="s_email">
                        </div>
                        <div class="input-group">
                            <label>Phoneno</label>
                            <input type="number" placeholder="Mobile Number" name="phone" id="s_phone">
                        </div>
                        <div class="gender-group">
                            <label class="gender-label">Gender : </label>
                            <div class="radio-options">
                                <label class="radio-option">
                                <input type="radio" name="gender" value="male">Male
                                </label>
                                <label class="radio-option">
                                <input type="radio" name="gender" value="female">Female
                                </label>
                                <label class="radio-option">
                                <input type="radio" name="gender" value="not-to-say" checked>Rather not to say
                                </label>
                                <input type="radio" name="request_type" value="signup" checked style="display: none;">
                            </div>
                        </div>
                        <button type="submit" class="btn">Create account</button>
                    </form>
                </div>
            </div>

            <!-- Login Form View -->
            <div class="form-view login-view">
                <nav>
                    <div class="logo">
                        <span>NexusChat</span>
                    </div>
                    <div class="nav-links">
                        <a href="#">Home</a>
                        <a href="#">info</a>
                        <a href="#">Contact</a>
                        <a href="#">Privacy and policy</a>
                    </div>
                </nav>
                <div class="form">
                    <div class="subtitle">Welcome Back</div>
                    <h1>Continue Exploring NexusChat</h1>
                    <div class="login-link">New to NexusChat? <button type="button" class="link-btn" onclick="flip_page()">Create Account</button></div>
                    <form action="signup-login.php" method="POST">
                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" placeholder="Username" name="username" id="l_username">
                        </div>
                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" placeholder="Password" name="password" id="l_password">
                        </div>
                        <input type="radio" name="request_type" value="login" checked style="display: none;">
                        <button type="submit" class="btn">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php 
    require_once "api.php"; 
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
</body>
</html>