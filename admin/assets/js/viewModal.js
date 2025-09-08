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

          // Format appointment date and compute day of week
          if (data.date) {
            const appointmentDate = new Date(data.date);
            const dateOptions = {
              year: "numeric",
              month: "long",
              day: "numeric",
              timeZone: "Asia/Manila",
            };
            const dayOptions = { weekday: "long", timeZone: "Asia/Manila" };
            const viewDateEl = document.getElementById("viewDate");
            const viewDayEl = document.getElementById("viewDay");
            if (viewDateEl) {
              viewDateEl.value = appointmentDate.toLocaleDateString(
                "en-US",
                dateOptions
              );
            }
            if (viewDayEl) {
              viewDayEl.value = appointmentDate.toLocaleDateString(
                "en-US",
                dayOptions
              );
            }
          }

          document.getElementById("viewTime_slot").value = data.time_slot || "";
          document.getElementById("viewSymptoms").value = data.symptoms || "";
          const remarksEl = document.getElementById("viewRemarks");
          if (remarksEl) {
            remarksEl.value = data.remarks || "";
          }
          const labTypeEl = document.getElementById("viewLabType");
          if (labTypeEl) {
            labTypeEl.value = "Laboratory Check up";
          }

          // Toggle View ID button
          const btnViewUploadedId =
            document.getElementById("btnViewUploadedId");
          if (btnViewUploadedId) {
            if (data.patient_type === "senior_pwd" && data.uploaded_id) {
              btnViewUploadedId.classList.remove("d-none");
              btnViewUploadedId.setAttribute("data-image", data.uploaded_id);
            } else {
              btnViewUploadedId.classList.add("d-none");
              btnViewUploadedId.removeAttribute("data-image");
            }
          }

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
