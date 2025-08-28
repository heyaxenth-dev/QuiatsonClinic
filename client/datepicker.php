<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tempus Dominus CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus/dist/css/tempus-dominus.min.css">

<div class="container mt-4">
    <label for="datePicker" class="form-label">Choose Appointment Date</label>
    <div class="input-group" id="datePickerWrapper">
        <input type="text" class="form-control" id="datePicker" />
        <span class="input-group-text">
            <i class="bi bi-calendar"></i>
        </span>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tempus Dominus JS -->
<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus/dist/js/tempus-dominus.min.js"></script>

<script>
const fullyBookedDates = ["2025-09-02", "2025-09-05", "2025-09-08"];

const picker = new tempusDominus.TempusDominus(document.getElementById('datePickerWrapper'), {
    restrictions: {
        minDate: new Date(), // ✅ disable past dates
        disabledDates: fullyBookedDates.map(date => new Date(date)) // ✅ disable booked dates
    }
});
</script>