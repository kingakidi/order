<?php
  include "./control/functions.php";
  if (!isset($_SESSION['oUsername'])) {
      
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
              <button class="btn-action" id="place-order">Send Food</button>
            <button class="btn-action" id="transaction-table">Transactions</button
            ><button class="btn-action" name="btn-users" id="btn-users">Users</button
            >
          </section>
          <section class="cards">
            <div class="card" name="request" id="send-request">Send Request</div>
            <div class="card" name="request" id="completed-request">Completed Request</div>
            <div class="card" name="request" id="pending-request">Pending Request</div>
            <div class="card" name="request" id="all-request">All Send Request</div>
          </section>
          <section class="show-actions" id="show-actions">
            <h2 class="text-center">Select any of the above button to perfom action here</h2>

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
