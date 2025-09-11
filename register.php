<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sign Up | Admin - Quiatson Clinic</title>
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="assets/img/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="assets/js/sweetalert2.all.min.js"></script>

    <!-- Custom validation styles -->
    <style>
    .form-control.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .form-control.is-valid:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .form-select.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .form-select.is-valid:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .invalid-feedback {
        display: block !important;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .valid-feedback {
        display: block !important;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #28a745;
    }

    .password-strength {
        margin-top: 0.5rem;
    }

    .password-strength .progress {
        height: 5px;
    }
    </style>
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
                            <h4>New here?</h4>
                            <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                            <form class="pt-3" method="POST" action="admin-register.php" id="adminRegisterForm">
                                <div id="duplicate-warning" class="alert alert-warning" style="display:none;"></div>
                                <div class="invalid-feedback" id="username-feedback" style="display:none;"></div>
                                <div class="invalid-feedback" id="mobile_no-feedback" style="display:none;"></div>
                                <div class="invalid-feedback" id="email-feedback" style="display:none;"></div>
                                <div class="invalid-feedback" id="role-feedback" style="display:none;"></div>
                                <div class="invalid-feedback" id="password-feedback" style="display:none;"></div>
                                <div class="invalid-feedback" id="confirm_password-feedback" style="display:none;">
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" id="username"
                                        name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" id="mobile_no"
                                        name="mobile_no" placeholder="Mobile No." required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg" id="email"
                                        placeholder="Email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <select class="form-select form-select-lg" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Clinic Assistant">Clinic Assistant</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        name="password" placeholder="Password" required>
                                    <div class="password-strength">
                                        <div class="progress">
                                            <div class="progress-bar" id="password-strength-bar" role="progressbar"
                                                style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="password-strength-text">Enter a password</small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="confirm_password"
                                        name="confirm_password" placeholder="Confirm Password" required>
                                </div>
                                <div class="mt-3 d-grid gap-2">
                                    <button name="register"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                        href="index.php">SIGN UP</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Already have an account? <a href="login.php" class="text-primary">Login</a>
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

    <script>
    // $(document).ready(function() {
    //     let usernameDuplicate = false;
    //     let emailDuplicate = false;
    //     let mobileDuplicate = false;
    //     let passwordValid = false;
    //     let confirmPasswordValid = false;
    //     let validationTimeout = null;

    //     // Password strength checker
    //     function checkPasswordStrength(password) {
    //         let strength = 0;

    //         if (password.length >= 8) strength++;
    //         if (/[a-z]/.test(password)) strength++;
    //         if (/[A-Z]/.test(password)) strength++;
    //         if (/[0-9]/.test(password)) strength++;
    //         if (/[^A-Za-z0-9]/.test(password)) strength++;

    //         const strengthBar = $('#password-strength-bar');
    //         const strengthText = $('#password-strength-text');

    //         switch (strength) {
    //             case 0:
    //             case 1:
    //                 strengthBar.attr('class', 'progress-bar bg-danger').css('width', '20%');
    //                 strengthText.text('Very Weak').attr('class', 'text-danger');
    //                 break;
    //             case 2:
    //                 strengthBar.attr('class', 'progress-bar bg-warning').css('width', '40%');
    //                 strengthText.text('Weak').attr('class', 'text-warning');
    //                 break;
    //             case 3:
    //                 strengthBar.attr('class', 'progress-bar bg-warning').css('width', '60%');
    //                 strengthText.text('Fair').attr('class', 'text-warning');
    //                 break;
    //             case 4:
    //                 strengthBar.attr('class', 'progress-bar bg-success').css('width', '80%');
    //                 strengthText.text('Good').attr('class', 'text-success');
    //                 break;
    //             case 5:
    //                 strengthBar.attr('class', 'progress-bar bg-success').css('width', '100%');
    //                 strengthText.text('Strong').attr('class', 'text-success');
    //                 break;
    //         }
    //         return strength >= 3;
    //     }

    //     // AJAX duplicate checker
    //     function checkDuplicate(field, value, callback) {
    //         if (!value || value.length < 3) {
    //             callback({
    //                 duplicate: false,
    //                 fields: [],
    //                 error: null
    //             });
    //             return;
    //         }
    //         $.ajax({
    //             url: 'check_admin_duplicate.php',
    //             type: 'POST',
    //             data: {
    //                 field: field,
    //                 value: value
    //             },
    //             dataType: 'json',
    //             success: callback,
    //             error: () => callback({
    //                 duplicate: false,
    //                 fields: [],
    //                 error: 'Network error.'
    //             })
    //         });
    //     }

    //     // Generalized field validator
    //     function validateField($el, regex, feedbackEl, duplicateFlag, duplicateMsg, fieldName) {
    //         const value = $el.val().trim();
    //         if (!value) {
    //             $el.removeClass('is-valid is-invalid');
    //             $(feedbackEl).hide();
    //             window[duplicateFlag] = false;
    //             return;
    //         }

    //         if (!regex.test(value)) {
    //             $el.addClass('is-invalid').removeClass('is-valid');
    //             $(feedbackEl).text(`Invalid ${fieldName}.`).show();
    //             window[duplicateFlag] = false;
    //             return;
    //         }

    //         if (validationTimeout) clearTimeout(validationTimeout);
    //         validationTimeout = setTimeout(() => {
    //             checkDuplicate(fieldName, value, (res) => {
    //                 if (res.error) {
    //                     $el.addClass('is-invalid').removeClass('is-valid');
    //                     $(feedbackEl).text(res.error).show();
    //                     window[duplicateFlag] = false;
    //                 } else if (res.duplicate && res.fields.includes(fieldName)) {
    //                     $el.addClass('is-invalid').removeClass('is-valid');
    //                     $(feedbackEl).text(duplicateMsg).show();
    //                     window[duplicateFlag] = true;
    //                 } else {
    //                     $el.removeClass('is-invalid').addClass('is-valid');
    //                     $(feedbackEl).hide();
    //                     window[duplicateFlag] = false;
    //                 }
    //             });
    //         }, 300);
    //     }

    //     // Username
    //     $('#username').on('input', function() {
    //         validateField(
    //             $(this),
    //             /^[a-zA-Z0-9_]{3,20}$/,
    //             '#username-feedback',
    //             'usernameDuplicate',
    //             'This username is already taken.',
    //             'username'
    //         );
    //     });

    //     // Email
    //     $('#email').on('input', function() {
    //         validateField(
    //             $(this),
    //             /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    //             '#email-feedback',
    //             'emailDuplicate',
    //             'This email is already registered.',
    //             'email'
    //         );
    //     });

    //     // Mobile
    //     $('#mobile_no').on('input', function() {
    //         validateField(
    //             $(this),
    //             /^(\+63|0)\d{10}$/,
    //             '#mobile_no-feedback',
    //             'mobileDuplicate',
    //             'This mobile number is already registered.',
    //             'mobile_no'
    //         );
    //     });

    //     // Role
    //     $('#role').on('change input', function() {
    //         if ($(this).val()) {
    //             $(this).removeClass('is-invalid').addClass('is-valid');
    //             $('#role-feedback').hide();
    //         } else {
    //             $(this).addClass('is-invalid').removeClass('is-valid');
    //             $('#role-feedback').text('Please select a role.').show();
    //         }
    //     });

    //     // Password
    //     $('#password').on('input', function() {
    //         const password = $(this).val().trim();
    //         passwordValid = checkPasswordStrength(password);
    //         if (!passwordValid && password) {
    //             $(this).addClass('is-invalid').removeClass('is-valid');
    //             $('#password-feedback').text(
    //                 'Password must be at least 8 characters with uppercase, lowercase, and numbers.'
    //             ).show();
    //         } else if (passwordValid) {
    //             $(this).removeClass('is-invalid').addClass('is-valid');
    //             $('#password-feedback').hide();
    //         } else {
    //             $(this).removeClass('is-invalid is-valid');
    //             $('#password-feedback').hide();
    //         }
    //         $('#confirm_password').trigger('input');
    //     });

    //     // Confirm password
    //     $('#confirm_password').on('input', function() {
    //         const password = $('#password').val().trim();
    //         const confirmPassword = $(this).val().trim();
    //         if (!confirmPassword) {
    //             confirmPasswordValid = false;
    //             $(this).removeClass('is-valid is-invalid');
    //             $('#confirm_password-feedback').hide();
    //         } else if (password !== confirmPassword) {
    //             confirmPasswordValid = false;
    //             $(this).addClass('is-invalid').removeClass('is-valid');
    //             $('#confirm_password-feedback').text('Passwords do not match.').show();
    //         } else {
    //             confirmPasswordValid = true;
    //             $(this).removeClass('is-invalid').addClass('is-valid');
    //             $('#confirm_password-feedback').hide();
    //         }
    //     });

    //     // Form submit
    //     $('#adminRegisterForm').on('submit', function(e) {
    //         e.preventDefault();
    //         const hasErrors =
    //             usernameDuplicate || emailDuplicate || mobileDuplicate || !passwordValid || !
    //             confirmPasswordValid;
    //         if (hasErrors) {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Validation Error',
    //                 text: 'Please fix the highlighted errors before submitting.'
    //             });
    //             return false;
    //         }
    //         this.submit();
    //     });
    // });
    </script>
</body>

</html>