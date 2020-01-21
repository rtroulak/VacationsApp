<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 20/01/2020
 * Time: 16:00 AM
 */

require_once("SimpleRest.php");
include "application.php";

class applicationRestHandler extends SimpleRest
{


    /**
     * @param $object
     * @param $request_type (post:1,get:2,push:3,delete:4,error:0)
     */
    function getInfo($object, $request_type){
        $application = new application($object);
        $application->init($object);
     
        if($request_type == 1) {
            $application->setPostData($object);
            $rawData = $application->getPostData();
        }else if ($request_type == 3){
            $application->putApprove($object);
            $rawData = $application->getPostData();
        }else{

        }


        if(empty($rawData)) {
            $this->getErrorMsg();
            exit;
        } else {
            $statusCode = 200;
        }

        $requestContentType = "application/json";
        $this ->setHttpHeaders($requestContentType, $statusCode);

        $response = $this->encodeJson($rawData);
        echo $response;

    }

    /**
     * Get Error messages
     */
    function getErrorMsg(){
        $statusCode = 404;
        $rawData = array("hasError" => 'true', 'message' => 'Empty return response Data');
        $requestContentType = "application/json";
        $this ->setHttpHeaders($requestContentType, $statusCode);
        $response = $this->encodeJson($rawData);
        echo $response;
    }

    /**
     * Get Error on Auth
     */
    function getErrorMsgUnAuthorized(){
        $statusCode = 401;
        $rawData = array("hasError" => 'true', "message"=> 'Unauthorized');
        $requestContentType = "application/json";
        $this ->setHttpHeaders($requestContentType, $statusCode);
        $response = $this->encodeJson($rawData);
        echo $response;
    }

    function accessDenied($server){
        $statusCode = 403;
        $rawData = array("hasError" => 'true', "message"=> 'Access Denied to IP:'.$server);
        $requestContentType = "application/json";
        $this ->setHttpHeaders($requestContentType, $statusCode);
        $response = $this->encodeJson($rawData);
        echo $response;
    }

    public function encodeJson($responseData) {
        $jsonResponse = json_encode($responseData);
        return $jsonResponse;
    }



}