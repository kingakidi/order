<?php
  include "./control/functions.php";
     if (!$_SESSION['oUsername']) {
        header("Location: ./login.php");
    }

    ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Dashboard</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="dashboard.css" />
  </head>
  <body>
    <nav>
      <div class="logo"><?php echo ucwords($_SESSION['oUsername']); ?></div>
      <div class="user-profile">
        <a href="./logout.php">Logout</a>
      </div>
    </nav>
    <div id="wrapper">
      <div class="dashboard-container">
        <main class="dashboard-main">
          <section class="action">
            <button class="btn-action" id="transaction-table">Payments</button
            ><button class="btn-action">Users</button
            ><button class="btn-action" id="place-order">Foods</button>
          </section>
          <section class="cards">
            <div class="card">Food</div>
            <div class="card">Transactions</div>
            <div class="card">All Transactions</div>
            <div class="card">Users</div>
          </section>
          <section class="show-actions" id="show-actions">
            <?php
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
                        // print_r($merchant);
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
              ?>

          </section>

        </main>

      </div>
    </div>
    <div class="popup-page" id="popup-page">
      <div class="popup-content" id="popup-content">
      <div class="popup-close form-group text-end">
          <button id="popup-close" class="btn btn-danger">X</button>
        </div>
        <div class="show-popup-content" id="show-popup-content">
            
        </div>
       
      </div>
    </div>
   <script src="./js/functions.js"></script>
    <script src="./script.js"></script>
  </body>
</html>
