<?php
// Enforce strict typing.
declare(strict_types=1);

// Include the configuration and authentication functions.
require_once "config.php";

// This function clears the "Remember Me" token from storage and the browser cookie.
forget_user();

// --- Standard Session Logout Procedure ---

// 1. Unset all session variables to clear the session data.
$_SESSION = [];

// 2. Delete the session cookie from the browser.
// This is a robust way to ensure the client-side session is fully cleared.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        "",
        time() - 42000, // Set the cookie to expire in the past.
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy the session on the server side.
session_destroy();

// 4. Redirect the user to the login page after they have been logged out.
header("Location: login.php?status=loggedout");
// Ensure that no further code is executed after the redirect.
exit();
?>
