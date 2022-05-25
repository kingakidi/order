let register = _("register");
let username = _("username");
let fullname = _("fullname");
let email = _("email");
let phone = _("phone");
let bank = _("bank");
let account = _("bank-account");
let password = _("password");
let show = _("show");
let usernameCheck = _("check-username");
let btnRegister = _("btn-register");
let showAccountVerification = _("show-account");

// VERIFY USERNAME EXISTENCE
username.addEventListener("focusout", () => {
  if (clean(username) > 0) {
    $.ajax({
      url: url,
      method: "POST",
      data: {
        username: username.value,
        verifyUsernameExist: true,
      },
      beforeSend() {
        usernameCheck.innerHTML = "verifying...";
      },
      success(data) {
        // usernameCheck.innerHTML = data;
        if (data === "Username is taken") {
          usernameCheck.innerHTML = error(data);
          btnRegister.disabled = true;
        } else if (data === "Username is valid") {
          usernameCheck.innerHTML = success(data);
          btnRegister.disabled = false;
        }
      },
    });
  } else {
    usernameCheck.innerHTML = error("Username is required ");
  }
});
register.addEventListener("submit", (e) => {
  e.preventDefault();
  // check all fields
  if (
    clean(username) > 0 &&
    clean(fullname) > 0 &&
    clean(email) > 0 &&
    clean(phone) > 0 &&
    clean(bank) > 0 &&
    clean(account) > 0 &&
    clean(password) > 0
  ) {
    // CHECK FOR PASSWORD LENGTH
    if (clean(password) < 6) {
      show.innerHTML = error("Password is too short");
    } else if (clean(phone) < 11) {
      show.innerHTML = error("Invalid Phone number");
    } else {
      // SEND
      $.ajax({
        url: url,
        method: "POST",
        data: {
          username: username.value,
          fullname: fullname.value,
          email: email.value,
          phone: phone.value,
          bank: bank.value,
          account: account.value,
          password: password.value,
          escrowUserRegister: true,
        },
        beforeSend() {},
        success(data) {
          show.innerHTML = data;
          if (data === "Escrow Application Submitted for approval") {
            show.innerHTML = success(data);
            // location.replace("./login.php?register=successfully");
          } else {
            show.innerHTML = error(data);
          }
          console.log(data);
        },
      });
    }
  } else {
    show.innerHTML = error("All fields required");
  }
});
