<?php
session_start();
if (!isset($_SESSION["room_no"])) {
    header("location: bill.php");
    exit;
}

$service_cost = 0;
$roomNo = trim($_SESSION["room_no"]);
$payment = $_SESSION["payment"];
require_once 'practice.php';
$query = "select name,room_type,room_cost,start_date,end_date
            from customer,room
            where cust_id=customer_id and room_no=$roomNo";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$name = $row["name"];
$room_type = $row["room_type"];
$room_cost = $row["room_cost"];
$checkin = $row["start_date"];
$checkout = $row["end_date"];
$ccost = array();
$mcost = array();
$fcost = array();
$cab_list = array();
$mas_list = array();
$food_list = array();

$query_s = "select service_id from contribut_to where room_no=$roomNo";
$result_s = mysqli_query($conn, $query_s);
// $row = mysqli_fetch_assoc($result_s);
$service_id = array();
// and $row["service_id"] != null
if (mysqli_num_rows($result_s) > 0) {

    while ($row = mysqli_fetch_assoc($result_s)) {
        array_push($service_id, $row["service_id"]);
        // $service_id = $row;

        // $test = print_r($service_id, true);

        // echo $test;

    }

    foreach ($service_id as $service) {
        $querys = "select service_type from services where service_id=$service";
        $res = mysqli_query($conn, $querys);
        $row = mysqli_fetch_assoc($res);

        if ($row["service_type"] == "cab") {
            array_push($cab_list, $service);
        } else if ($row["service_type"] == "massage") {
            array_push($mas_list, $service);

        } else if ($row["service_type"] == "food") {
            array_push($food_list, $service);

        }

    }

    $mascost = 0;
    $cabcost = 0;
    $foodcost = 0;

    if (isset($cab_list)) {
        foreach ($cab_list as $service) {
            $querys = "select ccost from cab where service_id=$service";
            $res = mysqli_query($conn, $querys);
            $row = mysqli_fetch_assoc($res);
            array_push($ccost, $row["ccost"]);
            // echo $row["ccost"];
        }
        $cabcost = array_sum($ccost);
        $ccount = count($cab_list);

    }
    if (isset($food_list)) {
        foreach ($food_list as $service) {
            $querys = "select fcost from food where service_id=$service";
            $res = mysqli_query($conn, $querys);
            $row = mysqli_fetch_assoc($res);
            array_push($fcost, $row["fcost"]);
            // echo $row["fcost"];

        }
        $foodcost = array_sum($fcost);
        $fcount = count($food_list);
    }
    if (isset($mas_list)) {
        foreach ($mas_list as $service) {
            $querys = "select mcost from massage where service_id=$service";
            $res = mysqli_query($conn, $querys);
            $row = mysqli_fetch_assoc($res);
            array_push($mcost, $row["mcost"]);
            // echo $row["mcost"];

        }
        $mascost = array_sum($mcost);
        $mcount = count($mas_list);

    }
    $service_cost = $mascost + $cabcost + $foodcost;
}
$total_cost = $room_cost + $service_cost;
$query_bill = "insert into bill(payment_method,amount,bill_no) VALUES (?,?,NULL)";
$stmt = mysqli_prepare($conn, $query_bill);

mysqli_stmt_bind_param($stmt, "si", $payment, $total_cost);

mysqli_stmt_execute($stmt);

$query2 = "SELECT bill_no FROM bill ORDER BY bill_no DESC LIMIT 1;";
$response = @mysqli_query($conn, $query2);
if ($response) {
    $row = mysqli_fetch_array($response);
    $bill_no = $row['bill_no'];
}

$query4 = "select bill_no from contribut_to where room_no=$roomNo";
$result = mysqli_query($conn, $query4);
$row = mysqli_fetch_assoc($result);
if ($row["bill_no"] == null) {
    $query3 = "update contribut_to set bill_no=$bill_no where room_no=$roomNo";
    mysqli_query($conn, $query3);
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



<div class="bill-cs">
<div class="bill-cust"></div>
<div class="bill-serv"></div>
</div>
<br>
<div class ="bill"></div>

<form name="service"method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="centreButton"><input class="subButton centreButton" type="submit" name="submit" value="Confirm Payment"></div>
        </form>
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
    var total = '<?php echo $total_cost; ?>';
    var payment= '<?php echo $payment; ?>'
    var name='<?php echo $name; ?>';
    var room_no='<?php echo $roomNo; ?>';
    var room_type='<?php echo $room_type; ?>';
    var room_cost='<?php echo $room_cost; ?>';
    var service_cost='<?php echo $service_cost; ?>';
    var checkin='<?php echo $checkin; ?>';
    var checkout='<?php echo $checkout; ?>';
    var ccost = <?php echo json_encode($ccost); ?>;
    var fcost=<?php echo json_encode($fcost); ?>;
    var mcost=<?php echo json_encode($mcost); ?>;

    console.log(typeof(ccost));
    const billcustDOM=document . querySelector(".bill-cust");
    const billservDOM=document . querySelector(".bill-serv");
    const billtotalDOM=document . querySelector(".bill");
    result=`<div class="customer">
            <h2> Booking Details</h2><br>
            <p>Customer name : ${name}</p><br>
            <p> Room no.: ${room_no}</p><br>
            <p> Room Type.: ${room_type}</p><br>
            <p> Check-in: ${checkin}</p><br>
            <p> Check-out: ${checkout}</p><br>
            </div>
    `;
    res_service="";
    if (ccost.length >0){
        res_service+=`<h2>Cab</h2><br>`
        ccost.forEach(cab=>{
            res_service+=`<p>Cost: ${cab}`;
        });
    }
    if (mcost.length >0){
        res_service+=`<h2>Massage</h2><br>`
        mcost.forEach(mas=>{
            res_service+=`<p>Cost: ${mas}`;
        });
    }
    if (fcost.length >0){
        res_service+=`<h2>Food</h2><br>`
        fcost.forEach(food=>{
            res_service+=`<p>Cost: ${food}`;
        });
    }
    res_total=`<h2>Total: </h2><p>${total}</p><br>
                <h2>Payment Method: </h2><p>${payment}</p>`;
    billcustDOM.innerHTML=result;
    billservDOM.innerHTML=res_service;
    billtotalDOM.innerHTML=res_total;
    </script>
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $delct = "delete from contribut_to where room_no=$roomNo";
    mysqli_query($conn, $delct);
    $delroom = "delete from room where room_no=$roomNo";
    mysqli_query($conn, $delroom);
    $delcust = "delete from customer where name=$name";
    mysqli_query($conn, $delcust);
    mysqli_close($conn);
    header("location: admin.php");

}
?>
  </body>
</html>
