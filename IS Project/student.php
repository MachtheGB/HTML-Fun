<?php
session_start();
include 'connect.php';
$_SESSION['user_role'] = 'student';
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
    $studentID = $_POST['student_ID'];
    $studentEmail = $_POST['student_Email'];
    $visitReason = $_POST['visit_reason'];
    $issueDescription = $_POST['issue_description'];
    $chosenAdmin = $_POST['chosen_admin'];

    $errors = array();

    // Form validation
    if (empty($studentID)) {
        $errors['student_ID'] = "Student ID is required";
    }

    if (empty($studentEmail)) {
        $errors['student_Email'] = "Email Address is required";
    } elseif (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {
        $errors['student_Email'] = "Invalid email format";
    }

    if (empty($visitReason)) {
        $errors['visit_reason'] = "Reason of Visit is required";
    }

    if (empty($chosenAdmin)) {
        $errors['chosen_admin'] = "Admin Selection is required";
    }

    // If there are no errors, proceed with data insertion
    if (empty($errors)) {
              
        $currentDateTime = date("Y-m-d H:i:s");

        // Prepare the SQL statement with placeholders
$sql = "INSERT INTO student_info (student_ID, student_Email, visit_reason, issue_description, chosen_admin, time_generated)
VALUES (?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters to the placeholders
$stmt->bind_param("ssssss", $studentID, $studentEmail, $visitReason, $issueDescription, $chosenAdmin, $currentDateTime);

// Execute the statement
if ($stmt->execute()) {
// Redirect to the ticket page after successful form submission
header("Location: studentticket.php");
exit(); // Terminate script to ensure a clean redirect
} else {
echo "<p>Error: " . $stmt->error . "</p>";
}

// Close the statement
$stmt->close();

        if ($conn->query($sql) === TRUE) {
             // Redirect to the ticket page after successful form submission
             header("Location: studentticket.php");
             exit(); // Terminate script to ensure a clean redirect
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">
    <link rel="stylesheet" type="text/css" href="style_entry.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="navbar">
    <h1 style="font-size:30px; margin-left:30px; font-family:Cinzel; font-weight:700; ">STRATHMORE QUEUEING SYSTEM</h1>
        <a style="font-family:Playfair Display;" href="homepage.html">Home</a>
        <a style="font-family:Playfair Display;" href="about.html">About</a>
        <a style="font-family:Playfair Display;" href="contact.html">Contact</a>
        <!-- Add more navigation links as needed -->
    </div>

    <form method="post">
        <!-- Form elements here -->
        <div class="form-group">
            <label for="exampleInputStudentID"><br>
                <div class="form-txt">Student ID</div>
            </label>
            <input type="text" class="form-control" id="exampleInputLecturerID" placeholder="Enter Student ID" name="student_ID">
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1"><br>
                <div class="form-txt">Email Address</div>
            </label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email" name="student_Email">
            <small id="emailHelp" class="form-text text-muted"><br>
                <div class="form-txt">We'll never share your email with anyone else.</div>
            </small>
        </div>

        <br>
        <h1>Student Issue Selection</h1>
        <br>
        <div class="form-group">
            <label for="studentIssue"><br>
                <div class="form-txt">Select Reason of Visit:</div>
            </label>
            <select class="form-control" id="studentIssue" name="visit_reason">
                <option value="Lost ID">Lost ID</option>
                <option value="Fees Inquiry">Fees Inquiry</option>
                <option value="Timetable">Timetable</option>
                <option value="Discipline Case">Discipline Case</option>
                <option value="Class Inquiry">Class Inquiry</option>
                <option value="Unit Registration">Unit Registration</option>
                <option value="ID Collection">ID Collection</option>
                <option value="Exam Card">Exam Card</option>
                <option value="Other">Other</option>
            </select>
            <br>
            <label for="issueDescription">
                <div class="form-txt">Issue Description:</div>
            </label>
            <textarea class="form-control" id="issue_Description" placeholder="Enter a short description" name="issue_description"></textarea>
        </div>

        <br>
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
        <!-- Display validation errors -->
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




         





                                       
