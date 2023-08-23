<?php
session_start(); // Start the PHP session to use session variables for admin information
include 'connect.php'; // Include the connect.php file to establish the database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID'])) {
    // If not logged in, redirect to the login page
    header('Location: adminpagge.php');
    exit;
}

// Check if the form was submitted for updating the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Get the entered admin details from the form
    $adminID = $_SESSION['admin_ID'];
    $adminEmail = $_POST['admin_email'];
    $adminName = $_POST['admin_name'];
    $adminStatus = $_POST['admin_status'];
    $unavailabilityDescription = $_POST['unavailability_description'];

    // Escape the input data to prevent SQL injection
    $adminEmail = mysqli_real_escape_string($conn, $adminEmail);
    $adminName = mysqli_real_escape_string($conn, $adminName);
    $adminStatus = mysqli_real_escape_string($conn, $adminStatus);
    $unavailabilityDescription = mysqli_real_escape_string($conn, $unavailabilityDescription);

    // Perform an update query to save the edited details to the database
    $updateQuery = "UPDATE admin_info SET admin_email = '$adminEmail', admin_name = '$adminName', admin_status = '$adminStatus', unavailability_description = '$unavailabilityDescription' WHERE admin_ID = '$adminID'";
    $updateResult = mysqli_query($conn, $updateQuery);

    // Check if the update was successful
    if ($updateResult) {
        // Update successful, display a success message or redirect to the profile page with updated details
        // You can use header('Location: adminprofile.php') here if you want to redirect to the profile page after update.
        echo "Profile updated successfully.";
    } else {
        // Update failed, display an error message
        echo "Failed to update profile. Please try again.";
    }
}

// Fetch admin details from the database
$adminID = $_SESSION['admin_ID'];
$query = "SELECT * FROM admin_info WHERE admin_ID = '$adminID'";
$result = mysqli_query($conn, $query);
$adminData = mysqli_fetch_assoc($result);

// Check if adminData is not empty (admin exists)
if (!$adminData) {
    // If admin does not exist in the database, handle it as needed (e.g., redirect back to login).
    header('Location: adminpagge.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">

    <link rel="stylesheet" type="text/css" href="adminprofile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="navbar">
<h1 style="font-size:30px; margin-left:30px; font-family:Cinzel; font-weight:700; ">STRATHMORE QUEUEING SYSTEM</h1>
    <a style="font-family:Playfair Display;" href="adminprofile.php">Profile</a>
    <a style="font-family:Playfair Display;" href="tickets.php">Tickets</a>
    <a style="font-family:Playfair Display;" href="logout.php">Logout</a>
    <!-- Add more navigation links as needed -->
</div>

<div class="profile-container">
    <h2>Admin Profile</h2>
    <form method="post" action="adminprofile.php">
        <div class="form-group">
            <label for="admin_ID">Admin ID</label>
            <input type="text" class="form-control" id="admin_ID" value="<?php echo $adminData['admin_ID']; ?>" disabled>
        </div>
        <div class="form-group">
            <label for="admin_email">Email Address</label>
            <input type="email" class="form-control" id="admin_email" name="admin_email" value="<?php echo $adminData['admin_email']; ?>">
        </div>
        <div class="form-group">
            <label for="admin_name">Admin Name</label>
            <input type="text" class="form-control" id="admin_name" name="admin_name" value="<?php echo $adminData['admin_name']; ?>">
        </div>
        <div class="form-group">
            <label for="admin_status">Admin Status</label>
            <select class="form-control" id="admin_status" name="admin_status">
                <option value="available" <?php if ($adminData['admin_status'] === 'available') echo 'selected'; ?>>Available</option>
                <option value="set away" <?php if ($adminData['admin_status'] === 'set away') echo 'selected'; ?>>Set Away</option>
            </select>
        </div>
        <div class="form-group">
            <label for="unavailability_description">Unavailability Description</label>
            <textarea class="form-control" id="unavailability_description" name="unavailability_description"><?php echo $adminData['unavailability_description']; ?></textarea>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
