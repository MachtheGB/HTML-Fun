<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'is_run';
//Database connection

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    echo ("connection unsuccessful: " . mysqli_connect_error());

}
// else{
    //echo "Connected successfully";
    /*if(isset($_GET["submit"])){
        echo "<script>alert(\"Registration successful!\");</script>";
        }
        $stmt = $conn->prepare("insert into is1(studentID, studentEmail, lecID, lecEmail, PK, nationalID, egEmail) values(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $studentEmail, "sssssi", $lecEmail, "sssssi", "sssssi", $egEmail);
        $stmt->excecute();
        echo "registration success";
        $stmt->close();
        $conn->close();

}*/
?>