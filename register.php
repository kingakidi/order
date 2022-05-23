<?php
    require("./views/header.php");
    if (isset($_SESSION['oUsername'])) {
      header("Location: ./index.php");
    }
?>
    <title>Register</title>
  </head>
  <body>
    <div class="form-container">
     
      <h1>SignUp</h1>
      <form action="" class="signup" id="register">
        <input type="text" class='order-input' id="username" placeholder="Username" />
        <div id='check-username'></div>
        <input type="text" class="order-input" id="fullname" placeholder="Fullname" />
        <input type="email" class="order-input" id="email" placeholder="Email Address" />
        <input
          id="phone"
          type="number" class="order-input"
          name="phone"
          placeholder="Enter phone number"
        />
        <select class="input-order" name="bank" id="bank" >
          <option value="">Select Banks</option>
            <?php $curl = curl_init();
                  curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.flutterwave.com/v3/banks/ng",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                      "Authorization: Bearer $flutterLiveSecretKey"
                  ),
                  ));
                  $response = curl_exec($curl);

                  curl_close($curl);
                  $res = json_decode($response, true);
                  
                  //  echo "<option value=''> Choose Bank </option>";
                  foreach ($res['data'] as $banksArray) {
                  $bankName = $banksArray['name'];
                  $bankCode = $banksArray['code'];
                      echo "<option data-bank-name='$bankName' value='$bankName'> $bankName </option>";
                  }
            ?>
        </select>
        <input
          type="number"
          name="bank-account"
          id="bank-account" class="order-input"
          placeholder="Bank Account Number"

        />
        
        <input type="password" class="order-input" id="password" placeholder="Enter Password" />
        <div id="show"></div>
        <input type="submit" class="btn btn-order" id="btn-register" value="Register" />

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
