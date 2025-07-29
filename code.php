<?php
session_start();
include'dbcon.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
    
require 'vendor/autoload.php';

$mail = new PHPMailer(true);
function sendemail_verify($name,$email,$verify_token)
{
    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = 2;                      
    $mail->isSMTP();                                                          
    $mail->SMTPAuth   = true;
    
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'kbmoshoette@gmail.com';                     //SMTP username
    $mail->Password   = "flhn juqz yrvh xsmo";  
                                 
    
    $mail->SMTPSecure = "tls";            
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom("kbmoshoette@gmail.com",$name);
    $mail->addAddress($email, $name);    

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Verification from MDiHub SMME board';
    
    $email_template = "
        <h2>You have Registered with MDiHub SMME board</h2>
        <h5>Verify your email to Login with the below given link</h5>
        <br></br>
        <a href='http://localhost/register-login-with-verification/verify-email.php?token=$verify_token'>Link to your account</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
    //echo 'Message has been sent';
}

if(isset($_POST['register_btn']))
{
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_token = md5(rand());

    // Email Exists or not
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
         $_SESSION['status'] = "Email id already Exists";
         header("Location: register.php");
    }
    else
    {
    //  Insert User / Registration User Data
        $query = "INSERT INTO users (name,phone,email,password,verify_token) VALUES ('$name', '$phone', '$email', '$password', '$verify_token')";
        $query_run = mysqli_query($con, $query);

        if($query_run)
        {
            sendemail_verify("$name","$email", "$verify_token");

            $_SESSION['status'] = "Registration Successful.! Please verify your Email Address.";
            header("Location: register.php");
        }
        else
        {
            $_SESSION['status'] = "Registration failed";
            header("Location: register.php");
        }
    }
}

?>