<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 19/01/2020
 */
header('Content-Type: text/html; charset=utf-8');
session_start();

require_once "../../db_vars.php";
require_once "../../functions.php";
 
// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
$dateStart = defSqlInjection($_POST['dateStart']);
$dateEnd = defSqlInjection($_POST['dateEnd']);
$reason = defSqlInjection($_POST['reason']);
$employeeId= defSqlInjection($_POST['employeeId']);


$dateStart = strtotime($dateStart);
$dateEnd = strtotime($dateEnd) ;


$sql = "INSERT INTO applications (dateStart, dateEnd,reason,employeeId,status,vacationId) VALUES ( '".$dateStart."', '".$dateEnd."','".$reason."','".$employeeId."','".$status."','".$vacationId."')";

if (mysqli_query($conn, $sql)) {
    $last_id = mysqli_insert_id($conn);
    // echo "<br>New record created successfully: ".$last_id;
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

 
?>

<!DOCTYPE html>
<!--App: atosystem-->
<!--Company: atoinsurance-->
<!--Dev: rtroulak-->
<!--Date: 4/1/2019-->
<html lang="en" >
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Vacations App</title>
<meta charset=utf-8" />

    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
    <link rel="stylesheet" href="../../assets/css/style.css">

</head>
<body>
<div class="login-wrap">
    <div class="login-html">
        <a href="../../app.php"><img width="50px" height="50px" src="../../assets/images/home.png"></a>
        <img style="display: block;margin: 0 auto;" width="600px" height="150px" src="../../assets/images/whitelogo.gif">
        <h1 style="color:white" align="center">Vacations App</h1>
        <br>
        <br>
        <h1>Application for Vacation Added!</h1>
    </div>
</div>


</body>
</html>
<?php

mysqli_close($conn);

?>