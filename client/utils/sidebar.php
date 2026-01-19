<?php 
// Get the current script name without the file extension
$current_page = basename($_SERVER['PHP_SELF'], ".php");

// Function to check if a file exists, fallback to Page404.html if not
function get_page_link($page_name) {
    $file_path = $page_name . '.php';
    if (file_exists($file_path)) {
        return $file_path;
    } else {
        return 'pages-error-404.html';
    }
}

$today = date('Y-m-d');
$appointments = [];

if ($user_id) {
    $stmt = $conn->prepare("SELECT id, appointment_date, time_slot, status, symptom, patient_type, severity 
                            FROM appointments 
                            WHERE created_by = ? AND appointment_date = ? 
                            ORDER BY time_slot ASC");
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    $stmt->close();
}

$total_appointments_today = count($appointments);

// Optional badge count for today's appointments (set in specific pages like appointments_today.php)
$today_appointments_badge = isset($total_appointments_today) ? (int) $total_appointments_today : null;
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'homepage') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('homepage')?>" data-bs-toggle="tooltip" data-bs-placement="right"
                title="View your appointment calendar and dashboard. See all your scheduled, pending, and past appointments">
                <i class="bi bi-house"></i>
                <span>Home</span>
            </a>
        </li>
        <!-- End Home Nav -->

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between <?= ($current_page == 'appointments_today') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('appointments_today')?>" data-bs-toggle="tooltip" data-bs-placement="right"
                title="View and manage your appointments scheduled for today. You can reschedule or cancel active appointments.">
                <span>
                    <i class="bi bi-calendar-day"></i>
                    <span class="ms-1">Today's Appointments</span>
                </span>
                <?php if (!is_null($today_appointments_badge) && $today_appointments_badge >= 0): ?>
                <span class="badge bg-primary rounded-pill">
                    <?php echo $today_appointments_badge; ?>
                </span>
                <?php endif; ?>
            </a>
        </li>
        <!-- End Today's Appointments Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'appointment') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('appointment')?>" data-bs-toggle="tooltip" data-bs-placement="right"
                title="Book a new appointment. Select your preferred date and time, choose appointment type (regular or urgent), and provide reason for visit">
                <i class="bi bi-calendar4-week"></i>
                <span>Appointment Form</span>
            </a>
        </li>
        <!-- End Appointment Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'lab_results') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('lab_results')?>" data-bs-toggle="tooltip" data-bs-placement="right"
                title="View your laboratory test results uploaded by clinic staff. Download or print results as needed">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laboratory Result</span>
            </a>
        </li>
        <!-- End Laboratory Result Page Nav -->

        <li class="nav-heading">Account</li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'users-profile') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('users-profile')?>" data-bs-toggle="tooltip" data-bs-placement="right"
                title="View and update your personal information, contact details, and change your account password">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>
        <!-- End Profile Page Nav -->

    </ul>
</aside>
<!-- End Sidebar-->