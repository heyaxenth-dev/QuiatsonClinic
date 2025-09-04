<!-- Conclusion Modal -->
<div class="modal fade" id="conclusionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="conclusionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="conclusionModalLabel">Conclude Appointment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="conclusionForm">
                <div class="modal-body">
                    <div class="container">
                        <div id="conclusionPrintArea">
                            <div class="text-center mb-2">
                                <h5 class="mb-0">QUIATSON MEDICAL CLINIC</h5>
                                <div style="font-weight: bold;">ROY I. QUIATSON, MD. PFAFFP</div>
                                <div>FAMILY MEDICINE</div>
                                <div>POBLACION TIBIAO, ANTIQUE</div>
                                <div class="fw-bold mt-2">RECORD OF REPORTS</div>
                            </div>
                            <div class="row g-3 mb-2">
                                <div class="col-md-7">
                                    <label class="fw-bold">Patient Name:</label>
                                    <input type="text" class="form-control form-control-sm" name="patient_name"
                                        id="conclusionPatientName" />
                                </div>
                                <div class="col-md-2">
                                    <label class="fw-bold">Age:</label>
                                    <input type="number" class="form-control form-control-sm" name="age"
                                        id="conclusionAge" />
                                </div>
                                <div class="col-md-3">
                                    <label class="fw-bold">Sex:</label>
                                    <input type="text" class="form-control form-control-sm" name="sex"
                                        id="conclusionSex" />
                                </div>
                            </div>
                            <div class="row g-3 mb-2">
                                <div class="col-7">
                                    <label class="fw-bold">Address:</label>
                                    <input type="text" class="form-control form-control-sm" name="address"
                                        id="conclusionAddress" />
                                </div>
                                <div class="col-5">
                                    <label class="fw-bold">Civil Status:</label>
                                    <input type="text" class="form-control form-control-sm" name="civil_status"
                                        id="conclusionCivilStatus" />
                                </div>
                            </div>
                            <div class="row g-3 mb-2">
                                <div class="col-7">
                                    <label class="fw-bold">Phone:</label>
                                    <input type="text" class="form-control form-control-sm" name="phone"
                                        id="conclusionPhone" />
                                </div>
                                <div class="col-5">
                                    <label class="fw-bold">Status:</label>
                                    <input type="text" class="form-control form-control-sm fw-bold" name="status"
                                        id="conclusionStatus" />
                                </div>
                            </div>
                            <div class="row g-3 mb-2">
                                <div class="col-6">
                                    <div class="fw-bold">DATE AND TIME</div>
                                    <div>
                                        <input type="text" class="form-control form-control-sm mb-1" name="day"
                                            id="conclusionDay" placeholder="Day (e.g. Monday)" />
                                        <input type="text" class="form-control form-control-sm mb-1" name="date"
                                            id="conclusionDate" />
                                        <input type="text" class="form-control form-control-sm" name="time"
                                            id="conclusionTime" placeholder="Time (e.g. 2:00-3:00 PM)" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fw-bold">TYPE OF APPOINTMENT</div>
                                    <input type="text" class="form-control form-control-sm mb-1" name="checkup_type"
                                        id="conclusionCheckupType" placeholder="Check up: ..." />
                                    <input type="text" class="form-control form-control-sm" name="lab_type"
                                        id="conclusionLabType" placeholder="Laboratory Check up" />
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <textarea class="form-control" name="remarks" id="conclusionRemarks" rows="4"
                                        placeholder="Add Remarks"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="makeConclusion">Save Remarks</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="printConclusionReport()"><i
                        class="bi bi-printer"></i> Print</button> -->
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function printConclusionReport() {
    // Get the print area content
    var printContents = document.getElementById('conclusionPrintArea').innerHTML;
    // Create a new window
    var printWindow = window.open('', '', 'height=800,width=900');
    printWindow.document.write('<html><head><title>Print Report</title>');
    // Optional: Add Bootstrap CDN for print styling
    printWindow.document.write(
        '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    printWindow.document.write(
        '<style>body{padding:20px;} input, textarea { border: none; background: none; width: auto; font-weight: inherit; font-size: inherit; padding: 0; } input:focus, textarea:focus { outline: none; } .form-control, .form-control-sm { box-shadow: none; } </style>'
    );
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>