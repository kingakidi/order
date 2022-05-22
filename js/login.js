let login = _("login");
let username = _("username");
let password = _("password");
let show = _("show");
let btnLogin = _("btn-login");
login.addEventListener("submit", (e) => {
  e.preventDefault();
  if (clean(username) > 0 && cleanPassword(password) > 0) {
    $.ajax({
      url: url,
      method: "POST",
      data: {
        username: username.value,
        password: password.value,
        loginUser: true,
      },
      beforeSend() {
        btnLogin.innerHTML = "Logging...";
        btnLogin.disabled = true;
      },
      success(data) {
        if (data === "Login Successfully") {
          location.reload();
          show.innerHTML = success(data);
          btnLogin.innerHTML = "Redirecting...";
          btnLogin.disabled = true;
        } else {
          show.innerHTML = error(data);
          console.log(data);
          btnLogin.innerHTML = "Login";
          btnLogin.disabled = false;
        }
      },
    });
  } else {
    show.innerHTML = error("All fields required");
  }
});
