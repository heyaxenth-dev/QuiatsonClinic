document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".view-appointment").forEach((button) => {
    button.addEventListener("click", () => {
      const appointmentId = button.getAttribute("data-id");

      fetch(`get_patient_details.php?id=${appointmentId}`)
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          // Set basic information
          document.getElementById("viewSeverity").value = data.severity || "";
          document
            .getElementById("viewSeverity")
            .classList.add(
              data.severity == "Urgent" ? "text-danger" : "text-primary"
            );
          document.getElementById("viewId").value = data.id || "";
          document.getElementById("viewLastname").value = data.lastname || "";
          document.getElementById("viewFirstname").value = data.firstname || "";
          document.getElementById("viewMiddle_initial").value =
            data.middle_initial || "";
          document.getElementById("viewAddress").value = data.address || "";
          document.getElementById("viewAge").value = data.age || "";
          document.getElementById("viewSex").value = data.sex || "";

          // Format birthdate
          if (data.birthdate) {
            const birthdate = new Date(data.birthdate);
            const options = {
              year: "numeric",
              month: "long",
              day: "numeric",
              timeZone: "Asia/Manila",
            };
            document.getElementById("viewBirthdate").value =
              birthdate.toLocaleDateString("en-US", options);
          }

          document.getElementById("viewCivil_status").value =
            data.civil_status || "";
          document.getElementById("viewPhone").value = data.phone || "";
          document.getElementById("viewWeight").value = data.weight || "";
          document.getElementById("viewHeight").value = data.height || "";
          document.getElementById("viewBloodtype").value = data.bloodtype || "";

          // Format appointment date
          if (data.date) {
            const appointmentDate = new Date(data.date);
            const options = {
              year: "numeric",
              month: "long",
              day: "numeric",
              timeZone: "Asia/Manila",
            };
            document.getElementById("viewDate").value =
              appointmentDate.toLocaleDateString("en-US", options);
          }

          document.getElementById("viewTime_slot").value = data.time_slot || "";
          document.getElementById("viewSymptoms").value = data.symptoms || "";

          // Show the modal using getOrCreateInstance for accessibility
          const viewModal = document.getElementById("viewDetails");
          const modalInstance = bootstrap.Modal.getOrCreateInstance(viewModal);
          modalInstance.show();
        })
        .catch((error) =>
          console.error("Error fetching patient details:", error)
        );
    });
  });
});
