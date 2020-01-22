<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 03/01/2020
 * Time: 16:00 AM
 */



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class user
{

    var $hasError = 'false';
    var $message = 'success';
    var $result = '';
    var $username  = '';
    var  $password = '';
    var  $registerDate = '';
    var  $lastloginDate = '';
    var  $type = 0;
    var  $firstName = '';
    var  $lastName = '';
    var  $email = '';
    var  $employeeId = '';

    
   


    /**
     * @param string $object
     */
    function _constructor($object)
    {
        
    }

    function init($object)
    {

        require_once "../../db_vars.php";
        require_once "../../functions.php";
        $this->username = defSqlInjection($object['username']);
        $this->password = defSqlInjection($object['password']);
        $this->registerDate = defSqlInjection($object['registerDate']);
        $this->lastloginDate= defSqlInjection($object['lastloginDate']);
        $this->type = defSqlInjection($object['type']);
        $this->firstName = defSqlInjection($object['firstName']);
        $this->lastName = defSqlInjection($object['lastName']);
        $this->email= defSqlInjection($object['email']);
        $this->status = 1;
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;    
        $this->conn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

  
    }

   

    /**
     * @return array
     */
    function getPostData(){
        $this->response = array("hasError"=>$this->hasError, "message"=>$this->message, "data" =>$this->result);
        return $this->response;
    }

    /**
     * @param $object
     * @return int
     */
    function setPostData($object)
    {

        mysqli_set_charset($this->conn, "utf8");
        $username = $this->firstName;
        $password = $this->password;
        $registerDate = strtotime("now");
        $lastloginDate = strtotime("now");
        $type = $this->type;
        $firstName = $this->firstName;
        $lastName = $this->lastName;
        $email = $this->email;


     

        $sql = "INSERT INTO users (username, password,registerDate,lastloginDate,admin) VALUES ( '".$username."', '".$password."','".$registerDate."','".$lastloginDate."','".$type."')";

        if (mysqli_query($this->conn, $sql)) {
            $this->hasError = "false";
            $this->message = "User Add Succeed!";
            $this->result = 'https://vacations-app.tk/app.php';
            $this->userId = mysqli_insert_id($this->conn);
            
        } else {
            $this->hasError = "true";
            $this->message = "User Add Failed!";
            $this->result = 'https://vacations-app.tk/app.php';
            
        }


        echo $sql = "INSERT INTO employees (firstName, lastName,email,userId) VALUES ( '".$firstName."', '".$lastName."','".$email."','".$this->userId."')";

        if (mysqli_query($this->conn, $sql)) {
            $this->hasError = "false";
            $this->message = "User Add Succeed!";
            $this->result = 'https://vacations-app.tk/app.php';
            $this->employeeId = mysqli_insert_id($this->conn);
            return 1;
        } else {
            $this->hasError = "true";
            $this->message = "User Add Failed!";
            $this->result = 'https://vacations-app.tk/app.php';
            return 0;
        }


    }


}