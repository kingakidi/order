<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" >
    <link rel="stylesheet" href="style.css">

</head>
<body>

    <div class="form-container">
        <form  class="order-form" id="order-form">
            <div class="form-group">
                <label for="food-list">Select Food</label>
                <select name="food-list" id="food-list" class="form-control" required>
                    <option value="" disabled selected>Choose...</option>
                    <option value="chicken">Chicken</option>
                    <option value="jellof rice"> Jellof Rice</option>
                    <option value="fried rice"> Fried Rice</option>
                </select>

            </div>
            <div class="form-group">
                <label for="customer-username" > Customer Username </label>
                <input type="text" placeholder="Customer Username" class="form-control" required>
                <div id="check-username"></div>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" class="form-control" placeholder="Amount" >
            </div>

            <!-- second section  -->
            <div class="form-group">
                <select name="order-type" id="order-type" class="form-control">
                    <option value="" selected disabled>Choose...</option>

                    <option value="selling">Selling</option>
                    <option value="buying">Buying</option>
                </select>

            </div>
            <div class="form-group">
                <label for="gram">Gram</label>
                <input type="number" id="gram" class="form-control" placeholder="gram">
            </div>
            <div class="form-group">
                <label for="outcome">outcome</label>
                <input type="number" id="gram" class="form-control" placeholder="gram">
            </div>
            
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./script.js"></script>
</body>
</html>