<?php
session_start();
include 'connect.php';

$_SESSION['user_role'] = 'external_guest';

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
    $nationalID = $_POST['national_ID'];
    $egEmail = $_POST['eg_email'];
    $visitReason = $_POST['visit_reason'];
    $issueDescription = $_POST['issue_description'];
    $chosenAdmin = $_POST['chosen_admin'];

    $errors = array();

    // Form validation
    if (empty($nationalID)) {
        $errors['national_ID'] = "National ID is required";
    }

    if (empty($egEmail)) {
        $errors['eg_email'] = "Email Address is required";
    } elseif (!filter_var($egEmail, FILTER_VALIDATE_EMAIL)) {
        $errors['eg_email'] = "Invalid email format";
    }

    if (empty($visitReason)) {
        $errors['visit_reason'] = "Reason of Visit is required";
    }

    if (empty($chosenAdmin)) {
        $errors['chosen_admin'] = "Admin Selection is required";
    }

    // If there are no errors, proceed with data insertion
    if (empty($errors)) {

        // Create a DateTime object with UTC timezone
        $currentDateTimeUTC = new DateTime("now", new DateTimeZone('UTC'));

        // Convert to the desired timezone (Africa/Nairobi)
        $currentDateTimeUTC->setTimezone(new DateTimeZone('Africa/Nairobi'));

        // Format the datetime as needed
        $localTime = $currentDateTimeUTC->format('Y-m-d H:i:s');
        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO externalg_info (national_ID, eg_email, visit_reason, issue_description, chosen_admin, time_generated)
                VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters to the placeholders
        $stmt->bind_param("ssssss", $nationalID, $egEmail, $visitReason, $issueDescription, $chosenAdmin, $localTime);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: extguestticket.php");
            exit(); // Terminate script to ensure a clean redirect
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> External Guest</title>
    <!-- Bootstrap CSS 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/
    4.5.2/css/bootstrap.min.css" integrity="<KEY>" crossorigin
      ="anonymous">-->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap">
      <link rel="stylesheet" type="text/css" href="style_entry.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
  <div class="navbar">
     <h1 style="font-size:30px; margin-left:30px; font-family:Cinzel; font-weight:700; ">STRATHMORE QUEUEING SYSTEM</h1>
    <a style="font-family:Playfair Display;"  href="homepage.html">Home</a>
    <a style="font-family:Playfair Display;" href="about.html">About</a>
    <a style="font-family:Playfair Display;" href="contact.html">Contact</a>
    <!-- Add more navigation links as needed -->
</div>

    <form  method="post">
  

  <div class="form-group">
    <label for="exampleInputNationalID"><br><div class="form-txt">National ID</div></label>
    <input type="text" class="form-control" id="exampleInputLecturerID" placeholder="Enter National ID" name="national_ID">
    
  <div class="form-group">
    <label for="exampleInputEmail1"><br><div class="form-txt">Email Address</div></label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Email" name="eg_email">
    <small id="emailHelp" class="form-text text-muted"><br><div class="form-txt">We'll never share your email with anyone else.</div></small>
  </div>
  
<br>
        <h1>External Guest Issue Selection</h1>
        <br>
        <div class="form-group">
        <label for="externalguestissue">
        <div class="form-txt">Select Reason of Visit:</div>
        </label>
        <select class="form-control" id="extgIssue" name="visit_reason">
    <option value="Admission">Admission</option>
    <option value="Interview">Interview</option>
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