<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Sign Up - Quiatson Clinic</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Favicons -->
    <link href="assets/img/favicon.ico" rel="icon" />
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/vendor/aos/aos.css" rel="stylesheet" />
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="assets/js/sweetalert2.all.min.js"></script>
</head>

<body class="starter-page-page">
    <header id="header" class="header sticky-top">
        <div class="topbar d-flex align-items-center">
            <div class="container d-flex justify-content-center justify-content-md-between">
                <div class="contact-info d-flex align-items-center">
                    <i class="bi bi-envelope d-flex align-items-center"><a
                            href="mailto:quiatsonclinic@gmail.com">quiatsonclinic@gmail.com</a></i>
                    <i class="bi bi-phone d-flex align-items-center ms-4"><span>+639150623505</span></i>
                </div>
                <div class="social-links d-none d-md-flex align-items-center">
                    <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
        <!-- End Top Bar -->

        <div class="branding d-flex align-items-center">
            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="index" class="logo d-flex align-items-center me-auto">
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <!-- <img src="assets/img/logo.png" alt=""> -->
                    <h1 class="sitename">Quiatson Clinic</h1>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li>
                            <a href="index">Home<br /></a>
                        </li>
                        <!-- <li><a href="#about">About</a></li> -->
                        <li><a href="index#doctors">Doctors</a></li>
                        <li><a href="index#contact">Contact</a></li>
                        <li><a href="login">Admin</a></li>
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

                <a class="cta-btn d-none d-sm-block" href="#appointment">Make an Appointment</a>
            </div>
        </div>
    </header>
    <?php 
 include "alert.php";  
