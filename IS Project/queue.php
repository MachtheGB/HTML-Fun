<?php
session_start();
include 'connect.php';

// Function to get all forms submitted on a specific date
function getFormsByDate($conn, $date) {
    // SQL query remains the same
    $sql = "SELECT * FROM (
        SELECT student_ID, student_Email, visit_reason, issue_description, chosen_admin, time_generated, NULL AS lecturer_id, NULL AS lecturer_email, NULL AS privilege_key, NULL AS eg_email, NULL AS national_ID, NULL AS form_type FROM student_info
        UNION ALL
        SELECT NULL AS student_ID, NULL AS student_Email, visit_reason, issue_description, chosen_admin, time_generated, lecturer_id, lecturer_email, privilege_key, NULL AS eg_email, NULL AS national_ID, NULL AS form_type FROM lecturer_info
        UNION ALL
        SELECT NULL AS student_ID, NULL AS student_Email, visit_reason, issue_description, chosen_admin, time_generated, NULL AS lecturer_id, NULL AS lecturer_email, NULL AS privilege_key, eg_email, national_ID, 'external guest' AS form_type FROM externalg_info
    ) AS all_forms
    WHERE DATE(time_generated) = '$date'
    ORDER BY time_generated ASC";

    $result = $conn->query($sql);

    $formsData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $formsData[] = $row;
        }
    }

    return $formsData;
}

// Get the current date
$today = date("Y-m-d");

// Check if the user_role is set in the session
$userRole = $_SESSION['user_role'] ?? '';

// Get forms submitted today
$todayForms = getFormsByDate($conn, $today);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms Queue for Today</title>
    <link rel="stylesheet" type="text/css" href="style_entry.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <a href="homepage.html">Home</a>
        <a href="about.html">About</a>
        <a href="contact.html">Contact</a>
        <!-- Add more navigation links as needed -->
    </div>

    <div class="container">
        <h1>Forms Queue for Today (<?php echo $today; ?>)</h1>
        <ul>
            <?php $totalForms = count($todayForms); ?>

            <?php foreach ($todayForms as $index => $form): ?>
                <li>
                    <?php $formNumber = $index + 1; ?> <!-- Calculate the form number based on the index -->
                    <?php echo $formNumber; ?>. <!-- Display the calculated form number -->
                    <?php echo $form['form_type']; ?> - 
                    <?php echo $form['time_generated']; ?> - 
                    <?php echo $form['visit_reason']; ?> - 
                    <?php echo $form['chosen_admin']; ?> - 
                    <?php echo $form['issue_description']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <p>Number of people ahead: <?php echo $totalForms - 1; ?></p> <!-- Display the number of people ahead -->
    </div>
</body>
</html>
