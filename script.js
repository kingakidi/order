let _ = ($val) => {
  return document.getElementById($val);
};

let clean = ($val) => {
  return $val.value.trim().length;
};

let error = ($val) => {
  return `<div class="text-danger"> ${$val} </div>`;
};

let success = ($val) => {
  return `<div class="text-success"> ${$val} </div>`;
};
let popupPage = _("popup-page");
let popupClose = _("popup-close");
let showPopupContent = _("show-popup-content");
// console.log(popupPage.style.display);

let url = "./control/control.php";
let orderForm = _("order-form");
let foodList = _("food-list");
let customerUsername = _("customer-username");
let amount = _("amount");
let orderType = _("order-type");
let gram = _("gram");
let outcome = _("outcome");
let show = _("show");
let btn = _("btn-order");
let usernameCheck = _("check-username");
// CHECK USERNAME ON FOCUS OUT
customerUsername.addEventListener("focusout", () => {
  if (clean(customerUsername) > 0) {
    $.ajax({
      url: url,
      method: "POST",
      data: {
        username: customerUsername.value,
        verifyUsername: true,
      },
      beforeSend() {
        usernameCheck.innerHTML = "verifying...";
      },
      success(data) {
        // usernameCheck.innerHTML = data;
        if (data === "Invalid Username") {
          usernameCheck.innerHTML = error(data);
          btn.disabled = true;
        } else {
          usernameCheck.innerHTML = success(data);
          btn.disabled = false;
        }
      },
    });
  } else {
    usernameCheck.innerHTML = error("Username is required ");
  }
});
orderForm.addEventListener("submit", (e) => {
  e.preventDefault();
  if (
    clean(foodList) > 0 &&
    clean(customerUsername) > 0 &&
    clean(amount) > 0 &&
    clean(orderType) > 0 &&
    clean(gram) > 0 &&
    clean(outcome) > 0
  ) {
    // show.innerHTML = success("Good to go ");
    $.ajax({
      url: url,
      method: "POST",
      data: {
        foodList: foodList.value,
        customerUsername: customerUsername.value,
        amount: amount.value,
        orderType: orderType.value,
        gram: gram.value,
        outcome: outcome.value,
        sendOrder: true,
      },
      beforeSend() {
        btn.disabled = true;
        btn.innerHTML = "Loading...";
        show.innerHTML = "Placing your order...";
      },
      success(data) {
        // console.log(data);

        if (
          data === "<div class='text-success'> Order Placed Successfully </div>"
        ) {
          show.innerHTML = data;
          btn.disabled = true;
        } else {
          show.innerHTML = error(data);
          btn.disabled = false;
        }
        btn.innerHTML = "Place Request";
      },
    });
  } else {
    show.innerHTML = error("All fields required");
  }
});

// PENDING ORDERS

let btnPendingOrders = document.getElementsByName("btn-pending-orders");
btnPendingOrders.forEach((el) => {
  el.addEventListener("click", () => {
    let orderId = el.getAttribute("data-order-id");
    let merchantId = el.getAttribute("data-merchant-id");

    popupPage.style.display = "block";

    showPopupContent.innerHTML = "Loading...";
    showPopupContent.innerHTML = merchantId;
    // payment request
    $.ajax({
      url: url,
      method: "POST",
      data: {
        getMerchantPaymentDetails: merchantId,
        orderId: orderId,
      },
      beforeSend() {},
      success(data) {
        showPopupContent.innerHTML = data;
      },
    }).done(() => {
      let accept = _("btn-accept");
      let decline = _("btn-decline");
      let showStatus = _("show-status");
      accept.addEventListener("click", () => {
        // console.log(orderId);

        $.ajax({
          url: url,
          method: "POST",
          data: {
            acceptOrder: true,
            orderId: orderId,
          },
          beforeSend() {
            accept.disabled = true;
            decline.disabled = true;
            showStatus.innerHTML = "";
          },
          success(data) {
            if (data === "Order Submitted") {
              alert(data);
              location.reload();
            } else {
              showStatus.innerHTML = data;
            }
            console.log(data);
          },
        });
      });
      //   DECLINE ORDER
      decline.addEventListener("click", () => {
        $.ajax({
          url: url,
          method: "POST",
          data: {
            declineOrder: true,
            orderId: orderId,
          },
          beforeSend() {
            accept.disabled = true;
            decline.disabled = true;
            showStatus.innerHTML = "";
          },
          success(data) {
            if (data === "Order Declined") {
              alert(data);
              location.reload();
            } else {
              showStatus.innerHTML = data;
            }
            console.log(data);
          },
        });
      });
    });
    popupClose.addEventListener("click", () => {
      popupPage.style.display = "none";
    });
  });
});

// TOGGLES
let orderBtn = _("place-order");
let showItems = _("show-items");
let transactionTable = _("transaction-table");
orderBtn.addEventListener("click", () => {
  // GET PLACE OTHER FORM
  $.ajax({
    url: url,
    method: "POST",
    data: {
      getPlaceOrderForm: true,
    },
    success(data) {
      showItems.innerHTML = data;
    },
  });
});

// TRANSACTION TABLES
transactionTable.addEventListener("click", () => {
  // GET PLACE OTHER FORM
  $.ajax({
    url: url,
    method: "POST",
    data: {
      getTransactionTable: true,
    },
    success(data) {
      showItems.innerHTML = data;
    },
  });
});
