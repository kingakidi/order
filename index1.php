<?php require("./views/header.php"); ?>
<title>Order</title>
  </head>
  <body>

    <div id="app">
      <div class="toggle-container" id="toggle-container">
        <button class="btn-toggle" id="place-order">Place Order</button><button class="btn-toggle" id="transaction-table">Transaction Tables</button>


      </div>

      <main id="show-items" >
      <div class='form-container'>
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
              <label for="escrow"> Select Escrow </label>
              <select name="" id="escrow" class="order-input" required>
                <option value="" selected disabled>Select Escrow</option>
                <?php
                  $bytes = strtoupper(bin2hex(random_bytes(5)));
                  echo $bytes;
                    $eQuery = $conn->query("SELECT * FROM users WHERE user_type = 'escrow' AND users.status = 1");
                    if (!$eQuery) {
                      die($conn->error);
                    }else{
                      while ($row = $eQuery->fetch_assoc()) {
                        extract($row);

                        echo "<option value='$id'> $username </option>";
                      }
                    }
                ?>
              </select>
            </div>
            <div class='show' id='show'></div>
            <div class='form-group'>
            <button type='submit' class='btn btn-order' id='btn-order'>
                Place Request
            </button>
            </div>
        </form>
        </div>
      </main>
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
    <script src="./script copy.js?v=3"></script>
  </body>
</html>
