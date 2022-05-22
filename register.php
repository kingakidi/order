<?php
    require("./views/header.php");
?>

    <title>Register</title>
  </head>
  <body>
    <div class="form-container">
      <h1>SignUp</h1>
      <form action="" class="signup" id="register">
        <input type="text" id="username" placeholder="Username" />
        <div id='check-username'></div>
        <input type="text" id="fullname" placeholder="Fullname" />
        <input type="email" id="email" placeholder="Email Address" />
        <input
          id="phone"
          type="number"
          name="phone"
          placeholder="Enter phone number"
        />
        <select name="bank" id="bank" class="bank">
          <option value="">Select Banks</option>
          <?php include("./control/banks.php"); ?>
        </select>
        <input
          type="number"
          name="bank-account"
          id="bank-account"
          placeholder="Bank Account Number"
        />
        
        <input type="password" id="password" placeholder="Enter Password" />
        <div id="show"></div>
        <input type="submit" id="btn-register" value="Register" />

        <div>
          Already have an account?
          <a href="login.php"> Login </a>
        </div>
      </form>
    </div>
    <script src="js/functions.js"></script>
    <script src="js/register.js"></script>
  </body>
</html>
