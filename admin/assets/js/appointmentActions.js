document.addEventListener("DOMContentLoaded", () => {
  // Approve Appointment
  document.querySelectorAll(".approve-appointment").forEach((button) => {
    button.addEventListener("click", () => {
      const appointmentId = button.getAttribute("data-approve-id");

      Swal.fire({
        title: "Approve Appointment",
        text: "Are you sure you want to approve this appointment?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, approve it!",
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("update_appointment_status.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${appointmentId}&status=Approved`,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                Swal.fire({
                  title: "Approved!",
                  text: "Appointment has been approved successfully.",
                  icon: "success",
                  confirmButtonColor: "#3085d6",
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: data.message,
                  icon: "error",
                  confirmButtonColor: "#3085d6",
                });
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              Swal.fire({
                title: "Error!",
                text: "An error occurred while approving the appointment.",
                icon: "error",
                confirmButtonColor: "#3085d6",
              });
            });
        }
      });
    });
  });

  // Reschedule Appointment
  document.querySelectorAll(".reschedule-appointment").forEach((button) => {
    button.addEventListener("click", () => {
      const appointmentId = button.getAttribute("data-reschedule-id");

      Swal.fire({
        title: "Reschedule Appointment",
        html: `
          <div class="mb-3">
            <label for="newDate" class="form-label">New Date</label>
            <input type="date" id="newDate" class="form-control">
          </div>
          <div class="mb-3">
            <label for="newTime" class="form-label">New Time</label>
            <select id="newTime" class="form-select">
              <option value="">Select Time</option>
              <option value="8:30 AM - 9:30 AM">8:30 AM - 9:30 AM</option>
              <option value="9:30 AM - 10:30 AM">9:30 AM - 10:30 AM</option>
              <option value="10:30 AM - 11:30 AM">10:30 AM - 11:30 AM</option>
              <option value="11:30 AM - 12:30 PM">11:30 AM - 12:30 PM</option>
            </select>
          </div>
        `,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Reschedule",
        cancelButtonText: "Cancel",
        focusConfirm: false,
        preConfirm: () => {
          const newDate = document.getElementById("newDate").value;
          const newTime = document.getElementById("newTime").value;
          if (!newDate || !newTime) {
            Swal.showValidationMessage("Please fill in all fields");
            return false;
          }
          return { newDate, newTime };
        },
      }).then((result) => {
        if (result.isConfirmed) {
          const { newDate, newTime } = result.value;
          fetch("update_appointment_status.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${appointmentId}&status=Rescheduled&new_date=${newDate}&new_time=${newTime}`,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                Swal.fire({
                  title: "Rescheduled!",
                  text: "Appointment has been rescheduled successfully.",
                  icon: "success",
                  confirmButtonColor: "#3085d6",
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: data.message,
                  icon: "error",
                  confirmButtonColor: "#3085d6",
                });
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              Swal.fire({
                title: "Error!",
                text: "An error occurred while rescheduling the appointment.",
                icon: "error",
                confirmButtonColor: "#3085d6",
              });
            });
        }
      });
    });
  });

  // Cancel Appointment
  document.querySelectorAll(".cancel-appointment").forEach((button) => {
    button.addEventListener("click", () => {
      const appointmentId = button.getAttribute("data-cancel-id");

      Swal.fire({
        title: "Cancel Appointment",
        input: "textarea",
        inputLabel: "Reason for cancellation",
        inputPlaceholder: "Type your reason here...",
        inputAttributes: {
          "aria-label": "Type your reason here",
        },
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, cancel it!",
        cancelButtonText: "No, keep it",
        inputValidator: (value) => {
          if (!value) {
            return "You need to provide a reason!";
          }
        },
      }).then((result) => {
        if (result.isConfirmed) {
          fetch("update_appointment_status.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `id=${appointmentId}&status=Cancelled&reason=${encodeURIComponent(
              result.value
            )}`,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                Swal.fire({
                  title: "Cancelled!",
                  text: "Appointment has been cancelled successfully.",
                  icon: "success",
                  confirmButtonColor: "#3085d6",
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: data.message,
                  icon: "error",
                  confirmButtonColor: "#3085d6",
                });
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              Swal.fire({
                title: "Error!",
                text: "An error occurred while cancelling the appointment.",
                icon: "error",
                confirmButtonColor: "#3085d6",
              });
            });
        }
      });
    });
  });
});
