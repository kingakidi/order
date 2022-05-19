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
    show.innerHTML = success("Good to go ");
  } else {
    show.innerHTML = error("All fields required");
  }
});
