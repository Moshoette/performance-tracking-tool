<?php
include('authentication.php');
include('dbcon.php'); // Ensure you have a file to connect to the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sales = $_POST['sales'];
    $profit = $_POST['profit'];
    $new_clients = $_POST['new_clients'];

    $sql = "INSERT INTO performance_data (sales, profit, new_clients) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ddi", $sales, $profit, $new_clients);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Data saved successfully!";
    } else {
        $_SESSION['status'] = "Error saving data!";
    }

    header("Location: dashboard.php");
    exit();
}
?>
