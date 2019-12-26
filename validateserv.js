function formValidation() {
  var room_no = document.forms["service"]["room_no"];
  var dest = document.querySelector(".dest");
  if (allnumeric(room_no)) {
    if (allLetter(dest)) {
    }
  }
}
function allLetter(payment) {
  var letters = /^[A-Za-z]+$/;
  if (payment.value.match(letters)) {
    return true;
  } else {
    alert("Payment method must have alphabet characters only");
    payment.focus();
    return false;
  }
}

function allnumeric(room_no) {
  var numbers = /^[0-9]+$/;
  if (room_no.value.match(numbers)) {
    return true;
  } else {
    alert("Room number must have numeric characters only");
    room_no.focus();
    return false;
  }
}
