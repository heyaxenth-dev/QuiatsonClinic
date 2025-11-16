<?php 
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';

// Determine current client's patient identifiers from their appointments
$client_id = $_SESSION['user_id'];
$client_email = $_SESSION['email'];

// We will show all lab results tied to appointments that belong to this client via email or name match.
// Prefer matching via email if stored; fallback to name/phone if needed. Here, appointments table doesn't have client_id, so we match by email and by created records owned by this email if available.

$firstname_safe = '';
$lastname_safe = '';
$mobile_safe = '';
if (isset($firstname)) { $firstname_safe = mysqli_real_escape_string($conn, $firstname); }
if (isset($lastname)) { $lastname_safe = mysqli_real_escape_string($conn, $lastname); }
if (isset($mobile_no)) { $mobile_safe = mysqli_real_escape_string($conn, $mobile_no); }

// Filter results to those that match client's name and/or phone from their profile
$q = "SELECT lr.id, lr.file_path, lr.original_name, lr.uploaded_at, lr.appointment_id, 
             a.appointment_date, a.time_slot, a.patient_id, a.lastname, a.firstname
      FROM lab_results lr
      INNER JOIN appointments a ON a.id = lr.appointment_id
      WHERE (
        (a.firstname = '$firstname_safe' AND a.lastname = '$lastname_safe')
        OR (a.phone = '$mobile_safe')
      )
      ORDER BY lr.uploaded_at DESC";
$res = mysqli_query($conn, $q);
?>

<main id="main" class="main">
    <div class="pagetitle mb-4">
        <h1>My Laboratory Results</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Available Results</h5>

                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Appointment</th>
                                <th>Patient ID</th>
                                <th>Uploaded</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($res && mysqli_num_rows($res) > 0) { $i=1; ?>
                            <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row['appointment_date'] . ' ' . $row['time_slot']); ?></td>
                                <td><?= htmlspecialchars($row['patient_id']); ?></td>
                                <td><?= htmlspecialchars($row['uploaded_at']); ?></td>
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <a class="btn btn-sm btn-primary"
                                            href="../<?= htmlspecialchars($row['file_path']); ?>"
                                            target="_blank">
                                            <i class="bi bi-eye"></i> View/Download
                                        </a>
                                        <a class="btn btn-sm btn-success"
                                            href="appointment.php?reappointment=1&appointment_id=<?= htmlspecialchars($row['appointment_id']); ?>&lab_result_id=<?= htmlspecialchars($row['id']); ?>"
                                            title="Request appointment for reading this lab result">
                                            <i class="bi bi-calendar-plus"></i> Request Re-appointment
                                        </a>
                                    </div>
                                    <?php if (!empty($row['original_name'])) { ?>
                                    <div class="mt-2"><small class="text-muted">Original:
                                            <?= htmlspecialchars($row['original_name']); ?></small></div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center">No lab results available yet.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include './utils/footer.php'; ?>