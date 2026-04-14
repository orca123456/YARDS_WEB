<?php
/**
 * Handles proof-of-payment image upload from the pre-order form.
 * Called via preorder.php after a successful order insert.
 * Saves file to backend/uploads/ and updates the preorders row.
 */
require_once 'db.php';

$orderId = (int)($_POST['order_id'] ?? 0);
$method  = trim($_POST['payment_method'] ?? '');

if (!$orderId) {
    echo "<script>alert('Invalid order reference.');history.back();</script>";
    exit;
}

$proofFilename = '';

if (!empty($_FILES['payment_proof']['name'])) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    $mime    = mime_content_type($_FILES['payment_proof']['tmp_name']);

    if (!in_array($mime, $allowed)) {
        echo "<script>alert('Only image files (JPG, PNG, GIF, WEBP) are allowed for payment proof.');history.back();</script>";
        exit;
    }

    if ($_FILES['payment_proof']['size'] > 5 * 1024 * 1024) {
        echo "<script>alert('File size must be under 5MB.');history.back();</script>";
        exit;
    }

    $ext           = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
    $proofFilename = 'proof_' . $orderId . '_' . time() . '.' . strtolower($ext);
    move_uploaded_file($_FILES['payment_proof']['tmp_name'], $uploadDir . $proofFilename);
}

// Update preorders row with payment info
$stmt = $conn->prepare("UPDATE preorders SET payment_method = ?, payment_proof = ? WHERE id = ?");
$stmt->bind_param("ssi", $method, $proofFilename, $orderId);
$stmt->execute();
$stmt->close();
$conn->close();

echo "<script>alert('Pre-order submitted! Payment info received. We will contact you soon.');window.location='../frontend/index.html';</script>";
?>
