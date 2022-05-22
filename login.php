<?php
    require("./views/header.php");
?>
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form action="" class="login" @submit.prevent="login()">
            <input type="email" id="email" placeholder="Enter Email Address" />
            <input type="password" v-model="password" placeholder="Enter Password" />
            <input type="submit" value="Login" />
            <div>
                Don't have an account?
                <a href="register.php"> Create account </a>
            </div>
            <div></div>
        </form>
    </div>
</body>
</html>