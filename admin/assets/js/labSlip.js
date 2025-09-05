document.addEventListener("DOMContentLoaded", function () {
  const modalEl = document.getElementById("labSlipModal");
  if (!modalEl) return;

  const modal = new bootstrap.Modal(modalEl);
  const printBtn = document.getElementById("btn-print-lab-slip");

  function setText(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value ?? "-";
  }

  // Open handler from any trigger button with data attributes
  document.body.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-lab-slip");
    if (!btn) return;

    // Populate header
    setText(
      "ls-labno",
      btn.getAttribute("data-labno") || btn.getAttribute("data-id") || "-"
    );
    setText(
      "ls-date",
      btn.getAttribute("data-date") || new Date().toLocaleDateString()
    );

    // Patient details
    setText("ls-name", btn.getAttribute("data-name"));
    setText("ls-gender", btn.getAttribute("data-gender"));
    setText("ls-age", btn.getAttribute("data-age"));
    setText("ls-civil", btn.getAttribute("data-civil"));
    setText("ls-address", btn.getAttribute("data-address"));
    setText("ls-email", btn.getAttribute("data-email"));

    // Reset all checkboxes on open
    document
      .querySelectorAll("#labSlipModal input.ls-test")
      .forEach((cb) => (cb.checked = false));

    modal.show();
  });

  // Print
  if (printBtn) {
    printBtn.addEventListener("click", function () {
      const selected = Array.from(
        document.querySelectorAll("#labSlipModal input.ls-test:checked")
      );
      if (selected.length === 0) {
        // Require at least one test
        if (window.Swal) {
          Swal.fire({
            icon: "warning",
            title: "Select tests",
            text: "Please select at least one laboratory test before printing.",
          });
        } else {
          alert("Please select at least one laboratory test before printing.");
        }
        return;
      }

      // Create a print window with the slip content only
      const content = document.getElementById("lab-slip-content");
      if (!content) return;

      const printWindow = window.open("", "_blank");
      if (!printWindow) return;

      const styles = `
                <style>
                    body { font-family: Arial, Helvetica, sans-serif; }
                    .fw-bold { font-weight: 700; }
                    .text-center { text-align: center; }
                    .row { display: flex; flex-wrap: wrap; }
                    .col-md-6 { width: 50%; box-sizing: border-box; }
                    .col-md-3 { width: 25%; box-sizing: border-box; }
                    .mb-2 { margin-bottom: 8px; }
                    .mb-3 { margin-bottom: 12px; }
                    .mt-3 { margin-top: 12px; }
                </style>
            `;

      printWindow.document.write(
        "<html><head><title>Laboratory Request Slip</title>" +
          styles +
          "</head><body>" +
          content.outerHTML +
          "</body></html>"
      );
      printWindow.document.close();
      printWindow.focus();
      printWindow.print();
    });
  }
});
