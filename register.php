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
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
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
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
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
    $(document).ready(function() {
        let usernameDuplicate = false;
        let emailDuplicate = false;
        let mobileDuplicate = false;
        let passwordValid = false;
        let confirmPasswordValid = false;
        let validationTimeout = null;

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;

            if (password.length >= 8) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            const strengthBar = $('#password-strength-bar');
            const strengthText = $('#password-strength-text');

            switch (strength) {
                case 0:
                case 1:
                    strengthBar.removeClass('bg-success bg-warning').addClass('bg-danger');
                    strengthBar.css('width', '20%');
                    strengthText.text('Very Weak').removeClass('text-success text-warning').addClass(
                        'text-danger');
                    break;
                case 2:
                    strengthBar.removeClass('bg-success bg-danger').addClass('bg-warning');
                    strengthBar.css('width', '40%');
                    strengthText.text('Weak').removeClass('text-success text-danger').addClass('text-warning');
                    break;
                case 3:
                    strengthBar.removeClass('bg-success bg-danger').addClass('bg-warning');
                    strengthBar.css('width', '60%');
                    strengthText.text('Fair').removeClass('text-success text-danger').addClass('text-warning');
                    break;
                case 4:
                    strengthBar.removeClass('bg-warning bg-danger').addClass('bg-success');
                    strengthBar.css('width', '80%');
                    strengthText.text('Good').removeClass('text-warning text-danger').addClass('text-success');
                    break;
                case 5:
                    strengthBar.removeClass('bg-warning bg-danger').addClass('bg-success');
                    strengthBar.css('width', '100%');
                    strengthText.text('Strong').removeClass('text-warning text-danger').addClass(
                    'text-success');
                    break;
            }

            return strength >= 3;
        }

        // Check duplicate function with improved error handling
        function checkDuplicate(field, value, callback) {
            if (!value || value.length < 3) {
                callback({
                    duplicate: false,
                    fields: [],
                    error: null
                });
                return;
            }

            $.ajax({
                url: 'check_admin_duplicate.php',
                type: 'POST',
                data: {
                    field: field,
                    value: value
                },
                dataType: 'json',
                timeout: 5000,
                success: function(response) {
                    callback(response);
                },
                error: function(xhr, status, error) {
                    console.error('Duplicate check error:', error);
                    callback({
                        duplicate: false,
                        fields: [],
                        error: 'Network error. Please try again.'
                    });
                }
            });
        }

        // Username validation
        $('#username').on('blur input', function() {
            const username = $(this).val().trim();
            const $this = $(this);
            const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;

            if (username) {
                if (!usernameRegex.test(username)) {
                    usernameDuplicate = false;
                    $this.addClass('is-invalid').removeClass('is-valid');
                    $('#username-feedback').text(
                            'Username must be 3-20 characters, letters, numbers, and underscores only.')
                        .show();
                } else {
                    // Clear previous timeout
                    if (validationTimeout) {
                        clearTimeout(validationTimeout);
                    }

                    // Debounce the duplicate check
                    validationTimeout = setTimeout(() => {
                        checkDuplicate('username', username, function(response) {
                            if (response.error) {
                                usernameDuplicate = false;
                                $this.addClass('is-invalid').removeClass('is-valid');
                                $('#username-feedback').text(response.error).show();
                            } else if (response.duplicate && response.fields.includes(
                                    'username')) {
                                usernameDuplicate = true;
                                $this.addClass('is-invalid').removeClass('is-valid');
                                $('#username-feedback').text(
                                    'This username is already taken.').show();
                            } else {
                                usernameDuplicate = false;
                                $this.removeClass('is-invalid').addClass('is-valid');
                                $('#username-feedback').hide();
                            }
                        });
                    }, 500);
                }
            } else {
                usernameDuplicate = false;
                $this.removeClass('is-invalid is-valid');
                $('#username-feedback').hide();
            }
        });

        // Email validation
        $('#email').on('blur input', function() {
            const email = $(this).val().trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const $this = $(this);

            if (email) {
                if (!emailRegex.test(email)) {
                    emailDuplicate = false;
                    $this.addClass('is-invalid').removeClass('is-valid');
                    $('#email-feedback').text('Please enter a valid email address.').show();
                } else {
                    // Clear previous timeout
                    if (validationTimeout) {
                        clearTimeout(validationTimeout);
                    }

                    // Debounce the duplicate check
                    validationTimeout = setTimeout(() => {
                        checkDuplicate('email', email, function(response) {
                            if (response.error) {
                                emailDuplicate = false;
                                $this.addClass('is-invalid').removeClass('is-valid');
                                $('#email-feedback').text(response.error).show();
                            } else if (response.duplicate && response.fields.includes(
                                    'email')) {
                                emailDuplicate = true;
                                $this.addClass('is-invalid').removeClass('is-valid');
                                $('#email-feedback').text(
                                    'This email is already registered.').show();
                            } else {
                                emailDuplicate = false;
                                $this.removeClass('is-invalid').addClass('is-valid');
                                $('#email-feedback').hide();
                            }
                        });
                    }, 500);
                }
            } else {
                emailDuplicate = false;
                $this.removeClass('is-invalid is-valid');
                $('#email-feedback').hide();
            }
        });

        // Mobile number validation
        $('#mobile_no').on('blur input', function() {
            const mobile = $(this).val().trim();
            const mobileRegex = /^[0-9+\-\s()]{10,15}$/;
            const $this = $(this);

            if (mobile) {
                if (!mobileRegex.test(mobile)) {
                    mobileDuplicate = false;
                    $this.addClass('is-invalid').removeClass('is-valid');
                    $('#mobile_no-feedback').text('Please enter a valid mobile number.').show();
                } else {
                    // Clear previous timeout
                    if (validationTimeout) {
                        clearTimeout(validationTimeout);
                    }

                    // Debounce the duplicate check
                    validationTimeout = setTimeout(() => {
                        checkDuplicate('mobile_no', mobile, function(response) {
                            if (response.error) {
                                mobileDuplicate = false;
                                $this.addClass('is-invalid').removeClass('is-valid');
                                $('#mobile_no-feedback').text(response.error).show();
                            } else if (response.duplicate && response.fields.includes(
                                    'mobile_no')) {
                                mobileDuplicate = true;
                                $this.addClass('is-invalid').removeClass('is-valid');
                                $('#mobile_no-feedback').text(
                                        'This mobile number is already registered.')
                                    .show();
                            } else {
                                mobileDuplicate = false;
                                $this.removeClass('is-invalid').addClass('is-valid');
                                $('#mobile_no-feedback').hide();
                            }
                        });
                    }, 500);
                }
            } else {
                mobileDuplicate = false;
                $this.removeClass('is-invalid is-valid');
                $('#mobile_no-feedback').hide();
            }
        });

        // Role validation
        $('#role').on('change', function() {
            const $this = $(this);
            if ($this.val()) {
                $this.removeClass('is-invalid').addClass('is-valid');
                $('#role-feedback').hide();
            } else {
                $this.addClass('is-invalid').removeClass('is-valid');
                $('#role-feedback').text('Please select a role.').show();
            }
        });

        // Password validation
        $('#password').on('input', function() {
            const password = $(this).val();
            const $this = $(this);
            passwordValid = checkPasswordStrength(password);

            if (password && !passwordValid) {
                $this.addClass('is-invalid').removeClass('is-valid');
                $('#password-feedback').text(
                    'Password must be at least 8 characters with uppercase, lowercase, and numbers.'
                    ).show();
            } else if (password && passwordValid) {
                $this.removeClass('is-invalid').addClass('is-valid');
                $('#password-feedback').hide();
            } else {
                $this.removeClass('is-invalid is-valid');
                $('#password-feedback').hide();
            }

            // Re-check confirm password if it has a value
            if ($('#confirm_password').val()) {
                $('#confirm_password').trigger('input');
            }
        });

        // Confirm password validation
        $('#confirm_password').on('input', function() {
            const password = $('#password').val();
            const confirmPassword = $(this).val();
            const $this = $(this);

            if (confirmPassword) {
                if (password !== confirmPassword) {
                    confirmPasswordValid = false;
                    $this.addClass('is-invalid').removeClass('is-valid');
                    $('#confirm_password-feedback').text('Passwords do not match.').show();
                } else {
                    confirmPasswordValid = true;
                    $this.removeClass('is-invalid').addClass('is-valid');
                    $('#confirm_password-feedback').hide();
                }
            } else {
                confirmPasswordValid = false;
                $this.removeClass('is-invalid is-valid');
                $('#confirm_password-feedback').hide();
            }
        });

        // Form submission validation
        $('#adminRegisterForm').on('submit', function(e) {
            e.preventDefault();

            // Check all validations
            const hasErrors = usernameDuplicate || emailDuplicate || mobileDuplicate || !
                passwordValid || !confirmPasswordValid;

            if (hasErrors) {
                let errorMessages = [];

                if (usernameDuplicate) errorMessages.push('Username is already taken');
                if (emailDuplicate) errorMessages.push('Email is already registered');
                if (mobileDuplicate) errorMessages.push('Mobile number is already registered');
                if (!passwordValid) errorMessages.push('Password does not meet requirements');
                if (!confirmPasswordValid) errorMessages.push('Passwords do not match');

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: errorMessages.join(', '),
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // If all validations pass, submit the form
            this.submit();
        });

        // Real-time form validation feedback
        $('input, select').on('blur', function() {
            const $this = $(this);
            const fieldId = $this.attr('id');

            if ($this.prop('required') && !$this.val()) {
                $this.addClass('is-invalid').removeClass('is-valid');
            } else if ($this.hasClass('is-invalid') && $this.val()) {
                // Only remove invalid class if it's not a duplicate error or password validation
                if (!fieldId || (!fieldId.includes('username') && !fieldId.includes('email') &&
                        !fieldId.includes('mobile_no') && !fieldId.includes('password'))) {
                    $this.removeClass('is-invalid').addClass('is-valid');
                }
            }
        });
    });
    </script>
</body>

</html>