<?php
// Assuming you have already included 'connect.php' that establishes the database connection
include 'connect.php';

date_default_timezone_set('Africa/Nairobi');

// Get the current date in 'Y-m-d' format
$currentDate = date("Y-m-d");

// Fetch tickets for the current date from the database
$sql = "SELECT * FROM student_info WHERE DATE(time_generated) = '$currentDate'";
$result = $conn->query($sql);

$ticketsData = array();

if ($result->num_rows > 0) {
    // Loop through each row of the result set and store data in the array
    while ($row = $result->fetch_assoc()) {
        $ticketsData[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets for Today</title>
    <link rel="stylesheet" type="text/css" href="student_Ticket_style.css">
</head>
<body>
    <div class="navbar">
        <a href="homepage.html">Home</a>
        <a href="about.html">About</a>
        <a href="contact.html">Contact</a>
        <!-- Add more navigation links as needed -->
    </div>

    <h1>Tickets for Today</h1>
    <br>

    <?php if (!empty($ticketsData)): ?>
        <table>
            <tr>
                <th>Student ID</th>
                <th>Email Address</th>
                <th>Reason of Visit</th>
                <th>Issue Description</th>
                <th>Admin Selected</th>
                <th>Time and Date Generated</th>
            </tr>

            <?php foreach ($ticketsData as $ticket): ?>
                <tr>
                    <td><?php echo $ticket['student_ID']; ?></td>
                    <td><?php echo $ticket['student_Email']; ?></td>
                    <td><?php echo $ticket['visit_reason']; ?></td>
                    <td><?php echo $ticket['issue_description']; ?></td>
                    <td><?php echo $ticket['chosen_admin']; ?></td>
                    <td><?php echo $ticket['time_generated']; ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <p>No tickets found for today.</p>
    <?php endif; ?>

</body>
</html>
