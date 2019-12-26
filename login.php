<?php

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: admin.php");
    exit;
}

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        if (($username == "admin") && ($password == "password")) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            header("location: admin.php");

        } else {
            echo "Incorrect password or Username";
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
     <link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <script src="https://kit.fontawesome.com/cc2ea4088a.js"></script>
    <link rel="stylesheet" href="main.css" />
</head>
<body>
    <!-- navbar -->
    <nav class="navbar">
      <div class="navbar-center">
        <!-- <span class="nav-icon">
          <i class="fas fa-bars"></i>
        </span> -->
        <img class="logo" src="./images/logo.jpg" alt="store logo" />

        <a class="nav-links" href="main.html">Home</a>
        <a class="nav-links" href="/about.html">About</a>
        <a class="nav-links" href="/accommodation.html">Accomodation</a>
        <a class="nav-links" href="/login.php">Login</a>
      </div>
    </nav>
    <!-- end of nav -->
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" required>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            
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
    <script src="main.js"></script>
  </body>
</html>

</body>
</html>