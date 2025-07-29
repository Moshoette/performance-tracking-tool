<?php
session_start();

require_once('vendor/autoload.php');                                     // Ensure to include the autoload file from Composer
include('dbcon.php');

$user = null; // Initialize $user to handle cases where it's not set

if (isset($_SESSION['auth_user']['id'])) {
    $userId = $_SESSION['auth_user']['name'];
    $userId = $_SESSION['auth_user']['phone'];
    $userId = $_SESSION['auth_user']['email'];
    error_log("User ID: " . $userId); // Debugging line

    $userSql = "SELECT name, phone, email FROM users WHERE id = ?";
    $stmt = $con->prepare($userSql);
    $stmt->bind_param("i", $userId);                       // Assuming user ID is an integer

    if ($stmt->execute()) {
        $userResult = $stmt->get_result();
        $user = $userResult->fetch_assoc();
        error_log("User data: " . print_r($user, true)); // Debugging line
    } else {
        error_log("Query failed: " . $stmt->error);
    }
} else {
    error_log("Session variable not set.");
}

// Create new PDF document
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Performance Data', 0, 1, 'C');

// Include user information
if ($user) {
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Name: ' . $user['name'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $user['email'], 0, 1);
    $pdf->Cell(0, 10, 'Phone: ' . $user['phone'], 0, 1);
    $pdf->Ln(10); // Add some space before the performance data
} else {
    error_log("No user data found.");
}

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 10, 'Sales', 1);
$pdf->Cell(40, 10, 'Profit', 1);
$pdf->Cell(40, 10, 'New Clients', 1);
$pdf->Cell(60, 10, 'Date', 1);
$pdf->Ln();

$sql = "SELECT sales, profit, new_clients, created_at FROM performance_data";
$result = $con->query($sql);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(40, 10, $row['sales'], 1);
    $pdf->Cell(40, 10, $row['profit'], 1);
    $pdf->Cell(40, 10, $row['new_clients'], 1);
    $pdf->Cell(60, 10, $row['created_at'], 1);
    $pdf->Ln();
}

$pdf->Output('performance_data.pdf', 'D');                    // 'D' for download
exit();
?>
