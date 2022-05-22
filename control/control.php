<?php
    require("./functions.php");


    if (isset($_POST['registerUser'])) {
       extract($_POST);
        $username = clean($username);
        $fullname = clean($fullname);
        $email = clean($email);
        $phone = clean($phone); 
        $bank = clean($bank);
        $account = clean($account); 
        $password = ($password);

        if (!empty($username) AND !empty($fullname) AND !empty($email) AND !empty($phone) AND !empty($bank) AND !empty($account) AND !empty($password)) {
           
            // CHECK FOR VALID USERNAME 
           if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo error("Invalid Email Address");
           }else if (usernameCheck($username)) {
                echo error("Username already exist");
            } else if(emailCheck($email)){
                echo error("Email already Exist ");
            }else if(phoneCheck($phone)){
                echo error("Phone number already exist");
            }else if (strlen($password) < 6) {
                echo error("Password is too short");
            }else{
                    $nPassword = password_hash($password, PASSWORD_DEFAULT);

                    $query = $conn->query("INSERT INTO users (username, fullname, email, phone, bank_code, account_number, users.password) VALUES ('$username', '$fullname','$email', '$phone', '$bank', '$account', '$nPassword')"); 

                    if (!$query) {
                        die($conn->error);
                    }else{
                        echo "Register Successfully";
                    }

                // SEND ALL TO DB 

            }

            // CHECK FOR PASSWORD LENGTH  

            // CHECK FOR EMAIL VALIDITY 
        }else{
            echo error("All fields required");
        }
    }

    // LOGIN USER 
    if (isset($_POST['loginUser'])) {
       extract($_POST);
       $username = clean($username);

        // CHECK FOR USERNAME EXIST 
        $uQuery = $conn->query("SELECT * FROM users WHERE users.username = '$username' LIMIT 1");

        if (!$uQuery) {
            die("Unable to verify username");
        }else{
            if($uQuery->num_rows > 0){
                $row = $uQuery->fetch_assoc();
                // CHECK ELIGIBILITY TO LOGIN 
                if($row['status']){
                    
                        if (password_verify($password, $row['password'])) {
                        //    SET THE SESSIONS AND ECHO SUCCESS 
                            $_SESSION['oUsername'] = $row['username'];
                            $_SESSION['oEmail'] = $row['email'];
                            $_SESSION['phone'] = $row['phone'];
                            echo "Login Successfully";
                        }else{
                            echo error("Invalid Password");
                        }
                }else{
                    echo error("Account Suspended Contact Admin for support");
                }
            }else{
               echo error("Username does not exist");
            }
        }
        


    }


    // PLACE ORDER REQUEST
    if (isset($_POST['getPlaceOrderForm'])) {
       echo "<div class='form-container'>
        <h3>Food Request Form</h3>
        <form class='order-form' id='order-form'>
            <div class='row'>
            <div class='col-sm'>
                <div class='form-group'>
                <label for='food-list'>Select Food</label>
                <select
                    name='food-list'
                    id='food-list'
                    class='form-control'
                    required
                >
                    <option value='' disabled selected>Choose...</option>
                    <option value='chicken'>Chicken</option>
                    <option value='jellof rice'>Jellof Rice</option>
                    <option value='fried rice'>Fried Rice</option>
                </select>
                </div>
                <div class='form-group'>
                <label for='customer-username' class='mt-3'> Customer Username </label>
                <input
                    type='text'
                    placeholder='Customer Username'
                    class='form-control order-input'
                    id='customer-username'
                    required
                />
                <div id='check-username'></div>
                </div>
                <div class='form-group'>
                <label for='amount' class='mt-3'>Amount</label>
                <input
                    type='number'
                    class='form-control'
                    id='amount'
                    placeholder='Amount'
                />
                </div>
            </div>
            <div class='col-sm'>
                <div class='form-group'>
                <label for='order-type'>Buying or Selling</label>
                <select name='order-type' id='order-type' class='form-control'>
                    <option value='' selected disabled>Choose...</option>
    
                    <option value='selling'>Selling</option>
                    <option value='buying'>Buying</option>
                </select>
                </div>
                <div class='form-group'>
                <label for='gram'>Gram</label>
                <input
                    type='number'
                    id='gram'
                    class='form-control'
                    placeholder='Gram'
                />
                </div>
                <div class='form-group'>
                <label for='outcome'>Outcome</label>
                <input
                    type='number'
                    id='outcome'
                    class='form-control'
                    placeholder='Outcome'
                />
                </div>
            </div>
            </div>
            <div class='show' id='show'></div>
            <div class='form-group'>
            <button type='submit' class='btn btn-order' id='btn-order'>
                Place Request
            </button>
            </div>
        </form>
        </div>";
    }

    // TRANSCACTION TABLE REQUEST 
    if (isset($_POST['getTransactionTable'])) {
        echo " <div class='order-list'>
      <h3>Transaction List</h3>
       <div class='list-container'>";
       
          $myOrdersQuery = $conn->query("SELECT * FROM request_table WHERE customer_username='$username' ORDER BY request_table.id DESC");
          if (!$myOrdersQuery) {
           die($conn->error);
          }else{
            if ($myOrdersQuery->num_rows > 0 ) {
           
              
            $sn = 1;
            while ($row = $myOrdersQuery->fetch_assoc()) {
              extract($row);
              $merchant = merchantDetailsById($merchant_id);
              $merchant_username =  ucwords($merchant['username']);
              echo "<ol class='single-list $status'>
              <li> $sn </li>
              <li> $merchant_username is sending you $outcome $food_name </li>";

              if ($status === 'pending') {
                echo "<li><button class='btn-order-pending' data-merchant-id=$merchant_id data-order-id=$id name='btn-pending-orders'> Click here to view and Accept </button></li>
             ";
              }else if($status === 'completed'){
                echo "<li><button class='btn-order-completed' disabled> Completed </button></li>
                ";
              }else if($status === 'declined'){
                echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                ";
              }
              echo '</ol>';
              $sn++;
            }
           
            }else{
              echo '<h3> You have no order at the moment </h3>';
            }
            
          }
        
     
        echo "</div>
    </div>";
    }






    if (isset($_POST['verifyUsernameExist'])) {
        extract($_POST);
        $username = clean($username);
        $uQuery = $conn->query("SELECT * FROM users WHERE username='$username'"); 
        if (!$uQuery) {
            die($conn->error);
        }else{
            if ($uQuery->num_rows > 0) {
               echo "Username is taken";
            }else{
                echo "Username is valid";
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