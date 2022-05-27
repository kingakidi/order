// console.log(popupPage.style.display);

// TOGGLES
let orderBtn = _("place-order");
let showItems = _("show-actions");
let transactionTable = _("transaction-table");

showPopupContent.style.color = "#fff";

orderBtn.addEventListener("click", () => {
  // GET PLACE OTHER FORM
  $.ajax({
    url: url,
    method: "POST",
    data: {
      getPlaceOrderForm: true,
    },
    beforeSend() {
      showItems.innerHTML = "Loading Order Form...";
    },
    success(data) {
      showItems.innerHTML = data;
    },
  }).done(function () {
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
    let escrow = _("escrow");
    let showEscrow = _("show-escrow");
    escrow.addEventListener("change", () => {
      // Bring Escrow Fullname
      $.ajax({
        url: url,
        method: "POST",
        data: {
          getEscrowDetails: true,
          id: escrow.value,
        },
        beforeSend() {
          showEscrow.innerHTML = "Loading Escrow Details";
        },
        success(data) {
          showEscrow.innerHTML = data;
        },
      });
    });
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
        clean(outcome) > 0 &&
        clean(escrow) > 0
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
            escrow: escrow.value,
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
              data ===
              "<div class='text-success'> Order Placed Successfully </div>"
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
    beforeSend() {
      showItems.innerHTML = "Loading Transactions ....";
    },
    success(data) {
      showItems.innerHTML = data;
    },
  }).done(function () {
    // PENDING ORDERS

    if (document.getElementsByName("btn-pending-orders")) {
      let btnPendingOrders = document.getElementsByName("btn-pending-orders");

      btnPendingOrders.forEach((el) => {
        el.addEventListener("click", () => {
          let orderId = el.getAttribute("data-order-id");
          let merchantId = el.getAttribute("data-merchant-id");
          let escrowId = el.getAttribute("data-escrow-id");

          popupPage.style.display = "block";

          showPopupContent.innerHTML = "Loading...";
          showPopupContent.innerHTML = merchantId;
          // payment request
          $.ajax({
            url: url,
            method: "POST",
            data: {
              getMerchantPaymentDetails: true,
              merchantId: merchantId,
              escrowId: escrowId,
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
            let trxTrackId = _("trx_track_id");
            let receipt = _("receipt");
            let imagePreview = _("imagePreview");

            function fileValidation_merchant() {
              imagePreview.innerHTML = "";
              imagePreview.style.height = "0px";
              imagePreview.style.width = "0px";
              var fileInput = document.getElementById("receipt");

              var filePath = fileInput.value;

              // Allowing file type
              var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

              if (!allowedExtensions.exec(filePath)) {
                alert("Invalid file type");
                fileInput.value = "";
                return false;
              } else {
                // Image preview
                if (fileInput.files && fileInput.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                    imagePreview.style.width = "120px";

                    imagePreview.innerHTML =
                      '<img src="' + e.target.result + '"/>';
                  };

                  reader.readAsDataURL(fileInput.files[0]);
                }
              }
            }
            receipt.addEventListener("change", () => {
              fileValidation_merchant();
            });
            accept.addEventListener("click", () => {
              if (clean(trxTrackId) < 1) {
                showStatus.innerHTML = error("Invalid Trx Track Id");
              } else if (receipt.files.length < 1) {
                showStatus.innerHTML = error("Receipt of Payment is required");
              } else if (receipt.files[0].size > 5000000) {
                showStatus.innerHTML = error(
                  " Receipt is too large: Maximum of 500kb"
                );
              } else {
                let fd = new FormData();
                fd.append("acceptOrder", true);
                fd.append("orderId", orderId);
                fd.append("trxTrackId", trxTrackId.value);
                fd.append("customerReceipt", receipt.files[0]);
                $.ajax({
                  url: url,
                  method: "POST",
                  contentType: false,
                  processData: false,
                  data: fd,
                  beforeSend() {
                    accept.disabled = true;
                    decline.disabled = true;
                    showStatus.innerHTML = "";
                  },
                  success(data) {
                    if (data === "Order Submitted") {
                      showStatus.innerHTML = data;
                      alert(data);
                      location.reload();
                    } else {
                      showStatus.innerHTML = data;
                    }
                    // console.log(data);
                  },
                });
              }
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
                    showStatus.innerHTML = data;
                    alert(data);
                    location.reload();
                  } else {
                    showStatus.innerHTML = data;
                  }
                },
              });
            });
          });
        });
      });
    }

    if (document.getElementsByName("btn-final-transaction-customer-approval")) {
      let btnFinalTransCusApproval = document.getElementsByName(
        "btn-final-transaction-customer-approval"
      );

      btnFinalTransCusApproval.forEach((el) => {
        el.addEventListener("click", () => {
          let orderId = el.getAttribute("data-order-id");

          if (confirm("Are you sure you want to approve the delivery")) {
            $.ajax({
              url: url,
              method: "POST",
              data: {
                approvedDelivery: true,
                orderId: orderId,
              },
              beforeSend() {
                showPopupContent.innerHTML =
                  "<h3 class='text-center m-3'> Loading... </h3>";
                popupPage.style.display = "block";
              },
              success(data) {
                showPopupContent.innerHTML = data;

                if (data === "Delivery approved successfully") {
                  showPopupContent.innerHTML = data;
                  alert(data);
                  location.reload();
                }
              },
            });
          } else {
            console.log("cancelled");
          }
        });
      });
    }
  });
});

