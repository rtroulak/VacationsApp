<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 21/01/2020
 * Time: 16:00 AM
 */

require_once("include/config.php");
require_once("userRestHandler.php");

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
        $userRestHandler = new userRestHandler();
        $userRestHandler->getErrorMsgUnAuthorized();
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


$body = file_get_contents('php://input');
$object = json_decode($body,true);

switch($token){
    case PASS_MB:
        // to handle REST Url /customers/list/
        $userRestHandler = new userRestHandler();

        $userRestHandler->getInfo($object,$request_type);
        break;
    case "" :
        //404 - not found;
        $userRestHandler = new userRestHandler();
        $userRestHandler->getErrorMsgUnAuthorized();
        break;
}

