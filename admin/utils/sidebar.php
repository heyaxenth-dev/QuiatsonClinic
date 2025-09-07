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
            <a class="nav-link <?= ($current_page == 'home') ? '' : 'collapsed' ?> " href="<?= get_page_link('home')?>">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'appointments_urgent' || $current_page == 'appointments_regular' || $current_page == 'appointments_history') ? '' : 'collapsed' ?> "
                data-bs-toggle="collapse" href="#appointments-nav">
                <i class="bi bi-calendar4-week"></i><span>Appointments</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="appointments-nav"
                class="nav-content collapse<?= $current_page == 'appointments_urgent' || $current_page == 'appointments_regular' || $current_page == 'appointments_history' ? 'show' : ''; ?>">
                <li>
                    <a class="<?= ($current_page == 'appointments_urgent') ? 'active' : '' ?> "
                        href="<?= get_page_link('appointments_urgent')?>">
                        <i class="bi bi-circle"></i><span class="text-danger">Urgent/Priority</span>
                    </a>
                </li>
                <li>
                    <a class="<?= ($current_page == 'appointments_regular') ? 'active' : '' ?> "
                        href="<?= get_page_link('appointments_regular')?>">
                        <i class="bi bi-circle"></i><span>Regular</span>
                    </a>
                </li>

                <li>
                    <a class="<?= ($current_page == 'appointments_history') ? 'active' : '' ?> "
                        href="<?= get_page_link('appointments_history')?>">
                        <i class="bi bi-circle"></i><span>History</span>
                    </a>
                </li>

            </ul>
        </li>
        <!-- End Appointments Nav -->


        <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'laboratory_result') ? '' : 'collapsed' ?> "
                href="<?= get_page_link('laboratory_result')?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laboratory Result</span>
            </a>
        </li>
        <!-- End Laboratory Result Page Nav -->

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