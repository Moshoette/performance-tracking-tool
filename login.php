<?php 
session_start();
if(isset($_SESSION['authenticated']))
{
    $_SESSION['status'] = "You are already Logged In";
     header('Location: dashboard.php');
     exit(0);
}


$page_title = "Login Form";
include'includes/header.php';
include'includes/navbar.php';
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                 <?php
                     if(isset($_SESSION['status']))
                     {
                        ?>
                        <div class="alert alert-success">
                             <h5><?= $_SESSION['status']; ?></h5>
                        </div>
                        <?php
                     }                 
                 ?>

                <div class="card-shadow">
                    <div class="card-header">
                        <h5>Login Form</h5>
                    </div>
                    <di class="card-body">

                        <form action="logincode.php" method="POST">

                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control" >
                            </div>

                            <div class="form-group">
                                <button type="submit" name="login_now_btn" class="btn btn-primary">Login Now</button>
                            </div>
                            
                        </form>

                        <hr>
                        <h5>
                            Did not receive your Verificaion Email?
                            <a href="resend-email-verification.php">Resend</a>
                        </h5>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include'includes/footer.php';?>