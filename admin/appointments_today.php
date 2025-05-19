<?php 
include 'authentication.php';
checkLogin(); // Call the function to check if the user is logged in
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Appointments Today</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Appointments</li>
                <li class="breadcrumb-item active">Today</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Urgent Appointments</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Severity</th>
                                    <th>
                                        <b>N</b>ame
                                    </th>
                                    <th>Address</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Mobile Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php 
                                // Fetch appointments from database
                                $sql = "SELECT * FROM appointments WHERE DATE(created_at) = CURDATE() AND status != 'Concluded' AND status != 'Pending' AND severity = 'Urgent'";
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
                                
                            ?>
                                <tr>
                                    <td><span class="fw-bold text-danger"><?=$row['severity']?></span></td>
                                    </td>
                                    <td><?= $fullname ?></td>
                                    <td><?=$row['address']?></td>
                                    <td><?=$row['age']?></td>
                                    <td><?=$row['sex']?></td>
                                    <td><?=$row['civil_status']?></td>
                                    <td><?=$row['phone']?></td>
                                    <td><?=$status?></td>
                                    <td>
                                        <button data-id="<?=$row['id']?>"
                                            class="btn btn-sm btn-primary view-appointment"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button data-conclude-id="<?=$row['id']?>"
                                            class="btn btn-sm btn-success conclude-appointment"><i
                                                class="bi bi-check-circle"></i>
                                            Conclude Appointment</button>
                                    </td>
                                </tr>
                                <?php }
                             ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Regular Appointments</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Severity</th>
                                    <th>
                                        <b>N</b>ame
                                    </th>
                                    <th>Address</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Mobile Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php 
                                // Fetch appointments from database
                                $sql = "SELECT * FROM appointments WHERE DATE(created_at) = CURDATE() AND status != 'Concluded' AND status != 'Pending' AND severity = 'Regular'";
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
                                
                            ?>
                                <tr>
                                    <td><span class="fw-bold text-primary"><?=$row['severity']?></span></td>
                                    </td>
                                    <td><?= $fullname ?></td>
                                    <td><?=$row['address']?></td>
                                    <td><?=$row['age']?></td>
                                    <td><?=$row['sex']?></td>
                                    <td><?=$row['civil_status']?></td>
                                    <td><?=$row['phone']?></td>
                                    <td><?=$status?></td>
                                    <td>
                                        <button data-id="<?=$row['id']?>"
                                            class="btn btn-sm btn-primary view-appointment"><i class="bi bi-eye"></i>
                                            View</button>
                                        <button data-conclude-id="<?=$row['id']?>"
                                            class="btn btn-sm btn-success conclude-appointment"><i
                                                class="bi bi-check-circle"></i>
                                            Conclude Appointment</button>
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
                ?>
                <script src="assets/js/viewModal.js"></script>
                <?php include './includes/conclusionModal.php'; ?>
                <script src="assets/js/conclusionModal.js"></script>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php 
include './utils/footer.php';
?>