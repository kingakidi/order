<?php
    require_once("conn.php");

    function clean($val){
        global $conn;
        return mysqli_real_escape_string($conn, strtolower(trim($val)));
    }

    function error($val){
        return "<div class='text-danger'> $val </div>";
    };
      
    function success($val) {
        return "<div class='text-success'> $val </div>";
    };

    function verifyUsername($username){
        global $conn; 
        $uQuery = $conn->query("SELECT * FROM users WHERE username='$username'"); 
        if (!$uQuery) {
            die($conn->error);
        }else{
            if ($uQuery->num_rows > 0) {
                $dbFullname = $uQuery->fetch_assoc()['fullname'];
                return true;
            }else{
                return false;
            }
        }
    }
    if (isset($_POST['verifyUsername'])) {
        extract($_POST);
        $username = clean($username);
        $uQuery = $conn->query("SELECT * FROM users WHERE username='$username'"); 
        if (!$uQuery) {
            die($conn->error);
        }else{
            if ($uQuery->num_rows > 0) {
               $dbFullname = $uQuery->fetch_assoc()['fullname'];
               echo $dbFullname;
            }else{
                echo "Invalid Username";
            }
        }
    }


   $user_id = 1;
    if (isset($_POST['sendOrder'])) {
        extract($_POST);
        $foodList = clean($foodList);
        $customerUsername = clean($customerUsername);
        $amount = clean($amount);
        $orderType = clean($orderType);
        $gram = clean($gram);
        $outcome = clean($outcome);

        if (!empty($foodList) && !empty($customerUsername) && !empty($amount) && !empty($orderType) && !empty($gram) && !empty($outcome)) {
            // VALIDITY OF USERNAME 
            if (verifyUsername($customerUsername)) {
              $addOrderQuery = $conn->query("INSERT INTO `request_table`(food_name, customer_username, merchant_id, amount, order_type, gram, outcome, status) VALUES ('$foodList', '$customerUsername', $user_id, $amount, '$orderType', $gram, $outcome, 'pending' )");

              if (!$addOrderQuery) {
                  die(error($conn->error));
              }else{
                  echo success("Order Placed Successfully");
              }
            }else{
                echo error("Invalid Username");
            }
        }else{
            echo error("All fields required");
        }
    }