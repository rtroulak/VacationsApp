<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/01/2020
 * Time: 1:42 μμ
 */
header('Content-Type: text/html; charset=utf-8');

echo $_COOKIE[$cookie_username]." - ".$_COOKIE[$cookie_password];
unset($_COOKIE['username']);
unset($_COOKIE['password']);
$_COOKIE['username']='';
$_COOKIE['password']='';
session_start();


?>

<!DOCTYPE html>

<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Vacations App</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
<div class="login-wrap">
    <div class="login-html">
        <img style="display: block;margin: 0 auto;" " src="assets/images/whitelogo.gif">
        <h1 style="color:white" align="center">Vacations App</h1>
        <br>
        <br>
        <div class="login-form">
            <form class="sign-in-htm" action="app.php" method="POST">
                <div class="group">
                    <label for="user" class="label">Username</label>
                    <input id="username" name="username" type="text" class="input">
                </div>
                <div class="group">
                    <label for="pass" class="label">Password</label>
                    <input id="password" name="password" type="password" class="input" data-type="password">
                </div>
                <div class="group">
                    <input id="check" type="checkbox" class="check" checked>
                    <label for="check"><span class="icon"></span> Keep me Signed in</label>
                </div>
                <div class="group">
                    <input type="submit" class="button" value="Log In">
                </div>
                <div class="hr"></div>
                <div class="foot-lnk">
                <a href="#forgot">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
</html>