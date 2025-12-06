<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Welcome - Quiatson Clinic</title>
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
</head>

<body class="index-page">
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
                            <a href="#hero" class="active">Home<br /></a>
                        </li>
                        <!-- <li><a href="#about">About</a></li> -->
                        <li><a href="#doctors">Doctors</a></li>
                        <li><a href="#contact">Contact</a></li>
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
        <!-- Hero Section -->
        <section id="hero" class="hero section light-background">
            <img src="assets/img/hero-bg-new.jpg" alt="" data-aos="fade-in" />

            <div class="container position-relative">
                <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
                    <h2>WELCOME TO QUIATSON CLINIC</h2>
                    <p>
                        a medical clinic that provides a wide range of healthcare services to patients.
                    </p>
                </div>
                <!-- End Welcome -->


            </div>
        </section>
        <!-- /Hero Section -->

        <!-- Client Section -->
        <section id="appointment" class="appointment section">
            <div class="container" data-aos="fade-up">
                <div class="row justify-content-center align-items-center">
                    <div class="section-title">
                        <h2>Client Login</h2>
                        <p class="mt-3">
                            Access your patient profile, appointment schedules, and records. Please log in using
                            your registered email and password.
                        </p>
                    </div>

                    <!-- Left Column: Title & Description -->
                    <div class="col-md-7 mb-4 mb-md-0">
                        <img src="assets/img//undraw_doctor_aum1.svg" class="img-fluid" alt="">
                    </div>

                    <!-- Right Column: Login Form -->
                    <div class="col-md-5">
                        <form action="signup_code.php" method="POST" role="form" class="bg-light p-4 rounded shadow">
                            <h5 class="mb-4 text-center">Login to Your Account</h5>

                            <!-- Email -->
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" required />
                            </div>

                            <!-- Password -->
                            <div class="form-group mb-3" style="position: relative;">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required
                                    style="padding-right: 40px;" />

                                <!-- Eye Icon (anchor tag) -->
                                <a href="javascript:void(0)" id="togglePassword"
                                    style="position: absolute; right: 12px; top: 65%; transform: translateY(-50%); cursor: pointer; color: #333; text-decoration: none;">
                                    <i class="bi bi-eye-slash"></i>
                                </a>
                            </div>

                            <script>
                            const togglePassword = document.querySelector('#togglePassword');
                            const passwordInput = document.querySelector('#password');
                            const icon = togglePassword.querySelector('i');

                            togglePassword.addEventListener('click', () => {
                                const isPassword = passwordInput.type === 'password';
                                passwordInput.type = isPassword ? 'text' : 'password';

                                // Swap eye / eye-slash
                                icon.classList.toggle('bi-eye');
                                icon.classList.toggle('bi-eye-slash');
                            });
                            </script>


                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" name="login_client" class="btn btn-primary w-100">Login</button>
                            </div>

                            <!-- Optional Links -->
                            <div class="text-center mt-3">
                                <a href="client/forgot_password.php">Forgot Password?</a> | <a
                                    href="sign-up.php">Register</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </section>
        <!-- /Client Section -->

        <!-- Doctors Section -->
        <section id="doctors" class="doctors section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Doctors</h2>
                <p>
                    We proudly serve the community with skilled and caring doctors who are dedicated
                    to improving the health and well-being of every patient.
                </p>
            </div>
            <!-- End Section Title -->

            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="team-member d-flex align-items-start">
                            <div class="pic">
                                <img src="assets/img/doctors/doctors-1.jpg" class="img-fluid" alt="" />
                            </div>
                            <div class="member-info">
                                <h4>Dr. Roy Ilisan Quiatson</h4>
                                <span>Specialty: Family Medicine</span>
                                <p>
                                    Address:Centro Pojo, Bugasong Antique
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- End Team Member -->
                </div>
            </div>
        </section>
        <!-- /Doctors Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>
                    Necessitatibus eius consequatur ex aliquid fuga eum quidem sint
                    consectetur velit
                </p>
            </div>
            <!-- End Section Title -->

            <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
                <iframe style="border: 0; width: 100%; height: 270px"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13862.72233200135!2d122.05738423761272!3d11.049454356553682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33afb7439627d461%3A0xc823c08ca5580d4d!2sCentro%20Pojo%2C%20Bugasong%2C%20Antique!5e0!3m2!1sen!2sph!4v1763384130431!5m2!1sen!2sph"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <!-- End Google Maps -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <div class="col-lg-4">
                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Location</h3>
                                <p>Centro Pojo, Bugasong Antique</p>
                            </div>
                        </div>
                        <!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Call Us</h3>
                                <p>+639150623505</p>
                            </div>
                        </div>
                        <!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Us</h3>
                                <p>quiatsonclinic@gmail.com</p>
                            </div>
                        </div>
                        <!-- End Info Item -->
                    </div>

                    <div class="col-lg-8">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name"
                                        required="" autocomplete="off" />
                                </div>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email"
                                        required="" autocomplete="off" />
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required="" />
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message"
                                        required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">
                                        Your message has been sent. Thank you!
                                    </div>

                                    <button type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Contact Form -->
                </div>
            </div>
        </section>
        <!-- /Contact Section -->
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
</body>

</html>