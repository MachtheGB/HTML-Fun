<?php
session_start();
include 'connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['admin_email']) || !isset($_SESSION['admin_name'])) {
    // Redirect to the admin login page if not logged in
    header("Location: adminlogin.php");
    exit();
}

// Function to get all forms submitted on a specific date and chosen by the current admin
function getAdminFormsByDate($conn, $date, $adminName) {
    $sql = "SELECT * FROM (
        SELECT student_ID, student_Email, visit_reason, issue_description, chosen_admin, time_generated, NULL AS lecturer_id, NULL AS lecturer_email, NULL AS privilege_key, NULL AS eg_email, NULL AS national_ID, 'student' AS form_type FROM student_info
        UNION ALL
        SELECT NULL AS student_ID, NULL AS student_Email, visit_reason, issue_description, chosen_admin, time_generated, lecturer_id, lecturer_email, privilege_key, NULL AS eg_email, NULL AS national_ID, 'lecturer' AS form_type FROM lecturer_info
        UNION ALL
        SELECT NULL AS student_ID, NULL AS student_Email, visit_reason, issue_description, chosen_admin, time_generated, NULL AS lecturer_id, NULL AS lecturer_email, NULL AS privilege_key, eg_email, national_ID, 'external guest' AS form_type FROM externalg_info
    ) AS all_forms
    WHERE DATE(time_generated) = '$date' AND chosen_admin = '$adminName'
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

// Get the current admin's name from the session
$adminName = $_SESSION['admin_name'];

// Get forms submitted today and chosen by the current admin
$adminFormsToday = getAdminFormsByDate($conn, $today, $adminName);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Queue for Today</title>
    <link rel="stylesheet" type="text/css" href="style_adminqueue.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">

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

<div class="container">
    <h1>Admin Queue for Today (<?php echo $today; ?>) - <?php echo $adminName; ?></h1>
    <ul>
        <?php $counter = 1; // Initialize the counter ?>
        <?php foreach ($adminFormsToday as $form): ?>
            <li>
                <?php echo $counter++; ?>. <!-- Increment the counter and display the auto-incremental number -->
                <?php echo $form['form_type']; ?> -
                <?php echo $form['time_generated']; ?> -
                <?php echo $form['visit_reason']; ?> -
                <?php echo $form['chosen_admin']; ?> -
                <?php echo $form['issue_description']; ?>
                <form method="post" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this form?');">
                    <?php
                    // Determine the correct ID field based on the form type
                    $formIDField = '';
                    switch ($form['form_type']) {
                        case 'external guest':
                            $formIDField = 'national_ID';
                            break;
                        case 'student':
                            $formIDField = 'student_ID';
                            break;
                        case 'lecturer':
                            $formIDField = 'lecturer_id';
                            break;
                    }
                    ?>
                    <input type="hidden" name="form_id" value="<?php echo $form[$formIDField]; ?>">
                    <input type="hidden" name="form_type" value="<?php echo $form['form_type']; ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
  
    <a href="adminreport.php" class="btn btn-primary">View Admin Report</a>
</div>

</body>
</html>
