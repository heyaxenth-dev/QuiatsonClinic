<?php 
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>

<main id="main" class="main">
    <div class="pagetitle mb-4">
        <h1>Laboratory Results</h1>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Concluded Appointments</h5>

                <?php 
                // Fetch concluded appointments
                $sql = "SELECT a.id, a.patient_id, a.lastname, a.firstname, a.middle_initial, a.appointment_date, a.time_slot, a.status,
                               lr.id AS lr_id, lr.file_path, lr.uploaded_at, lr.original_name
                        FROM appointments a
                        LEFT JOIN lab_results lr ON lr.appointment_id = a.id
                        WHERE LOWER(a.status) = 'concluded'
                        ORDER BY a.appointment_date DESC, a.time_slot ASC";
                $result = mysqli_query($conn, $sql);
                ?>

                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Patient ID</th>
                                <th>Date</th>
                                <th>Time Slot</th>
                                <th>Result</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0) { $i = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { 
                                    $patientName = trim($row['firstname'] . ' ' . ($row['middle_initial'] ? $row['middle_initial'] . '. ' : '') . $row['lastname']);
                                ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($patientName); ?></td>
                                <td><?= htmlspecialchars($row['patient_id']); ?></td>
                                <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                                <td><?= htmlspecialchars($row['time_slot']); ?></td>
                                <td>
                                    <?php if (!empty($row['file_path'])) { ?>
                                    <a class="btn btn-sm btn-outline-primary"
                                        href="../<?= htmlspecialchars($row['file_path']); ?>" target="_blank">View</a>
                                    <div><small class="text-muted">Uploaded:
                                            <?= htmlspecialchars($row['uploaded_at']); ?></small></div>
                                    <?php } else { ?>
                                    <span class="badge bg-secondary">No result</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#uploadModal" data-appointment-id="<?= (int)$row['id']; ?>"
                                        data-patient-id="<?= htmlspecialchars($row['patient_id']); ?>">
                                        <?= empty($row['file_path']) ? 'Upload' : 'Replace'; ?>
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">No concluded appointments found.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Laboratory Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="upload_lab_result.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="appointment_id" id="modal_appointment_id">
                        <input type="hidden" name="patient_id" id="modal_patient_id">
                        <div class="mb-3">
                            <label class="form-label">Result File (PDF/Image)</label>
                            <input type="file" name="result_file" class="form-control" accept=".pdf,.png,.jpg,.jpeg"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (optional)</label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Any notes for the patient"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var uploadModal = document.getElementById('uploadModal');
        if (!uploadModal) return;
        uploadModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var apptId = button.getAttribute('data-appointment-id');
            var patientId = button.getAttribute('data-patient-id');
            document.getElementById('modal_appointment_id').value = apptId;
            document.getElementById('modal_patient_id').value = patientId;
        });
    });
    </script>
</main>

<?php include './utils/footer.php'; ?>