let _ = ($val) => {
  return document.getElementById($val);
};

let clean = ($val) => {
  return $val.value.trim().length;
};

let error = ($val) => {
  return `<div> ${$val} </div>`;
};
let orderForm = _("order-form");
let foodList = _("food-list");
let customerUsername = _("customer-username");
let amount = _("amount");
let orderType = _("order-type");
let gram = _("gram");
let outcome = _("outcome");
let show = _("show");
let btn = _("btn-order");

// CHECK USERNAME ON FOCUS OUT
customerUsername.addEventListener("focusout", () => {});
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
  } else {
    show.innerHTML = error("All fields required");
  }
});
