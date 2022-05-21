<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
<div class="form-container">
    <h1>SignUp</h1>
    <form action="" class="signup" @submit.prevent="signup()">
      <input type="text" v-model="resturantName" placeholder="Resturant Name" />
      <input
        type="email"
        v-model="resturantEmail"
        placeholder="Enter Email Address"
      />
      <input
        type="password"
        v-model="resturantPassword"
        placeholder="Enter Password"
      />
      <input type="submit" value="Register Resturant" />
      <div>
        Already have an account?
        <router-link to="/login"> Login </router-link>
      </div>
      <div>{{ error }}</div>
    </form>
  </div>
</body>
</html>