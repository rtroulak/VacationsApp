<?php
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

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://vacations-app.tk/api/application/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\n\t\"dateStart\" : \"$dateStart\",\n\t\"dateEnd\" : \"$dateEnd\",\n\t\"reason\" : \"$reason\",\n\t\"employeeId\": \"$employeeId\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: Basic YXBpdXNlcjpwYXNzd29yZA=="
  ),    
));

$response = curl_exec($curl);
$array = json_decode($response);
curl_close($curl);

 
?>

<!DOCTYPE html>
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
        <img style="display: block;margin: 0 auto;" src="../../assets/images/whitelogo.gif">
        <h1 style="color:white" align="center">Vacations App</h1>
        <br>
        <br>
        <?php if($array->hasError == "false"){?>
        <h1 align="center"><?php echo $array->message;?></h1>
        <?php }?>
        <?php if($array->hasError == "true"){?>
        <h1 align="center" style="color: red"><?php echo "Attention: ".$array->message."";?></h1>
        <?php }?>
    </div>
</div>


</body>
</html>
<?php

mysqli_close($conn);

?>