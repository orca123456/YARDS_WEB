<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Admin account not found.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – Yard Handicraft</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --pink: #e84393; }
        * { margin: 0; box-sizing: border-box; font-family: Verdana, Geneva, Tahoma, sans-serif; outline: none; border: none; text-decoration: none; transition: .2s linear; }
        html { font-size: 62.5%; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f7f7f7; }

        .login-wrapper {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 .5rem 2rem rgba(0,0,0,.12);
            padding: 4rem 3.5rem;
            width: 100%;
            max-width: 420px;
            border-top: 4px solid var(--pink);
        }
        .login-wrapper .brand {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .login-wrapper .brand h1 {
            font-size: 2.8rem;
            color: #333;
            font-weight: bold;
        }
        .login-wrapper .brand h1 span { color: var(--pink); }
        .login-wrapper .brand p {
            font-size: 1.4rem;
            color: #999;
            margin-top: .5rem;
        }
        .form-group { margin-bottom: 1.8rem; }
        .form-group label {
            display: block;
            font-size: 1.4rem;
            color: #555;
            margin-bottom: .5rem;
        }
        .form-group .input-wrap {
            position: relative;
        }
        .form-group .input-wrap i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.6rem;
            color: #bbb;
        }
        .form-group input {
            width: 100%;
            padding: 1.1rem 1.2rem 1.1rem 3.8rem;
            font-size: 1.6rem;
            border: .1rem solid #e0e0e0;
            border-radius: .5rem;
            color: #333;
            background: #fafafa;
        }
        .form-group input:focus { border-color: var(--pink); background: #fff; }

        .btn-login {
            display: block;
            width: 100%;
            padding: 1.2rem;
            font-size: 1.7rem;
            background: #333;
            color: #fff;
            border-radius: 5rem;
            cursor: pointer;
            text-align: center;
            margin-top: .5rem;
        }
        .btn-login:hover { background: var(--pink); }

        .error-msg {
            background: rgba(232,67,147,.1);
            color: var(--pink);
            border: .1rem solid rgba(232,67,147,.3);
            border-radius: .5rem;
            padding: 1rem 1.5rem;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 2rem;
            font-size: 1.4rem;
            color: #999;
        }
        .back-link a { color: var(--pink); }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <h1>Yard Handicraft<span>.</span></h1>
        <p><i class="fas fa-lock" style="color:var(--pink);margin-right:.4rem;"></i> Admin Panel</p>
    </div>

    <?php if ($error): ?>
        <div class="error-msg"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrap">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="Enter admin username" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
        </div>
        <button type="submit" class="btn-login">Login <i class="fas fa-sign-in-alt"></i></button>
    </form>

    <p class="back-link"><a href="../frontend/index.html"><i class="fas fa-arrow-left"></i> Back to Website</a></p>
</div>
</body>
</html>
