<?php
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Slot Availability</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Slot Availability</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Check Slot Availability</h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="check_date" class="form-label">Select Date</label>
                                <input type="date" id="check_date" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" id="check_availability" class="btn btn-primary d-block">Check
                                    Availability</button>
                            </div>
                        </div>

                        <div id="availability_results"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkDate = document.getElementById('check_date');
    const checkButton = document.getElementById('check_availability');
    const resultsDiv = document.getElementById('availability_results');

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    checkDate.setAttribute('min', today);

    function checkAvailability() {
        const selectedDate = checkDate.value;

        if (!selectedDate) {
            alert('Please select a date');
            return;
        }

        resultsDiv.innerHTML =
            '<div class="text-center"><i class="bi bi-hourglass-split"></i> Loading...</div>';

        fetch('get_available_slots.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'date=' + encodeURIComponent(selectedDate)
            })
            .then(response => response.json())
            .then(data => {
                let html = '<h6 class="mt-3">Availability for ' + selectedDate + '</h6>';

                if (data.length === 0) {
                    html +=
                        '<div class="alert alert-warning">All slots are fully booked for this date.</div>';
                } else {
                    html += '<div class="table-responsive"><table class="table table-striped">';
                    html +=
                        '<thead><tr><th>Time Slot</th><th>Available</th><th>Booked</th><th>Status</th></tr></thead><tbody>';

                    data.forEach(slot => {
                        const statusClass = slot.available_count <= 3 ? 'text-warning' :
                            'text-success';
                        const statusText = slot.available_count <= 3 ? 'Limited' : 'Available';

                        html += `<tr>
                        <td>${slot.time_slot}</td>
                        <td>${slot.available_count}</td>
                        <td>${slot.booked_count}</td>
                        <td class="${statusClass}">${statusText}</td>
                    </tr>`;
                    });

                    html += '</tbody></table></div>';

                    const totalAvailable = data.reduce((sum, slot) => sum + slot.available_count, 0);
                    const totalBooked = data.reduce((sum, slot) => sum + slot.booked_count, 0);

                    html += `<div class="alert alert-info">
                    <strong>Summary:</strong> ${data.length} time slots available with ${totalAvailable} total openings. 
                    ${totalBooked} appointments already booked.
                </div>`;
                }

                resultsDiv.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.innerHTML =
                    '<div class="alert alert-danger">Error loading availability data.</div>';
            });
    }

    checkButton.addEventListener('click', checkAvailability);

    // Auto-check on date change
    checkDate.addEventListener('change', checkAvailability);

    // Initial load
    checkAvailability();
});
</script>

<?php 
include './utils/footer.php';
?>