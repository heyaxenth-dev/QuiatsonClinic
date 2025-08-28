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

                        <form action="code.php" method="POST" role="form">
                            <h5 class="mb-3">Patient's Information</h5>

                            <div class="row">
                                <!-- Last Name -->
                                <div class="col-md-4 form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" required />
                                </div>

                                <!-- First Name -->
                                <div class="col-md-4 form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" name="firstname" id="firstname" class="form-control" required />
                                </div>

                                <!-- Middle Initial -->
                                <div class="col-md-4 form-group">
                                    <label for="middle_initial">Middle Initial</label>
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
                                    <label for="height">Height (cm)</label>
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

                            <div class="form-group mt-3 mb-3">
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
<script src="assets/js/datepicker.js"></script>

<?php 
include './utils/footer.php';
?>