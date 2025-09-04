<?php 
include 'authentication.php';
checkLogin(); // Call the function to check if the user is logged in
include '../database/conn.php';
include './utils/header.php';
?>
<script src="assets/js/sweetalert2.all.min.js"></script>
<?php
        if (isset($_SESSION['logged'])) {
        ?>
<script type="text/javascript">
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

Toast.fire({
    background: '#53a653',
    color: '#fff',
    icon: '<?php echo $_SESSION['logged_icon']; ?>',
    title: '<?php echo $_SESSION['logged']; ?>'
});
</script>
<?php
            unset($_SESSION['logged']);
}
include './utils/sidebar.php';
include 'alert.php';

?>


<main id="main" class="main">
    <div class="pagetitle mb-4">
        <h1>Home</h1>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Appointment Calendar -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="card-body">
                                <h5 class="card-title">Appointment Calendar</h5>
                                <link rel="stylesheet"
                                    href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
                                <div id="calendar" style="min-height: 600px;"></div>
                                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var calendarEl = document.getElementById('calendar');
                                    if (!calendarEl) return;

                                    var calendar = new FullCalendar.Calendar(calendarEl, {
                                        initialView: 'dayGridMonth',
                                        headerToolbar: {
                                            left: 'prev,next today',
                                            center: 'title',
                                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                                        },
                                        height: 'auto',
                                        navLinks: true,
                                        nowIndicator: true,
                                        eventTimeFormat: {
                                            hour: 'numeric',
                                            minute: '2-digit',
                                            meridiem: 'short'
                                        },
                                        events: {
                                            url: 'get_user_appointments.php',
                                            failure: function() {
                                                console.error('Failed to load appointments');
                                            }
                                        },
                                        eventDidMount: function(info) {
                                            // Color by status
                                            var status = info.event.extendedProps && info.event
                                                .extendedProps.status;
                                            if (status === 'Pending') {
                                                info.el.style.backgroundColor = '#ffc107';
                                                info.el.style.borderColor = '#ffc107';
                                            } else if (status === 'Approved' || status ===
                                                'Success') {
                                                info.el.style.backgroundColor = '#28a745';
                                                info.el.style.borderColor = '#28a745';
                                            } else if (status === 'Cancelled' || status ===
                                                'Error') {
                                                info.el.style.backgroundColor = '#dc3545';
                                                info.el.style.borderColor = '#dc3545';
                                            }
                                        },
                                        eventClick: function(info) {
                                            var title = info.event.title || 'Appointment';
                                            var time = info.event.extendedProps && info.event
                                                .extendedProps.time_slot ? info.event.extendedProps
                                                .time_slot : '';
                                            Swal.fire({
                                                icon: 'info',
                                                title: title,
                                                text: time,
                                                confirmButtonText: 'Close'
                                            });
                                        }
                                    });

                                    calendar.render();
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                    <!-- End Appointment Calendar -->
                </div>
            </div>
            <!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        <div class="row">
                            <div class="d-grid gap-2">
                                <a href="appointment.php" class="btn btn-primary">Consult Now!</a>
                                <a href="lab_results.php" class="btn btn-secondary">View Lab Results</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Quick Actions -->
            </div>
            <!-- End Right side columns -->
        </div>
    </section>
</main>
<!-- End #main -->

<?php 
include './utils/footer.php';
?>