<?php
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
//init cookie
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

// check for valid credentials
$sql = "SELECT * FROM `users` WHERE username = '".$username."'";

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $rowpass =  $row["password"];
        $rowusername = $row["username"];
        $admin = $row['admin'];
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
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
<div class="login-wrap">
    <div class="login-html">
        <img style="display: block;margin: 0 auto;" src="assets/images/whitelogo.gif">
        <h1 style="color:white" align="center">Vacations App</h1>
               <h3 style="color:white" align="center">LoggedIn as <?php echo $username; if(!$admin){?></h3>
        <br>
        <br>
        <input id="tab-1" type="radio" name="tab" class="application-in" checked><label for="tab-1" class="tab">LIST OF APPLICATIONS</label>
        <input id="tab-2" type="radio" name="tab" class="application-up"><label for="tab-2" class="tab">NEW APPLICATION</label>
        <div class="login-form">
            <form class="application-in-htm" action="" method="POST">
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


                    // get and print the list of user's application

                    $sql = "SELECT * FROM `applications` INNER JOIN employees on employees.employeeId = applications.employeeId INNER JOIN users on users.id = employees.userid WHERE username = '".$username."'";

                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        echo "<table  style='width:100%'>";
                        echo "<th>Application ID</th><th>Date Start</th><th>Date End</th><th>Reason</th><th>employees</th><th>status</th>";
                        while($row = mysqli_fetch_assoc($result)) {
                            $employeeId = $row['employeeId'];
                           echo "<tr>";
                            echo "<td id='content'>". $row["applicationId"]. "</td><td id='content'>". date("d/m/Y",$row["dateStart"]). "</td><td id='content'>". date("d/m/Y",$row["dateEnd"]). "</td><td id='content'>". $row["reason"]. "</td><td id='content''>".$row["lastName"]."</td><td id='content'>". status($row["status"]). "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "0 results";
                    }

                ?>
                <div style="text-align:center;">
                    <br>
                    <br>
                     <input id="tab-3" type="button" name="tab1" class="application-up" ><label for="tab-2" class="tab">NEW APPLICATION</label>
                </div>
            </form>


           
            <!-- Application form -->
            <form class="application-up-htm" action="api/Application/newApplication.php" method="POST">


                <div class="group">
                    <label for="user" class="label" >VACATION FROM</label>
                    <input id="dateStart" name="dateStart" type="date" class="input" placeholder="" onkeyup="this.value = this.value.toUpperCase();" required>
                </div>
                <div class="group">
                    <label for="user" class="label" >VACATION TO</label>
                    <input id="dateEnd" name="dateEnd" type="date" class="input" placeholder="" onkeyup="this.value = this.value.toUpperCase();" required>
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
                
            </form>
        </div>
    </div>
</div>
<?php
}else{
?>
</h3>
        <br>
        <br>
        <input id="tab-1" type="radio" name="tab" class="application-in" checked><label for="tab-1" class="tab">LIST OF USERS</label>
        <input id="tab-2" type="radio" name="tab" class="application-up"><label for="tab-2" class="tab">NEW USER</label>
        <div class="login-form">
            <form class="application-in-htm" action="" method="POST">
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

                    // get and print the list of users

                    $sql = "SELECT * FROM `users` INNER JOIN employees on employees.userid = users.id ";

                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        echo "<table  style='width:100%'>";
                        echo "<th>User first name</th><th>User last name</th><th>User email</th><th>User type</th>";
                        while($row = mysqli_fetch_assoc($result)) {
                            $employeeId = $row['employeeId'];
                           echo "<tr> ";
                            echo "<td id='content'><a href='api/Application/showUser.php?employeeId=".$row['employeeId']."'>". $row["firstName"]. "</a></td><td id='content'>". $row["lastName"]. "</td><td id='content'>". $row["email"]. "</td><td id='content'>". type($row["admin"]). "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "0 results";
                    }

                ?>
                <div style="text-align:center;">
                    <br>
                    <br>
                     <input id="tab-3" type="button" name="tab1" class="application-up" ><label for="tab-2" class="tab">NEW USER</label>
                </div>
            </form>


           

            <form class="application-up-htm" action="api/Application/newUser.php" method="POST">


                <!-- USER -->
                <div class="group">
                    <label for="user" class="label">FIRST NAME</label>
                    <input id="firstName" name="firstName" type="text" class="input" placeholder="e.g. Georgios Rafail">
                </div>
                <div class="group">
                    <label for="user" class="label">LAST NAME</label>
                    <input id="lastName" name="lastName" type="text" class="input" placeholder="e.g. Troulakis">
                </div>
                <div class="group">
                    <label for="user" class="label">EMAIL</label>
                    <input id="email" name="email" type="text" class="input" placeholder="e.g. rtroulak@protonmail.com">
                </div>
                <div class="group">
                    <label for="user" class="label">PASSWORD</label>
                    <input id="password" name="password" type="text" class="input" placeholder="e.g. XXXXXXXXXXXXX">
                </div>

                 <div class="group">
                    <label for="user" class="label">USERTYPE</label>
                    <select name="type">
                      <option value="0" selected>Employee</option>
                      <option value="1">Admin</option>
                </select> 
                </div>
               

                <div class="group">
                    <input type="submit" class="button" value="CREATE">
                </div>
                
                <!--<div class="foot-lnk">-->
                    <!--<label for="tab-1">Already Member?</a>-->
                <!--</div>-->
            </form>
        </div>
    </div>
</div>
<?php } ?>

</body>
</html>
