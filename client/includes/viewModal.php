<!-- Modal -->
<div class="modal fade" id="viewDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="viewDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailsLabel">Patient's Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="viewId">

                <!-- Severity of Appointment -->
                <div class="col-md-4 mb-3">
                    <div class="form-floating">
                        <input type="text" name="severity" id="viewSeverity" class="form-control fw-bold"
                            placeholder="Severity" readonly />
                        <label for="viewSeverity">Severity</label>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="lastname" id="viewLastname" class="form-control"
                                placeholder="Last Name" readonly />
                            <label for="viewLastname">Last Name</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="firstname" id="viewFirstname" class="form-control"
                                placeholder="First Name" readonly />
                            <label for="viewFirstname">First Name</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="middle_initial" id="viewMiddle_initial" class="form-control"
                                placeholder="Middle Initial" readonly />
                            <label for="viewMiddle_initial">Middle Initial</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="address" id="viewAddress" class="form-control"
                                placeholder="Address" readonly />
                            <label for="viewAddress">Address</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="number" name="age" id="viewAge" class="form-control" placeholder="Age" min="0"
                                readonly />
                            <label for="viewAge">Age</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" name="sex" id="viewSex" class="form-control" placeholder="Sex"
                                readonly />
                            <label for="viewSex">Sex</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" name="birthdate" id="viewBirthdate" class="form-control"
                                placeholder="Birthdate" readonly />
                            <label for="viewBirthdate">Birthdate</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" name="civil_status" id="viewCivil_status" class="form-control"
                                placeholder="Civil Status" readonly />
                            <label for="viewCivil_status">Civil Status</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="tel" name="phone" id="viewPhone" class="form-control"
                                placeholder="Phone Number" readonly />
                            <label for="viewPhone">Phone Number</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" name="weight" id="viewWeight" class="form-control"
                                placeholder="Weight (kg)" readonly />
                            <label for="viewWeight">Weight (kg)</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" name="height" id="viewHeight" class="form-control"
                                placeholder="Height (cm)" readonly />
                            <label for="viewHeight">Height (cm)</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="text" name="bloodtype" id="viewBloodtype" class="form-control"
                                placeholder="Blood Type" readonly />
                            <label for="viewBloodtype">Blood Type</label>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Appointment Schedule</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="date" id="viewDate" class="form-control datepicker"
                                placeholder="Appointment Date" readonly />
                            <label for="viewDate">Appointment Date</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="time_slot" id="viewTime_slot" class="form-control"
                                placeholder="Time Slot" readonly />
                            <label for="viewTime_slot">Time Slot</label>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Symptoms</h5>

                <div class="form-floating">
                    <textarea name="symptoms" id="viewSymptoms" class="form-control" placeholder="Symptoms"
                        readonly></textarea>
                    <label for="viewSymptoms">Symptoms</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"><i class="bi bi-printer"></i> Print Details</button>
            </div>
        </div>
    </div>
</div>