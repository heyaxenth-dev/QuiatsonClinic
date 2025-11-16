<?php 
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>
<script src="assets/js/sweetalert2.all.min.js"></script>
<?php

// Get current logged-in staff ID
$current_staff_id = $_SESSION['user_id'] ?? 1; // Fallback to 1 if not set
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Staff Schedule Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item active">Staff Schedules</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Manage Your Schedule</h5>
                        <p class="text-muted">Mark dates as unavailable to prevent clients from booking appointments on those dates.</p>

                        <!-- Add Schedule Form -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Unavailable Date</h6>
                            </div>
                            <div class="card-body">
                                <form id="scheduleForm">
                                    <input type="hidden" name="staff_id" value="<?= $current_staff_id; ?>">
                                    <input type="hidden" name="created_by" value="<?= $current_staff_id; ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="schedule_date" class="form-label">Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="schedule_date" name="schedule_date" required>
                                        </div>
                                        
                                        <div class="col-md-3 mb-3">
                                            <label for="start_time" class="form-label">Start Time (Optional)</label>
                                            <input type="time" class="form-control" id="start_time" name="start_time">
                                        </div>
                                        
                                        <div class="col-md-3 mb-3">
                                            <label for="end_time" class="form-label">End Time (Optional)</label>
                                            <input type="time" class="form-control" id="end_time" name="end_time">
                                        </div>
                                        
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary d-block w-100">
                                                <i class="bi bi-plus-lg"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="reason" class="form-label">Reason (Optional)</label>
                                            <input type="text" class="form-control" id="reason" name="reason" placeholder="e.g., Vacation, Training, etc.">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Calendar View -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Unavailable Dates Calendar</h6>
                                <div>
                                    <input type="month" id="calendarMonth" class="form-control form-control-sm" style="width: 200px;">
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="calendarContainer"></div>
                            </div>
                        </div>

                        <!-- List of Unavailable Dates -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Unavailable Dates List</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="schedulesTable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Time Range</th>
                                                <th>Reason</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="schedulesTableBody">
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this unavailable date? Clients will be able to book appointments on this date again.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scheduleForm = document.getElementById('scheduleForm');
    const schedulesTableBody = document.getElementById('schedulesTableBody');
    const calendarMonth = document.getElementById('calendarMonth');
    const calendarContainer = document.getElementById('calendarContainer');
    let deleteScheduleId = null;

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('schedule_date').setAttribute('min', today);
    
    // Set calendar month to current month
    const now = new Date();
    calendarMonth.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

    // Load schedules on page load
    loadSchedules();
    renderCalendar();

    // Handle form submission
    scheduleForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(scheduleForm);
        formData.append('action', 'add');

        fetch('api/schedule_api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Schedule added successfully!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    scheduleForm.reset();
                    loadSchedules();
                    renderCalendar();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to add schedule',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while adding the schedule.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        });
    });

    // Load schedules list
    function loadSchedules() {
        fetch('api/schedule_api.php?action=get&staff_id=<?= $current_staff_id; ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderSchedulesTable(data.schedules || []);
                } else {
                    schedulesTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading schedules</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                schedulesTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading schedules</td></tr>';
            });
    }

    // Render schedules table
    function renderSchedulesTable(schedules) {
        if (schedules.length === 0) {
            schedulesTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No unavailable dates scheduled</td></tr>';
            return;
        }

        schedulesTableBody.innerHTML = schedules.map(schedule => {
            const date = new Date(schedule.schedule_date);
            const formattedDate = date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            const timeRange = schedule.start_time && schedule.end_time 
                ? `${schedule.start_time} - ${schedule.end_time}`
                : schedule.start_time 
                    ? `From ${schedule.start_time}`
                    : 'All Day';
            
            const createdDate = new Date(schedule.created_at);
            const formattedCreated = createdDate.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });

            return `
                <tr>
                    <td>${formattedDate}</td>
                    <td>${timeRange}</td>
                    <td>${schedule.reason || '-'}</td>
                    <td>${formattedCreated}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteSchedule(${schedule.id})">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Delete schedule
    window.deleteSchedule = function(id) {
        deleteScheduleId = id;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    };

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!deleteScheduleId) return;

        fetch('api/schedule_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&id=${deleteScheduleId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Schedule deleted successfully!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    loadSchedules();
                    renderCalendar();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                    modal.hide();
                    deleteScheduleId = null;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to delete schedule',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while deleting the schedule.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        });
    });

    // Render calendar
    function renderCalendar() {
        const monthValue = calendarMonth.value;
        const [year, month] = monthValue.split('-').map(Number);
        
        fetch(`api/schedule_api.php?action=get&staff_id=<?= $current_staff_id; ?>&year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const unavailableDates = (data.schedules || []).map(s => s.schedule_date);
                    renderCalendarView(year, month, unavailableDates);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function renderCalendarView(year, month, unavailableDates) {
        const firstDay = new Date(year, month - 1, 1);
        const lastDay = new Date(year, month, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();
        
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];
        
        let html = `<h5 class="mb-3">${monthNames[month - 1]} ${year}</h5>`;
        html += '<div class="table-responsive"><table class="table table-bordered">';
        html += '<thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>';
        html += '<tbody><tr>';
        
        // Empty cells for days before the first day of the month
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += '<td></td>';
        }
        
        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isUnavailable = unavailableDates.includes(dateStr);
            const isPast = new Date(dateStr) < new Date(today);
            const cellClass = isUnavailable ? 'bg-danger text-white' : isPast ? 'text-muted' : '';
            const cellContent = isUnavailable ? `<strong>${day}</strong><br><small>Unavailable</small>` : day;
            
            html += `<td class="${cellClass}" style="height: 80px; vertical-align: top; padding: 5px;">${cellContent}</td>`;
            
            if ((startingDayOfWeek + day) % 7 === 0) {
                html += '</tr><tr>';
            }
        }
        
        html += '</tr></tbody></table></div>';
        calendarContainer.innerHTML = html;
    }

    calendarMonth.addEventListener('change', renderCalendar);
});
</script>

<?php 
include './utils/footer.php';
?>

