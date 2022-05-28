<?php
    require("./functions.php");


    // REGISTER USER 

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

    // ESCROW SIGNUP 
    if (isset($_POST['escrowUserRegister'])) {
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
 
                     $query = $conn->query("INSERT INTO users (username, fullname, email, phone, bank_code, account_number, users.password, users.status, user_type) VALUES ('$username', '$fullname','$email', '$phone', '$bank', '$account', '$nPassword', 0, 'escrow')"); 
 
                     if (!$query) {
                         die($conn->error);
                     }else{
                         echo "Escrow Application Submitted for approval";
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
            die($conn->error ."Unable to verify username");
        }else{
            if($uQuery->num_rows > 0){
                $row = $uQuery->fetch_assoc();
                // CHECK ELIGIBILITY TO LOGIN 
                if($row['status']){
                    
                        if (password_verify($password, $row['password'])) {
                        //    SET THE SESSIONS AND ECHO SUCCESS 
                            $_SESSION['oUsername'] = $row['username'];
                            $_SESSION['oId'] = $row['id'];

                            $_SESSION['oEmail'] = $row['email'];
                            $_SESSION['phone'] = $row['phone'];
                            $_SESSION['oUserType'] = $row['user_type'];
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
                <label for='food-list' class='mt-3'>Select Food</label>
                <select
                    name='food-list'
                    id='food-list'
                    class='order-input'
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
                    class='order-input order-inputt mb-0'
                    id='customer-username'
                    required
                />
                <div id='check-username'></div>
                </div>
                <div class='form-group'>
                <label for='amount' class='mt-3'>Amount</label>
                <input
                    type='number'
                    class='order-input'
                    id='amount'
                    placeholder='Amount'
                />
                </div>
            </div>
            <div class='col-sm'>
                <div class='form-group'>
                <label for='order-type' class='mt-3'>Buying or Selling</label>
                <select name='order-type' id='order-type' class='order-input'>
                    <option value='' selected disabled>Choose...</option>
    
                    <option value='selling'>Selling</option>
                    <option value='buying'>Buying</option>
                </select>
                </div>
                <div class='form-group'>
                <label for='gram' class='mt-3'>Gram</label>
                <input
                    type='number'
                    id='gram'
                    class='order-input order-inputt'
                    placeholder='Gram'
                />
                </div>
                <div class='form-group'>
                <label for='outcome' class='mt-3'>Outcome</label>
                <input
                    type='number'
                    id='outcome'
                    class='order-input'
                    placeholder='Outcome'
                />
                </div>
            </div>
            </div>
            <div>
            <label for='escrow'> Select Escrow </label>
            <select name='' id='escrow' class='order-input' required>
              <option value='' selected disabled>Select Escrow</option>";
              
                $eQuery = $conn->query("SELECT * FROM users WHERE user_type = 'escrow' AND users.status = 1");
                if (!$eQuery) {
                    die($conn->error);
                }else{
                    while ($row = $eQuery->fetch_assoc()) {
                    extract($row);

                    echo "<option value='$id'> $username </option>";
                    }
                }
    
            echo "</select>
                <div id='show-escrow'> </div>
            </div>";
         
            echo "<div class='show' id='show'></div>
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
        $username = $_SESSION['oUsername'];
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
                    echo "<li><button class='btn-order-pending' data-merchant-id=$merchant_id data-escrow-id='$escrow_id' data-order-id=$id name='btn-pending-orders'> Click here to view and Accept </button></li>
                ";
                }else if($status === 'completed'){
                    echo "<li><button class='btn-order-completed' disabled> Completed </button></li>
                    ";
                }else if($status === 'declined'){
                    echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                    ";
                }else{
                    // CHECK IF IT'S IN TRANSIT 
                    $transCheckQuery = $conn->query("SELECT * FROM transit_transaction WHERE request_id = $id "); 
                    if (!$transCheckQuery) {
                        die($conn->error);
                    }else{
                        if ($transCheckQuery->num_rows > 0) {
                            $transRow = $transCheckQuery->fetch_assoc();
                            $transit_level = (int)$transRow['transit_level'];
                            if ($transit_level === 3) {
                                echo "<li><button class='btn-order-pending' data-merchant-id=$merchant_id data-escrow-id='$escrow_id' data-order-id=$id name='btn-final-transaction-customer-approval' disabled> Awaiting Escrow Approval </button></li>
                            ";
                            }else{
                                echo "<li><button class='btn-order-declined await-seller' disabled> $status </button></li>";
                            }
                            
                        }else{
                            echo "<li><button class='btn-order-declined' disabled> $status </button></li>
                            ";
                        }
                    }
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
               echo success(ucwords($dbFullname));
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
        $user_id = $_SESSION['oId'];
        $escrow = clean($escrow);
        $customerId = merchantDetailsByUsername($customerUsername)['id'];

        if (!empty($foodList) && !empty($customerUsername) && !empty($amount) && !empty($orderType) && !empty($gram) && !empty($outcome) && !empty($escrow)) {
            // VALIDITY OF USERNAME 
            // CHECK IF THE ESCROW IS NOT THE SENDER 
            
            
            if ($_SESSION['oUsername'] === $customerUsername) {
                echo error("You can't send item to yourself");
            }else if($_SESSION['oId'] === $escrow){
                echo error("You can't use yourself as Escrow  merchant on your sells");
            }else if($escrow === $customerId){
                echo error("Error: Receiver and Escrow merchant is can't be the same");
            }else{
                if (verifyUsername($customerUsername)) {
                    $addOrderQuery = $conn->query("INSERT INTO `request_table`(food_name, customer_username, merchant_id, escrow_id, amount, order_type, gram, outcome, request_table.status) VALUES ('$foodList', '$customerUsername', $user_id, $escrow, $amount, '$orderType', $gram, $outcome, 'pending' )");
        
                    if (!$addOrderQuery) {
                        die(error($conn->error));
                    }else{
                        echo success("Order Placed Successfully");
                    }
                }else{
                    echo error("Invalid Username");
                }
            }
           
        }else{
            echo error("All fields required");
        }
    }


    if (isset($_POST['getMerchantPaymentDetails'])) {
        extract($_POST);
         
        $escrow = merchantDetailsById($escrowId);
        $merchant = merchantDetailsById($merchantId);

        $request = getRequestDetailsById($orderId);
        extract($merchant);
        extract($request);
        $TRX_TRACK_ID = strtoupper(bin2hex(random_bytes(5)));
        
        $merchant_username =  ucwords($merchant['username']);
        $grams = $request['gram'];
        $amount = $request['amount'];
        $fullname = ucwords($fullname);

        $escrow_account_number = $escrow['account_number']; 
        $escrow_bank_code = $escrow['bank_code']; 
        $escrow_fullname = $escrow['fullname']; 



        echo "<input type='text' value='$TRX_TRACK_ID' id='trx_track_id' hidden /><div class='single-request '><p>$merchant_username is sending you food <br> Food Label: $outcome $food_name <br> Grams: $gram <br> Amount: $amount <hr>  </p> <p> Escrow Payment Details  <br> Account Number: $escrow_account_number <br> Bank: $escrow_bank_code <br> Fullname: $escrow_fullname <br> TXT_TRCK_ID: $TRX_TRACK_ID</p>
        <div class='m-3'>
            <label> Select Receipt </label>
            <input type='file' id='receipt' class='input-order' />
        </div>
        <div id ='imagePreview'></div>
        <div id='show-status'></div>
        <div>
            <button id='btn-accept'> Accept </button>
            <button id='btn-decline'> Decline </button>
        </div> 
        </p>";
    }

    // ACCEPT ORDER  
    if (isset($_POST['acceptOrder'])) {
        extract($_POST);
        
        $rName = $_FILES['customerReceipt']['name'];
        $size = $_FILES['customerReceipt']['size'];
        $type = $_FILES['customerReceipt']['type'];
        $tmp = $_FILES['customerReceipt']['tmp_name'];

        $orderId = clean($orderId);
        $trxTrackId = clean($trxTrackId);
        $request = getRequestDetailsById($orderId);
        extract($request);

        $customerId = merchantDetailsByUsername($customer_username)['id'];
       
        
        
        if (!empty($_FILES['customerReceipt']) && !empty($trxTrackId) && !empty($orderId)) {

            // CHECK THE FILE SIZE 
            $ext = pathinfo($rName, PATHINFO_EXTENSION);
                        
            $nCFName = $trxTrackId."_CustomerReceipt_".date("d-m-Y").".".$ext;
            $cd = dirname(__DIR__, 1);
            if (move_uploaded_file($tmp, $cd."/receipts/customer/$nCFName")) {
                // CHECK IF REQUEST ID ALREADY EXIST 
                $rCheckQuery = $conn->query("SELECT * FROM transit_transaction WHERE request_id = $orderId");
        
                if (!$rCheckQuery) {
                    die($conn->error);
                }else{
                    if ($rCheckQuery->num_rows > 0) {
                        echo error("Order Already Submitted for this request");
                    }else{
                        $uOrderQuery = mysqli_multi_query($conn, "INSERT INTO `transit_transaction`(request_id, `escrow_id`, `customer_id`, `merchant_id`, `trx_track_id`, `status`, `transit_level`, customer_receipt) VALUES ($id, $escrow_id, $customerId, $merchant_id, '$trxTrackId', 'Awaiting Seller Payment ', 1, '$nCFName'); UPDATE request_table SET request_table.status = 'Awaiting Seller Payment' WHERE request_table.id = $orderId");

                        if (!$uOrderQuery) {
                            die($conn->error);
                        }else{
                            echo "Order Submitted";
                        }
                    }
                }
            }
            
        }else{
            echo error("All fields required");
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


    // GET ALL USERNAME
    if (isset($_POST['getAllUsers'])) {
        // CHECK IF USERTYPE IS ADMIN 
        if ($_SESSION['oUserType'] === 'admin') {
            $query = $conn->query("SELECT * FROM users ORDER BY users.id DESC"); 

            if (!$query) {
                die($conn->error);
            }else{
                echo '<div class="list-container"> 
                    <ol class="single-list" id="userTable">
                    
                        <li>S/N</li>
                        <li>EMAIL</li>
                        <li>PHONE</li>
                        <li>USER TYPE</li>
                        <li>STATUS</li>
                        
                    </ol>';
                                        
                    $sn = 0;
                    while ($row = mysqli_fetch_assoc($query)) {

                        $sn++;
                        $id = $row['id'];
                        $e = $row['email'];
                        $p = $row['phone'];
                        $s = $row['status'];
                        $time = $row['created_at'];
                        $usertype = ucwords($row['user_type']);
                        echo "<ol class='single-list'>
                            <li>$sn</li>
                            <li><a href='?view=$e&userid=$id' class='users-link' id='$id' name='viewuser'> $e </a></li>
                            <li>$p</li>
                            <li>$usertype</li>
                        
                            <li>";

                            if ($s === '1') {
                                echo "Active";
                            } else{
                                echo "Deactivated";
                            }
                    echo "</li>                       
                        </ol>";
                    }
                
            }
        } else{
            echo error("Invalid Access");
        }
    }
    
    // USER STATUS FORM 
    // CHANGE USER STATUS 
    if (isset($_POST['userStatusForm'])) {
        $userId = $_POST['userId'];
        echo '<form class="userStatusForm" id="userStatusForm">
                <div class="form-group">
                <label for="password"> Password</label>
                    <input type="password" class="order-input order-inputt" placeholder="Password" id="password">
                </div>
                <div class="userStatusForm-error text-light" id="userStatusForm-error">
                </div>
                <div class="form-group text-right" >
                    
                    <button type="submit" class="btn order-input btn-order" id="submit-userStatusForm">CHANGE STATUS</button>
                </div>
        </form>';
    }

    // USER STATUS UPDATE 
    if (isset($_POST['userStatusUpdate'])) {
        $statusUserId = clean($_POST['id']);
        $password = $_POST['password'];
        // CHECK IF THE PASSWORD IS CORRECT 
            if ($password !== "") {
                
                $pQuery = mysqli_query($conn, "SELECT * FROM users WHERE id='$statusUserId'");
                if (!$pQuery) {
                    die("PASSWORD VERIFICATION FIALED ".mysqli_error($conn));
                }else {
                    $dbPassword = mysqli_fetch_assoc($pQuery)['password'];
                    
                    if (password_verify($password, $dbPassword)) {
                        
                        $uSQ = mysqli_query($conn, "SELECT * FROM users WHERE id=$statusUserId");
                        if (!$uSQ) {
                            die("UNABLE TO FETCH USER DETAILS ".mysqli_error($conn));
                        }else{
    
                            
                            $status = mysqli_fetch_assoc($uSQ)['status'];
                            if ($status === '1') {
                                $cSQuery = mysqli_query($conn, "UPDATE users SET status=0 WHERE id=$statusUserId");
                                if (!$cSQuery) {
                                    die("FAILED TO UPDATE USERS STATUS ".mysqli_error($conn));
                                }else{
                                    echo '<span class="text-success">STATUS CHANGED SUCCESSFULLY</span>';
                                }
                                
                            }else{
                                $cSQuery = mysqli_query($conn, "UPDATE users SET status=1 WHERE id=$statusUserId");
                                if (!$cSQuery) {
                                    die("FAILED TO UPDATE USERS STATUS ".mysqli_error($conn));
                                }else{
                                    echo '<span class="text-success">STATUS CHANGED SUCCESSFULLY</span>';
                                }
                            }
                        }
                // TOGGLE THE STATUS 
                        
                    }else{
                        echo '<span class="text-danger">INVALID PASSWORD</span>';
    
                    }
                }
            }else{
                echo '<span class="text-danger">PASSWORD IS REQUIRED</span>';
            }
    
    }
    
    if (isset($_POST['userTypeForm'])) {
        echo '<form class="userTypeForm" id="userTypeForm">
        <div class="form-group">
            <select  id="typeOption" class="order-input">
                <option value="" selected disabled>SELECT TYPE</option>
                <option value="user">User</option>
                <option value="editor">Editor</option>
                <option value="admin">Admin</option>
                <option value="Super Admin"> Super Admin </option>
            </select>
        </div>
        <div class="form-group">
            <input type="password" class="order-input" id="password" placeholder="Password">
        </div>
        <div class="userTypeForm-error error" id="userTypeForm-error"></div>
        <div class="form-group text-right">
            <button  type="submit" id="sTFBtn" class="btn btn-order order-input">CHANGE TYPE</button>
        </div>
        </form>';
    }

    if (isset($_POST['uUType'])) {
        $userId =  $_SESSION['oId'];
        $id = clean($_POST['id']);
        $type  = clean(($_POST['type']));
        $password = $_POST['password'];
        // CHECK FOR EMPTY FIELD 
        if ($type !=="" AND $password !=="" ) {
            $pQuery = mysqli_query($conn, "SELECT * FROM users WHERE id=$userId");
            if (!$pQuery) {
                die("PASSWORD VERIFICATION FIALED ".mysqli_error($conn));
            }else {
                $dbPassword = mysqli_fetch_assoc($pQuery)['password'];
                if (password_verify($password, $dbPassword)) {
                    // IF CORRECT SET UPDATE TO USER TYPE 
        
                    $updateUTQuery = mysqli_query($conn, "UPDATE `users` SET `user_type`='$type' WHERE id=$id");
                    if (!$updateUTQuery) {
                        die("UNABLE TO UPDATE USER TYPE ".mysqli_error($conn));
                    }else{
                        echo "<span class='text-success'>USER TYPE CHANGE SUCCESSFULLY</span>";
                    }
                }else{
                    echo "<span class='text-danger'>INVALID PASSWORD</span>";
                }
            }
        }else{
            echo "ALL FIELD REQUIRED";
        }
        
    }

    // FETCH USER DETAILS 

    if (isset($_POST['fetchSingleUser'])) {
      
        $userid = clean($_POST['userid']);
        $suQuery = mysqli_query($conn, "SELECT * FROM users WHERE id=$userid");
        if (!$suQuery) {
            die("UNABLE TO FETCH USERS ".mysqli_error($conn));
        }else {
            $row = mysqli_fetch_assoc($suQuery);
            $id = $row['id']; 
            $fullname =ucwords($row['fullname']);
            $e = $row['email'];
            $phone = $row['phone']; 
            $usertype = ucwords($row['user_type']);
            $status = $row['status'];
            if ($status === "0") {
                $showStatus = 'Deactivated';
            }else if($status === "1"){
                $showStatus = "Active";
            }
            
           echo "
             <div class='m-3'>
            <input type='text' name='' id='email' value='$e' class='order-input' style='visibility:hidden' disabled>
              
            <div class='form-group'>
                    <label class='label'> Action: <label>
               
              </div>
              
                <div class='form-group'>               
                  <select name='users-actions' id='user-actions' class='order-input order-inputt'>
                      <option value='' selected disabled>SELECT ACTION</option>
                      <option value='status'>CHANGE STATUS</option>
                      <option value='usertype'>CHANGE USERTYPE</option>
                  </select>     
              </div> 
              <div class='show-action ml-3 mr-3' id='show-popup-actions'></div>             
          </div>
         
            
          <div class='list-container text-light'> 
            <ol class='single-list'>
                <li>FULLNAME</li>
                <li> $fullname </li>
            </ol>
            <ol class='single-list'>
                <li>EMAIL ADDRESS</li>
                <li> $e</li>
            </ol>
            <ol class='single-list'>
                <li>PHONE</li>
                <li>  $phone</li>
            </ol>
            <ol class='single-list'>
                <li>USERTYPE</li>
                <li> $usertype</li>
            </ol>
            <ol class='single-list'>
                <li>STATUS</li>
                <li> $showStatus </li>
            </ol>
          
          </div>
          ";
           
        }

        
    }
 
    
    // BEGINNING OF REQUEST 
    if (isset($_POST['userOrderSentRequest'])) {
        
        $user_id = $_SESSION['oId'];
        
        echo " <div class='order-list'>
        <h3>Send Request List</h3>
        <div class='list-container'>";
        
            $myOrdersQuery = $conn->query("SELECT * FROM request_table WHERE merchant_id=$user_id ORDER BY request_table.id DESC");
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
                    <li> Kindly send $customer_username,  $outcome $food_name and confirm </li>";

                    if ($status === 'pending') {
                        echo "<li><button class='btn-order-pending' disabled> Pending </button></li>";
                    }else if($status === 'completed'){
                       
                        echo "<li><button class='btn-order-completed' data-merchant-id=$merchant_id data-order-id=$id name='btn-pending-orders'> Accepted </button></li>";
                    }else if($status === 'declined'){
                        echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                        ";
                    }else {

                         // CHECK IF IT'S IN TRANSIT 
                    $transCheckQuery = $conn->query("SELECT * FROM transit_transaction WHERE request_id = $id "); 
                    if (!$transCheckQuery) {
                        die($conn->error);
                    }else{
                        if ($transCheckQuery->num_rows > 0) {
                            $transRow = $transCheckQuery->fetch_assoc();
                            
                            $transit_level = (int)$transRow['transit_level'];
                            $transit_id = $transRow['id'];
                            if ($transit_level === 1) {
                                echo "<li><button class='btn-order-pending' data-merchant-id=$merchant_id data-escrow-id='$escrow_id' data-order-id=$id name='btn-final-transaction-merchant-approval' data-transaction-track-id='$transit_id'> Click here to confirm delivery and Payment </button></li>
                            ";
                            }else{
                                echo "<li><button class='btn-order-declined await-seller' disabled> $status </button></li>";
                            }
                            
                        }else{
                            echo "<li><button class='btn-order-declined' disabled> $status </button></li>
                            ";
                        }
                    }
                       
                    }
                    echo '</ol>';
                    $sn++;
                    }
            
                }else{
                    echo "<h2> You have not send any request! </h2>";
                }
                
            }
            
        
            echo "</div>
        </div>";
    }


    // COMPLETED REQUEST 
    if (isset($_POST['userOrderCompletedRequest'])) {
        
        $user_id = $_SESSION['oId'];
        
        echo " <div class='order-list'>
        <h3>Completed Request List</h3>
        <div class='list-container'>";
        
            $myOrdersQuery = $conn->query("SELECT * FROM request_table WHERE merchant_id=$user_id AND request_table.status = 'completed' ORDER BY request_table.id DESC");
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
                    <li> you send $customer_username $outcome $food_name </li>";

                    if ($status === 'pending') {
                        echo "<li><button class='btn-order-pending' disabled> Pending </button></li>";
                    }else if($status === 'completed'){
                       
                        echo "<li><button class='btn-order-completed' data-merchant-id=$merchant_id data-order-id=$id name='btn-pending-orders'> Accepted </button></li>";
                    }else if($status === 'declined'){
                        echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                        ";
                    }
                    echo '</ol>';
                    $sn++;
                    }
            
                }else{
                    echo "<h2> You don't have any completed request at the moment </h2>";
                }
                
            }
            
        
            echo "</div>
        </div>";
    }

    // PENDING REQUEST 
    if (isset($_POST['userOrderPendingRequest'])) {
        
        $user_id = $_SESSION['oId'];
        
        echo " <div class='order-list'>
            <h3>Pending Request List</h3>
            <div class='list-container'>";
            
                $myOrdersQuery = $conn->query("SELECT * FROM request_table WHERE merchant_id=$user_id AND request_table.status = 'pending' ORDER BY request_table.id DESC");
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
                        <li> you send $customer_username $outcome $food_name </li>";

                        if ($status === 'pending') {
                            echo "<li><button class='btn-order-pending' disabled> Pending </button></li>";
                        }else if($status === 'completed'){
                        
                            echo "<li><button class='btn-order-completed' data-merchant-id=$merchant_id data-order-id=$id name='btn-pending-orders'> Accepted </button></li>";
                        }else if($status === 'declined'){
                            echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                            ";
                        }
                        echo '</ol>';
                        $sn++;
                        }
                
                    }else{
                        echo "<h2> You don't have any pending request at the moment </h2>";
                    }
                    
                }
                
            
                echo "</div>
            </div>";
    }

    if (isset($_POST['userOrderDeclineRequest'])) {
        
        $user_id = $_SESSION['oId'];
        
        echo " <div class='order-list'>
            <h3>Pending Request List</h3>
            <div class='list-container'>";
            
                $myOrdersQuery = $conn->query("SELECT * FROM request_table WHERE merchant_id=$user_id AND request_table.status = 'declined' ORDER BY request_table.id DESC");
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
                        <li> you send $customer_username $outcome $food_name </li>";

                        if ($status === 'pending') {
                            echo "<li><button class='btn-order-pending' disabled> Pending </button></li>";
                        }else if($status === 'completed'){
                        
                            echo "<li><button class='btn-order-completed' data-merchant-id=$merchant_id data-order-id=$id name='btn-pending-orders'> Accepted </button></li>";
                        }else if($status === 'declined'){
                            echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                            ";
                        }
                        echo '</ol>';
                        $sn++;
                        }
                
                    }else{
                        echo "<h2> You don't have any pending request at the moment </h2>";
                    }
                    
                }
                
            
                echo "</div>
            </div>";
    }

    
    if (isset($_POST['getEscrowDetails'])) {
        $escrowId = clean($_POST['id']);
        $escrowDetails = merchantDetailsById($escrowId);

        echo success(ucwords($escrowDetails['fullname']));
    }


    // GET ALL ESCROW REQUEST 
    if (isset($_POST['getAllUsersEscrow'])) {
     
        $escrow_id = $_SESSION['oId'];
        $gQuery = $conn->query("SELECT * FROM transit_transaction WHERE escrow_id = $escrow_id ");

        if (!$gQuery) {
            die($conn->error);
        }else{
            if ($gQuery->num_rows > 0) {
                echo " <div class='order-list'>
                <h3>Send Request List</h3>
                <div class='list-container'>
                <ol class='single-list' id='userTable'>
                    
                        <li>S/N</li>
                        <li>Seller</li>
                        <li>Buyer</li>
                        <li>Transaction Track Id</li>
                        <li> Status/Action</li>
                        
                    </ol>';";
                
                $sn = 1;
                while ($row = $gQuery->fetch_assoc()) {
                    extract($row);
                    $transit_level = (int)$transit_level;
                    $merchant = merchantDetailsById($merchant_id);
                    $customer = merchantDetailsById($customer_id);

                    $merchant_username =  ucwords($merchant['username']);
                    $customer_username =  ucwords($customer['username']);

                 
                    echo "<ol class='single-list pending'>
                    <li> $sn </li>
                    <li> $merchant_username </li>
                    <li> $customer_username </li>

                    
                    <li> $trx_track_id </li>";
                    
                    if ($transit_level === 1) {
                        echo "<li><button class='btn-order-pending' disabled> Awaiting Seller Payment </button></li>";
                      
                        
                    }else if($transit_level === 2){
                    
                        echo "<li><button class='btn-order-pending'  disabled> $status  </button></li>";
                    }else if($transit_level === 3){
                        echo "<li><button class='btn-order-pending' data-order-id=$request_id data-transaction-track-id=$id  name='btn-escrow-approval'> Awaiting your approval </button></li>
                        ";
                    }else if($transit_level > 3){
                        echo "<li><button class='btn-order-pending $status'> $status </button></li>
                        ";
                    }
                    echo '</ol>';
                    $sn++;
                            
                        
                    }                
                    echo "</div>
                </div>";
             
            }else{
                echo error("<h3 class='text-center'>You have no Escrow transaction a the moment </h3>");
            }
        }
    }

    // GET ESCROW DETAILS 

    if (isset($_POST['getEscrowTransactionDetails'])) {
        extract($_POST);

        $transaction = getTransitTransactionById($transId);
        
        // print_r($transaction);
        extract($transaction);
        $merchant_username = merchantDetailsById($merchant_id);
        $customer_username = merchantDetailsById($customer_id)['username'];
        $trx_track_id = strtoupper($trx_track_id);
        
         $request = getRequestDetailsById($request_id);
        
        extract($request);
     
        
       
        $grams = $request['gram'];
        $amount = $request['amount'];
        $fullname = ucwords($fullname);
        echo "<input type='text' value='$trx_track_id' data-order-id=$request_id id='trx_track_id' hidden /><div class='single-request '><p>$customer_username has initiate payment   transaction track id: $trx_track_id Amount: $amount,  Gram: $grams. Kindly acknowlege </p> 
        <div id='imagePreview' class='d-flex flex-column '> 
          
            Buyer's Receipt <br>
            <img src='./receipts/customer/$customer_receipt' width='100%' height='100%'/>
            
        </div>
        <div><a href='./receipts/customer/$customer_receipt' target='_blank'>Preview Buyer's Receipt </a></div>
        <div id='imagePreview1' class='d-flex flex-column' style='width: 120px'> 
            Seller's Receipt <br>
            <img src='./receipts/seller/$seller_receipt' width='100%' height='100%'/>
            
        </div>
        <div> <a href='./receipts/seller/$seller_receipt' target='_blank'>  Preview Seller's Receipt </a> </div>
        <div id='show-status'></div>
        <div>
            <button id='btn-accept'> Approve </button>
            <button id='btn-decline'> Decline </button>
        </div> 
        </div>";

    }

    // ACCEPT ORDER  
    if (isset($_POST['escrowAcceptCustomerPayment'])) {
      
        extract($_POST);
        $orderId = clean($orderId);
        $trxTrackId = clean($trxTrackId);
        // $request = getRequestDetailsById($orderId);
        // extract($request);

        // $customerId = merchantDetailsByUsername($customer_username)['id'];
       
        // CHECK IF REQUEST ID ALREADY EXIST 
        
    
        $rCheckQuery = $conn->query("SELECT * FROM transit_transaction WHERE request_id = $orderId");
        
        if (!$rCheckQuery) {
            die(" Transaction Check Failed ".$conn->error);
        }else{
            if ($rCheckQuery->num_rows > 0) {
                $updateTQuery = mysqli_multi_query($conn, "UPDATE `transit_transaction` SET `status`='completed',`transit_level`='4',`updated_at`= NOW() WHERE request_id = $orderId; UPDATE request_table SET request_table.status = 'completed' WHERE request_table.id = $orderId");


                if (!$updateTQuery) {
                    die("Updating Transit Failed " .$conn->error);
                }else{
                    echo "Submitted Successfully";
                }
            }else{
               echo error("Invalid Transaction Request");
            }
        }
        
        
   
       
    }

    // DECLINE ORDER  
    if (isset($_POST['escrowRejectCustomerPayment'])) {
        extract($_POST);
        $orderId = clean($orderId);
 
        $uOrderQuery= $conn->query("UPDATE `request_table` SET request_table.status ='declined' WHERE request_table.id = $orderId");
 
        if (!$uOrderQuery) {
            die($conn->error);
        }else{
            echo "Order Declined";
        }
    }

    // FINAL DELIVERY APPROVAL 
    if (isset($_POST['approvedDelivery'])) {
        extract($_POST);
        $orderId = clean($orderId);

        $dAQuery= mysqli_multi_query($conn, "UPDATE transit_transaction SET transit_level = 4, transit_transaction.status = 'completed' WHERE transit_transaction.request_id = $orderId; UPDATE request_table SET request_table.status = 'completed' WHERE request_table.id = $orderId");

        if (!$dAQuery) {
            die($conn->error);
        }else{
            echo "Delivery approved successfully";
        }
    }

    
    // MERCHANT DELIVERY APPROVAL 
    if (isset($_POST['merchantDeliveryApproval'])) {
        extract($_POST);
        $orderId = (int)clean($orderId);
        $rName = $_FILES['merchantReceipt']['name'];
        $size = $_FILES['merchantReceipt']['size'];
        $type = $_FILES['merchantReceipt']['type'];
        $tmp = $_FILES['merchantReceipt']['tmp_name'];
      

        if (!empty($_FILES['merchantReceipt']) && !empty($trxTrackId) && !empty($orderId)) {

            // CHECK THE FILE SIZE 
            $ext = pathinfo($rName, PATHINFO_EXTENSION);
                        
            $nCFName = $trxTrackId."_MerchantReceipt_".date("d-m-Y").".".$ext;
            $cd = dirname(__DIR__, 1);
            if (move_uploaded_file($tmp, $cd."/receipts/seller/$nCFName")) {
                // CHECK IF REQUEST ID ALREADY EXIST 
                 $dAQuery= mysqli_multi_query($conn, "UPDATE transit_transaction SET transit_level = 3, transit_transaction.status = 'Awaiting Escrow Approval', seller_receipt = '$nCFName' WHERE transit_transaction.request_id = $orderId; UPDATE request_table SET request_table.status = 'Awaiting Escrow Approval' WHERE request_table.id = $orderId");

                if (!$dAQuery) {
                    die($conn->error);
                }else{
                    echo "Merchant payment submitted successfully";
                }
                
            }
            
        }else{
            echo error("All fields required");
        }
       
    }

    // MERCHANT ACCEPT CUSTOMER PAYEMENT 
    if (isset($_POST['merchantGetCustomerTransactionDetails'])) {
        extract($_POST);
        

        // <div id='imagePreview' class='mt-3' width='120px'>
        //     Buyer's Receipt: 
        //     <img src='./receipts/customer/$customer_receipt' width='100%' height='100%'/>
        // </div>
        $transaction = getTransitTransactionById($transId);

        extract($transaction);
        $merchant_username = merchantDetailsById($merchant_id);
        $customer_username = merchantDetailsById($customer_id)['username'];
        $trx_track_id = strtoupper($trx_track_id);
        $escrowDetails = merchantDetailsById($escrow_id);
        $escrow_account_number  =  $escrowDetails['account_number'];
        $bank_name = $escrowDetails['bank_code'];
        $escrow_fullname = $escrowDetails['fullname'];
        echo "<input type='text' value='$trx_track_id' data-order-id=$request_id id='trx_track_id' hidden /><div class='single-request '><p> Kindly make payment and upload receipt to enable Escrow to proceed using <strong> Tracking ID: $trx_track_id </strong></p> 
        <p>Account Number: $escrow_account_number <br>
        Fullname: $escrow_fullname <br>
        Bank: $bank_name </p>
        
       
        <div id ='imagePreview1' class='mb-1'>
           
        </div>
        <div class='mb-3'>
            <label> Select Seller Receipt </label>
            <input type='file' id='receipt' class='input-order' />
        </div>
        
        <div id='show-status'></div>
        <div>
            <button id='btn-accept'> Confirm </button>
            
        </div> 
        </div>";

    }
