<?php
    require("./views/header.php");
    if (isset($_SESSION['oUsername'])) {
        header("Location: ./home.php");
      }
?>
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <div>
            
        </div>
        <h1>Login</h1>
        <form action="" class="login" id="login">
            <input type="text" id="username" placeholder="Enter Username" />
            <input type="password" id="password" placeholder="Enter Password" />
            <div class="show" id="show">

            </div>
            <input type="submit" value="Login" id="btn-login"/>

            <div>
                Don't have an account?
                <a href="register.php"> Create account </a>
            </div>
           
        </form>
    </div>
    <script src="js/functions.js"></script>
    <script src="js/login.js"></script>
</body>
</html>