<?php
/**
 * ONE-TIME SETUP SCRIPT
 * ─────────────────────────────────────────────────────────────────────────
 * Run this ONCE in your browser:  http://localhost/YARDS_WEB/backend/create_admin.php
 * After the admin account is created, DELETE this file for security.
 * ─────────────────────────────────────────────────────────────────────────
 */

require_once 'db.php';

// ── Change these before running ──────────────────────────────────────────
$adminUsername = 'admin';
$adminPassword = 'admin123';   // ← Change to a stronger password
// ─────────────────────────────────────────────────────────────────────────

$hashed = password_hash($adminPassword, PASSWORD_DEFAULT);

// Check if already exists
$check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$check->bind_param("s", $adminUsername);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    $conn->close();
    echo "
    <!DOCTYPE html><html><head><title>Setup – Yard Handicraft</title>
    <style>body{font-family:Verdana,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f7f7f7;}
    .box{background:#fff;padding:3rem 4rem;border-radius:1rem;box-shadow:0 .5rem 2rem rgba(0,0,0,.1);text-align:center;max-width:450px;}
    h2{color:#e84393;font-size:2rem;margin-bottom:1rem;} p{color:#666;font-size:1.5rem;line-height:1.8;} a{color:#e84393;}
    </style></head><body>
    <div class='box'>
        <h2>⚠️ Already Exists</h2>
        <p>An admin account with username <strong>$adminUsername</strong> already exists.</p>
        <p>If you forgot your password, update it directly in phpMyAdmin → admins table.</p>
        <p><a href='admin_login.php'>Go to Admin Login</a></p>
    </div>
    </body></html>";
    exit;
}
$check->close();

$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $adminUsername, $hashed);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    echo "
    <!DOCTYPE html><html><head><title>Setup – Yard Handicraft</title>
    <style>body{font-family:Verdana,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f7f7f7;}
    .box{background:#fff;padding:3rem 4rem;border-radius:1rem;box-shadow:0 .5rem 2rem rgba(0,0,0,.1);text-align:center;max-width:450px;border-top:4px solid #e84393;}
    h2{color:#27ae60;font-size:2.2rem;margin-bottom:1rem;} p{color:#555;font-size:1.5rem;line-height:1.8;} strong{color:#333;}
    .warn{background:#fff3cd;border:1px solid #ffc107;border-radius:.5rem;padding:1rem;font-size:1.4rem;color:#856404;margin-top:1.5rem;}
    a{display:inline-block;margin-top:1.5rem;background:#e84393;color:#fff;padding:.8rem 2.5rem;border-radius:5rem;text-decoration:none;font-size:1.5rem;}
    </style></head><body>
    <div class='box'>
        <h2>✅ Admin Created!</h2>
        <p>Username: <strong>$adminUsername</strong><br>Password: <strong>$adminPassword</strong></p>
        <div class='warn'>⚠️ <strong>IMPORTANT:</strong> Delete <code>create_admin.php</code> from your server now for security!</div>
        <a href='admin_login.php'>Go to Admin Login →</a>
    </div>
    </body></html>";
} else {
    echo "<p style='color:red;font-size:1.6rem;padding:2rem;'>Error creating admin: " . $conn->error . "</p>";
}
?>