// REQUEST
let request = document.getElementsByName("request");
request.forEach((el) => {
  el.addEventListener("click", () => {
    let id = el.id;
    if (id === "send-request") {
      $.ajax({
        url: url,
        method: "POST",
        data: {
          userOrderSentRequest: true,
        },
        beforeSend() {
          showItems.innerHTML = "Loading your send request";
        },
        success(data) {
          showItems.innerHTML = data;
        },
      }).done(() => {
        if (
          document.getElementsByName("btn-final-transaction-merchant-approval")
        ) {
          let bFTMApproval = document.getElementsByName(
            "btn-final-transaction-merchant-approval"
          );
          bFTMApproval.forEach((el) => {
            el.addEventListener("click", () => {
              transId = el.getAttribute("data-transaction-track-id");
              showPopupContent.innerHTML = "Loading...";
              popupPage.style.display = "block";
              let orderId = el.getAttribute("data-order-id");
              // console.log(popupPage);
              console.log(orderId);
              $.ajax({
                url: url,
                method: "POST",
                data: {
                  merchantGetCustomerTransactionDetails: true,
                  transId: transId,
                },
                beforeSend() {},
                success(data) {
                  showPopupContent.innerHTML = data;
                },
              }).done(() => {
                let accept = _("btn-accept");
                // let decline = _("btn-decline");
                let showStatus = _("show-status");
                let trxTrackId = _("trx_track_id");
                let imagePreview1 = _("imagePreview1");
                // let imagePreview = _("imagePreview");
                // imagePreview.style.width = "120px";

                console.log(orderId);
                let receipt = _("receipt");
                function fileValidation_1() {
                  imagePreview1.innerHTML = "";

                  var fileInput = document.getElementById("receipt");

                  var filePath = fileInput.value;

                  // Allowing file type
                  var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

                  if (!allowedExtensions.exec(filePath)) {
                    alert("Invalid file type");
                    fileInput.value = "";
                    return false;
                  } else {
                    // Image preview
                    if (fileInput.files && fileInput.files[0]) {
                      imagePreview1.style.width = "120px";
                      var reader = new FileReader();
                      reader.onload = function (e) {
                        imagePreview1.innerHTML =
                          '<img src="' +
                          e.target.result +
                          '" width="100%" height="100%" />';
                      };

                      reader.readAsDataURL(fileInput.files[0]);
                    }
                  }
                }
                receipt.addEventListener("change", () => {
                  fileValidation_1();
                });
                accept.addEventListener("click", () => {
                  if (clean(trxTrackId) < 1) {
                    showStatus.innerHTML = error("Invalid Trx Track Id");
                  } else if (receipt.files.length < 1) {
                    showStatus.innerHTML = error(
                      "Receipt of Payment is required"
                    );
                  } else if (receipt.files[0].size > 5000000) {
                    showStatus.innerHTML = error(
                      " Receipt is too large: Maximum of 500kb"
                    );
                  } else {
                    let fd = new FormData();
                    fd.append("merchantDeliveryApproval", true);
                    fd.append("orderId", orderId);
                    fd.append("trxTrackId", trxTrackId.value);
                    fd.append("merchantReceipt", receipt.files[0]);
                    $.ajax({
                      url: url,
                      method: "POST",
                      contentType: false,
                      processData: false,
                      data: fd,
                      beforeSend() {
                        popupPage.style.display = "block";
                        showPopupContent.innerHTML =
                          "<h3 class='text-center'> Sending Delivery Request </h3>";
                      },
                      success(data) {
                        popupPage.style.display = "block";

                        if (
                          data === "Merchant payment submitted successfully"
                        ) {
                          showPopupContent.innerHTML = data;
                          alert(data);
                          location.reload();
                        } else {
                          showPopupContent.innerHTML = data;
                        }
                      },
                    });
                  }
                });
                //   DECLINE ORDER
              });
            });
          });
        }
      });
    } else if (id === "completed-request") {
      $.ajax({
        url: url,
        method: "POST",
        data: {
          userOrderCompletedRequest: true,
        },
        beforeSend() {
          showItems.innerHTML = "Loading your send request";
        },
        success(data) {
          showItems.innerHTML = data;
        },
      });
    } else if (id === "pending-request") {
      $.ajax({
        url: url,
        method: "POST",
        data: {
          userOrderPendingRequest: true,
        },
        beforeSend() {
          showItems.innerHTML = "Loading your send request";
        },
        success(data) {
          showItems.innerHTML = data;
        },
      });
    } else if (id === "decline-request") {
      $.ajax({
        url: url,
        method: "POST",
        data: {
          userOrderDeclineRequest: true,
        },
        beforeSend() {
          showItems.innerHTML = "Loading your send request";
        },
        success(data) {
          showItems.innerHTML = data;
        },
      });
    }
  });
});

