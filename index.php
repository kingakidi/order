<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="toggle-container" id="toggle-container">
      <button class="btn-toggle" id="place-order">Place Order</button><button class="btn-toggle" id="transaction-table">Transaction Tables</button>


    </div>

    <main id="show-items">

    </main>

   

    <div class="order-list">
      <h3>Transaction List</h3>
       <div class="list-container">
        <?php
          require("./control/functions.php");

         
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
              echo " <ol class='single-list $status'>
              <li> $sn </li>
              <li> $merchant_username is sending you $outcome $food_name </li>";

              if ($status === 'pending') {
                echo "<li><button class='btn-order-pending' data-merchant-id=$merchant_id data-order-id=$id name='btn-pending-orders'> Click here to view and Accept </button></li>
             ";
              }else if($status === "completed"){
                echo "<li><button class='btn-order-completed' disabled> Completed </button></li>
                ";
              }else if($status === "declined"){
                echo "<li><button class='btn-order-declined' disabled> Declined </button></li>
                ";
              }
              echo "</ol>";
              $sn++;
            }
           
            }else{
              echo "<h3> You have no order at the moment </h3>";
            }
            
          }
        
        ?>
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
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
      integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script src="./script.js"></script>
  </body>
</html>
