<?php
$host = "3306";
$user = "root";
$pass = "";
$db = "yardhandicraft";
$conn = new mysqli("localhost", "root", "", "yardhandicraft", 3306);

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$product = $_POST['product'];
$price = $_POST['price'];
$name = $_POST['name'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$fb_link = $_POST['fb_link'];
$notes = $_POST['notes'];

if ($product && $price && $name && $address && $contact && $fb_link) {
    $stmt = $conn->prepare("INSERT INTO preorders (product, price, name, address, contact, fb_link, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssss", $product, $price, $name, $address, $contact, $fb_link, $notes);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Pre-order submitted! We will contact you soon.');window.location='../frontend/index.html';</script>";
} else {
    echo "<script>alert('Please fill in all required fields');history.back();</script>";
}
$conn->close();
?>
