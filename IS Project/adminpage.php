<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminID = $_POST['admin_ID'];
    $adminEmail = $_POST['admin_email'];
    $adminName = $_POST['admin_name'];
    $adminStatus = $_POST['admin_status'];
   $unavailabiltyDescription = $_POST['unavailability_description'];


    $errors = array();

    // Form validation
    if (empty($adminID)) {
        $errors['admin_ID'] = "Admin ID is required";
    }

    if (empty($adminEmail)) {
        $errors['admin_email'] = "Email Address is required";
    } elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $errors['admin_email'] = "Invalid email format";
    }

    if (empty($adminName)) {
      $errors['admin_name'] = "Name is required";
  }

    if (empty($adminStatus)) {
        $errors['admin_status'] = "Status is required";
    }

  
    // If there are no errors, proceed with data insertion
    if (empty($errors)) {


        // SQL query to insert data into the "student_info" table
        $sql = "INSERT INTO admin_info (admin_ID, admin_email, admin_name, admin_status, unavailability_description)
                VALUES ('$adminID', '$adminEmail', '$adminName', '$adminStatus', ' $unavailabiltyDescription')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Form data submitted successfully!</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
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
    <link rel="stylesheet" type="text/css" href="style_entry.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">
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

    <form method="post">

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

  
<div class="form-group">
            <label for="adminStatus"><br>
                <div class="form-txt">Choose Status:</div>
            </label>
            <select class="form-control" id="adminstatus" name="admin_status">
                <option value="available">Available</option>
                <option value="set away">Set Away</option>
            </select> 
            <br></br>
            <div id="unavailabilityContainer" style="display: none;">
            <div class="form-txt">Reason for unavailability:</div>
            </label>
            <textarea class="form-control" id="unavailablity_Description" placeholder="Enter a short description" name="unavailability_description"></textarea>
        </div>
</div>


  <button type="submit" class="btn btn-primary">Submit</button>  

</form> 
<script src="adminpage.js"></script>
</body>
</html>