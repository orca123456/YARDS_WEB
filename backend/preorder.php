<?php
$conn = new mysqli("localhost", "root", "", "yardhandicraft", 3306);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$product        = $_POST['product']        ?? '';
$price          = $_POST['price']          ?? '';
$name           = $_POST['name']           ?? '';
$address        = $_POST['address']        ?? '';
$contact        = $_POST['contact']        ?? '';
$fb_link        = $_POST['fb_link']        ?? '';
$notes          = $_POST['notes']          ?? '';
$payment_method = trim($_POST['payment_method'] ?? '');

if ($product && $price && $name && $address && $contact && $fb_link) {

    // ── Insert pre-order ─────────────────────────────────────────────────
    $stmt = $conn->prepare(
        "INSERT INTO preorders (product, price, name, address, contact, fb_link, notes, payment_method)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sdssssss", $product, $price, $name, $address, $contact, $fb_link, $notes, $payment_method);
    $stmt->execute();
    $newId = $conn->insert_id;
    $stmt->close();

    // ── Handle payment proof upload ──────────────────────────────────────
    $proofFilename = '';
    if (!empty($_FILES['payment_proof']['name'])) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $mime    = mime_content_type($_FILES['payment_proof']['tmp_name']);

        if (in_array($mime, $allowed) && $_FILES['payment_proof']['size'] <= 5 * 1024 * 1024) {
            $ext           = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
            $proofFilename = 'proof_' . $newId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['payment_proof']['tmp_name'], $uploadDir . $proofFilename);

            // Update the row with the proof filename
            $upd = $conn->prepare("UPDATE preorders SET payment_proof = ? WHERE id = ?");
            $upd->bind_param("si", $proofFilename, $newId);
            $upd->execute();
            $upd->close();
        }
    }

    $conn->close();
    echo "<script>alert('Pre-order submitted! We will contact you soon.');window.location='../frontend/index.html';</script>";

} else {
    $conn->close();
    echo "<script>alert('Please fill in all required fields.');history.back();</script>";
}
?>
