<?php 
include 'authentication.php';
checkLogin();
include '../database/conn.php';
include './utils/header.php';
include './utils/sidebar.php';
include 'alert.php';

// Handle re-appointment request from lab results
$reappointment_data = null;
$is_reappointment = isset($_GET['reappointment']) && $_GET['reappointment'] == '1';
$lab_result_id = isset($_GET['lab_result_id']) ? intval($_GET['lab_result_id']) : 0;
$original_appointment_id = isset($_GET['appointment_id']) ? intval($_GET['appointment_id']) : 0;

if ($is_reappointment && $original_appointment_id > 0) {
    // Fetch the original appointment data
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $original_appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reappointment_data = $result->fetch_assoc();
    }
    $stmt->close();
}
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

                        <!-- Re-appointment Notice -->
                        <?php if ($is_reappointment && $reappointment_data): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Re-appointment for Lab Result Reading</strong>
                            <p class="mb-0 mt-2">You are requesting a re-appointment to discuss your lab results from your appointment on 
                                <strong><?= htmlspecialchars($reappointment_data['appointment_date']); ?></strong>. 
                                Your previous information has been pre-filled below. Please review and select a new date and time.</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

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
                                <label class="form-label">Patient Type</label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="patient_type" id="regular"
                                            value="regular" <?= $reappointment_data && $reappointment_data['patient_type'] == 'regular' ? 'checked' : ''; ?> required>
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
                                            value="senior_pwd" <?= $reappointment_data && $reappointment_data['patient_type'] == 'senior_pwd' ? 'checked' : ''; ?> required>
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
                                
                                // Initialize on page load
                                toggleUpload();
                                
                                <?php if ($reappointment_data): ?>
                                // If re-appointment, ensure the correct patient type toggle is set
                                if (<?= $reappointment_data['patient_type'] == 'senior_pwd' ? 'true' : 'false'; ?>) {
                                    seniorRadio.checked = true;
                                    toggleUpload();
                                }
                                <?php endif; ?>
                            });
                            </script>

                            <h5 class="mb-3">Patient's Information</h5>

                            <div class="row">
                                <!-- Last Name -->
                                <div class="col-md-4 form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['lastname']) : ''; ?>" required />
                                </div>

                                <!-- First Name -->
                                <div class="col-md-4 form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" name="firstname" id="firstname" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['firstname']) : ''; ?>" required />
                                </div>

                                <!-- Middle Initial -->
                                <div class="col-md-4 form-group">
                                    <label for="middle_initial">Middle Initial</label>
                                    <input type="text" name="middle_initial" id="middle_initial" class="form-control"
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['middle_initial']) : ''; ?>"
                                        maxlength="1" required />
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Address -->
                                <div class="col-md-6 form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['address']) : ''; ?>" required
                                        autocomplete="off" />
                                </div>

                                <!-- Age -->
                                <div class="col-md-2 form-group">
                                    <label for="age">Age</label>
                                    <input type="number" name="age" id="age" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['age']) : ''; ?>" min="0" required />
                                </div>

                                <!-- Sex -->
                                <div class="col-md-2 form-group">
                                    <label for="sex">Sex</label>
                                    <select name="sex" id="sex" class="form-control" required>
                                        <option value="">Select Sex</option>
                                        <option value="Male" <?= $reappointment_data && $reappointment_data['sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?= $reappointment_data && $reappointment_data['sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>

                                <!-- Birthdate -->
                                <div class="col-md-2 form-group">
                                    <label for="birthdate">Birthdate</label>
                                    <input type="date" name="birthdate" id="birthdate" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['birthdate']) : ''; ?>" required />
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Civil Status -->
                                <div class="col-md-3 form-group">
                                    <label for="civil_status">Civil Status</label>
                                    <select name="civil_status" id="civil_status" class="form-control" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="Single" <?= $reappointment_data && $reappointment_data['civil_status'] == 'Single' ? 'selected' : ''; ?>>Single</option>
                                        <option value="Married" <?= $reappointment_data && $reappointment_data['civil_status'] == 'Married' ? 'selected' : ''; ?>>Married</option>
                                        <option value="Widowed" <?= $reappointment_data && $reappointment_data['civil_status'] == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                                        <option value="Separated" <?= $reappointment_data && $reappointment_data['civil_status'] == 'Separated' ? 'selected' : ''; ?>>Separated</option>
                                    </select>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-3 form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['phone']) : ''; ?>" required
                                        autocomplete="off" />
                                </div>

                                <!-- Weight -->
                                <div class="col-md-2 form-group">
                                    <label for="weight">Weight (kg)</label>
                                    <input type="text" name="weight" id="weight" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['weight']) : ''; ?>" required />
                                </div>

                                <!-- Height -->
                                <div class="col-md-2 form-group">
                                    <label for="height">Height (cm )</label>
                                    <input type="text" name="height" id="height" class="form-control" 
                                        value="<?= $reappointment_data ? htmlspecialchars($reappointment_data['height']) : ''; ?>" required />
                                </div>

                                <!-- Blood Type -->
                                <div class="col-md-2 form-group">
                                    <label for="bloodtype">Blood Type</label>
                                    <select name="bloodtype" id="bloodtype" class="form-control" required>
                                        <option value="">Select Blood Type</option>
                                        <?php 
                                        $blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                        foreach ($blood_types as $bt) {
                                            $selected = ($reappointment_data && $reappointment_data['bloodtype'] == $bt) ? 'selected' : '';
                                            echo "<option value=\"$bt\" $selected>$bt</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Hidden input file for Senior/PWD ID -->
                            <div class="mt-4 mb-3 d-none" id="seniorIdUpload">
                                <label for="upload_id" class="form-label">Upload Senior Citizen / PWD ID</label>
                                <input class="form-control" type="file" id="upload_id" name="upload_id"
                                    accept="image/*,.pdf">
                            </div>

                            <div class="form-group mt-4 mb-3">
                                <h6>Select Symptoms:</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="fever"
                                                value="Fever" required>
                                            <label class="form-check-label" for="fever">Fever</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="cough"
                                                value="Cough" required>
                                            <label class="form-check-label" for="cough">Cough</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="fatigue"
                                                value="Fatigue" required>
                                            <label class="form-check-label" for="fatigue">Fatigue</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom"
                                                id="toxicLooking" value="Toxic Looking" required>
                                            <label class="form-check-label" for="toxicLooking">Toxic Looking</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="headache"
                                                value="Headache" required>
                                            <label class="form-check-label" for="headache">Headache</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="sore_throat"
                                                value="Sore Throat" required>
                                            <label class="form-check-label" for="sore_throat">Sore Throat</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom"
                                                id="shortness_of_breath" value="Shortness of Breath" required>
                                            <label class="form-check-label" for="shortness_of_breath">Shortness of
                                                Breath</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="chestPain"
                                                value="Chest Pain" required>
                                            <label class="form-check-label" for="chestPain">Chest Pain (Moderate to
                                                severe)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="nausea"
                                                value="Nausea" required>
                                            <label class="form-check-label" for="nausea">Nausea</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom" id="no_symptoms"
                                                value="No Symptoms" required>
                                            <label class="form-check-label" for="no_symptoms">No Symptoms</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom"
                                                id="abdominalPain" value="Abdominal Pain" required>
                                            <label class="form-check-label" for="abdominalPain">Abdominal Pain (Moderate
                                                to
                                                severe)</label>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($is_reappointment): ?>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom"
                                                id="lab_result_reading" value="Lab Result Reading" checked required>
                                            <label class="form-check-label" for="lab_result_reading">
                                                <strong class="text-primary">Lab Result Reading</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="symptom"
                                                id="other_symptom" value="Other" required>
                                            <label class="form-check-label" for="other_symptom">Other</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9" id="other_symptom_input_container" style="display: none;">
                                        <label for="other_symptom_text" class="form-label">Please specify your symptom:</label>
                                        <input type="text" class="form-control" id="other_symptom_text" name="other_symptom_text" 
                                            placeholder="Enter your symptom here">
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3 mt-3">Select Schedule</h5>

                            <div class="row">
                                <!-- Appointment Date -->
                                <div class="col-md-4 form-group mt-3">
                                    <label for="date">Appointment Date</label>
                                    <input type="date" name="date" id="date" class="form-control datepicker" required />
                                    <small class="text-muted">Select a date to see available time slots</small>
                                </div>

                                <!-- Time Slot -->
                                <div class="col-md-4 form-group mt-3">
                                    <label for="time_slot">Time Slot</label>
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
<!-- Include SweetAlert2 for better alerts -->
<script src="assets/js/sweetalert2.all.min.js"></script>
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

    // Handle "Other" symptom option - Define variables and function first
    const otherSymptomRadio = document.getElementById('other_symptom');
    const otherSymptomInput = document.getElementById('other_symptom_text');
    const otherSymptomContainer = document.getElementById('other_symptom_input_container');
    
    // Make toggle function available globally for history population
    function toggleOtherSymptomInput() {
        if (otherSymptomRadio && otherSymptomContainer) {
            if (otherSymptomRadio.checked) {
                otherSymptomContainer.style.display = 'block';
                if (otherSymptomInput) {
                    otherSymptomInput.setAttribute('required', 'required');
                }
            } else {
                otherSymptomContainer.style.display = 'none';
                if (otherSymptomInput) {
                    otherSymptomInput.removeAttribute('required');
                    otherSymptomInput.value = '';
                }
            }
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

        // Set symptom
        if (historyData.symptom) {
            // Check if symptom is one of the predefined options
            const symptomRadio = document.querySelector(
            `input[name="symptom"][value="${historyData.symptom}"]`);
            if (symptomRadio) {
                symptomRadio.checked = true;
                // If it's "Other", show the input field
                if (historyData.symptom === 'Other' && otherSymptomRadio) {
                    toggleOtherSymptomInput();
                }
            } else {
                // If symptom is not in the predefined list, it's a custom symptom
                // Set "Other" and populate the text field
                if (otherSymptomRadio && otherSymptomInput) {
                    otherSymptomRadio.checked = true;
                    otherSymptomInput.value = historyData.symptom;
                    toggleOtherSymptomInput();
                }
            }
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
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'question',
                    title: 'Clear Form?',
                    text: 'Are you sure you want to clear all form data?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Clear',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.reset();
                        // Reset patient type toggle
                        document.getElementById('seniorIdUpload').classList.add('d-none');
                        document.getElementById('upload_id').removeAttribute('required');
                        // Reset other symptom input
                        if (otherSymptomContainer) {
                            otherSymptomContainer.style.display = 'none';
                            if (otherSymptomInput) {
                                otherSymptomInput.removeAttribute('required');
                                otherSymptomInput.value = '';
                            }
                        }
                        showNotification('Form cleared successfully!', 'info');
                    }
                });
            } else {
                if (confirm('Are you sure you want to clear all form data?')) {
                    form.reset();
                    // Reset patient type toggle
                    document.getElementById('seniorIdUpload').classList.add('d-none');
                    document.getElementById('upload_id').removeAttribute('required');
                    // Reset other symptom input
                    if (otherSymptomContainer) {
                        otherSymptomContainer.style.display = 'none';
                        if (otherSymptomInput) {
                            otherSymptomInput.removeAttribute('required');
                            otherSymptomInput.value = '';
                        }
                    }
                    showNotification('Form cleared successfully!', 'info');
                }
            }
        });

        submitButton.parentNode.insertBefore(clearButton, submitButton);
    }

    // Initialize clear form button
    addClearFormButton();
    
    // Set up "Other" symptom option handlers (variables already defined above)
    if (otherSymptomRadio && otherSymptomContainer) {
        otherSymptomRadio.addEventListener('change', toggleOtherSymptomInput);
        
        // Add change listeners to all symptom radios
        document.querySelectorAll('input[name="symptom"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.id !== 'other_symptom') {
                    otherSymptomContainer.style.display = 'none';
                    otherSymptomInput.removeAttribute('required');
                    otherSymptomInput.value = '';
                } else {
                    toggleOtherSymptomInput();
                }
            });
        });
        
        // Handle form submission - replace "Other" with custom text if provided
        const appointmentForm = document.getElementById('appointmentForm');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                if (otherSymptomRadio.checked) {
                    if (!otherSymptomInput || !otherSymptomInput.value.trim()) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Symptom Required',
                                text: 'Please specify your symptom in the "Other" field.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#ffc107'
                            });
                        } else {
                            alert('Please specify your symptom in the "Other" field.');
                        }
                        return false;
                    }
                    
                    // Create a hidden input with the custom symptom value
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'symptom';
                    hiddenInput.value = otherSymptomInput.value.trim();
                    this.appendChild(hiddenInput);
                    
                    // Remove the "Other" radio value
                    otherSymptomRadio.disabled = true;
                }
            });
        }
    }
});
</script>

<?php 
include './utils/footer.php';
?>