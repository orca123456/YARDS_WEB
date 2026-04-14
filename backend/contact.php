<?php
$host = "3306";
$user = "root";
$pass = "";
$db = "yardhandicraft";
$conn = new mysqli("localhost", "root", "", "yardhandicraft", 3306);

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name    = $_POST['name'];
$fb_link = $_POST['fb_link'];
$number  = $_POST['number'];
$message = $_POST['message'];

if ($name && $fb_link && $number && $message) {
    $stmt = $conn->prepare("INSERT INTO contacts (name, fb_link, number, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $fb_link, $number, $message);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Thank you for contacting us!');window.location='../frontend/index.html';</script>";
} else {
    echo "<script>alert('Please fill in all required fields');history.back();</script>";
}
$conn->close();
?>
