<?php
// Establish MySQLi connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "is_run";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get queue count for a specific admin
function getQueueCountForAdmin($conn, $adminName) {
    $sqlCountQueue = "SELECT COUNT(*) AS queue_count FROM student_info WHERE chosen_admin = ?";
    $stmtCountQueue = $conn->prepare($sqlCountQueue);
    $stmtCountQueue->bind_param("s", $adminName);
    $stmtCountQueue->execute();
    $resultCountQueue = $stmtCountQueue->get_result();
    $rowCountQueue = $resultCountQueue->fetch_assoc();
    $queueCount = $rowCountQueue['queue_count'];
    $stmtCountQueue->close();
    return $queueCount;
}
?>