// USERS REQUEST
if (
  document.getElementsByName("btn-users") &&
  document.getElementsByName("btn-users").length > 0
) {
  let btnUsers = _("btn-users");

  btnUsers.addEventListener("click", () => {
    $.ajax({
      url: url,
      method: "POST",
      data: {
        getAllUsers: true,
      },
      beforeSend() {
        showItems.innerHTML = "Loading Users ....";
      },
      success(data) {
        showItems.innerHTML = data;
      },
    }).done(() => {
      let viewuser = document.getElementsByName("viewuser");
      viewuser.forEach(function (el) {
        el.onclick = function (event) {
          event.preventDefault();
          let id = el.id;
          $.ajax({
            method: "POST",
            url: url,
            data: {
              fetchSingleUser: true,
              userid: id,
            },
            success(data) {
              showPopupContent.innerHTML = data;
              popupPage.style.display = "block";
            },
          }).done(function () {
            popupClose.addEventListener("click", () => {
              popupPage.style.display = "none";
            });
            let showAction = _("show-popup-actions");
            let userAction = _("user-actions");
            userAction.onchange = function () {
              showAction.innerHTML = `${loadIcon}`;
              if (userAction.value.trim().toLowerCase() === "status") {
                $.ajax({
                  url: url,
                  method: "POST",
                  data: { userStatusForm: "userStatusForm", userId: id },
                  beforeSend: function () {
                    showAction.innerHTML = `${loadIcon}`;
                  },
                  success: function (data) {
                    showAction.innerHTML = data;
                  },
                }).done(function () {
                  let submitUserStaus = _("submit-userStatusForm");
                  let userStatusForm = _("userStatusForm");
                  let password = _("password");
                  let uSFE = _("userStatusForm-error");
                  userStatusForm.onsubmit = function (event) {
                    event.preventDefault();
                    if (clean(password) < 1) {
                      uSFE.innerHTML = "PASSWORD IS REQUIRED";

                      // uSFE.style.visibility = "visible";
                    } else {
                      // SEND QUERY TO DATABASE
                      $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                          userStatusUpdate: "userStatusUpdate",
                          password: password.value,
                          id: id,
                        },
                        beforeSend: function () {
                          uSFE.innerHTML = `${loadIcon}`;
                          uSFE.style.visibility = "visible";
                          submitUserStaus.disabled = true;
                          submitUserStaus.innerHTML = `${loadIcon} Sending`;
                        },
                        success: function (data) {
                          submitUserStaus.disabled = false;
                          submitUserStaus.innerHTML = `Change Status`;
                          if (
                            data.trim() ===
                            '<span class="text-success">STATUS CHANGED SUCCESSFULLY</span>'
                          ) {
                            uSFE.innerHTML = data;
                            uSFE.style.visibility = "visible";
                            userStatusForm.reset();
                          } else {
                            uSFE.innerHTML = data;
                            uSFE.style.visibility = "visible";
                          }
                        },
                      });
                      // $.post(
                      //   "./control/action.php",
                      //   {
                      //     userStatusUpdate: "userStatusUpdate",
                      //     password: password.value,
                      //     id: id,
                      //   },
                      //   function (data) {
                      //     if (
                      //       data.trim() ===
                      //       '<span class="text-success">STATUS CHANGED SUCCESSFULLY</span>'
                      //     ) {
                      //       uSFE.innerHTML = data;
                      //       uSFE.style.visibility = "visible";
                      //       userStatusForm.reset();
                      //     } else {
                      //       uSFE.innerHTML = data;
                      //       uSFE.style.visibility = "visible";
                      //     }
                      //   }
                      // );
                    }
                  };
                });
              } else if (userAction.value.trim().toLowerCase() === "usertype") {
                $.ajax({
                  url: url,
                  method: "POST",
                  data: { userTypeForm: "userTypeForm" },
                  beforeSend: function () {
                    showAction.innerHTML = `${loadIcon}`;
                  },
                  success: function (data) {
                    showAction.innerHTML = data;
                  },
                }).done(function () {
                  let uTFE = _("userTypeForm-error");
                  let uTF = _("userTypeForm");
                  let sUTF = _("sTFBtn");
                  let password = _("password");
                  let type = _("typeOption");
                  _("userTypeForm").onsubmit = function (event) {
                    event.preventDefault();
                    if (clean(password) < 1 || clean(type) < 1) {
                      uTFE.innerHTML =
                        "<span class='text-danger'>ALL FIELD REQUIRED</span>";
                      uTFE.style.visibility = "visible";
                    } else {
                      // SEND AJAX REQUEST
                      $.ajax({
                        url: url,
                        method: "POST",
                        data: {
                          uUType: "Change User type",
                          password: password.value,
                          type: type.value,
                          id: id,
                        },
                        beforeSend: function () {
                          uTFE.innerHTML = `${loadIcon}`;
                          uTFE.style.visibility = "visible";
                          sUTF.disabled = true;
                          sUTF.innerHTML = `${loadIcon} Sending`;
                        },
                        success: function (data) {
                          sUTF.disabled = false;
                          sUTF.innerHTML = `CHANGE TYPE`;
                          if (
                            data.trim() ===
                            "<span class='text-success'>USER TYPE CHANGE SUCCESSFULLY</span>"
                          ) {
                            uTFE.innerHTML = data;
                            uTFE.style.visibility = "visible";
                            _("userTypeForm").reset();
                          } else {
                            uTFE.innerHTML = data;
                            uTFE.style.visibility = "visible";
                          }
                        },
                      });
                      $.post(
                        "./control/action.php",
                        {
                          uUType: "Change User type",
                          password: password.value,
                          type: type.value,
                          id: id,
                        },
                        function (data) {
                          if (
                            data.trim() ===
                            "<span class='text-success'>USER TYPE CHANGE SUCCESSFULLY</span>"
                          ) {
                            uTFE.innerHTML = data;
                            uTFE.style.visibility = "visible";
                            _("userTypeForm").reset();
                          } else {
                            uTFE.innerHTML = data;
                            uTFE.style.visibility = "visible";
                          }
                        }
                      );
                    }
                  };
                });
              }
            };
          });
        };
      });
    });
  });
}

