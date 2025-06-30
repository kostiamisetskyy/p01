<?php
// Enforce strict typing.
declare(strict_types=1);

// Include the configuration and authentication functions.
require_once "config.php";

// If the user is already logged in (either by session or a valid cookie),
// redirect them directly to the admin panel.
if (is_logged_in()) {
    header("Location: admin.php");
    exit();
}

$error_message = "";

// Process form submission when the user clicks the "Login" button.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    $remember_me = isset($_POST["remember_me"]);

    // --- Password Verification ---
    // This is a direct string comparison. This is NOT secure for production
    // but matches the current plain-text password setup in config.php.
    // The previous use of password_verify() was incorrect for a plain-text password.
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        // If credentials are correct, establish a new session for the user.
        establish_user_session();

        // Handle the "Remember Me" functionality.
        if ($remember_me) {
            // Create and set a new "Remember Me" cookie.
            remember_user();
        } else {
            // Ensure any previous "Remember Me" cookies are cleared.
            forget_user();
        }

        // Redirect the user to the protected admin panel.
        header("Location: admin.php");
        exit();
    } else {
        // If credentials are incorrect, set an error message to be displayed.
        $error_message = "Invalid username or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BKK Street Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            background-color: #f4f7f6;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header img {
            max-width: 100px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body">

            <div class="login-header">
                <img src="assets/img/LOGO-BKK-png.png" alt="BKK Street Food Logo">
                <h4>Admin Panel Login</h4>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
                    <label for="username"><i class="bi bi-person"></i> Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock"></i> Password</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                    <label class="form-check-label" for="remember_me">
                        Remember me
                    </label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
