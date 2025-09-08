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
        <h1>Generate Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item active">Generate Report</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg">
                <div class="card-body text-center p-4">
                    <h5 class="card-title mb-4">Generate Report</h5>

                    <form action="generated_report.php" method="GET"
                        class="d-flex flex-column align-items-center gap-3">
                        <!-- Month & Year Input -->
                        <div class="w-75">
                            <label for="reportMonth" class="form-label">Select Month & Year</label>
                            <input type="month" id="reportMonth" name="reportMonth" class="form-control" required>
                        </div>

                        <!-- Generate Button -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-4">Generate Report</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>


</main><!-- End #main -->

<?php 
include './utils/footer.php';
?>