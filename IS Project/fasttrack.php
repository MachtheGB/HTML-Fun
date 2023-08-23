<?php
session_start();
include 'connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['admin_email']) || !isset($_SESSION['admin_name'])) {
    // Redirect to the admin login page if not logged in
    header("Location: adminlogin.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lecturerID = $_POST['lecturer_id'];

    // Validate lecturer ID (you can add additional validation if needed)
    if (empty($lecturerID)) {
        // Handle the error and redirect back to the admin queue page
        $_SESSION['fasttrack_error'] = "Lecturer ID is required";
        header("Location: adminqueue.php");
        exit();
    }

    // Create a new DateTime object representing the current date and time in 'Africa/Nairobi' time zone
    $dateTimeObj = new DateTime('now', new DateTimeZone('Africa/Nairobi'));
    // Format the DateTime object as a string in the desired format (Y-m-d H:i:s)
    $currentDateTime = $dateTimeObj->format('Y-m-d H:i:s');

    // Update the timestamp of the selected lecturer form
    $sql = "UPDATE lecturer_info SET time_generated = '$currentDateTime' WHERE lecturer_id = '$lecturerID'";
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the admin queue page after successful fast-tracking
        header("Location: adminqueue.php");
        exit();
    } else {
        // Handle the error and redirect back to the admin queue page
        $_SESSION['fasttrack_error'] = "Error updating the form timestamp: " . $conn->error;
        header("Location: adminqueue.php");
        exit();
    }
} else {
    // If the form was not submitted, redirect back to the admin queue page
    header("Location: adminqueue.php");
    exit();
}
?>
