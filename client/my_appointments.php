<?php
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';

$user_id = (int) $_SESSION['user_id'];

$appointments = [];
$stmt = $conn->prepare("SELECT id, appointment_date, time_slot, status, symptom, patient_type, severity, uploaded_id 
    FROM appointments 
    WHERE created_by = ? AND status NOT IN ('Cancelled', 'Concluded') 
    ORDER BY appointment_date ASC, time_slot ASC");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $appointments[] = $row;
    }
    $stmt->close();
}
?>

<script src="assets/js/sweetalert2.all.min.js"></script>

<main id="main" class="main">
    <div class="pagetitle mb-4">
        <h1>My Appointments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="homepage.php">Home</a></li>
                <li class="breadcrumb-item active">My Appointments</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage your bookings</h5>
                        <p class="text-muted small mb-3">
                            Reschedule or cancel visits that are still active. For Senior / PWD bookings you can replace your ID attachment.
                            Use <a href="appointment.php">Appointment Form</a> to book a new visit.
                        </p>

                        <?php if (empty($appointments)) : ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            You have no active appointments to manage.
                            <a href="appointment.php" class="alert-link">Book an appointment</a>.
                        </div>
                        <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Reason / symptoms</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>ID file</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($appointments as $appt) : ?>
                                    <?php
                                        $ad = $appt['appointment_date'] ?? '';
                                        $dateIso = '';
                                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $ad)) {
                                            $dateIso = $ad;
                                            $dateLabel = date('F j, Y', strtotime($ad));
                                        } else {
                                            $ts = strtotime($ad);
                                            $dateLabel = $ad ?: '—';
                                            if ($ts) {
                                                $dateIso = date('Y-m-d', $ts);
                                                $dateLabel = date('F j, Y', $ts);
                                            }
                                        }
                                        $ptype = strtolower((string) ($appt['patient_type'] ?? ''));
                                        $canUpload = in_array($ptype, ['senior', 'senior_pwd'], true);
                                        $uploadPath = trim((string) ($appt['uploaded_id'] ?? ''));
                                        $status = $appt['status'];
                                        $badgeClass = 'bg-secondary';
                                        if ($status === 'Pending') {
                                            $badgeClass = 'bg-warning text-dark';
                                        } elseif ($status === 'Approved' || $status === 'Success') {
                                            $badgeClass = 'bg-success';
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($dateLabel) ?></td>
                                        <td><?= htmlspecialchars($appt['time_slot'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($appt['symptom'] ?: '—') ?></td>
                                        <td><?= htmlspecialchars(ucfirst(str_replace('_', '/', $appt['patient_type'] ?? ''))) ?></td>
                                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                                        <td>
                                            <?php if ($canUpload) : ?>
                                                <?php if ($uploadPath !== '') : ?>
                                                <a href="<?= htmlspecialchars($uploadPath) ?>" target="_blank" rel="noopener" class="small">View current</a>
                                                <?php else : ?>
                                                <span class="text-muted small">None</span>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group flex-wrap justify-content-center" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-reschedule"
                                                    data-id="<?= (int) $appt['id'] ?>"
                                                    data-date="<?= htmlspecialchars($dateIso) ?>"
                                                    data-time-slot="<?= htmlspecialchars($appt['time_slot'] ?? '') ?>">
                                                    <i class="bi bi-clock-history"></i> Reschedule
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-cancel"
                                                    data-id="<?= (int) $appt['id'] ?>">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                                <?php if ($canUpload) : ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-upload"
                                                    data-id="<?= (int) $appt['id'] ?>">
                                                    <i class="bi bi-upload"></i> <?= $uploadPath !== '' ? 'Replace ID' : 'Upload ID' ?>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reschedule Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rescheduleForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rescheduleModalLabel">
                            <i class="bi bi-clock-history me-1"></i>Reschedule appointment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="resched_appointment_id" name="appointment_id">
                        <div class="mb-3">
                            <label for="resched_date" class="form-label">New date</label>
                            <input type="date" class="form-control" id="resched_date" name="date" required>
                            <small class="text-muted">Only available dates are offered in the next step.</small>
                        </div>
                        <div class="mb-3">
                            <label for="resched_time_slot" class="form-label">New time slot</label>
                            <select class="form-select" id="resched_time_slot" name="time_slot" required>
                                <option value="">Select a date first</option>
                            </select>
                            <div id="resched_slot_info" class="mt-2 small text-muted"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload / replace ID modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="uploadAttachmentForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">
                            <i class="bi bi-upload me-1"></i>Update ID attachment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="upload_appointment_id" name="appointment_id">
                        <p class="small text-muted">JPG, PNG, or PDF. Maximum 5 MB.</p>
                        <input class="form-control" type="file" id="upload_id" name="upload_id" accept="image/*,.pdf" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rescheduleModalEl = document.getElementById('rescheduleModal');
    const rescheduleModal = rescheduleModalEl ? new bootstrap.Modal(rescheduleModalEl) : null;
    const uploadModalEl = document.getElementById('uploadModal');
    const uploadModal = uploadModalEl ? new bootstrap.Modal(uploadModalEl) : null;

    const reschedDateInput = document.getElementById('resched_date');
    const reschedTimeSelect = document.getElementById('resched_time_slot');
    const reschedSlotInfo = document.getElementById('resched_slot_info');
    const reschedAppointmentIdInput = document.getElementById('resched_appointment_id');

    const uploadForm = document.getElementById('uploadAttachmentForm');
    const uploadApptInput = document.getElementById('upload_appointment_id');
    const uploadFileInput = document.getElementById('upload_id');

    if (reschedDateInput) {
        reschedDateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
    }

    function loadAvailableSlots(selectedDate, currentTimeSlot) {
        if (!selectedDate || !reschedTimeSelect) return;
        reschedTimeSelect.innerHTML = '<option value="">Loading…</option>';
        reschedSlotInfo.textContent = '';

        fetch('get_available_slots.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ date: selectedDate })
        })
        .then(res => res.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) {
                reschedTimeSelect.innerHTML = '<option value="">No slots for this date</option>';
                reschedSlotInfo.innerHTML = '<span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Try another date.</span>';
                return;
            }
            let html = '<option value="">Select a time slot</option>';
            let totalAvail = 0;
            data.forEach(slot => {
                const rem = slot.available_count ?? 0;
                totalAvail += rem;
                const sel = (currentTimeSlot && currentTimeSlot === slot.time_slot) ? 'selected' : '';
                html += `<option value="${slot.time_slot}" ${sel}>${slot.time_slot} (Remaining: ${rem})</option>`;
            });
            reschedTimeSelect.innerHTML = html;
            reschedSlotInfo.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> Openings today for that date: ${totalAvail}</span>`;
        })
        .catch(() => {
            reschedTimeSelect.innerHTML = '<option value="">Error loading slots</option>';
            reschedSlotInfo.innerHTML = '<span class="text-danger">Could not load slots.</span>';
        });
    }

    document.querySelectorAll('.btn-reschedule').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            let date = this.getAttribute('data-date');
            const timeSlot = this.getAttribute('data-time-slot');
            if (reschedAppointmentIdInput) reschedAppointmentIdInput.value = id;
            if (!date) {
                const today = new Date().toISOString().split('T')[0];
                date = today;
            }
            if (reschedDateInput) reschedDateInput.value = date;
            if (rescheduleModal) rescheduleModal.show();
            if (date) loadAvailableSlots(date, timeSlot);
        });
    });

    if (reschedDateInput) {
        reschedDateInput.addEventListener('change', function() {
            if (this.value) loadAvailableSlots(this.value, '');
        });
    }

    const rescheduleForm = document.getElementById('rescheduleForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = reschedAppointmentIdInput.value;
            const newDate = reschedDateInput.value;
            const newTimeSlot = reschedTimeSelect.value;
            if (!id || !newDate || !newTimeSlot) {
                Swal.fire({ icon: 'warning', title: 'Incomplete', text: 'Choose a date and time slot.' });
                return;
            }
            fetch('reschedule_appointment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ appointment_id: id, date: newDate, time_slot: newTimeSlot })
            })
            .then(async res => {
                const text = await res.text();
                try { return JSON.parse(text); } catch (err) {
                    console.error(text);
                    throw new Error('Invalid server response.');
                }
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Updated', text: data.message || 'Rescheduled.' })
                        .then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Unable to reschedule', text: data.message || 'Try again.' });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Request failed.' });
            });
        });
    }

    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) return;
            Swal.fire({
                icon: 'warning',
                title: 'Cancel appointment?',
                text: 'This cannot be undone from here.',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'Keep it',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (!result.isConfirmed) return;
                fetch('cancel_appointment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ appointment_id: id })
                })
                .then(async res => {
                    const text = await res.text();
                    try { return JSON.parse(text); } catch (e) { throw new Error(text.slice(0, 120)); }
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({ icon: 'success', title: 'Cancelled', text: data.message || 'Done.' })
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Unable to cancel', text: data.message || 'Try again.' });
                    }
                })
                .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message }));
            });
        });
    });

    document.querySelectorAll('.btn-upload').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (uploadApptInput) uploadApptInput.value = id;
            if (uploadFileInput) uploadFileInput.value = '';
            if (uploadModal) uploadModal.show();
        });
    });

    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = uploadApptInput.value;
            if (!id || !uploadFileInput.files || !uploadFileInput.files.length) {
                Swal.fire({ icon: 'warning', title: 'File required', text: 'Choose a file to upload.' });
                return;
            }
            const fd = new FormData();
            fd.append('appointment_id', id);
            fd.append('upload_id', uploadFileInput.files[0]);

            fetch('upload_appointment_attachment.php', { method: 'POST', body: fd })
                .then(async res => {
                    const text = await res.text();
                    try { return JSON.parse(text); } catch (e) { throw new Error(text.slice(0, 120)); }
                })
                .then(data => {
                    if (data.success) {
                        if (uploadModal) uploadModal.hide();
                        Swal.fire({ icon: 'success', title: 'Uploaded', text: data.message || 'Saved.' })
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Upload failed', text: data.message || 'Try again.' });
                    }
                })
                .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: err.message }));
        });
    }
});
</script>

<?php include './utils/footer.php'; ?>
