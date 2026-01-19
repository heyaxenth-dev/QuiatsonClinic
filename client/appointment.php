<?php 
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Make Appointments</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Appointments</li>
                <!-- <li class="breadcrumb-item active">Today</li> -->
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Appointment Form</h5>

                        <!-- History Section -->
                        <div class="alert alert-info" id="historyAlert" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-clock-history me-2"></i>
                                    <strong>Previous Data Available!</strong>
                                    <span id="historyInfo">We found your previous appointment data.</span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2"
                                        id="previewHistory">
                                        <i class="bi bi-eye"></i> Preview
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" id="useHistory">
                                        <i class="bi bi-arrow-clockwise"></i> Use Previous Data
                                    </button>
                                </div>
                            </div>
                        </div>

                        <form action="code.php" method="POST" role="form" enctype="multipart/form-data"
                            id="appointmentForm">

                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">

                            <div class="mb-3">
                                <label class="form-label">
                                    Patient Type
                                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                       title="Select Regular for standard patients, or Senior Citizen/PWD for priority appointments. Senior/PWD patients may need to upload valid ID."></i>
                                </label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="patient_type" id="regular"
                                            value="regular" required>
                                        <!-- Loader Overlay -->
                                        <div id="loaderOverlay"
                                            style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);z-index:9999;align-items:center;justify-content:center;">
                                            <div style="text-align:center;">
                                                <div class="spinner-border text-primary" role="status"
                                                    style="width:3rem;height:3rem;">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div style="margin-top:1rem;font-size:1.2rem;">Processing your
                                                    appointment...</div>
                                            </div>
                                        </div>

                                        <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            var form = document.querySelector("form[action='code.php']");
                                            var loader = document.getElementById("loaderOverlay");
                                            if (form) {
                                                form.addEventListener("submit", function(e) {
                                                    loader.style.display = "flex";
                                                });
                                            }
                                        });
                                        </script>
                                        <label class="form-check-label" for="regular">
                                            Regular
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="patient_type" id="senior_pwd"
                                            value="senior_pwd" required>
                                        <label class="form-check-label" for="senior">
                                            Senior Citizen / PWD
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const seniorRadio = document.getElementById("senior_pwd");
                                const regularRadio = document.getElementById("regular");
                                const uploadDiv = document.getElementById("seniorIdUpload");
                                const uploadInput = document.getElementById("upload_id");

                                function toggleUpload() {
                                    if (seniorRadio.checked) {
                                        uploadDiv.classList.remove("d-none");
                                        uploadInput.setAttribute("required", "required");
                                    } else {
                                        uploadDiv.classList.add("d-none");
                                        uploadInput.removeAttribute("required");
                                        uploadInput.value = ""; // clear file input if hidden
                                    }
                                }

                                seniorRadio.addEventListener("change", toggleUpload);
                                regularRadio.addEventListener("change", toggleUpload);
                                toggleUpload();
                            });
                            </script>

                            <h5 class="mb-3">Patient's Information</h5>

                            <div class="row">
                                <!-- Last Name -->
                                <div class="col-md-4 form-group">
                                    <label for="lastname">
                                        Last Name
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter the patient's last name (surname)"></i>
                                    </label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" required />
                                </div>

                                <!-- First Name -->
                                <div class="col-md-4 form-group">
                                    <label for="firstname">
                                        First Name
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter the patient's first name"></i>
                                    </label>
                                    <input type="text" name="firstname" id="firstname" class="form-control" required />
                                </div>

                                <!-- Middle Initial -->
                                <div class="col-md-4 form-group">
                                    <label for="middle_initial">
                                        Middle Initial
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Enter only the first letter of the middle name"></i>
                                    </label>
                                    <input type="text" name="middle_initial" id="middle_initial" class="form-control"
                                        maxlength="1" required />
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Address -->
                                <div class="col-md-6 form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control" required
                                        autocomplete="off" />
                                </div>

                                <!-- Age -->
                                <div class="col-md-2 form-group">
                                    <label for="age">Age</label>
                                    <input type="number" name="age" id="age" class="form-control" min="0" required />
                                </div>

                                <!-- Sex -->
                                <div class="col-md-2 form-group">
                                    <label for="sex">Sex</label>
                                    <select name="sex" id="sex" class="form-control" required>
                                        <option value="">Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <!-- Birthdate -->
                                <div class="col-md-2 form-group">
                                    <label for="birthdate">Birthdate</label>
                                    <input type="date" name="birthdate" id="birthdate" class="form-control" required />
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Civil Status -->
                                <div class="col-md-3 form-group">
                                    <label for="civil_status">Civil Status</label>
                                    <select name="civil_status" id="civil_status" class="form-control" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Separated">Separated</option>
                                    </select>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-3 form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" required
                                        autocomplete="off" />
                                </div>

                                <!-- Weight -->
                                <div class="col-md-2 form-group">
                                    <label for="weight">Weight (kg)</label>
                                    <input type="text" name="weight" id="weight" class="form-control" required />
                                </div>

                                <!-- Height -->
                                <div class="col-md-2 form-group">
                                    <label for="height">Height (cm )</label>
                                    <input type="text" name="height" id="height" class="form-control" required />
                                </div>

                                <!-- Blood Type -->
                                <div class="col-md-2 form-group">
                                    <label for="bloodtype">Blood Type</label>
                                    <select name="bloodtype" id="bloodtype" class="form-control" required>
                                        <option value="">Select Blood Type</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Hidden input file for Senior/PWD ID -->
                            <div class="mt-4 mb-3 d-none" id="seniorIdUpload">
                                <label for="upload_id" class="form-label">
                                    Upload Senior Citizen / PWD ID
                                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                       title="Upload a clear photo or scanned copy of your valid Senior Citizen ID or PWD ID. Accepted formats: JPG, PNG, PDF. This is required for priority appointments."></i>
                                </label>
                                <input class="form-control" type="file" id="upload_id" name="upload_id"
                                    accept="image/*,.pdf">
                            </div>

                            <div class="form-group mt-4 mb-3">
                                <h6>
                                    Select Symptoms:
                                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                       title="Select all symptoms or reasons for your visit. You can choose more than one."></i>
                                </h6>
                                <small class="text-muted d-block mb-2">You may select multiple symptoms that apply.</small>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="fever"
                                                value="Fever">
                                            <label class="form-check-label" for="fever">Fever</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="cough"
                                                value="Cough">
                                            <label class="form-check-label" for="cough">Cough</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="fatigue"
                                                value="Fatigue">
                                            <label class="form-check-label" for="fatigue">Fatigue</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]"
                                                id="toxicLooking" value="Toxic Looking">
                                            <label class="form-check-label" for="toxicLooking">Toxic Looking</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="headache"
                                                value="Headache">
                                            <label class="form-check-label" for="headache">Headache</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="sore_throat"
                                                value="Sore Throat">
                                            <label class="form-check-label" for="sore_throat">Sore Throat</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]"
                                                id="shortness_of_breath" value="Shortness of Breath">
                                            <label class="form-check-label" for="shortness_of_breath">Shortness of
                                                Breath</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="chestPain"
                                                value="Chest Pain (Moderate to severe)">
                                            <label class="form-check-label" for="chestPain">Chest Pain (Moderate to
                                                severe)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="nausea"
                                                value="Nausea">
                                            <label class="form-check-label" for="nausea">Nausea</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]" id="no_symptoms"
                                                value="No Symptoms">
                                            <label class="form-check-label" for="no_symptoms">No Symptoms</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="symptom[]"
                                                id="abdominalPain" value="Abdominal Pain (Moderate to severe)">
                                            <label class="form-check-label" for="abdominalPain">Abdominal Pain (Moderate
                                                to
                                                severe)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3 mt-3">
                                Select Schedule
                                <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                   title="Choose your preferred appointment date and time. Available slots are shown based on clinic schedule. Book in advance to secure your preferred time."></i>
                            </h5>

                            <div class="row">
                                <!-- Appointment Date -->
                                <div class="col-md-4 form-group mt-3">
                                    <label for="date">
                                        Appointment Date
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Select your preferred appointment date. Available dates are shown. Some dates may be fully booked."></i>
                                    </label>
                                    <input type="date" name="date" id="date" class="form-control datepicker" required />
                                    <small class="text-muted">Select a date to see available time slots</small>
                                </div>

                                <!-- Time Slot -->
                                <div class="col-md-4 form-group mt-3">
                                    <label for="time_slot">
                                        Time Slot
                                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="After selecting a date, available time slots will appear here. Choose your preferred time. Slots are limited per day."></i>
                                    </label>
                                    <select name="time_slot" id="time_slot" class="form-select" required>
                                        <option value="">Select a date first</option>
                                    </select>
                                    <small class="text-muted">Maximum 10 patients per hour</small>
                                    <div id="slot-info" class="mt-2"></div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="text-center">
                                    <button type="submit" name="makeAppointment" class="btn btn-primary">Make an
                                        Appointment</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->

