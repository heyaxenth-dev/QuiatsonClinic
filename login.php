<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Log In | Admin - Quiatson Clinic</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendor/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendor/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/login.css" />
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <?php 
    include "alert.php";
?>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <a href="index"><img src="assets/img/logo-tag.svg" alt="logo" /></a>
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" method="POST" action="admin-register.php">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="username"
                                        id="exampleInputEmail1" placeholder="Username" />
                                </div>
                                <div class="form-group" style="position: relative;">
                                    <input type="password" class="form-control form-control-lg" name="password"
                                        id="exampleInputPassword1" placeholder="Password"
                                        style="padding-right: 40px;" />

                                    <!-- Eye Icon -->
                                    <a href="javascript:void(0)" id="togglePassword2"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #333; text-decoration: none;">
                                        <i class="bi bi-eye-slash"></i>
                                    </a>
                                </div>

                                <script>
                                const togglePassword2 = document.querySelector('#togglePassword2');
                                const passwordInput2 = document.querySelector('#exampleInputPassword1');
                                const icon2 = togglePassword2.querySelector('i');

                                togglePassword2.addEventListener('click', () => {
                                    const isPassword = passwordInput2.type === 'password';
                                    passwordInput2.type = isPassword ? 'text' : 'password';

                                    // Swap between eye and eye-slash
                                    icon2.classList.toggle('bi-eye');
                                    icon2.classList.toggle('bi-eye-slash');
                                });
                                </script>
                                <div class="mt-3 d-grid gap-2">
                                    <button type="submit" name="login"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                        href="index.php">SIGN IN</button>
                                </div>
                                <div class="my-2 d-flex justify-content-end align-items-end">
                                    <a href="admin/forgot_password.php" class="auth-link text-black">Forgot
                                        password?</a>
                                </div>

                                <div class="text-center mt-4 font-weight-light">
                                    Don't have an account?
                                    <a href="register.php" class="text-primary">Create</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendor/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
</body>

</html>