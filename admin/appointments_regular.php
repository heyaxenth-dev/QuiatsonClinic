<?php 
include 'authentication.php';
checkLogin(); // Call the function to check if the user is logged in
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>

<script src="assets/js/sweetalert2.all.min.js"></script>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Pending Appointments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Appointments</li>
                <li class="breadcrumb-item active">Pendings</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Regular Appointments</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <b>N</b>ame
                                    </th>
                                    <th>Address</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Mobile Number</th>
                                    <!-- <th>Status</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php 
                                $count = 0;
                                // Fetch appointments from database
                                $sql = "SELECT * FROM appointments WHERE status != 'Concluded' AND patient_type != 'senior_pwd' AND severity = 'Regular' ORDER BY created_at ASC";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {

                                $fullname = $row['firstname'] . " " . $row['middle_initial'] .". " . $row['lastname'];

                                switch ($row['status']) {
                                    case 'Pending':
                                        $status = '<span class="badge bg-warning">Pending</span>';
                                        break;
                                    case 'Approved':
                                        $status = '<span class="badge bg-success">Approved</span>';
                                        break;
                                    case 'Concluded':
                                        $status = '<span class="badge bg-primary">Concluded</span>';
                                        break;
                                    case 'Rescheduled':
                                        $status = '<span class="badge bg-info">Rescheduled</span>';
                                        break;
                                    default:
                                        $status = '<span class="badge bg-danger">Cancelled</span>';
                                        break;
                                }

                                $count++
                                
                            ?>
                                <tr>
                                    <td><span class="fw-bold text-primary"><?=$count?></span></td>
                                    </td>
                                    <td><?= $fullname ?></td>
                                    <td><?=$row['address']?></td>
                                    <td><?=$row['age']?></td>
                                    <td><?=$row['sex']?></td>
                                    <td><?=$row['civil_status']?></td>
                                    <td><?=$row['phone']?></td>
                                    <!-- <td><?=$status?></td> -->
                                    <td>
                                        <button data-id="<?=$row['id']?>"
                                            class="btn btn-sm btn-primary view-appointment"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button class="btn btn-sm btn-outline-primary btn-lab-slip"
                                            data-id="<?=$row['id']?>" data-labno="<?=$row['id']?>"
                                            data-date="<?= date('m-d-Y'); ?>"
                                            data-name="<?= htmlspecialchars($fullname); ?>"
                                            data-gender="<?= htmlspecialchars($row['sex']); ?>"
                                            data-age="<?= htmlspecialchars($row['age']); ?>"
                                            data-civil="<?= htmlspecialchars($row['civil_status']); ?>"
                                            data-address="<?= htmlspecialchars($row['address']); ?>"
                                            data-email="<?= htmlspecialchars($row['email'] ?? ''); ?>">
                                            <i class="bi bi-printer"></i> Lab Slip
                                        </button>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button data-reschedule-id="<?=$row['id']?>"
                                                        class="dropdown-item reschedule-appointment text-info">
                                                        <i class="bi bi-clock-history"></i> Reschedule
                                                    </button>
                                                </li>
                                                <!-- <li>
                                                    <button data-cancel-id="<?=$row['id']?>"
                                                        class="dropdown-item cancel-appointment text-danger">
                                                        <i class="bi bi-x-circle"></i> Cancel
                                                    </button>
                                                </li> -->
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button data-conclude-id="<?=$row['id']?>"
                                                        class="dropdown-item text-success conclude-appointment">
                                                        <i class="bi bi-check-circle"></i> Conclude Appointment
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php }
                             ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

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

</main><!-- End #main -->

<?php 
include './utils/footer.php';
?>