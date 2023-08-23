<?php
// Assuming you have already included 'connect.php' that establishes the database connection
include 'connect.php';
include 'queue_functions.php';



// Check if form data is submitted and received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $studentID = $_POST['student_ID'];
    $studentEmail = $_POST['student_Email'];
    $visitReason = $_POST['visit_reason'];
    $issueDescription = $_POST['issue_description'];
    $chosenAdmin = $_POST['chosen_admin'];

    // Save the data to the database or perform any other required actions
    // ... (your code to insert the data into the database)
    $currentDateTime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO student_info (student_ID, student_Email, visit_reason, issue_description, chosen_admin, time_generated)
            VALUES ('$studentID', '$studentEmail', '$visitReason', '$issueDescription', '$chosenAdmin', '$currentDateTime')";
    // Redirect to the ticket page with a success message or perform other actions if needed
    // For simplicity, we'll set default values for the $sampleData
    $sampleData = [
        'student_ID' => $studentID,
        'student_Email' => $studentEmail,
        'visit_reason' => $visitReason,
        'issue_description' => $issueDescription,
        'chosen_admin' => $chosenAdmin,
        'time_generated' => $currentDateTime,
    ];

    // Set the student ID in the session
    session_start();
    $_SESSION['student_ID'] = $studentID;

} else {
    // If the form data is not submitted, retrieve data from the database
    // Fetch data from the "student_info" table
    $sql = "SELECT * FROM student_info ORDER BY time_generated DESC LIMIT 1";
    $result = $conn->query($sql);

    // Check if there are any records in the table
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sampleData = [
            'student_ID' => $row['student_ID'],
            'student_Email' => $row['student_Email'],
            'visit_reason' => $row['visit_reason'],
            'issue_description' => $row['issue_description'],
            'chosen_admin' => $row['chosen_admin'],
            'time_generated' => $row['time_generated'],
        ];

    } else {
        // Set default values when no data is available in the database
        $sampleData = [
            'student_ID' => 'Not Available',
            'student_Email' => 'Not Available',
            'visit_reason' => 'Not Available',
            'issue_description' => 'Not Available',
            'chosen_admin' => 'Not Available',
            'time_generated' => 'Not Available',
        ];
    }
}

// Close the database connection
//$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
    <link rel="stylesheet" type="text/css" href="student_Ticket_style.css">
    <title>Ticket Page</title>
    <style></style>
</head>
<body>
<div class="navbar">
    <h1 style="font-size:30px; margin-left:30px; font-family:Cinzel; font-weight:700; ">STRATHMORE QUEUEING SYSTEM</h1>
        <a style="font-family:Playfair Display;" href="homepage.html">Home</a>
        <a style="font-family:Playfair Display;" href="about.html">About</a>
        <a style="font-family:Playfair Display;" href="contact.html">Contact</a>
        <!-- Add more navigation links as needed -->
    </div>
    <br></br>
  <br></br>  
  <br></br>


    <!-- The rest of the ticket page HTML remains unchanged -->
    <div class="Details">
    <table>
        <tr>
            <th>Details</th>
            <th style="width:1700px;">Information</th>
        </tr>
        <tr>
            <td>Student ID</td>
            <td style="width: 1700px;"><?php echo $sampleData['student_ID']; ?></td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td style="width: 1700px;"><?php echo $sampleData['student_Email']; ?></td>
        </tr>
        <tr>
            <td>Reason of Visit</td>
            <td style="width: 1700px;"><?php echo $sampleData['visit_reason']; ?></td>
        </tr>
        <tr>
            <td>Issue Description</td>
            <td style="width: 1700px;"><?php echo $sampleData['issue_description']; ?></td>
        </tr>
        <tr>
            <td>Admin selected</td>
            <td style="width: 1700px;"><?php echo $sampleData['chosen_admin']; ?></td>
        </tr>
        <tr>
            <td>Time and Date Generated</td>
            <td style="width: 1700px;"><?php date_default_timezone_set('UTC');
    date_default_timezone_set('Africa/Nairobi');
    echo (new DateTime())->format('Y-m-d H:i'); ?></td>
        </tr>  
        
        <div class="container">
    
    <table class="table">
        <br>
        <br>
        <thead>
            <tr>
                <th>Administrator</th>
                <th>Number of People Ahead</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $adminNames = ['Macharia', 'Danson Mulinge', 'Charncidoe Shikoli', 'Jack Kitsao', 'Patience Mwangi', 'Mark Mwendwa'];
            foreach ($adminNames as $adminName) {
                $queueCount = getQueueCountForAdmin($conn, $adminName);
                echo '<tr>';
                echo '<td>' . $adminName . '</td>';
                echo '<td>' . $queueCount . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
    </table>

</body>
</html>
