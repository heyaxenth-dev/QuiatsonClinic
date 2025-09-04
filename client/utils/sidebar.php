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
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'homepage') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('homepage')?>">
                <i class="bi bi-house"></i>
                <span>Home</span>
            </a>
        </li>
        <!-- End Home Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'appointment') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('appointment')?>">
                <i class="bi bi-calendar4-week"></i>
                <span>Appointment Form</span>
            </a>
        </li>
        <!-- End Appointment Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'laboratory_slip') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('laboratory_slip')?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laboratory Slip</span>
            </a>
        </li>
        <!-- End Laboratory Slip Page Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'reports') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('reports')?>">
                <i class="bi bi-bar-chart-line"></i>
                <span>Reports</span>
            </a>
        </li>
        <!-- End Reports Page Nav -->

        <li class="nav-heading">Account</li>

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'users-profile') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('users-profile')?>">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li>
        <!-- End Profile Page Nav -->

    </ul>
</aside>
<!-- End Sidebar-->