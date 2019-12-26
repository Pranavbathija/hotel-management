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
      <script type="text/JavaScript">
            var checkIn
            var checkOut

            function check_in(input){
                checkIn = new Date(input);
            }
            function check_out(input){
                checkOut = new Date(input);
            }
          function check_avail(room){

                var Difference_In_Time = checkOut.getTime() - checkIn.getTime();

            var days = Difference_In_Time / (1000 * 3600 * 24);


              const pricesDOM=document . querySelector(".prices");
              if(room=="luxury"){
                  var price=2000*days
                  result=`
                  <p>Room price is ${price}</p>
                  `
              }
              else if(room=="duplex"){
                  var price=5000*days
                  result=`
                  <p>Room price is ${price}</p>
                  `
              }
              else if(room=="penthouse"){
                  var price=10000*days
                  result=`
                  <p>Room price is ${price}</p>
                  `
              }
              else if(room=="honeymoon"){
                  var price=8000*days
                  result=`
                  <p>Room price is ${price}</p>
                  `
              }

              pricesDOM.innerHTML=result




          }
        </script>
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



<?php

$name = $email = $id = $phone = $address = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim_input($_POST["name"]);
    $email = trim_input($_POST["email"]);
    $id = trim_input($_POST["id"]);
    $phone = trim_input($_POST["phone"]);
    $address = trim_input($_POST["address"]);
    $checkin = $_POST["checkin"];
    $checkout = $_POST["checkout"];
    $room = $_POST["room"];
    $room_no = trim_input($_POST["room_no"]);
    $datetime1 = strtotime($checkin);
    $datetime2 = strtotime($checkout);
    $secs = $datetime2 - $datetime1; // == <seconds between the two times>
    $days = $secs / 86400;
    if ($room == "luxury") {
        $price = 2000 * $days;
    } else if ($room == "duplex") {
        $price = 5000 * $days;
    } else if ($room == "penthouse") {
        $price = 10000 * $days;
    } else if ($room == "honeymoon") {
        $price = 8000 * $days;
    }
    $name_err = "";
    $phone_err = "";
    $roomNo_err = "";
    $id_err = "";
    $email_err = "";
    if (empty($room_no)) {
        $roomNo_err = "Please enter room.";
    } else if (!preg_match("/^[0-9]+$/", $room_no)) {
        $roomNo_err = "Please enter numeric values.";
    }
    if (empty($phone)) {
        $phone_err = "Please enter Phone no.";
    } else if (!preg_match("/^[0-9]+$/", $phone)) {
        $phone_err = "Please enter numeric values.";
    }
    if (empty($id)) {
        $id_err = "Please enter Aadhar no.";
    } else if (!preg_match("/^[0-9]+$/", $id)) {
        $id_err = "Please enter numeric values.";
    }

    if (empty($name)) {
        $name_err = "Please enter your Name.";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $name_err = "Please enter alphabetic values.";

    }
    if (empty($email)) {
        $email_err = "Please enter your Email.";
    } else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) {
        $email_err = "Please enter valid email.";

    }
    if (empty($roomNo_err) && empty($phone_err) && empty($id_err) && empty($name_err) && empty($id_err)) {
        require_once 'practice.php';

        $query_room = "select room_no from room";
        $result = @mysqli_query($conn, $query_room);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $rooms = array();
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($rooms, $row["room_no"]);
            }
        }

        if (!in_array($room_no, $rooms)) {
            $room_err = "";

            $query = "INSERT INTO customer (cust_id,name,email,phone,address) VALUES (?, ?, ?,?, ?)";
            $query2 = "Insert Into room (room_no,room_type,start_date,end_date,room_cost,customer_id,booking_id) values (?, ?, ?,?, ?,?,NULL)";
            $query3 = "Insert into contribut_to (room_no) values (?)";

            $stmt = mysqli_prepare($conn, $query);
            $stmt2 = mysqli_prepare($conn, $query2);
            $stmt3 = mysqli_prepare($conn, $query3);

            mysqli_stmt_bind_param($stmt, "sssss", $id, $name, $email, $phone, $address);
            mysqli_stmt_bind_param($stmt2, "ssssis", $room_no, $room, $checkin, $checkout, $price, $id);
            mysqli_stmt_bind_param($stmt3, "s", $room_no);

            mysqli_stmt_execute($stmt);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_execute($stmt3);

            $affected_rows = mysqli_stmt_affected_rows($stmt);

            if ($affected_rows == 1) {

                mysqli_stmt_close($stmt);
                mysqli_stmt_close($stmt2);
                mysqli_stmt_close($stmt3);

                mysqli_close($conn);

            } else {

                echo 'Error Occurred<br/>';
                echo mysqli_error($conn);

                mysqli_stmt_close($stmt);
                mysqli_stmt_close($stmt2);
                mysqli_stmt_close($stmt3);

                mysqli_close($conn);

            }
        } else {
            $room_err = "Room already booked";
        }
    }

}

function trim_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<div class="booking">
  <h2>Booking Details:</h2>
  <br>
<form  name="book" method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> "onsubmit="return check_avail()">
  <div class="lrpart">
<div class="leftpart">
<div class="name padd" > <input placeholder="Name" class="name" type="text" name="name" required></div>
  
  <div class="mail padd" >
  <input type="text" placeholder="E-mail" name="email" required>
</div>
  
  <div class="id padd" >
    <input type="text"  placeholder="Aadhar No." name="id" required>
  </div>
  
  <div class="phone padd" >
  <input type="text"  placeholder="Phone No." name="phone" required>
  </div>
 
  <div class="addr padd" >
  <input type="text"  placeholder="Address" name="address" required>
  </div>
  <div class="room_no padd" >
  <input  placeholder="Room No." type="text" name="room_no" required>
</div>
</div>
<div class ="rightpart">
  <div class="in padd" >
  Check-in:<br> <input   type="date" name="checkin" id="checkin" onchange="check_in(this.value)" required>
</div>
  <div class="out padd" >
  Check-out: <br><input type="date" name="checkout" id="checkout" onchange="check_out(this.value)" required>
</div>
  <div class="type padd" >
  Room Type:<br>
  <select name="room" onchange="check_avail(this.value)">
                <option value="">Select a room</option>
                <option value="luxury">Luxury</option>
                <option value="duplex">Duplex</option>
                <option value="honeymoon">Honeymoon</option>
                <option value="penthouse">Penthouse</option>
    </select>
</div>
    
</div>
</div>
    <div  class="prices"></div>
    <div class="err"></div>
  <div style="text-align:center"><input class="subButton"  type="submit" name="submit" value="Submit"></div>
</form>
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
    <script type="text/JavaScript">
    var name_err = '<?php echo $name_err; ?>';
    var phone_err= '<?php echo $phone_err; ?>';
    var roomNo_err= '<?php echo $roomNo_err; ?>';
    var room_err='<?php echo $room_err; ?>';
    var id_err= '<?php echo $id_err; ?>';
    var email_err= '<?php echo $email_err; ?>';
    const errDOM=document . querySelector(".err");
    res=""
    if(name_err.length>0){
        res+=`<p>${payment_err}</p>`;
    }
    if (phone_err.length>0){
        res+=`<p>${phone_err}</p>`
    }
    if (roomNo_err.length>0){
        res+=`<p>${roomNo_err}</p>`
    }
    if (id_err.length>0){
        res+=`<p>${id_err}</p>`
    }
    if (email_err.length>0){
        res+=`<p>${email_err}</p>`
    }
    if (room_err.length>0){
        res+=`<p>${room_err}</p>`
    }
    errDOM.innerHTML=res

</script>
  </body>
</html>
