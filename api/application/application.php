<?php
/**
 * Created by PhpStorm.
 * User: r.troulakis
 * Date: 03/01/2020
 * Time: 16:00 AM
 */



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class application
{

    var $hasError = 'false';
    var $message = 'success';
    var $result = '';
    var $email = '';
    var $adminEmail = '';
    var $dateStart = '';
    var $dateEnd = '';
    var $dateStartReal = '';
    var $dateEndReal = '';
    var $reason = '';
    var $employeeId= 0;
    var $status = 1;
    var $db_host = '';
    var $db_user = '';
    var $db_pass = '';
    var $db_nam = '';
    var $conn = '';
    var $applicationId = '';
    var $newApplicationId = '';
    var $approveLink = '';
    var $rejectLink = '';
    
   


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
        $this->dateStartReal = $object['dateStart'];
        $this->dateEndReal = $object['dateEnd'];;
        $this->dateStart = defSqlInjection($object['dateStart']);
        $this->dateEnd = defSqlInjection($object['dateEnd']);
        $this->reason = defSqlInjection($object['reason']);
        $this->employeeId= defSqlInjection($object['employeeId']);
        $this->status = 1;
        $this->db_host = $db_host;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;    
        $this->conn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $this->applicationId = $object['applicationId'];
        $this->status = $object['approve'];

        $this->email = $this->getEmail();
        $this->adminEmail = $this->getAdminEmail();
  
    }

    function putApprove(){
        mysqli_set_charset($this->conn, "utf8");
        $applicationId=  $this->applicationId;
        $status = $this->status;
        $this->email = $this->getEmailApplication();
      

        $sql = "UPDATE applications SET status = ".$status." WHERE applicationId = ".$applicationId;

        if (mysqli_query($this->conn, $sql)) {
            $this->hasError = "false";
            $this->message = "Application Status Update Succeed!";
            $this->result = 'https://vacations-app.tk/app.php';
            $this->sendEmailUser();
            return $last_id = mysqli_insert_id($this->conn);
            // echo "<br>New record created successfully: ".$last_id;
        } else {
            $this->hasError = "true";
            $this->message = "Application Status Update Failed!";
            $this->result = 'https://vacations-app.tk/app.php';
            return 0;
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
        $dateStart = strtotime($this->dateStart);
        $dateEnd =  strtotime($this->dateEnd);
        $reason =  $this->reason;
        $employeeId=  $this->employeeId;
        $status = $this->status;

      

        $sql = "INSERT INTO applications (dateStart, dateEnd,reason,employeeId,status,vacationId) VALUES ( '".$dateStart."', '".$dateEnd."','".$reason."','".$employeeId."','1','".$vacationId."')";

        if (mysqli_query($this->conn, $sql)) {
            $this->hasError = "false";
            $this->message = "Application Add Succeed!";
            $this->result = 'https://vacations-app.tk/app.php';
            $this->newApplicationId = mysqli_insert_id($this->conn);
            $this->sendEmail();
            return 1;
        } else {
            $this->hasError = "true";
            $this->message = "Application Add Failed!";
            $this->result = 'https://vacations-app.tk/app.php';
            return 0;
        }


    }


    function sendEmail(){
     
        require_once "../../vendor/autoload.php";
        require '../../vendor/phpmailer/phpmailer/src/Exception.php';
        require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

        $this->approveLink = "https://vacations-app.tk/api/Application/manageApplication.php?applicationId=".$this->newApplicationId."&approve=2";
        $this->rejectLink = "https://vacations-app.tk/api/Application/manageApplication.php?applicationId=".$this->newApplicationId."&approve=0";
        $mail = new PHPMailer;
        $mail->addAddress($this->adminEmail, 'Admin');
        $mail->setFrom($this->email, 'User');    
        $mail->Subject = 'Vacation Application';
            $mail->Body    = 'Dear supervisor, employee '.$this->email.' requested for some time off, starting on '.$this->dateStartReal.' and ending on '.$this->dateEndReal.', stating the reason: '.$this->reason.' Click on one of the below links to approve or reject the application: Approval Link: '.$this->approveLink.' - Rejection Link: '.$this->rejectLink.'';
        if(!$mail->send()) {
          // echo 'Message was not sent.';
          // echo 'Mailer error: ' . $mail->ErrorInfo;
        } else {
          // echo 'Message has been sent.';
        }
       
    }

    function sendEmailUser(){
     
        require_once "../../vendor/autoload.php";
        require '../../vendor/phpmailer/phpmailer/src/Exception.php';
        require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require '../../vendor/phpmailer/phpmailer/src/SMTP.php';

        $now = date("Y-m-d H:i:s");
        if($this->status == 2){
            $status_send = 'Accepted';
        }else{
             $status_send = 'Rejected';
        }
        $mail = new PHPMailer;
        $mail->addAddress($this->email, 'User');
        $mail->setFrom($this->adminEmail, 'Admin');    
        $mail->Subject = 'Vacation Application';
            $mail->Body    = 'Dear employee, your supervisor has '.$status_send.' your application submitted on '.$now;
        if(!$mail->send()) {
          // echo 'Message was not sent.';
          // echo 'Mailer error: ' . $mail->ErrorInfo;
        } else {
          // echo 'Message has been sent.';
        }
       
    }

    function getEmailApplication(){
        
        $sql = "SELECT * FROM `applications` INNER JOIN employees on applications.employeeId = employees.employeeId WHERE applicationId = '".$this->applicationId."'";
        $result = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                    return $row['email'];
            }
        }
        else {
            return '';
        }
    }

    function getEmail(){
        
        $sql = "SELECT * FROM `employees` WHERE employeeId = '".$this->employeeId."'";
        $result = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                    return $row['email'];
            }
        }
        else {
            return '';
        }
    }

    function getAdminEmail(){
        
        $sql = "SELECT * FROM `users` INNER JOIN employees on users.id = employees.userId WHERE admin = 1";
        $result = mysqli_query($this->conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                    return $row['email'];
            }
        } else {
            return '';
        }
    }


}