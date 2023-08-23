<?php
session_start(); // Start the PHP session to use session variables for admin information
include 'connect.php'; // Include the connect.php file to establish the database connection

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the necessary form fields are set
    if (isset($_POST['admin_ID']) && isset($_POST['admin_email']) && isset($_POST['admin_name'])) {
        // Get the entered credentials from the form
        $adminID = $_POST['admin_ID'];
        $adminEmail = $_POST['admin_email'];
        $adminName = $_POST['admin_name'];

        // Escape the input data to prevent SQL injection
        $adminID = mysqli_real_escape_string($conn, $adminID);
        $adminEmail = mysqli_real_escape_string($conn, $adminEmail);
        $adminName = mysqli_real_escape_string($conn, $adminName);

        // Perform a query to verify the admin's credentials
        $query = "SELECT * FROM admin_info WHERE admin_ID = '$adminID' AND admin_email = '$adminEmail' AND admin_name = '$adminName'";
        $result = mysqli_query($conn, $query);

        // Check if the query was successful and if the admin exists
        if (mysqli_num_rows($result) === 1) {
            // Admin exists and credentials are correct
            // Store admin information in session variables
            $_SESSION['admin_ID'] = $adminID;
            $_SESSION['admin_email'] = $adminEmail;
            $_SESSION['admin_name'] = $adminName;
            
            // Redirect to the selection page using JavaScript
            echo <<<'HTML'
            <script>
            function redirect(page) {
                window.location.href = page;
            }
            </script>
            HTML;

            // Ask the admin where they want to go
            echo "<div class='blurry-background'>";
            echo "<div class='option-box'>";
            echo "<form method='post'>";
            echo "<div>Please select where you want to go:</div>";
            echo "<button type='button' onclick='redirect(\"adminqueue.php\")'>Queue</button>";
            echo "<button type='button' onclick='redirect(\"adminprofile.php\")'>Admin Profile</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        } else {
            // Admin does not exist or credentials are incorrect
            // You can display an error message or handle the case as needed
            echo "Invalid credentials. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">

    <link rel="stylesheet" type="text/css" href="adminlogin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="navbar">
<h1 style="font-size:30px; margin-left:30px; font-family:Cinzel; font-weight:700; ">STRATHMORE QUEUEING SYSTEM</h1>
  <a style="font-family:Playfair Display;" href="homepage.html">Home</a>
  <a style="font-family:Playfair Display;" href="about.html">About</a>
  <a style="font-family:Playfair Display;" href="contact.html">FAQs</a>
  <!-- Add more navigation links as needed -->
</div>

    <form method="post" action="adminpagge.php">

  <div class="form-group">
    <label for="exampleInputAdminID"><br><div class="form-txt">Admin ID</div></label>
    <input type="text" class="form-control" id="exampleInputLecturerID" placeholder="Enter Admin ID" name="admin_ID">
    
  <div class="form-group">
    <label for="exampleInputEmail1"><br><div class="form-txt">Email Address</div></label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email" name="admin_email">
    <small id="emailHelp" class="form-text text-muted"><br><div class="form-txt">We'll never share your email with anyone else.</div></small>
  </div>
  
  <div class="form-group">
    <label for="exampleInputAdminName"><br><div class="form-txt">Admin Name</div></label>
    <input type="text" class="form-control" id="exampleInputAdminName" placeholder="Enter Admin Name" name="admin_name">
  </div>

  <button type="submit" class="btn btn-primary">Log In</button>  

</form> 

</body>
</html>