// ESCROW REQUEST
if (
  document.getElementsByName("btn-escrow") &&
  document.getElementsByName("btn-escrow").length > 0
) {
  // console.log(document.getElementsByName("btn-escrow"))
  let escrow = _("btn-escrow");
  //   ESCROW REQUEST
  escrow.addEventListener("click", () => {
    $.ajax({
      url: url,
      method: "POST",
      data: {
        getAllUsersEscrow: true,
      },
      beforeSend() {
        showItems.innerHTML = "Loading Escrow Request";
      },
      success(data) {
        showItems.innerHTML = data;
      },
    }).done(() => {
      if (document.getElementsByName("btn-escrow-approval")) {
        let escrowApproval = document.getElementsByName("btn-escrow-approval");
        escrowApproval.forEach((el) => {
          el.addEventListener("click", () => {
            transId = el.getAttribute("data-transaction-track-id");
            showPopupContent.innerHTML = "Loading...";
            popupPage.style.display = "block";
            let orderId = el.getAttribute("data-order-id");
            // console.log(popupPage);
            console.log(orderId);
            $.ajax({
              url: url,
              method: "POST",
              data: {
                getEscrowTransactionDetails: true,
                transId: transId,
              },
              beforeSend() {},
              success(data) {
                showPopupContent.innerHTML = data;
              },
            }).done(() => {
              let accept = _("btn-accept");
              let decline = _("btn-decline");
              let showStatus = _("show-status");
              let trxTrackId = _("trx_track_id");
              let imagePreview = _("imagePreview");
              imagePreview.style.height = "120px";
              imagePreview.style.width = "120px";
              console.log(orderId);
              accept.addEventListener("click", () => {
                if (clean(trxTrackId) > 0) {
                  $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                      escrowAcceptCustomerPayment: true,
                      orderId: orderId,
                      trxTrackId: trxTrackId.value,
                    },
                    beforeSend() {
                      // accept.disabled = true;
                      // decline.disabled = true;
                      showStatus.innerHTML = "";
                    },
                    success(data) {
                      if (data === "Order Submitted") {
                        showStatus.innerHTML = data;
                        alert(data);
                        location.reload();
                      } else {
                        showStatus.innerHTML = data;
                      }

                      // console.log(data);
                    },
                  });
                } else {
                  showStatus.innerHTML = error("Invalid Trx Track Id");
                  s;
                }
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
                      showStatus.innerHTML = data;
                      alert(data);
                      location.reload();
                    } else {
                      showStatus.innerHTML = data;
                    }
                    // console.log(data);
                  },
                });
              });
            });
          });
        });
      }
    });
  });
}
popupClose.addEventListener("click", () => {
  popupPage.style.display = "none";
});
