<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />

    <title>Home | Admin -Quiatson Clinic</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Favicons -->
    <link href="assets/img/favicon.ico" rel="icon" />
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet" />
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet" />
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php
    // Get session user details
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $query = "SELECT * FROM `admin_staff` WHERE `id` = '$id' AND `username` = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $role = $row['role'];
            $mobile_no = $row['mobile_no'];
            $email = $row['email'];
            $user = $row['username'];
            $dc = date("M d, Y", strtotime($row['date_created']));

        }
    }

    // Count Urgent/Priority for today only (same criteria as appointments_urgent.php)
    $urgentCountQuery = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = CURDATE() AND patient_type = 'senior_pwd' AND (severity = 'Regular' OR severity = 'Urgent') AND status NOT IN ('Concluded', 'Cancelled')";
    $urgentResult = mysqli_query($conn, $urgentCountQuery);
    $urgentCount = mysqli_fetch_assoc($urgentResult)['count'];

    // Count Regular for today only (same criteria as appointments_regular.php)
    $regularCountQuery = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = CURDATE() AND status NOT IN ('Concluded', 'Cancelled') AND patient_type != 'senior_pwd' AND severity = 'Regular'";
    $regularResult = mysqli_query($conn, $regularCountQuery);
    $regularCount = mysqli_fetch_assoc($regularResult)['count'];

    // Today's active appointments (same criteria as appointments_today.php)
    $todayCountQuery = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = CURDATE() AND status NOT IN ('Concluded', 'Cancelled')";
    $todayCountResult = mysqli_query($conn, $todayCountQuery);
    $todayCount = $todayCountResult ? (int) mysqli_fetch_assoc($todayCountResult)['count'] : 0;
    ?>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <a href="home" class="logo d-flex align-items-center">
                <img src="assets/img/favicon-32x32.png" alt="" />
                <span class="d-none d-lg-block">Quiatson Clinic</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        <!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/user-profile.png" alt="Profile" class="rounded-circle" />
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?=$user?></span> </a>
                    <!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?=$user?></h6>
                            <span><?=$role?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="users-profile">
                                <i class="bi bi-gear"></i>
                                <span>Account Settings</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="../logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                    <!-- End Profile Dropdown Items -->
                </li>
                <!-- End Profile Nav -->
            </ul>
        </nav>
        <!-- End Icons Navigation -->
    </header>
    <!-- End Header -->