<?php 
session_start();

$page_title = "Registration Form";
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
                        unset($_SESSION['status']);
                       }
                    ?>

                <div class="card-shadow">
                    <div class="card-header">
                        <h5>Registration Form</h5>
                    </div>
                    <di class="card-body">

                        <form action="code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Phone Number</label>
                                <input type="text" name="phone" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control" >
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control" >
                            </div>
                            <div class="form-group">
                                <button type="submit" name="register_btn" class="btn btn-primary">Register Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>