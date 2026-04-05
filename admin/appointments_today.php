<?php
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';

$today = date('Y-m-d');
$todayLabel = date('F j, Y', strtotime($today));
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Today's Appointments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Appointments</li>
                <li class="breadcrumb-item active">Today</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All appointments for <?= htmlspecialchars($todayLabel) ?></h5>
                        <p class="text-muted small">Active appointments only (excludes concluded and cancelled). Same actions as Urgent/Priority list.</p>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Time Slot</th>
                                    <th><b>N</b>ame</th>
                                    <th>Address</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Mobile Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 0;
                                $sql = "SELECT * FROM appointments 
                                        WHERE appointment_date = CURDATE() 
                                        AND status NOT IN ('Concluded', 'Cancelled') 
                                        ORDER BY time_slot ASC, created_at ASC";
                                $result = mysqli_query($conn, $sql);
                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $fullname = $row['firstname'] . ' ' . $row['middle_initial'] . '. ' . $row['lastname'];
                                        $labDate = !empty($row['appointment_date']) ? date('m-d-Y', strtotime($row['appointment_date'])) : date('m-d-Y');
                                        $count++;
                                ?>
                                <tr>
                                    <td><span class="fw-bold"><?= $count ?></span></td>
                                    <td><?= htmlspecialchars($row['time_slot'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($fullname) ?></td>
                                    <td><?= htmlspecialchars($row['address']) ?></td>
                                    <td><?= htmlspecialchars((string) $row['age']) ?></td>
                                    <td><?= htmlspecialchars($row['sex']) ?></td>
                                    <td><?= htmlspecialchars($row['civil_status']) ?></td>
                                    <td><?= htmlspecialchars($row['phone']) ?></td>
                                    <td>
                                        <button data-id="<?= (int) $row['id'] ?>"
                                            class="btn btn-sm btn-primary view-appointment"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button class="btn btn-sm btn-outline-primary btn-lab-slip"
                                            data-id="<?= (int) $row['id'] ?>" data-labno="<?= (int) $row['id'] ?>"
                                            data-date="<?= htmlspecialchars($labDate) ?>"
                                            data-name="<?= htmlspecialchars($fullname) ?>"
                                            data-gender="<?= htmlspecialchars($row['sex']) ?>"
                                            data-age="<?= htmlspecialchars((string) $row['age']) ?>"
                                            data-civil="<?= htmlspecialchars($row['civil_status']) ?>"
                                            data-address="<?= htmlspecialchars($row['address']) ?>"
                                            data-email="<?= htmlspecialchars($row['email'] ?? '') ?>">
                                            <i class="bi bi-printer"></i> Lab Slip
                                        </button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button data-reschedule-id="<?= (int) $row['id'] ?>"
                                                        class="dropdown-item reschedule-appointment text-info">
                                                        <i class="bi bi-clock-history"></i> Reschedule
                                                    </button>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button data-conclude-id="<?= (int) $row['id'] ?>"
                                                        class="dropdown-item text-success conclude-appointment">
                                                        <i class="bi bi-check-circle"></i> Conclude Appointment
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>

                <?php
                include './includes/viewModal.php';
                include './includes/labSlipModal.php';
                ?>
                <script src="assets/js/sweetalert2.all.min.js"></script>
                <script src="assets/js/viewModal.js"></script>
                <script src="assets/js/labSlip.js"></script>
                <?php include './includes/conclusionModal.php'; ?>
                <script src="assets/js/conclusionModal.js"></script>
                <script src="assets/js/appointmentActions.js"></script>

            </div>
        </div>
    </section>

</main>

<?php
include './utils/footer.php';
