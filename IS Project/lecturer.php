<?php
session_start();
include 'connect.php';

$_SESSION['user_role'] = 'lecturer';
function getAdminData($conn) {
    $sql = "SELECT admin_name, admin_status FROM admin_info";
    $result = $conn->query($sql);

    $adminData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $adminData[$row['admin_name']] = $row['admin_status'];
        }
    }

    return $adminData;
}

date_default_timezone_set('Africa/Nairobi');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lecturerID = $_POST['lecturer_id'];
    $lecturerEmail = $_POST['lecturer_email'];
    $privilegekey = $_POST['privilege_key'];
    $visitReason = $_POST['visit_reason'];
    $issueDescription = $_POST['issue_description'];
    $chosenAdmin = $_POST['chosen_admin'];

    $errors = array();

    // Form validation
    if (empty($lecturerID)) {
        $errors['lecturer_id'] = "Lecturer ID is required";
    }

    if (empty($lecturerEmail)) {
        $errors['lecturer_email'] = "Email Address is required";
    } elseif (!filter_var($lecturerEmail, FILTER_VALIDATE_EMAIL)) {
        $errors['lecturer_email'] = "Invalid email format";
    }

    if (empty($privilegekey)) {
        $errors['privilege_key'] = "Privilege Key is required";
    }

    if (empty($visitReason)) {
        $errors['visit_reason'] = "Reason of Visit is required";
    }

    if (empty($chosenAdmin)) {
        $errors['chosen_admin'] = "Admin Selection is required";
    }

    // If there are no errors, proceed with data insertion
    if (empty($errors)) {

        // Create a new DateTime object representing the current date and time in 'Africa/Nairobi' time zone
        $dateTimeObj = new DateTime('now', new DateTimeZone('Africa/Nairobi'));
        // Format the DateTime object as a string in the desired format (Y-m-d H:i:s)
        $currentDateTime = $dateTimeObj->format('Y-m-d H:i:s');

        $sql = "INSERT INTO lecturer_info (lecturer_id, lecturer_email, privilege_key, visit_reason, issue_description, chosen_admin, time_generated)
                VALUES ('$lecturerID', '$lecturerEmail', '$privilegekey', '$visitReason', '$issueDescription', '$chosenAdmin', '$currentDateTime')";
        }

        if ($conn->query($sql) === TRUE) {
             header("Location: lecturerticket.php");
            exit(); // Terminate script to ensure a clean redirect
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="style_entry.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
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
        <label for="exampleInputLecturerID"><br><div class="form-txt">Lecturer ID</div></label>
        <input type="text" class="form-control" id="exampleInputLecturerID" placeholder="Enter Lecturer ID" name="lecturer_id">
    </div>
    
    <div class="form-group">
        <label for="exampleInputEmail1"><br><div class="form-txt">Email Address</div></label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email" name="lecturer_email">
        <small id="emailHelp" class="form-text text-muted"><br><div class="form-txt">We'll never share your email with anyone else.</div></small>
    </div>
  
    <div class="form-group">
        <label for="exampleInputPrivilegeKey"><br><div class="form-txt">Privilege Key</div></label>
        <input type="text" class="form-control" id="exampleInputPrivilegeKey" placeholder="Enter Privilege Key" name="privilege_key">
    </div>

    <br>
    <h1>Lecturer Issue Selection</h1>
    <br>

    <div class="form-group">
        <label for="studentIssue"><br>
            <div class="form-txt">Select Reason of Visit:</div>
        </label>
        <select class="form-control" id="lecIssue" name="visit_reason">
            <option value="Printing docs">Printing docs</option>
            <option value="CAT Schedule">CAT Schedule</option>
            <option value="Discipline Case">Discipline Case</option>
            <option value="Class Inquiry">Class Inquiry</option>
            <option value="Other">Other</option>
        </select>  

        <br>
        <label for="issueDescription">
            <div class="form-txt">Issue Description:</div>
        </label>
        <textarea class="form-control" id="issue_Description" placeholder="Enter a short description" name="issue_description"></textarea>
    </div>

    <h1>Admin Selection</h1>
    <div class="form-group">
        <label for="peopleSelection"><br>
            <div class="form-txt">Select Admin:</div>
        </label>
        <select class="form-control" id="peopleSelection" name="chosen_admin">
            <?php
            $adminData = getAdminData($conn);
            foreach ($adminData as $adminName => $adminStatus) {
                echo '<option value="' . $adminName . '">' . $adminName . '. Status: ' . $adminStatus . '</option>';
            }
            ?>
        </select>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="error-msg">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</body>
</html>
