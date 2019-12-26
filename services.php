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

    <script>
        function show_food_price(food){
            const foodDOM=document . querySelector(".food");
            if(food=="breakfast"){
                res=`<p>price is 400`
            }else if(food=="lunch"){
                res=`<p>price is 500`
            }else if(food=="dinner"){
                res=`<p>price is 600`
            }
            foodDOM.innerHTML=res
        }

        function show_price(type){
            const pricesDOM=document . querySelector(".price");
            if(type=="cab"){
                result=`
                Destination: <input class="dest" type="text" name="dest" required>
                <br><br>
                <p> Price is 400</p>
                `
            }else if (type=="food"){
                result=`
                Food type:
                <select name="food" onchange="show_food_price(this.value)">
                <option value="">Select</option>
                <option value="breakfast">Breakfast</option>
                <option value="lunch">Lunch</option>
                <option value="dinner">Dinner</option>
                </select>
                <div class="food"></div>
                `

            }else if (type=="massage"){
                result=`<p>price is 600`
            }
            pricesDOM.innerHTML=result

        }
    </script>

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
$roomNo = $type = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomNo = trim($_POST["room_no"]);
    $type = $_POST["type"];
    if ($type == "cab") {
        $dest = $_POST["dest"];
        $price = 400;
    } else if ($type == "food") {
        $food = $_POST["food"];
        if ($food == "breakfast") {
            $price = 400;
        } else if ($food == "lunch") {
            $price = 500;
        } else if ($food == "dinner") {
            $price = 600;
        }
    } else if ($type == "massage") {
        $price = 600;
    }
    if (preg_match("/^[0-9]+$/", $roomNo)) {
        require_once 'practice.php';

        $query = "INSERT INTO services (service_type,service_id) VALUES (?, NULL)";

        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "s", $type);

        mysqli_stmt_execute($stmt);

        $query2 = "SELECT service_id FROM services ORDER BY service_id DESC LIMIT 1;";
        $response = @mysqli_query($conn, $query2);
        if ($response) {
            $row = mysqli_fetch_array($response);
            $service_id = $row['service_id'];
        }

        $query4 = "select service_id from contribut_to where room_no=$roomNo";
        $result = mysqli_query($conn, $query4);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) == 1 and $row["service_id"] == null) {
            $query3 = "update contribut_to set service_id=$service_id where room_no=$roomNo";
            mysqli_query($conn, $query3);
        } else {
            $query5 = "Insert into contribut_to (room_no,service_id) values (?,?)";
            $stmtcont = mysqli_prepare($conn, $query5);
            mysqli_stmt_bind_param($stmtcont, "si", $roomNo, $service_id);
            mysqli_stmt_execute($stmtcont);

        }

        if ($type == "cab") {
            $query_service = "Insert into cab (destination,ccost,service_id) values (?,?,?)";
            $stmt1 = mysqli_prepare($conn, $query_service);
            mysqli_stmt_bind_param($stmt1, "sii", $dest, $price, $service_id);
            mysqli_stmt_execute($stmt1);

        } else if ($type == "food") {
            $query_service = "Insert into food (food_type,fcost,service_id) values (?,?,?)";
            $stmt1 = mysqli_prepare($conn, $query_service);
            mysqli_stmt_bind_param($stmt1, "sii", $food, $price, $service_id);
            mysqli_stmt_execute($stmt1);

        } else if ($type == "massage") {
            $query_service = "Insert into massage (mcost,service_id) values (?,?)";
            $stmt1 = mysqli_prepare($conn, $query_service);
            mysqli_stmt_bind_param($stmt1, "ii", $price, $service_id);
            mysqli_stmt_execute($stmt1);

        }

        $affected_rows = mysqli_stmt_affected_rows($stmt);

        if ($affected_rows == 1) {

            mysqli_stmt_close($stmt);

            mysqli_close($conn);

        } else {

            echo 'Error Occurred<br />';
            echo mysqli_error();

            mysqli_stmt_close($stmt);

            mysqli_close($conn);

        }
    }

}
?>
<div class="services">
        <form name="service"method="post" onsubmit="formValidation()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            Service type:<br>
            <select name="type" onchange="show_price(this.value)">
                <option value="">Select</option>
                <option value="cab">Cab</option>
                <option value="food">Food</option>
                <option value="massage">Massage</option>

            </select>

            <br><br>

             <input placeholder="Room No." type="text" name="room_no" required>
             <br><br>

            <div class="price"></div>
            <input class="subButton"  type="submit" name="submit" value="Submit">


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
    <script src="validateserv.js"></script>
  </body>
</html>
