<?php
    require("./functions.php");

    
    if (isset($_POST['getPlaceOrderForm:'])) {
       echo ' <div class="form-container">
       <h3>Order Request Form</h3>
       <form class="order-form" id="order-form">
         <div class="row">
           <div class="col-sm">
             <div class="form-group">
               <label for="food-list">Select Food</label>
               <select
                 name="food-list"
                 id="food-list"
                 class="form-control"
                 required
               >
                 <option value="" disabled selected>Choose...</option>
                 <option value="chicken">Chicken</option>
                 <option value="jellof rice">Jellof Rice</option>
                 <option value="fried rice">Fried Rice</option>
               </select>
             </div>
             <div class="form-group">
               <label for="customer-username"> Customer Username </label>
               <input
                 type="text"
                 placeholder="Customer Username"
                 class="form-control"
                 id="customer-username"
                 required
               />
               <div id="check-username"></div>
             </div>
             <div class="form-group">
               <label for="amount">Amount</label>
               <input
                 type="number"
                 class="form-control"
                 id="amount"
                 placeholder="Amount"
               />
             </div>
           </div>
           <div class="col-sm">
             <div class="form-group">
               <label for="order-type">Buying or Selling</label>
               <select name="order-type" id="order-type" class="form-control">
                 <option value="" selected disabled>Choose...</option>
 
                 <option value="selling">Selling</option>
                 <option value="buying">Buying</option>
               </select>
             </div>
             <div class="form-group">
               <label for="gram">Gram</label>
               <input
                 type="number"
                 id="gram"
                 class="form-control"
                 placeholder="Gram"
               />
             </div>
             <div class="form-group">
               <label for="outcome">Outcome</label>
               <input
                 type="number"
                 id="outcome"
                 class="form-control"
                 placeholder="Outcome"
               />
             </div>
           </div>
         </div>
         <div class="show" id="show"></div>
         <div class="form-group">
           <button type="submit" class="btn btn-order" id="btn-order">
             Place Request
           </button>
         </div>
       </form>
     </div>';
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