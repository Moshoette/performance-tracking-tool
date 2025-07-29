<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
function resend_email_verify($name, $email, $verify_token)
{
    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = 2;                      
    $mail->isSMTP();                                                          
    $mail->SMTPAuth   = true;
    
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'kbmoshoette@gmail.com';                     //SMTP username
    $mail->Password   = "vthsdffsisinnmkw";  
                                 
    
    $mail->SMTPSecure = "tls";            
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom("kbmoshoette@gmail.com",$name);
    $mail->addAddress($email, $name);    

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Resend - Email Verification from MDiHub SMME board';
    
    $email_template = "
        <h2>You have Registered with MDiHub SMME board</h2>
        <h5>Verify your email to Login with the below given link</h5>
        <br></br>
        <a href='http://localhost/register-login-with-verification/verify-email.php?token=$verify_token'>Click Me</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}

if(isset($_POST['resend_email_verify_btn']))
{
    if(!empty(trim($_POST['email'])))
    {
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con,$check_email_query);

        if(mysqli_num_rows($check_email_query_run) > 0)
        {
            $row = mysqli_fetch_array($check_email_query_run);
            if($row['verify_status'] == "0")
            {
                $name = $row['name'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];

                resend_email_verify($name,$email,$verify_token);

                $_SESSION['status'] = "Verification email link has been sent to your email address";
                header("Location: login.php");
                exit(0);
            }
            else
            {
                $_SESSION['status'] = "Email already verified. Please Login";
                header("Location: resend-email-verification.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Email is not registered. Please register now.!";
            header("Location: register.php");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "Please enter the email field";
        header("Location: resend-email-verification.php");
        exit(0);
    }
}

?>