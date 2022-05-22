function _(x) {
  return document.getElementById(x);
}

function clean(x) {
  return x.value.trim().length;
}
function error(x) {
  return `<div class='text-danger'> ${x} </div>`;
}
function success(x) {
  return `<div class='text-success'> ${x} </div>`;
}
function info(x) {
  return `<div class='text-info'> ${x} </div>`;
}
function cleanPassword(x) {
  return x.value.length;
}

let popupPage = _("popup-page");
let popupClose = _("popup-close");
let showPopupContent = _("show-popup-content");

let url = "./control/control.php";
