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

                                <div class="form-group">
                                    <label for="username" class="form-label">
                                        Username
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Choose a unique username (3-20 characters, letters, numbers, and underscores only)"></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="username"
                                        name="username" placeholder="Username" required>
                                    <div class="invalid-feedback" id="username-feedback">Please enter a username.</div>
                                </div>

                                <div class="form-group">
                                    <label for="mobile_no" class="form-label">
                                        Mobile Number
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter your mobile number (10-15 digits). This will be used for appointment notifications."></i>
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="mobile_no"
                                        name="mobile_no" placeholder="Mobile No." required>
                                    <div class="invalid-feedback" id="mobile_no-feedback">Please enter a valid mobile
                                        number.</div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        Email
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter your email address. This will be used for account verification and notifications."></i>
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="email"
                                        placeholder="Email" name="email" required>
                                    <div class="invalid-feedback" id="email-feedback">Please enter a valid email.</div>
                                </div>

                                <div class="form-group">
                                    <label for="role" class="form-label">
                                        Role
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Select your role: Doctor (can manage appointments and view reports) or Clinic Assistant (can assist with appointments and administrative tasks)."></i>
                                    </label>
                                    <select class="form-select form-select-lg" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="Doctor">Doctor</option>
                                        <option value="Clinic Assistant">Clinic Assistant</option>
                                    </select>
                                    <div class="invalid-feedback" id="role-feedback">Please select a role.</div>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        Password
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Password must contain: at least 8 characters, one uppercase letter, one lowercase letter, and one number. Special characters are recommended for stronger security."></i>
                                    </label>
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        name="password" placeholder="Password" required>
                                    <div class="invalid-feedback" id="password-feedback"></div>
                                    <div class="password-strength mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="password-strength-bar" role="progressbar"
                                                style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="password-strength-text">Enter a password</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="confirm_password" class="form-label">
                                        Confirm Password
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Re-enter your password to confirm it matches. This helps prevent typing errors."></i>
                                    </label>
                                    <input type="password" class="form-control form-control-lg" id="confirm_password"
                                        name="confirm_password" placeholder="Confirm Password" required>
                                    <div class="invalid-feedback" id="confirm_password-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="showPassword">
                                        <label class="form-check-label" for="showPassword">
                                            Show Password
                                            <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="right" 
                                               title="Check this box to temporarily reveal your password as you type. Uncheck to hide it again."></i>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-3 d-grid gap-2">
                                    <button name="register"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                        type="submit">SIGN UP</button>
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
    <!-- Bootstrap Bundle for Tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tooltip System -->
    <script src="assets/js/tooltips.js"></script>

    <script>
    $(document).ready(function() {
        let emailTimeout, mobileTimeout;

        // === Utility: AJAX duplicate check ===
        function checkDuplicate(field, value, callback) {
            $.ajax({
                url: "check_admin_duplicate.php",
                type: "POST",
                data: {
                    field: field,
                    value: value
                },
                success: function(response) {
                    try {
                        callback(JSON.parse(response));
                    } catch (e) {
                        callback({
                            duplicate: false
                        });
                    }
                },
                error: function() {
                    callback({
                        duplicate: false
                    });
                }
            });
        }

        // === Username (basic required check only) ===
        $("#username").on("input blur", function() {
            if ($(this).val().trim().length < 3) {
                $(this).addClass("is-invalid");
                $("#username-feedback").text("Username must be at least 3 characters.");
            } else {
                $(this).removeClass("is-invalid").addClass("is-valid");
            }
        });

        // === Mobile number ===
        $("#mobile_no").on("input blur", function() {
            const mobile = $(this).val().trim();
            const regex = /^[0-9+\-\s()]{10,15}$/;
            const $this = $(this);

            $this.removeClass("is-valid is-invalid");

            if (!regex.test(mobile)) {
                $this.addClass("is-invalid");
                $("#mobile_no-feedback").text("Please enter a valid mobile number.");
                return;
            }

            if (mobileTimeout) clearTimeout(mobileTimeout);
            mobileTimeout = setTimeout(() => {
                checkDuplicate("mobile_no", mobile, function(res) {
                    if (res.duplicate) {
                        $this.removeClass("is-valid").addClass("is-invalid");
                        $("#mobile_no-feedback").text(
                            "This mobile number is already registered.");
                    } else {
                        $this.removeClass("is-invalid").addClass("is-valid");
                    }
                });
            }, 400);
        });

        // === Email ===
        $("#email").on("input blur", function() {
            const email = $(this).val().trim();
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const $this = $(this);

            $this.removeClass("is-valid is-invalid");

            if (!regex.test(email)) {
                $this.addClass("is-invalid");
                $("#email-feedback").text("Please enter a valid email address.");
                return;
            }

            if (emailTimeout) clearTimeout(emailTimeout);
            emailTimeout = setTimeout(() => {
                checkDuplicate("email", email, function(res) {
                    if (res.duplicate) {
                        $this.removeClass("is-valid").addClass("is-invalid");
                        $("#email-feedback").text("This email is already registered.");
                    } else {
                        $this.removeClass("is-invalid").addClass("is-valid");
                    }
                });
            }, 400);
        });

        // === Role ===
        $("#role").on("change", function() {
            if ($(this).val()) {
                $(this).removeClass("is-invalid").addClass("is-valid");
            } else {
                $(this).addClass("is-invalid");
                $("#role-feedback").text("Please select a role.");
            }
        });

        // === Password validation function ===
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            const metCount = Object.values(requirements).filter(Boolean).length;
            const allRequiredMet = requirements.length && requirements.uppercase && 
                                   requirements.lowercase && requirements.number;

            return {
                valid: allRequiredMet,
                requirements: requirements,
                strength: metCount
            };
        }

        // === Password strength ===
        $("#password").on("input", function() {
            const password = $(this).val();
            const strengthBar = $("#password-strength-bar");
            const strengthText = $("#password-strength-text");
            const $this = $(this);

            if (!password) {
                strengthBar.css("width", "0%");
                strengthText.text("Enter a password");
                strengthBar.removeClass().addClass("progress-bar bg-secondary");
                $this.removeClass("is-valid is-invalid");
                return;
            }

            const validation = validatePassword(password);
            const { requirements, strength } = validation;

            // Calculate strength percentage (out of 5 requirements)
            let width = (strength / 5) * 100;
            strengthBar.css("width", width + "%");

            // Update strength indicator
            switch (strength) {
                case 0:
                case 1:
                    strengthText.text("Very Weak");
                    strengthBar.removeClass().addClass("progress-bar bg-danger");
                    break;
                case 2:
                    strengthText.text("Weak");
                    strengthBar.removeClass().addClass("progress-bar bg-danger");
                    break;
                case 3:
                    strengthText.text("Fair");
                    strengthBar.removeClass().addClass("progress-bar bg-warning");
                    break;
                case 4:
                    strengthText.text("Good");
                    strengthBar.removeClass().addClass("progress-bar bg-info");
                    break;
                case 5:
                    strengthText.text("Strong");
                    strengthBar.removeClass().addClass("progress-bar bg-success");
                    break;
            }

            // Validate and show feedback
            if (!validation.valid) {
                $this.addClass("is-invalid").removeClass("is-valid");
                let missing = [];
                if (!requirements.length) missing.push("at least 8 characters");
                if (!requirements.uppercase) missing.push("one uppercase letter");
                if (!requirements.lowercase) missing.push("one lowercase letter");
                if (!requirements.number) missing.push("one number");
                $("#password-feedback").text("Password must contain: " + missing.join(", ") + ".");
            } else {
                $this.removeClass("is-invalid").addClass("is-valid");
                $("#password-feedback").text("");
            }
        });

        // === Confirm password ===
        $("#confirm_password, #password").on("input", function() {
            const password = $("#password").val();
            const confirm = $("#confirm_password").val();

            if (!confirm) {
                $("#confirm_password").removeClass("is-valid is-invalid");
                $("#confirm_password-feedback").text("");
                return;
            }

            if (password && password === confirm) {
                $("#confirm_password").removeClass("is-invalid").addClass("is-valid");
                $("#confirm_password-feedback").text("");
            } else {
                $("#confirm_password").removeClass("is-valid").addClass("is-invalid");
                $("#confirm_password-feedback").text("Passwords do not match.");
            }
        });

        // === Show/Hide Password ===
        $("#showPassword").on("change", function() {
            const isChecked = $(this).is(":checked");
            const passwordField = $("#password");
            const confirmPasswordField = $("#confirm_password");

            if (isChecked) {
                passwordField.attr("type", "text");
                confirmPasswordField.attr("type", "text");
            } else {
                passwordField.attr("type", "password");
                confirmPasswordField.attr("type", "password");
            }
        });

        // === Form submit ===
        $("#adminRegisterForm").on("submit", function(e) {
            e.preventDefault();
            let isValid = true;

            // Validate password
            const password = $("#password").val();
            const passwordValidation = validatePassword(password);
            if (!passwordValidation.valid) {
                $("#password").addClass("is-invalid").removeClass("is-valid");
                let missing = [];
                if (!passwordValidation.requirements.length) missing.push("at least 8 characters");
                if (!passwordValidation.requirements.uppercase) missing.push("one uppercase letter");
                if (!passwordValidation.requirements.lowercase) missing.push("one lowercase letter");
                if (!passwordValidation.requirements.number) missing.push("one number");
                $("#password-feedback").text("Password must contain: " + missing.join(", ") + ".");
                isValid = false;
            }

            // Validate confirm password
            const confirmPassword = $("#confirm_password").val();
            if (password && confirmPassword) {
                if (password !== confirmPassword) {
                    $("#confirm_password").addClass("is-invalid").removeClass("is-valid");
                    $("#confirm_password-feedback").text("Passwords do not match.");
                    isValid = false;
                } else {
                    $("#confirm_password").removeClass("is-invalid").addClass("is-valid");
                    $("#confirm_password-feedback").text("");
                }
            } else if (confirmPassword) {
                $("#confirm_password").addClass("is-invalid").removeClass("is-valid");
                $("#confirm_password-feedback").text("Passwords do not match.");
                isValid = false;
            }

            // Check HTML5 validity
            if (!this.checkValidity()) {
                isValid = false;
            }

            if (!isValid) {
                $(this).addClass("was-validated");
                e.stopPropagation();
                return false;
            }

            // If all validations pass, submit the form
            this.submit();
        });
    });
    </script>
</body>

</html>