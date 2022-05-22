<?php require("./views/header.php"); ?>
<title>Order</title>
  </head>
  <body>

    <div id="app">
      <div class="toggle-container" id="toggle-container">
        <button class="btn-toggle" id="place-order">Place Order</button><button class="btn-toggle" id="transaction-table">Transaction Tables</button>


      </div>

      <main id="show-items" ></main>
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
