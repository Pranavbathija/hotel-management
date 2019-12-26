<?php
// Initialize the session
session_start();
unset($_SESSION['room_no']);

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Hotel</title>




    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <script src="https://kit.fontawesome.com/cc2ea4088a.js"></script>
    <link rel="stylesheet" href="main.css" />
    <link rel="stylesheet" href="bill.css" />
  </head>

  <body>
    <!-- navbar -->
    <nav class="navbar">
      <div class="navbar-center">
        <!-- <span class="nav-icon">
          <i class="fas fa-bars"></i>
        </span> -->
        <img class="logo" src="./images/logo.jpg" alt="store logo" />

        <a class="nav-links" href="admin.php">Book</a>
        <a class="nav-links" href="/services.php">Services</a>
        <a class="nav-links" href="/bill.php">Generate Bill</a>
        <a class="nav-links" href="/logout.php">Logout</a>
      </div>
    </nav>
    <!-- end of nav -->




<div class="services">
        <form name="bill" method="post" onsubmit="return formValidation()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


            <input placeholder="Room no." type="text" name="room_no" required><br><br>
             <input placeholder="Payment Method" type="text" name="payment" required><br><br>
            <input class="subButton" type="submit" name="submit" value="Genertate Bill" >

            <div class="bill"></div>
        </form>
        <div class="err"></div>
        <?php
function errordisp($error)
{
    echo "<p>$error</p>";
}

?>

    </div>




    <!-- footer -->
    <div class="footer">
      <div class="quick">
        <h3>QUICK LINKS</h3>
        <br />
        <i class="fab fa-facebook fa-2x"></i>
        &nbsp
        <i class="fab fa-tripadvisor fa-2x"></i>
        &nbsp
        <i class="fab fa-twitter fa-2x"></i>
        &nbsp
        <i class="fab fa-instagram fa-2x"></i>
        &nbsp
        <i class="fab fa-google-plus fa-2x"></i>
      </div>
      <div class="contact">
        <h3>CONTACT US</h3>
        <br />
        <p>Address placeholder</p>

        <p>Telephone: Placeholder</p>

        <p>Email: Placeholder</p>
        <br />
      </div>
      <hr />
      <div class="designed_by">
        <p>&copy 2019 Hotel | Privacy Policy</p>
        <p>Designed & Developed by</p>
      </div>
    </div>
    <!-- <script src="validatebill.js"></script> -->

<?php

// if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
//     header("location: admin.php");
//     exit;
// }

$roomNo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_err = "";
    $roomNo = trim($_POST["room_no"]);
    $payment = trim($_POST["payment"]);
    $payment_err = "";
    $roomNo_err = "";
    if (empty(trim($_POST["room_no"]))) {
        $roomNo_err = "Please enter room.";
    } else if (!preg_match("/^[0-9]+$/", $roomNo)) {
        $roomNo_err = "Please enter numeric values.";

    } 

    if (empty(trim($_POST["payment"]))) {
        $payment_err = "Please enter your Method.";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $payment)) {
        $payment_err = "Please enter alphabetic values.";

    } 
    require_once 'practice.php';

    $query_room = "select room_no from room";
    $result = @mysqli_query($conn, $query_room);
    if (mysqli_num_rows($result) > 0) {
        $rooms = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($rooms, $row["room_no"]);
        }
    }

    if (in_array($roomNo, $rooms)) {

        if (empty($roomNo_err) && empty($payment_err)) {
            $_SESSION["room_no"] = $roomNo;
            $_SESSION["payment"] = $payment;
            header("location: showbill.php");

        }
    } else {
        $room_err = "Room not booked";
    }
}
?>
<script type="text/JavaScript">
    var payment_err="";
    var roomNo_err="";
    var room_err= '<?php echo $room_err; ?>';
    payment_err = '<?php echo $payment_err; ?>';
    roomNo_err= '<?php echo $roomNo_err; ?>';
    const errDOM=document . querySelector(".err");
    res=""
    if(payment_err.length>0){
        payment_err = '<?php echo $payment_err; ?>';
        console.log(typeof(payment_err))
        res+=`<p>${payment_err}</p>`;
    }
    if (roomNo_err.length>0){
        res+=`<p>${roomNo_err}</p>`
    }
    if (room_err.length>0){
        res+=`<p>${room_err}</p>`
    }
    errDOM.innerHTML=res

</script>
  </body>
</html>
