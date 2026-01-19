<?php
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>

<script src="assets/js/sweetalert2.all.min.js"></script>

<main id="main" class="main">
    <div class="pagetitle mb-4">
        <h1>Today's Appointments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="homepage.php">Home</a></li>
                <li class="breadcrumb-item active">Today's Appointments</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            Appointments for
                            <?php echo htmlspecialchars(date('F j, Y', strtotime($today))); ?>
                        </h5>

                        <?php if (empty($appointments)) : ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            You have no appointments scheduled for today.
                            <a href="appointment.php" class="alert-link">Book a new appointment</a>.
                        </div>
                        <?php else : ?>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Time Slot</th>
                                        <th>Symptom / Purpose</th>
                                        <th>Patient Type</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($appointments as $appt) : ?>
                                    <tr data-appointment-id="<?php echo (int)$appt['id']; ?>">
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($appt['time_slot']); ?></td>
                                        <td><?php echo htmlspecialchars($appt['symptom'] ?: '—'); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($appt['patient_type'])); ?></td>
                                        <td>
                                            <?php if (strtolower($appt['severity']) === 'urgent') : ?>
                                            <span class="badge bg-danger">Urgent</span>
                                            <?php else : ?>
                                            <span class="badge bg-primary">Regular</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                    $status = $appt['status'];
                                                    $badgeClass = 'bg-secondary';
                                                    if ($status === 'Pending') $badgeClass = 'bg-warning text-dark';
                                                    elseif ($status === 'Approved' || $status === 'Success') $badgeClass = 'bg-success';
                                                    elseif ($status === 'Cancelled' || $status === 'Error') $badgeClass = 'bg-danger';
                                                    ?>
                                            <span class="badge <?php echo $badgeClass; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!in_array($appt['status'], ['Cancelled', 'Concluded'], true)) : ?>
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary btn-reschedule"
                                                    data-id="<?php echo (int)$appt['id']; ?>"
                                                    data-date="<?php echo htmlspecialchars($appt['appointment_date']); ?>"
                                                    data-time-slot="<?php echo htmlspecialchars($appt['time_slot']); ?>">
                                                    <i class="bi bi-clock-history"></i> Reschedule
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-cancel"
                                                    data-id="<?php echo (int)$appt['id']; ?>">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                            </div>
                                            <?php else : ?>
                                            <small class="text-muted">No actions available</small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            You can only reschedule or cancel appointments that are not yet concluded or cancelled.
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reschedule Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rescheduleForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rescheduleModalLabel">
                            <i class="bi bi-clock-history me-1"></i>Reschedule Appointment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="resched_appointment_id" name="appointment_id">
                        <div class="mb-3">
                            <label for="resched_date" class="form-label">New Date</label>
                            <input type="date" class="form-control" id="resched_date" name="date" required>
                            <small class="text-muted">Only available dates will be allowed.</small>
                        </div>
                        <div class="mb-3">
                            <label for="resched_time_slot" class="form-label">New Time Slot</label>
                            <select class="form-select" id="resched_time_slot" name="time_slot" required>
                                <option value="">Select a date first</option>
                            </select>
                            <div id="resched_slot_info" class="mt-2 small text-muted"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle me-1"></i>Save Changes
                        </button>
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
    const reschedDateInput = document.getElementById('resched_date');
    const reschedTimeSelect = document.getElementById('resched_time_slot');
    const reschedSlotInfo = document.getElementById('resched_slot_info');
    const reschedAppointmentIdInput = document.getElementById('resched_appointment_id');

    // Limit reschedule date to today or later
    if (reschedDateInput) {
        const today = new Date().toISOString().split('T')[0];
        reschedDateInput.setAttribute('min', today);
    }

    function loadAvailableSlots(selectedDate, currentTimeSlot) {
        if (!selectedDate || !reschedTimeSelect) return;

        reschedTimeSelect.innerHTML = '<option value="">Loading available slots...</option>';
        reschedSlotInfo.textContent = '';

        fetch('get_available_slots.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    date: selectedDate
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    reschedTimeSelect.innerHTML =
                        '<option value="">No available slots for this date</option>';
                    reschedSlotInfo.innerHTML =
                        '<span class="text-warning"><i class="bi bi-exclamation-triangle"></i> This date is unavailable or fully booked.</span>';
                    return;
                }

                let optionsHtml = '<option value="">Select a time slot</option>';
                let totalAvailable = 0;

                data.forEach(slot => {
                    const remaining = slot.available_count ?? 0;
                    totalAvailable += remaining;
                    const label = `${slot.time_slot} (Remaining: ${remaining})`;
                    const selected = (currentTimeSlot && currentTimeSlot === slot.time_slot) ?
                        'selected' : '';
                    optionsHtml +=
                        `<option value="${slot.time_slot}" ${selected}>${label}</option>`;
                });

                reschedTimeSelect.innerHTML = optionsHtml;
                reschedSlotInfo.innerHTML =
                    `<span class="text-success"><i class="bi bi-check-circle"></i> Total available slots for this date: ${totalAvailable}</span>`;
            })
            .catch(() => {
                reschedTimeSelect.innerHTML = '<option value="">Error loading slots</option>';
                reschedSlotInfo.innerHTML =
                    '<span class="text-danger">Unable to load available slots. Please try again.</span>';
            });
    }

    // Handle reschedule button click
    document.querySelectorAll('.btn-reschedule').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const date = this.getAttribute('data-date');
            const timeSlot = this.getAttribute('data-time-slot');

            if (reschedAppointmentIdInput) reschedAppointmentIdInput.value = id;
            if (reschedDateInput) reschedDateInput.value = date;

            if (rescheduleModal) {
                rescheduleModal.show();
            }

            if (date) {
                loadAvailableSlots(date, timeSlot);
            }
        });
    });

    // Handle date change inside modal
    if (reschedDateInput) {
        reschedDateInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                loadAvailableSlots(selectedDate, '');
            }
        });
    }

    // Handle reschedule form submit
    const rescheduleForm = document.getElementById('rescheduleForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const id = reschedAppointmentIdInput.value;
            const newDate = reschedDateInput.value;
            const newTimeSlot = reschedTimeSelect.value;

            if (!id || !newDate || !newTimeSlot) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Details',
                    text: 'Please select both a new date and time slot.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            fetch('reschedule_appointment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        appointment_id: id,
                        date: newDate,
                        time_slot: newTimeSlot
                    })
                })
                .then(async res => {
                    const text = await res.text();
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON Parse Error:', text);
                        throw new Error('Invalid response from server: ' + text.substring(0,
                            100));
                    }
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Appointment Rescheduled',
                            text: data.message ||
                                'Your appointment has been updated successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Unable to Reschedule',
                            text: data.message || 'Something went wrong. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Reschedule Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: error.message ||
                            'Could not process your request. Please try again later.',
                        confirmButtonText: 'OK'
                    });
                });
        });
    }

    // Handle cancel button
    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) return;

            Swal.fire({
                icon: 'warning',
                title: 'Cancel Appointment?',
                text: 'Are you sure you want to cancel this appointment? This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes, Cancel',
                cancelButtonText: 'No, Keep it',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch('cancel_appointment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            appointment_id: id
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Appointment Cancelled',
                                text: data.message ||
                                    'Your appointment has been cancelled.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Unable to Cancel',
                                text: data.message ||
                                    'Something went wrong. Please try again.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Could not process your request. Please try again later.',
                            confirmButtonText: 'OK'
                        });
                    });
            });
        });
    });
});
</script>

<?php
include './utils/footer.php';
?>