<?php
require_once '../vendor/autoload.php'; // adjust path if needed
include 'authentication.php';
checkLogin();
include '../database/conn.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Get filter
$filterMonthYear = isset($_GET['reportMonth']) ? $_GET['reportMonth'] : '';

// Build SQL (exclude Pending, Approved, and Cancelled)
$sql = "SELECT * FROM appointments WHERE status NOT IN ('Approved', 'Pending', 'Cancelled')";
if (!empty($filterMonthYear)) {
    $year = date('Y', strtotime($filterMonthYear));
    $month = date('m', strtotime($filterMonthYear));
    $sql .= " AND YEAR(created_at) = '$year' AND MONTH(created_at) = '$month'";
}
$result = mysqli_query($conn, $sql);

// Build HTML
$html = "
    <h2 style='text-align:center;'>Appointments Report</h2>
    <p style='text-align:center;'>Period: " . 
        (!empty($filterMonthYear) ? date('F Y', strtotime($filterMonthYear)) : "All Records") . 
    "</p>
    <table border='1' cellpadding='6' cellspacing='0' width='100%'>
        <thead>
            <tr style='background:#f2f2f2;'>
                <th>Name</th>
                <th>Address</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Civil Status</th>
                <th>Mobile Number</th>
            </tr>
        </thead>
        <tbody>";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $fullname = $row['firstname'] . " " . $row['middle_initial'] . ". " . $row['lastname'];
        $html .= "
            <tr>
                <td>{$fullname}</td>
                <td>{$row['address']}</td>
                <td>{$row['age']}</td>
                <td>{$row['sex']}</td>
                <td>{$row['civil_status']}</td>
                <td>{$row['phone']}</td>
            </tr>";
    }
} else {
    $html .= "<tr><td colspan='6' style='text-align:center;'>No records found.</td></tr>";
}

$html .= "
        </tbody>
    </table>
";

// Dompdf setup
$options = new Options();
$options->set('isRemoteEnabled', true); // allow external assets
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // A4 horizontal
$dompdf->render();

// Output PDF
$dompdf->stream("appointments_report.pdf", ["Attachment" => true]);
exit;