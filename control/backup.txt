<?php
    require("./functions.php");

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


    if (isset($_POST['getMerchantPaymentDetails'])) {
        extract($_POST);
        $merchantId = $getMerchantPaymentDetails;
        $merchant = merchantDetailsById($merchantId);
        $request = getRequestDetailsById($orderId);
        extract($merchant);
        extract($request);
       
        $merchant_username =  ucwords($merchant['username']);
        $grams = $request['gram'];
        $amount = $request['amount'];
        echo "<div class='single-request '> <p>$merchant_username is sending you food <br> Food Label: $outcome $food_name <br> Grams: $gram <br> Amount: $amount <hr> Make payment to <br> Account Number: $account_number <br> Bank: $bank_name <br> Fullname: $fullname</p> 
        <div id='show-status'></div>
        <div>
            <button id='btn-accept'> Accept </button>
            <button id='btn-decline'> Decline </button>
        </div> 
        </div>";
    }

    // ACCEPT ORDER  
    if (isset($_POST['acceptOrder'])) {
       extract($_POST);
       $orderId = clean($orderId);

       $uOrderQuery= $conn->query("UPDATE `request_table` SET request_table.status ='completed' WHERE request_table.id = $orderId");

       if (!$uOrderQuery) {
           die($conn->error);
       }else{
           echo "Order Submitted";
       }
    }

    // DECLINE ORDER  
    if (isset($_POST['declineOrder'])) {
        extract($_POST);
        $orderId = clean($orderId);
 
        $uOrderQuery= $conn->query("UPDATE `request_table` SET request_table.status ='declined' WHERE request_table.id = $orderId");
 
        if (!$uOrderQuery) {
            die($conn->error);
        }else{
            echo "Order Declined";
        }
     }