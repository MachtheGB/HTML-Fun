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
    $formID = $_POST['form_id'];
    $formType = $_POST['form_type'];

    // Validate the form ID (you can add additional validation if needed)
    if (empty($formID)) {
        // Handle the error and redirect back to the admin queue page
        $_SESSION['delete_error'] = "Form ID is required";
        header("Location: adminqueue.php");
        exit();
    }

    // Determine the table name based on the form type
    $tableName = '';
    switch ($formType) {
        case 'external guest':
            $tableName = 'externalg_info';
            break;
        case 'student':
            $tableName = 'student_info';
            break;
        case 'lecturer':
            $tableName = 'lecturer_info';
            break;
        default:
            // Handle the error and redirect back to the admin queue page
            $_SESSION['delete_error'] = "Invalid form type";
            header("Location: adminqueue.php");
            exit();
    }

    // Delete the form from the appropriate table
    $sql = "DELETE FROM $tableName WHERE $formType" . "_id = '$formID'";
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the admin queue page after successful deletion
        header("Location: adminqueue.php");
        exit();
    } else {
        // Handle the error and redirect back to the admin queue page
        $_SESSION['delete_error'] = "Error deleting the form: " . $conn->error;
        header("Location: adminqueue.php");
        exit();
    }
} else {
    // If the form was not submitted, redirect back to the admin queue page
    header("Location: adminqueue.php");
    exit();
}
?>
