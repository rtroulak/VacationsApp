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
$employeeID = defSqlInjection($_GET['employeeId']);

//get users info to predefine form
$sql = "SELECT * FROM `users` INNER JOIN employees on employees.userid = users.id WHERE employeeID = ".$employeeID;

$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
   while($row = mysqli_fetch_assoc($result)) {
      $firstName = $row['firstName'];
      $lastName = $row['lastName'];
      $email = $row['email'];
      $type = $row['admin'];
    }
   
  }
                        

 
?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Vacations App</title>
<meta charset="utf-8" />

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
        <br> <input id="tab-1" type="radio" name="tab" class="application-in" checked><label for="tab-1" class="tab">USER'S PROPERTIES</label>
                <div class="login-form">
            <form  action="" method="POST">
                   <!-- USER -->
                <div class="group">
                    <label for="user" class="label">FIRST NAME</label>
                    <input id="firstName" name="firstName" type="text" class="input" value="<?php echo $firstName ?>" placeholder="e.g. Georgios Rafail">
                </div>
                <div class="group">
                    <label for="user" class="label">LAST NAME</label>
                    <input id="lastName" name="lastName" type="text" class="input" value="<?php echo $lastName ?>" placeholder="e.g. Troulakis">
                </div>
                <div class="group">
                    <label for="user" class="label">EMAIL</label>
                    <input id="email" name="email" type="text" class="input" value="<?php echo $email ?>" placeholder="e.g. rtroulak@protonmail.com">
                </div>
                <div class="group">
                    <label for="user" class="label">PASSWORD</label>
                    <input id="password" name="password" type="text" class="input" placeholder="e.g. XXXXXXXXXXXXX">
                </div>

                <?php 
                if(!$type){?>
                 <div class="group">
                    <label for="user" class="label">USERTYPE</label>
                    <select name="type">
                      <option value="0" selected>Employee</option>
                      <option value="1">Admin</option>
                 
                </select> 
                <?php }else{ ?>
                   <div class="group">
                    <label for="user" class="label">USERTYPE</label>
                    <select name="type">
                      <option value="0" >Employee</option>
                      <option value="1" selected>Admin</option>
                 
                </select> 
              <?php } ?>
            </form>


           

        </div>
    </div>
</div>

</body>
</html>
<?php

mysqli_close($conn);

?>