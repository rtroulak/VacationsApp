<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 19/01/2020
 */
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once "db_vars.php";
require_once "functions.php";

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

if($_POST['username']){
    unset($_COOKIE['username']);
    unset($_COOKIE['password']);
}


if(!isset($_COOKIE['username'])) {
        $username = defSqlInjection($_POST['username']);
        $password = defSqlInjection($_POST['password']);
        $cookie_username="username";
        $cookie_password="password";
}
else{
     $username = $_COOKIE['username'];
     $password = $_COOKIE['password'];
}

$sql = "SELECT * FROM `users` WHERE username = '".$username."'";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $rowpass =  $row["password"];
        $rowusername = $row["username"];
    }
    if($rowpass == $password && $rowusername == $username && 1){
        setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie('password', $password, time() + (86400 * 30), "/"); // 86400 = 1 day
    } 
    else {
        $_SESSION['LoginMsg'] = "Failed to Login: The username or password you entered is incorrect";
        header("Location:index.php");
    }

} else {
    $_SESSION['LoginMsg'] = "Failed to Login: The username or password you entered is incorrect or User Not Found";
    header("Location:index.php");
    
}

mysqli_close($conn);


?>

<!DOCTYPE html>

<html lang="en" >
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Vacations App</title>


    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
    <link rel="stylesheet" href="./assets/css/style.css">
    
    <script type="text/javascript">
    

        function validate(evt) {
              var theEvent = evt || window.event;

              // Handle paste
              if (theEvent.type === 'paste') {
                  key = event.clipboardData.getData('text/plain');
              } else {
              // Handle key press
                  var key = theEvent.keyCode || theEvent.which;
                  key = String.fromCharCode(key);
              }
              var regex = /[0-9\b]|\./;
              if( !regex.test(key) ) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
              }
        }

    
    </script>

</head>
<body>
<div class="login-wrap">
    <div class="login-html">
        <img style="display: block;margin: 0 auto;" src="assets/images/whitelogo.gif">
        <h1 style="color:white" align="center">Vacations App</h1>
               <h3 style="color:white" align="center">LoggedIn as <?php echo $username; ?></h3>
        <br>
        <br>
        <input id="tab-1" type="radio" name="tab" class="application-in" checked><label for="tab-1" class="tab">ΛΙΣΤΑ ΑΙΤΗΣΕΩΝ</label>
        <input id="tab-2" type="radio" name="tab" class="application-up"><label for="tab-2" class="tab">ΑΙΤΗΣΗ ΑΔΕΙΑΣ</label>
        <div class="login-form">
            <form class="application-in-htm" action="./api/User/search.php" method="POST">
                <?php

                    require_once "db_vars.php";
                    require_once "functions.php";
                        // Create connection
                    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    mysqli_set_charset($conn, "utf8");



                    $sql = "SELECT * FROM `applications` INNER JOIN employees on employees.employeeId = applications.employeeId INNER JOIN users on users.id = employees.userid WHERE username = '".$username."'";

                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        echo "<table  style='width:100%' border='0'>";
                        echo "<th>Application ID</th><th>Date Start</th><th>Date End</th><th>Reason</th><th>employees</th><th>status</th>";
                        while($row = mysqli_fetch_assoc($result)) {
                            $employeeId = $row['employeeId'];
                           echo "<tr>";
                            echo "<td style='color:lightgray'>". $row["applicationId"]. "</td><td style='color:lightgray'>". date("d/m/Y",$row["dateStart"]). "</td><td style='color:lightgray'>". date("d/m/Y",$row["dateEnd"]). "</td><td style='color:lightgray'>". $row["reason"]. "</td><td style='color:lightgray'>".$row["lastName"]."</td><td style='color:lightgray'>". status($row["status"]). "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "0 results";
                    }

                ?>
            </form>

           

            <form class="application-up-htm" action="./api/Application/newApplication.php" method="POST">


                <!-- USER -->
                <div class="group">
                    <label for="user" class="label" >VACATION FROM</label>
                    <input id="dateStart" name="dateStart" type="date" class="input" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                </div>
                <div class="group">
                    <label for="user" class="label" >VACATION TO</label>
                    <input id="dateEnd" name="dateEnd" type="date" class="input" placeholder="" onkeyup="this.value = this.value.toUpperCase();">
                </div>
                <div class="group">
                    <label for="user" class="label">REASON</label>
                    <input id="reason" name="reason" type="text" class="input" placeholder="e.g. I will travel to Amsterdam for vacations!:)">
                </div>

                 <div class="group" hidden="">
                    <label for="user" class="label"></label>
                    <input id="employeeId" name="employeeId" type="text" class="input" value=" <?php echo $employeeId; ?>">
                </div>

                <div class="group">
                    <input type="submit" class="button" value="SUBMIT">
                </div>
                
                <!--<div class="foot-lnk">-->
                    <!--<label for="tab-1">Already Member?</a>-->
                <!--</div>-->
            </form>
        </div>
    </div>
</div>


</body>
</html>