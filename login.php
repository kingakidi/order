<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form action="" class="login" @submit.prevent="login()">
        <input type="email" v-model="email" placeholder="Enter Email Address" />
        <input type="password" v-model="password" placeholder="Enter Password" />
        <input type="submit" value="Login" />
        <div>
            Don't have an account?
            <router-link to="/signup"> Create account </router-link>
        </div>
        <div>{{ error }}</div>
        </form>
    </div>
</body>
</html>