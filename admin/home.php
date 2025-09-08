<?php 
include 'authentication.php';
checkLogin(); // Call the function to check if the user is logged in
include '../database/conn.php';
include './utils/header.php';
?>
<script src="assets/js/sweetalert2.all.min.js"></script>
<?php
        if (isset($_SESSION['logged'])) {
        ?>
<script type="text/javascript">
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

Toast.fire({
    background: '#53a653',
    color: '#fff',
    icon: '<?php echo $_SESSION['logged_icon']; ?>',
    title: '<?php echo $_SESSION['logged']; ?>'
});
</script>
<?php
            unset($_SESSION['logged']);
}
include './utils/sidebar.php';
include 'alert.php';

?>


<main id="main" class="main">
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Appointments Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <?php 
                            // Get the number of appointments today
                            $sql = "SELECT COUNT(*) as total_appointments FROM appointments WHERE DATE(created_at) = CURDATE()";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $total_appointments = $row['total_appointments'];
                        ?>

                            <a href="appointments_today" class="card-body">
                                <h5 class="card-title">Appointments <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-arrow-in-down"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $total_appointments == 0 ? '<small class="text-muted">No appointments today</small>' : $total_appointments; ?>
                                        </h6>
                                        <!-- <span class="text-success small pt-1 fw-bold">12%</span>
                                        <span class="text-muted small pt-2 ps-1">increase</span> -->
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- End Appointments Card -->

                    <!-- Appointment Pendings Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <?php 
                            // Get the number of concluded appointments today
                            $sql = "SELECT COUNT(*) as total_concluded_appointments FROM appointments WHERE status = 'concluded' AND DATE(created_at) = CURDATE()";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $total_concluded_appointments = $row['total_concluded_appointments'];
                            ?>

                            <div class="card-body">
                                <h5 class="card-title">
                                    Served <span>| Today</span>
                                </h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-check"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $total_concluded_appointments == 0 ? '<small class="text-muted">No concluded appointments today</small>' : $total_concluded_appointments; ?>
                                        </h6>
                                        <!-- <span class="text-danger small pt-1 fw-bold">12%</span>
                                        <span class="text-muted small pt-2 ps-1">decrease</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Appointment Pendings Card -->

                    <!-- Served Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">

                            <?php 
                            // Get the number of pending appointments today
                            $sql = "SELECT COUNT(*) as total_pending_appointments FROM appointments WHERE status = 'Pending'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $total_pending_appointments = $row['total_pending_appointments'];
                            ?>

                            <div class="card-body">
                                <h5 class="card-title">
                                    Appointment Pendings
                                </h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $total_pending_appointments == 0 ? '<small class="text-muted">No pending appointments today</small>' : $total_pending_appointments; ?>
                                        </h6>
                                        <!-- <span class="text-success small pt-1 fw-bold">8%</span>
                                        <span class="text-muted small pt-2 ps-1">increase</span> -->
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Served Card -->

                    <!-- Appointment Tracking -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">
                                    Appointment Tracking
                                </h5>

                                <?php
                                // Compute today's slot availability
                                $selected_date = date('M d,Y');

                                $time_slots = [
                                    '8:30 AM - 9:30 AM',
                                    '9:30 AM - 10:30 AM',
                                    '10:30 AM - 11:30 AM',
                                    '11:30 AM - 12:30 PM',
                                    '1:30 PM - 2:30 PM',
                                    '2:30 PM - 3:30 PM',
                                    '3:30 PM - 4:30 PM',
                                    '4:30 PM - 5:30 PM'
                                ];

                                $booked_counts = [];
                                if ($stmt = mysqli_prepare($conn, "SELECT time_slot, COUNT(*) as booked_count FROM appointments WHERE appointment_date = ? GROUP BY time_slot")) {
                                    mysqli_stmt_bind_param($stmt, "s", $selected_date);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $booked_counts[$row['time_slot']] = (int)$row['booked_count'];
                                    }
                                    mysqli_stmt_close($stmt);
                                }

                                $available_rows = [];
                                $total_available = 0;
                                $total_booked = 0;
                                foreach ($time_slots as $slot) {
                                    $booked = isset($booked_counts[$slot]) ? (int)$booked_counts[$slot] : 0;
                                    $available = 10 - $booked; // capacity per slot
                                    if ($available > 0) {
                                        $available_rows[] = [
                                            'time_slot' => $slot,
                                            'available' => $available,
                                            'booked' => $booked
                                        ];
                                        $total_available += $available;
                                        $total_booked += $booked;
                                    }
                                }
                                ?>

                                <div class="mb-2">
                                    <small class="text-muted">Availability for
                                        <span
                                            class="fw-bold text-primary"><?= htmlspecialchars($selected_date); ?></span></small>
                                </div>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Time Slot</th>
                                            <th scope="col">Available</th>
                                            <th scope="col">Booked</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (count($available_rows) > 0) { ?>
                                        <?php foreach ($available_rows as $row) {
                                            $statusClass = $row['available'] <= 3 ? 'text-warning' : 'text-success';
                                            $statusText = $row['available'] <= 3 ? 'Limited' : 'Available';
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['time_slot']); ?></td>
                                            <td><?= (int)$row['available']; ?></td>
                                            <td><?= (int)$row['booked']; ?></td>
                                            <td class="<?= $statusClass; ?>"><?= $statusText; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">All slots are fully booked for today.
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <div class="alert alert-info mt-2">
                                    <strong>Summary:</strong> <?= count($available_rows); ?> time slots available with
                                    <?= (int)$total_available; ?> total openings. <?= (int)$total_booked; ?>
                                    appointments already booked.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Appointment Tracking -->

                    <!-- Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">Reports <span>/Today</span></h5>

                                <!-- Line Chart -->
                                <div id="reportsChart"></div>

                                <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new ApexCharts(
                                        document.querySelector("#reportsChart"), {
                                            series: [{
                                                    name: "Sales",
                                                    data: [31, 40, 28, 51, 42, 82, 56],
                                                },
                                                {
                                                    name: "Revenue",
                                                    data: [11, 32, 45, 32, 34, 52, 41],
                                                },
                                                {
                                                    name: "Customers",
                                                    data: [15, 11, 32, 18, 9, 24, 11],
                                                },
                                            ],
                                            chart: {
                                                height: 350,
                                                type: "area",
                                                toolbar: {
                                                    show: false,
                                                },
                                            },
                                            markers: {
                                                size: 4,
                                            },
                                            colors: ["#4154f1", "#2eca6a", "#ff771d"],
                                            fill: {
                                                type: "gradient",
                                                gradient: {
                                                    shadeIntensity: 1,
                                                    opacityFrom: 0.3,
                                                    opacityTo: 0.4,
                                                    stops: [0, 90, 100],
                                                },
                                            },
                                            dataLabels: {
                                                enabled: false,
                                            },
                                            stroke: {
                                                curve: "smooth",
                                                width: 2,
                                            },
                                            xaxis: {
                                                type: "datetime",
                                                categories: [
                                                    "2018-09-19T00:00:00.000Z",
                                                    "2018-09-19T01:30:00.000Z",
                                                    "2018-09-19T02:30:00.000Z",
                                                    "2018-09-19T03:30:00.000Z",
                                                    "2018-09-19T04:30:00.000Z",
                                                    "2018-09-19T05:30:00.000Z",
                                                    "2018-09-19T06:30:00.000Z",
                                                ],
                                            },
                                            tooltip: {
                                                x: {
                                                    format: "dd/MM/yy HH:mm",
                                                },
                                            },
                                        }
                                    ).render();
                                });
                                </script>
                                <!-- End Line Chart -->
                            </div>
                        </div>
                    </div>
                    <!-- End Reports -->

                </div>
            </div>
            <!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity <span>| Today</span></h5>

                        <div class="activity">
                            <div class="activity-item d-flex">
                                <div class="activite-label">32 min</div>
                                <i class="bi bi-circle-fill activity-badge text-success align-self-start"></i>
                                <div class="activity-content">
                                    Quia quae rerum
                                    <a href="#" class="fw-bold text-dark">explicabo officiis</a>
                                    beatae
                                </div>
                            </div>
                            <!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">56 min</div>
                                <i class="bi bi-circle-fill activity-badge text-danger align-self-start"></i>
                                <div class="activity-content">
                                    Voluptatem blanditiis blanditiis eveniet
                                </div>
                            </div>
                            <!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 hrs</div>
                                <i class="bi bi-circle-fill activity-badge text-primary align-self-start"></i>
                                <div class="activity-content">
                                    Voluptates corrupti molestias voluptatem
                                </div>
                            </div>
                            <!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">1 day</div>
                                <i class="bi bi-circle-fill activity-badge text-info align-self-start"></i>
                                <div class="activity-content">
                                    Tempore autem saepe
                                    <a href="#" class="fw-bold text-dark">occaecati voluptatem</a>
                                    tempore
                                </div>
                            </div>
                            <!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 days</div>
                                <i class="bi bi-circle-fill activity-badge text-warning align-self-start"></i>
                                <div class="activity-content">
                                    Est sit eum reiciendis exercitationem
                                </div>
                            </div>
                            <!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">4 weeks</div>
                                <i class="bi bi-circle-fill activity-badge text-muted align-self-start"></i>
                                <div class="activity-content">
                                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                                </div>
                            </div>
                            <!-- End activity item-->
                        </div>
                    </div>
                </div>
                <!-- End Recent Activity -->
            </div>
            <!-- End Right side columns -->
        </div>
    </section>
</main>
<!-- End #main -->

<?php 
include './utils/footer.php';
?>