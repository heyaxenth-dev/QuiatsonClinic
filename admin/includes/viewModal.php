<!-- Modal -->
<div class="modal fade" id="viewDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="viewDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailsLabel">Patient's Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div id="viewPrintArea">
                        <div class="text-center mb-2">
                            <h5 class="mb-0">QUIATSON MEDICAL CLINIC</h5>
                            <div style="font-weight: bold;">ROY I. QUIATSON, MD. PFAFFP</div>
                            <div>FAMILY MEDICINE</div>
                            <div>POBLACION TIBIAO, ANTIQUE</div>
                            <div class="fw-bold mt-2">RECORD OF REPORTS</div>
                        </div>
                        <input type="hidden" name="id" id="viewId">
                        <div class="row g-3 mb-2">
                            <div class="col-md-9">
                                <label class="fw-bold">Patient Name:</label>
                                <div class="row g-2">
                                    <div class="col-md-4"><input type="text" class="form-control form-control-sm"
                                            id="viewLastname" placeholder="Last Name" readonly /></div>
                                    <div class="col-md-4"><input type="text" class="form-control form-control-sm"
                                            id="viewFirstname" placeholder="First Name" readonly /></div>
                                    <div class="col-md-4"><input type="text" class="form-control form-control-sm"
                                            id="viewMiddle_initial" placeholder="Middle Initial" readonly /></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold">Status:</label>
                                <input type="text" class="form-control form-control-sm fw-bold" id="viewSeverity"
                                    readonly />
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-7">
                                <label class="fw-bold">Address:</label>
                                <input type="text" class="form-control form-control-sm" id="viewAddress" readonly />
                            </div>
                            <div class="col-5">
                                <label class="fw-bold">Civil Status:</label>
                                <input type="text" class="form-control form-control-sm" id="viewCivil_status"
                                    readonly />
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-2">
                                <label class="fw-bold">Age:</label>
                                <input type="number" class="form-control form-control-sm" id="viewAge" readonly />
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold">Sex:</label>
                                <input type="text" class="form-control form-control-sm" id="viewSex" readonly />
                            </div>
                            <div class="col-md-3">
                                <label class="fw-bold">Birthdate:</label>
                                <input type="text" class="form-control form-control-sm" id="viewBirthdate" readonly />
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Phone:</label>
                                <input type="tel" class="form-control form-control-sm" id="viewPhone" readonly />
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-4">
                                <label class="fw-bold">Weight (kg):</label>
                                <input type="text" class="form-control form-control-sm" id="viewWeight" readonly />
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Height (cm):</label>
                                <input type="text" class="form-control form-control-sm" id="viewHeight" readonly />
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Blood Type:</label>
                                <input type="text" class="form-control form-control-sm" id="viewBloodtype" readonly />
                            </div>
                        </div>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <div class="fw-bold">DATE AND TIME</div>
                                <input type="text" class="form-control form-control-sm mb-1" id="viewDay"
                                    placeholder="Day (e.g. Monday)" readonly />
                                <input type="text" class="form-control form-control-sm mb-1" id="viewDate"
                                    placeholder="Date" readonly />
                                <input type="text" class="form-control form-control-sm" id="viewTime_slot"
                                    placeholder="Time" readonly />
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">TYPE OF APPOINTMENT</div>
                                <input type="text" class="form-control form-control-sm mb-1" id="viewSymptoms"
                                    placeholder="Check up: ..." readonly />
                                <input type="text" class="form-control form-control-sm" id="viewLabType"
                                    placeholder="Laboratory Check up" readonly />
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <textarea class="form-control" id="viewRemarks" rows="4" placeholder="Add Remarks"
                                    readonly></textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-0">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="button" id="btnViewUploadedId"
                                    class="btn btn-outline-secondary btn-sm d-none">
                                    <i class="bi bi-card-image"></i> View ID
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printViewDetails()"><i class="bi bi-printer"></i>
                    Print Details</button>
            </div>
        </div>
    </div>
</div>
<script>
function printViewDetails() {
    var source = document.getElementById('viewPrintArea');
    var clone = source.cloneNode(true);

    // Replace form controls with static text copies for reliable printing
    clone.querySelectorAll('input').forEach(function(el) {
        var div = document.createElement('div');
        div.className = el.className;
        div.textContent = el.value || '';
        if (el.classList.contains('mb-1')) div.classList.add('mb-1');
        el.parentNode.replaceChild(div, el);
    });
    clone.querySelectorAll('textarea').forEach(function(el) {
        var div = document.createElement('div');
        div.className = el.className;
        div.style.minHeight = '3rem';
        div.textContent = el.value || '';
        el.parentNode.replaceChild(div, el);
    });

    var printWindow = window.open('', '', 'height=800,width=900');
    printWindow.document.write('<html><head><title>Print Details</title>');
    printWindow.document.write(
        '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    printWindow.document.write(
        '<style>body{padding:20px;} .modal{position:static; display:block;} .form-control, .form-control-sm{box-shadow:none;} .fw-bold{font-weight:700 !important;}</style>'
    );
    printWindow.document.write('</head><body>');
    printWindow.document.write(clone.outerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 200);
}
// Handle View ID button click
document.addEventListener('click', function(e) {
    if (e.target && (e.target.id === 'btnViewUploadedId' || e.target.closest('#btnViewUploadedId'))) {
        var btn = document.getElementById('btnViewUploadedId');
        var imgSrc = btn.getAttribute('data-image');
        if (imgSrc) {
            var idModalEl = document.getElementById('uploadedIdModal');
            if (!idModalEl) {
                // create modal on the fly
                var wrapper = document.createElement('div');
                wrapper.innerHTML =
                    '\n<div class="modal fade" id="uploadedIdModal" tabindex="-1" aria-hidden="true">\n  <div class="modal-dialog modal-dialog-centered modal-lg">\n    <div class="modal-content">\n      <div class="modal-header">\n        <h5 class="modal-title">Uploaded ID</h5>\n        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\n      </div>\n      <div class="modal-body text-center">\n        <img id="uploadedIdImage" src="' +
                    imgSrc +
                    '" class="img-fluid rounded border" alt="Uploaded ID"/>\n      </div>\n      <div class="modal-footer">\n        <a id="downloadIdImage" href="' +
                    imgSrc +
                    '" class="btn btn-primary" download>Download</a>\n        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>\n      </div>\n    </div>\n  </div>\n</div>';
                document.body.appendChild(wrapper.firstElementChild);
                idModalEl = document.getElementById('uploadedIdModal');
            } else {
                idModalEl.querySelector('#uploadedIdImage').setAttribute('src', imgSrc);
                idModalEl.querySelector('#downloadIdImage').setAttribute('href', imgSrc);
            }
            var modalInstance = bootstrap.Modal.getOrCreateInstance(idModalEl);
            modalInstance.show();
        }
    }
});
</script>