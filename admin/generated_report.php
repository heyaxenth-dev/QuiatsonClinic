<?php 
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';

// Get month & year from GET
$filterMonthYear = isset($_GET['reportMonth']) ? $_GET['reportMonth'] : '';
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Report for <?= date("F Y", strtotime($filterMonthYear)) ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item"><a href="reports">Reports</a></li>
                <li class="breadcrumb-item active">Report for <?= date("F Y", strtotime($filterMonthYear)) ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body mt-3">
                        <!-- Top bar -->
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title mb-0">
                                Showing results for:
                                <span class="text-primary">
                                    <?= !empty($filterMonthYear) ? date("F Y", strtotime($filterMonthYear)) : "All Records" ?>
                                </span>
                            </h5>
                            <div>
                                <a href="reports.php" class="btn btn-secondary">Back</a>
                                <button type="button" class="btn btn-success" onclick="printReport()">Print</button>
                                <a href="export_pdf.php?reportMonth=<?= urlencode($filterMonthYear) ?>"
                                    class="btn btn-danger">Export PDF</a>
                            </div>
                        </div>


                        <!-- Report Table -->
                        <div id="reportSection">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><b>N</b>ame</th>
                                        <th>Address</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Civil Status</th>
                                        <th>Mobile Number</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                                    // Build SQL query
                                    $sql = "SELECT * FROM appointments WHERE status != 'Approved' AND status != 'Pending'";
                                    
                                    if (!empty($filterMonthYear)) {
                                        $year = date('Y', strtotime($filterMonthYear));
                                        $month = date('m', strtotime($filterMonthYear));
                                        $sql .= " AND YEAR(created_at) = '$year' AND MONTH(created_at) = '$month'";
                                    }

                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $fullname = $row['firstname'] . " " . $row['middle_initial'] . ". " . $row['lastname'];
                                    ?>
                                    <tr>
                                        <td><?= $fullname ?></td>
                                        <td><?= $row['address'] ?></td>
                                        <td><?= $row['age'] ?></td>
                                        <td><?= $row['sex'] ?></td>
                                        <td><?= $row['civil_status'] ?></td>
                                        <td><?= $row['phone'] ?></td>
                                        <td class="no-print">
                                            <button data-id="<?= $row['id'] ?>"
                                                class="btn btn-sm btn-primary view-appointment">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No records found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- End reportSection -->

                    </div>
                </div>

                <?php 
                include './includes/viewModal.php';
                include './includes/labSlipModal.php';
                ?>
                <script src="assets/js/viewModal.js"></script>
                <script src="assets/js/labSlip.js"></script>

                <!-- Print Script -->
                <style>
                @media print {

                    .no-print,
                    .no-print * {
                        display: none !important;
                    }
                }
                </style>
                <script>
                function printReport() {
                    window.print();
                }
                </script>

            </div>
        </div>
    </section>

</main>

<?php include './utils/footer.php'; ?>