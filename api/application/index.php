<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 20/01/2020
 * Time: 16:00 AM
 */

require_once("include/config.php");
require_once("applicationRestHandler.php");

if(preg_match('/Basic+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches))
{

list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));

}


if (!isset($_SERVER['PHP_AUTH_USER'])) {

    header('WWW-Authenticate: Basic realm="Mdbill API Login"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
} else {
    if(!($_SERVER['PHP_AUTH_USER'] == USER_MB && $_SERVER['PHP_AUTH_PW'] == PASS_MB)) {
        //401 - not found;
        $applicationRestHandler = new applicationRestHandler();
        $applicationRestHandler->getErrorMsgUnAuthorized();
        exit;
    }
}
$token = $_SERVER['PHP_AUTH_PW'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_type = 1;
}else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $request_type = 2;
}else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $request_type = 3;
}else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $request_type = 4;
}
else{
    $request_type = 0;
}
/*
controls the RESTful services
URL mapping
*/
//echo $_SERVER['REMOTE_ADDR'];
// if ($_SERVER['REMOTE_ADDR']!= SITE_IP and $_SERVER['REMOTE_ADDR']!= SITE_IPv6 and $_SERVER['REMOTE_ADDR']!= SITE_IPv6 and $_SERVER['REMOTE_ADDR']!= VAGRANT_IP) {
//     $applicationRestHandler = new applicationRestHandler();
//     $applicationRestHandler->accessDenied($_SERVER['REMOTE_ADDR']);
//     exit(0);
// }

$body = file_get_contents('php://input');
$object = json_decode($body,true);
//print_r($object);

/*
controls the RESTful services
URL mapping
*/
switch($token){
    case PASS_MB:
        // to handle REST Url /customers/list/
        $applicationRestHandler = new applicationRestHandler();

        $applicationRestHandler->getInfo($object,$request_type);
        break;
    case "" :
        //404 - not found;
        $applicationRestHandler = new applicationRestHandler();
        $applicationRestHandler->getErrorMsgUnAuthorized();
        break;
}