?>
    <main class="main">
        <!-- Page Title -->
        <div class="page-title" data-aos="fade">
            <div class="heading">
                <div class="container">
                    <div class="row d-flex justify-content-center text-center">
                        <div class="col-lg-8">
                            <h1>Patient's Sign Up Form</h1>
                            <p class="mb-0">
                                Create your account to access our clinic services with ease.
                                By signing up, you can manage your personal information, book appointments, and stay
                                updated with your medical records—all in one secure place.

                                Our goal is to provide a smooth and convenient experience so you can focus on what
                                matters most: your health and well-being.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Starter Section Section -->
        <section id="starter-section" class="starter-section section">
            <!-- Section Title -->
            <!-- <div class="container section-title" data-aos="fade-up">
                <h2>Starter Section</h2>
                <p>
                    Necessitatibus eius consequatur ex aliquid fuga eum quidem sint
                    consectetur velit
                </p>
            </div> -->
            <!-- End Section Title -->

            <div class="container d-flex justify-content-center align-items-center" data-aos="fade-up">
                <div class="card">
                    <div class="card-body m-4">
                        <form action="signup_code.php" method="POST" id="signupForm">
                            <div id="duplicate-warning" class="alert alert-warning" style="display:none;"></div>
                            <div class="invalid-feedback" id="email-feedback" style="display:none;"></div>
                            <div class="invalid-feedback" id="phone-feedback" style="display:none;"></div>
                            <div class="row g-3">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label">First Name <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter your legal first name as it appears on your ID"></i>
                                    </label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" required />
                                </div>
                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label">Last Name <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter your legal last name (surname) as it appears on your ID"></i>
                                    </label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" required />
                                </div>
                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter a valid email address. You'll receive appointment confirmations and updates here."></i>
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required />
                                </div>
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Mobile Number <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter your mobile number (10-15 digits). You'll receive SMS notifications for appointments."></i>
                                    </label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required />
                                </div>
                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <label for="dob" class="form-label">Date of Birth <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Select your date of birth. This helps us provide age-appropriate care and verify your identity."></i>
                                    </label>
                                    <input type="date" class="form-control" id="dob" name="dob" required />
                                </div>
                                <!-- Sex -->
                                <div class="col-md-6">
                                    <label for="sex" class="form-label">Sex <span class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Select your biological sex. This information is required for medical records."></i>
                                    </label>
                                    <select id="sex" name="sex" class="form-select" required>
                                        <option value="">Select...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Password must contain: at least 8 characters, one uppercase letter, one lowercase letter, and one number. Special characters are recommended."></i>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            required />
                                        <a href="#" class="input-group-text toggle-password" data-target="password"
                                           data-bs-toggle="tooltip" title="Click to show/hide password">
                                            <i class="bi bi-eye-slash"></i>
                                        </a>
                                    </div>
                                    <div class="invalid-feedback" id="password-feedback" style="display:none;"></div>
                                    <div class="password-strength mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="password-strength-bar" role="progressbar"
                                                style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="password-strength-text">Enter a password</small>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="confirmPassword" class="form-label">Confirm Password <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Re-enter your password exactly as you typed it above to confirm there are no typing errors."></i>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirmPassword"
                                            name="confirmPassword" required />
                                        <a href="#" class="input-group-text toggle-password"
                                            data-target="confirmPassword"
                                            data-bs-toggle="tooltip" title="Click to show/hide password">
                                            <i class="bi bi-eye-slash"></i>
                                        </a>
                                    </div>
                                    <div class="invalid-feedback" id="confirm-password-feedback" style="display:none;"></div>
                                </div>

                                <!-- Address -->
                                <div class="col-12">
                                    <label for="address" class="form-label">Address <span
                                            class="text-danger">*</span>
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter your complete residential address including street, city, and postal code. This is used for medical records and emergency contact."></i>
                                    </label>
                                    <textarea class="form-control" id="address" name="address" rows="2"
                                        required></textarea>
                                </div>
                                <!-- Submit Button -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" name="client_register" class="btn btn-primary px-5">Sign
                                        Up</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
            $(document).ready(function() {
                let emailDuplicate = false;
                let phoneDuplicate = false;
                let passwordValid = false;
                let confirmPasswordValid = false;
                let validationTimeout = null;

                // Password validation function
                function validatePassword(password) {
                    const requirements = {
                        length: password.length >= 8,
                        uppercase: /[A-Z]/.test(password),
                        lowercase: /[a-z]/.test(password),
                        number: /[0-9]/.test(password),
                        special: /[^A-Za-z0-9]/.test(password)
                    };

                    const metCount = Object.values(requirements).filter(Boolean).length;
                    // Require: length, uppercase, lowercase, and number (special is optional but recommended)
                    const allRequiredMet = requirements.length && requirements.uppercase && 
                                           requirements.lowercase && requirements.number;

                    return {
                        valid: allRequiredMet,
                        requirements: requirements,
                        strength: metCount
                    };
                }

                // Password strength checker
                function checkPasswordStrength(password) {
                    if (!password) {
                        $('#password-strength-bar').css('width', '0%');
                        $('#password-strength-text').text('Enter a password');
                        $('#password-strength-bar').removeClass('bg-success bg-warning bg-danger').addClass('bg-secondary');
                        return false;
                    }

                    const validation = validatePassword(password);
                    const { requirements, strength } = validation;

                    const strengthBar = $('#password-strength-bar');
                    const strengthText = $('#password-strength-text');

                    // Calculate width based on 5 requirements
                    let width = (strength / 5) * 100;
                    strengthBar.css('width', width + '%');

                    switch (strength) {
                        case 0:
                        case 1:
                            strengthBar.removeClass('bg-success bg-warning bg-secondary').addClass('bg-danger');
                            strengthText.text('Very Weak').removeClass('text-success text-warning').addClass('text-danger');
                            break;
                        case 2:
                            strengthBar.removeClass('bg-success bg-danger bg-secondary').addClass('bg-warning');
                            strengthText.text('Weak').removeClass('text-success text-danger').addClass('text-warning');
                            break;
                        case 3:
                            strengthBar.removeClass('bg-success bg-danger bg-secondary').addClass('bg-warning');
                            strengthText.text('Fair').removeClass('text-success text-danger').addClass('text-warning');
                            break;
                        case 4:
                            strengthBar.removeClass('bg-warning bg-danger bg-secondary').addClass('bg-success');
                            strengthText.text('Good').removeClass('text-warning text-danger').addClass('text-success');
                            break;
                        case 5:
                            strengthBar.removeClass('bg-warning bg-danger bg-secondary').addClass('bg-success');
                            strengthText.text('Strong').removeClass('text-warning text-danger').addClass('text-success');
                            break;
                    }

                    return validation.valid;
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
                        url: 'check_duplicate.php',
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
                                        $this.addClass('is-invalid').removeClass(
                                            'is-valid');
                                        $('#email-feedback').text(response.error)
                                            .show();
                                    } else if (response.duplicate && response.fields
                                        .includes('email')) {
                                        emailDuplicate = true;
                                        $this.addClass('is-invalid').removeClass(
                                            'is-valid');
                                        $('#email-feedback').text(
                                                'This email is already registered.')
                                            .show();
                                    } else {
                                        emailDuplicate = false;
                                        $this.removeClass('is-invalid').addClass(
                                            'is-valid');
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

                // Phone validation
                $('#phone').on('blur input', function() {
                    const phone = $(this).val().trim();
                    const phoneRegex = /^[0-9+\-\s()]{10,15}$/;
                    const $this = $(this);

                    if (phone) {
                        if (!phoneRegex.test(phone)) {
                            phoneDuplicate = false;
                            $this.addClass('is-invalid').removeClass('is-valid');
                            $('#phone-feedback').text('Please enter a valid phone number.').show();
                        } else {
                            // Clear previous timeout
                            if (validationTimeout) {
                                clearTimeout(validationTimeout);
                            }

                            // Debounce the duplicate check
                            validationTimeout = setTimeout(() => {
                                checkDuplicate('phone', phone, function(response) {
                                    if (response.error) {
                                        phoneDuplicate = false;
                                        $this.addClass('is-invalid').removeClass(
                                            'is-valid');
                                        $('#phone-feedback').text(response.error)
                                            .show();
                                    } else if (response.duplicate && response.fields
                                        .includes('phone')) {
                                        phoneDuplicate = true;
                                        $this.addClass('is-invalid').removeClass(
                                            'is-valid');
                                        $('#phone-feedback').text(
                                            'This mobile number is already registered.'
                                        ).show();
                                    } else {
                                        phoneDuplicate = false;
                                        $this.removeClass('is-invalid').addClass(
                                            'is-valid');
                                        $('#phone-feedback').hide();
                                    }
                                });
                            }, 500);
                        }
                    } else {
                        phoneDuplicate = false;
                        $this.removeClass('is-invalid is-valid');
                        $('#phone-feedback').hide();
                    }
                });

                // Password validation
                $('#password').on('input', function() {
                    const password = $(this).val();
                    const $this = $(this);
                    passwordValid = checkPasswordStrength(password);

                    if (password && !passwordValid) {
                        $this.addClass('is-invalid').removeClass('is-valid');
                        const validation = validatePassword(password);
                        let missing = [];
                        if (!validation.requirements.length) missing.push('at least 8 characters');
                        if (!validation.requirements.uppercase) missing.push('one uppercase letter');
                        if (!validation.requirements.lowercase) missing.push('one lowercase letter');
                        if (!validation.requirements.number) missing.push('one number');
                        $('#password-feedback').text(
                            'Password must contain: ' + missing.join(', ') + '.'
                        ).show();
                    } else if (password && passwordValid) {
                        $this.removeClass('is-invalid').addClass('is-valid');
                        $('#password-feedback').hide();
                    } else {
                        $this.removeClass('is-invalid is-valid');
                        $('#password-feedback').hide();
                    }

                    // Re-check confirm password if it has a value
                    if ($('#confirmPassword').val()) {
                        $('#confirmPassword').trigger('input');
                    }
                });

                // Confirm password validation
                $('#confirmPassword').on('input', function() {
                    const password = $('#password').val();
                    const confirmPassword = $(this).val();
                    const $this = $(this);

                    if (!confirmPassword) {
                        confirmPasswordValid = false;
                        $this.removeClass('is-invalid is-valid');
                        $('#confirm-password-feedback').hide();
                        return;
                    }

                    // Only validate if password field has a value
                    if (!password) {
                        confirmPasswordValid = false;
                        $this.removeClass('is-invalid is-valid');
                        $('#confirm-password-feedback').hide();
                        return;
                    }

                    // Compare passwords
                    if (password === confirmPassword) {
                        confirmPasswordValid = true;
                        $this.removeClass('is-invalid').addClass('is-valid');
                        $('#confirm-password-feedback').hide();
                    } else {
                        confirmPasswordValid = false;
                        $this.addClass('is-invalid').removeClass('is-valid');
                        $('#confirm-password-feedback').text('Passwords do not match.').show();
                    }
                });

                // Toggle password visibility
                $('.toggle-password').on('click', function(e) {
                    e.preventDefault();
                    const target = $(this).data('target');
                    const input = $('#' + target);
                    const icon = $(this).find('i');

                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('bi-eye-slash').addClass('bi-eye');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('bi-eye').addClass('bi-eye-slash');
                    }
                });

                // Form submission validation
                $('#signupForm').on('submit', function(e) {
                    // Re-validate password before submission
                    const password = $('#password').val();
                    const passwordValidation = validatePassword(password);
                    passwordValid = passwordValidation.valid;

                    if (!passwordValid) {
                        $('#password').addClass('is-invalid').removeClass('is-valid');
                        let missing = [];
                        if (!passwordValidation.requirements.length) missing.push('at least 8 characters');
                        if (!passwordValidation.requirements.uppercase) missing.push('one uppercase letter');
                        if (!passwordValidation.requirements.lowercase) missing.push('one lowercase letter');
                        if (!passwordValidation.requirements.number) missing.push('one number');
                        $('#password-feedback').text(
                            'Password must contain: ' + missing.join(', ') + '.'
                        ).show();
                    }

                    // Re-validate confirm password
                    const confirmPassword = $('#confirmPassword').val();
                    if (password && confirmPassword) {
                        if (password !== confirmPassword) {
                            confirmPasswordValid = false;
                            $('#confirmPassword').addClass('is-invalid').removeClass('is-valid');
                            $('#confirm-password-feedback').text('Passwords do not match.').show();
                        } else {
                            confirmPasswordValid = true;
                            $('#confirmPassword').removeClass('is-invalid').addClass('is-valid');
                            $('#confirm-password-feedback').hide();
                        }
                    } else if (confirmPassword) {
                        confirmPasswordValid = false;
                        $('#confirmPassword').addClass('is-invalid').removeClass('is-valid');
                        $('#confirm-password-feedback').text('Passwords do not match.').show();
                    } else {
                        confirmPasswordValid = false;
                        $('#confirmPassword').removeClass('is-invalid is-valid');
                        $('#confirm-password-feedback').hide();
                    }

                    // Check all validations
                    const hasErrors = emailDuplicate || phoneDuplicate || !passwordValid || !
                        confirmPasswordValid;

                    if (hasErrors) {
                        e.preventDefault(); // ❗ Block only if there's an error

                        let errorMessages = [];

                        if (emailDuplicate) errorMessages.push('Email is already registered');
                        if (phoneDuplicate) errorMessages.push('Mobile number is already registered');
                        if (!passwordValid) {
                            let missing = [];
                            if (!passwordValidation.requirements.length) missing.push('at least 8 characters');
                            if (!passwordValidation.requirements.uppercase) missing.push('one uppercase letter');
                            if (!passwordValidation.requirements.lowercase) missing.push('one lowercase letter');
                            if (!passwordValidation.requirements.number) missing.push('one number');
                            errorMessages.push('Password must contain: ' + missing.join(', '));
                        }
                        if (!confirmPasswordValid) errorMessages.push('Passwords do not match');

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: errorMessages.join('. '),
                            confirmButtonText: 'OK'
                        });

                        return false;
                    }

                    // NO ERRORS → allow natural submit (NO e.preventDefault)
                });

                // Real-time form validation feedback
                $('input, select, textarea').on('blur', function() {
                    const $this = $(this);
                    const fieldId = $this.attr('id');

                    if ($this.prop('required') && !$this.val()) {
                        $this.addClass('is-invalid').removeClass('is-valid');
                    } else if ($this.hasClass('is-invalid') && $this.val()) {
                        // Only remove invalid class if it's not a duplicate error or password validation
                        if (!fieldId || (!fieldId.includes('email') && !fieldId.includes('phone') &&
                                !fieldId.includes('password'))) {
                            $this.removeClass('is-invalid').addClass('is-valid');
                        }
                    }
                });
            });
            </script>
        </section>
        <!-- /Starter Section Section -->
    </main>
    <footer id="footer" class="footer light-background">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Quiatson Clinic</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Centro Pojo, Bugasong </p>
                        <p>Antique, Philippines</p>
                        <p class="mt-3">
                            <strong>Phone:</strong> <span>+639150623505</span>
                        </p>
                        <p><strong>Email:</strong> <span>quiatsonclinic@gmail.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="#">Web Design</a></li>
                        <li><a href="#">Web Development</a></li>
                        <li><a href="#">Product Management</a></li>
                        <li><a href="#">Marketing</a></li>
                        <li><a href="#">Graphic Design</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Hic solutasetp</h4>
                    <ul>
                        <li><a href="#">Molestiae accusamus iure</a></li>
                        <li><a href="#">Excepturi dignissimos</a></li>
                        <li><a href="#">Suscipit distinctio</a></li>
                        <li><a href="#">Dilecta</a></li>
                        <li><a href="#">Sit quas consectetur</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Nobis illum</h4>
                    <ul>
                        <li><a href="#">Ipsam</a></li>
                        <li><a href="#">Laudantium dolorum</a></li>
                        <li><a href="#">Dinera</a></li>
                        <li><a href="#">Trodelas</a></li>
                        <li><a href="#">Flexo</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container copyright text-center mt-4">
            <p>
                © <span>Copyright</span>
                <strong class="px-1 sitename">Quiatson Clinic</strong>
                <span>All Rights Reserved</span>
            </p>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer>
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <!-- Preloader -->
    <div id="preloader"></div>
    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <!-- Bootstrap Bundle for Tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tooltip System -->
    <script src="assets/js/tooltips.js"></script>
</body>

</html>