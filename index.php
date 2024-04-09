<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-In</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    #alert,#register-box,#forgot-box{
        display:none;
    }
</style>
</head>
<body class="bg-dark">
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-4 offset-lg-4" id="alert">
                <div class="alert alert-success">
                    <strong id="result"></strong>
                </div>
            </div>
        </div>
        <!-- Login form -->
        <div class="row">
                <div class="col-lg-4 offset-lg-4 bg-light rounded" id="login-box">
                    <h2 class="text-center mt-2">Login</h2>
                    <form action="" method="Post" role="form" class="p-2" id="login-form">
                        <div class="form-group">
                            <input type="number" name="userId" class="form-control" placeholder="ID" required minlength="9" maxlength="9" value="<?php if(isset($_COOKIE['Id'])){echo $_COOKIE['Id'];}?>" >
                        </div>
                         <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required value="<?php if(isset($_COOKIE['Password'])){echo $_COOKIE['Password'];}?>">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="rem" class="custom-control-input" id="customCheck"
                                >
                                <label for="customCheck" class="custom-control-label">Remember Me</label>
                                <a href="#" id="forgot-btn" class="float-right">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="login" id="login" value="Login" class="btn btn-primary btn-block">
                        </div>
                        <div class="form-group">
                            <p class="text-center">New User?
                                <a href="#" id="register-btn">Register Here</a>
                            </p>
                        </div>
                    </form>
                </div>
        </div>

        <!-- registration form -->
         <div class="row">
                <div class="col-lg-4 offset-lg-4 bg-light rounded" id="register-box">
                    <h2 class="text-center mt-2">Register</h2>
                    <form action="" method="Post" role="form" class="p-2" id="register-form">
                        <div class="form-group">
                            <input type="number" name="Id" class="form-control" placeholder="ID" required minlength="9" maxlength="9">
                        </div>
                        <div class="form-group">
                            <input type="text" name="userName" class="form-control" placeholder="user Name" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="pass"class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="userPhone" class="form-control" required placeholder="Phone Number" minlength="10" maxlength="10">
                        </div>
                        <div class="form-group">
                            <input type="date" name="userBirthDay" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="userAddress" class="form-control" required placeholder="Address">
                        </div>
                        <div class="form-group">
                            <input type="email" name="userEmail" class="form-control" required placeholder="Email">
                        </div>
                        <!-- <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="rem" class="custom-control-input" id="customCheck2">
                                <label for="customCheck2" class="custom-control-label">
                                I agree to the <a href="#">terms & conditions.</a>
                                </label>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <input type="submit" name="register" id="register" value="Register" class="btn btn-primary btn-block">
                        </div>
                        <div class="form-group">
                            <p class="text-center">Already Registered? <a href="#" id="login-btn">login Here</a></p>
                        </div>
                    </form>
                </div>
        </div>
        <!-- Forgot Password -->
        <div class="row">
                <div class="col-lg-4 offset-lg-4 bg-light rounded" id="forgot-box">
                    <h2 class="text-center mt-2">Reset Password</h2>
                    <form action="" method="Post" role="form" class="p-2" id="forgot-form">
                        <div class="form-group">
                            <small class="text-muted">
                                To reset your password, enter the email to receive a reset password instructions on your email.
                            </small>
                        </div>
                        <div class="form-group">
                            <input type="email" name="femail" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="forgot" id="forgot"class="btn btn-primary btn-block" value="Reset" required>
                        </div>
                        <div class="form-group text-center">
                                <a href="#" id="back-btn">Back</a>
                        </div>
                    </form>
                </div>
        </div>
    </div>    
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="./assets/js/index-jquery.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
