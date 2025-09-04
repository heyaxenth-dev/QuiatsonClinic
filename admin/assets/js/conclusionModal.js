document.addEventListener("DOMContentLoaded", () => {
  // Open modal and populate fields
  document.querySelectorAll(".conclude-appointment").forEach((button) => {
    button.addEventListener("click", () => {
      const appointmentId = button.getAttribute("data-conclude-id");
      fetch(`get_patient_details.php?id=${appointmentId}`)
        .then((response) => response.json())
        .then((data) => {
          document.getElementById(
            "conclusionPatientName"
          ).value = `${data.firstname} ${data.middle_initial}. ${data.lastname}`;
          document.getElementById("conclusionAge").value = data.age || "";
          document.getElementById("conclusionSex").value = data.sex || "";
          document.getElementById("conclusionAddress").value =
            data.address || "";
          document.getElementById("conclusionCivilStatus").value =
            data.civil_status || "";
          document.getElementById("conclusionPhone").value = data.phone || "";
          document.getElementById("conclusionStatus").value = data.status || "";
          // Date and Day
          if (data.date) {
            const appointmentDate = new Date(data.date);
            document.getElementById("conclusionDay").value =
              appointmentDate.toLocaleDateString("en-US", { weekday: "long" });
            document.getElementById("conclusionDate").value =
              appointmentDate.toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
              });
          } else {
            document.getElementById("conclusionDay").value = "";
            document.getElementById("conclusionDate").value = "";
          }
          document.getElementById("conclusionTime").value =
            data.time_slot || "";
          document.getElementById("conclusionCheckupType").value =
            data.checkup_type || "";
          document.getElementById("conclusionLabType").value =
            data.lab_type || "";
          document.getElementById("conclusionRemarks").value =
            data.remarks || "";
          document
            .getElementById("conclusionForm")
            .setAttribute("data-appointment-id", appointmentId);
          const modal = new bootstrap.Modal(
            document.getElementById("conclusionModal")
          );
          modal.show();
        });
    });
  });

  // Handle form submission
  document
    .getElementById("conclusionForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const form = e.target;
      const appointmentId = form.getAttribute("data-appointment-id");
      const formData = new FormData(form);
      formData.append("id", appointmentId);
      formData.append("makeConclusion", "1");

      fetch("conclude_appointment.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Appointment concluded successfully!");
            location.reload();
          } else {
            alert("Error: " + data.message);
          }
        });
    });
});