<!-- History Preview Modal -->
<div class="modal fade" id="historyPreviewModal" tabindex="-1" aria-labelledby="historyPreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyPreviewModalLabel">
                    <i class="bi bi-clock-history me-2"></i>Previous Appointment Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Personal Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td id="previewName">-</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td id="previewAddress">-</td>
                            </tr>
                            <tr>
                                <td><strong>Age:</strong></td>
                                <td id="previewAge">-</td>
                            </tr>
                            <tr>
                                <td><strong>Sex:</strong></td>
                                <td id="previewSex">-</td>
                            </tr>
                            <tr>
                                <td><strong>Birthdate:</strong></td>
                                <td id="previewBirthdate">-</td>
                            </tr>
                            <tr>
                                <td><strong>Civil Status:</strong></td>
                                <td id="previewCivilStatus">-</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td id="previewPhone">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Medical Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Weight:</strong></td>
                                <td id="previewWeight">-</td>
                            </tr>
                            <tr>
                                <td><strong>Height:</strong></td>
                                <td id="previewHeight">-</td>
                            </tr>
                            <tr>
                                <td><strong>Blood Type:</strong></td>
                                <td id="previewBloodtype">-</td>
                            </tr>
                            <tr>
                                <td><strong>Patient Type:</strong></td>
                                <td id="previewPatientType">-</td>
                            </tr>
                            <tr>
                                <td><strong>Symptom:</strong></td>
                                <td id="previewSymptom">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Last updated: <span id="previewLastUpdated">-</span>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmUseHistory">
                    <i class="bi bi-arrow-clockwise me-1"></i>Use This Data
                </button>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/datepicker.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let historyData = null;

    // Check for appointment history on page load
    checkAppointmentHistory();

    function checkAppointmentHistory() {
        fetch('get_appointment_history.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    historyData = data.data;
                    showHistoryAlert();
                }
            })
            .catch(error => {
                console.error('Error fetching appointment history:', error);
            });
    }

    function showHistoryAlert() {
        if (historyData) {
            const historyAlert = document.getElementById('historyAlert');
            const historyInfo = document.getElementById('historyInfo');

            // Format the last updated date
            const lastUpdated = new Date(historyData.created_at).toLocaleDateString();
            historyInfo.textContent = `Last appointment: ${lastUpdated}`;

            historyAlert.style.display = 'block';
        }
    }

    function populateFormWithHistory() {
        if (!historyData) return;

        // Populate form fields with history data
        document.getElementById('lastname').value = historyData.lastname || '';
        document.getElementById('firstname').value = historyData.firstname || '';
        document.getElementById('middle_initial').value = historyData.middle_initial || '';
        document.getElementById('address').value = historyData.address || '';
        document.getElementById('age').value = historyData.age || '';
        document.getElementById('sex').value = historyData.sex || '';
        document.getElementById('birthdate').value = historyData.birthdate || '';
        document.getElementById('civil_status').value = historyData.civil_status || '';
        document.getElementById('phone').value = historyData.phone || '';
        document.getElementById('weight').value = historyData.weight || '';
        document.getElementById('height').value = historyData.height || '';
        document.getElementById('bloodtype').value = historyData.bloodtype || '';

        // Set patient type
        if (historyData.patient_type) {
            const patientTypeRadio = document.querySelector(
                `input[name="patient_type"][value="${historyData.patient_type}"]`);
            if (patientTypeRadio) {
                patientTypeRadio.checked = true;
                // Trigger the toggle function for senior/pwd upload
                patientTypeRadio.dispatchEvent(new Event('change'));
            }
        }

        // Set symptoms (may be multiple, stored as comma-separated string)
        if (historyData.symptom) {
            const symptoms = historyData.symptom.split(',').map(s => s.trim()).filter(Boolean);
            symptoms.forEach(sym => {
                const symptomCheckbox = document.querySelector(
                    `input[name="symptom[]"][value="${sym}"]`);
                if (symptomCheckbox) {
                    symptomCheckbox.checked = true;
                }
            });
        }

        // Show success message
        showNotification('Previous data loaded successfully!', 'success');
    }

    function showNotification(message, type = 'info') {
        // Create a simple notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }

    function populatePreviewModal() {
        if (!historyData) return;

        // Populate preview modal
        document.getElementById('previewName').textContent =
            `${historyData.firstname || ''} ${historyData.middle_initial || ''} ${historyData.lastname || ''}`
            .trim();
        document.getElementById('previewAddress').textContent = historyData.address || '-';
        document.getElementById('previewAge').textContent = historyData.age || '-';
        document.getElementById('previewSex').textContent = historyData.sex || '-';
        document.getElementById('previewBirthdate').textContent = historyData.birthdate || '-';
        document.getElementById('previewCivilStatus').textContent = historyData.civil_status || '-';
        document.getElementById('previewPhone').textContent = historyData.phone || '-';
        document.getElementById('previewWeight').textContent = historyData.weight || '-';
        document.getElementById('previewHeight').textContent = historyData.height || '-';
        document.getElementById('previewBloodtype').textContent = historyData.bloodtype || '-';
        document.getElementById('previewPatientType').textContent = historyData.patient_type || '-';
        document.getElementById('previewSymptom').textContent = historyData.symptom || '-';

        // Format last updated date
        const lastUpdated = new Date(historyData.created_at).toLocaleString();
        document.getElementById('previewLastUpdated').textContent = lastUpdated;
    }

    // Event listeners
    document.getElementById('useHistory').addEventListener('click', function() {
        populateFormWithHistory();
    });

    document.getElementById('previewHistory').addEventListener('click', function() {
        populatePreviewModal();
        const modal = new bootstrap.Modal(document.getElementById('historyPreviewModal'));
        modal.show();
    });

    document.getElementById('confirmUseHistory').addEventListener('click', function() {
        populateFormWithHistory();
        const modal = bootstrap.Modal.getInstance(document.getElementById('historyPreviewModal'));
        modal.hide();
    });

    // Add a "Clear Form" button functionality
    function addClearFormButton() {
        const form = document.getElementById('appointmentForm');
        const submitButton = form.querySelector('button[type="submit"]');

        const clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.className = 'btn btn-outline-secondary me-2';
        clearButton.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Clear Form';
        clearButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all form data?')) {
                form.reset();
                // Reset patient type toggle
                document.getElementById('seniorIdUpload').classList.add('d-none');
                document.getElementById('upload_id').removeAttribute('required');
                showNotification('Form cleared successfully!', 'info');
            }
        });

        submitButton.parentNode.insertBefore(clearButton, submitButton);
    }

    // Initialize clear form button
    addClearFormButton();
});
</script>

<?php 
include './utils/footer.php';
?>