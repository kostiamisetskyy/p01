<?php
// Enforce strict typing to prevent common type-related errors.
declare(strict_types=1);

// --- Constants and Configuration ---

// Define the administrator's username.
define("ADMIN_USERNAME", "admin");

// Define the administrator's password.
// IMPORTANT: For a production environment, generate a hash and use that instead.
// You can generate a hash using: echo password_hash('YourStrongPassword', PASSWORD_DEFAULT);
// Then replace 'password123' with the generated hash string.
define("ADMIN_PASSWORD", "password123");

// Define settings for the "Remember Me" cookie.
define("REMEMBER_ME_COOKIE_NAME", "bkk_auth_token");
define("REMEMBER_ME_DURATION", 86400 * 30); // 30 days in seconds

// Define the path to the file that will store authentication tokens.
define("AUTH_TOKEN_FILE", __DIR__ . "/auth_tokens.json");

// --- Session Management ---

// Start a new session or resume the existing one with secure cookie parameters.
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        "lifetime" => 1800, // Session expires after 30 minutes of inactivity.
        "path" => "/",
        "domain" => $_SERVER["HTTP_HOST"],
        "secure" => isset($_SERVER["HTTPS"]), // Only send cookies over a secure connection.
        "httponly" => true, // Prevent client-side scripts from accessing the cookie.
        "samesite" => "Lax", // Provides some protection against CSRF attacks.
    ]);
    session_start();
}

// --- Core Authentication Functions ---

/**
 * Checks if the user is authenticated via an active session.
 * @return bool True if the session is active, false otherwise.
 */
function is_session_active(): bool
{
    return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
}

/**
 * Sets the session variables to mark the user as logged in.
 * This should be called after successful password verification.
 */
function establish_user_session(): void
{
    // Regenerate the session ID to prevent session fixation attacks.
    session_regenerate_id(true);
    $_SESSION["loggedin"] = true;
}

/**
 * Verifies the "Remember Me" cookie and logs the user in if it's valid.
 * @return bool True if the user was successfully logged in via cookie, false otherwise.
 */
function login_via_cookie(): bool
{
    // Check if the "Remember Me" cookie exists.
    if (!isset($_COOKIE[REMEMBER_ME_COOKIE_NAME])) {
        return false;
    }

    $cookie_parts = explode(":", $_COOKIE[REMEMBER_ME_COOKIE_NAME], 2);
    if (count($cookie_parts) !== 2) {
        clear_remember_me_cookie();
        return false;
    }

    list($selector, $validator) = $cookie_parts;
    $tokens = get_auth_tokens();

    // Verify that the selector from the cookie exists in our token database.
    if (!isset($tokens[$selector])) {
        clear_remember_me_cookie();
        return false;
    }

    // Hash the validator from the cookie and compare it to the stored hash.
    if (password_verify($validator, $tokens[$selector]["hashed_validator"])) {
        // --- Successful Authentication ---
        // The cookie is valid. Establish a new session for the user.
        establish_user_session();

        // --- Security: Replace the used token with a new one ---
        // This prevents a stolen cookie from being used indefinitely.
        list(
            $new_selector,
            $new_validator,
            $new_hashed_validator,
        ) = generate_auth_token_triplet();
        unset($tokens[$selector]); // Remove the old token.
        $tokens[$new_selector] = [
            "hashed_validator" => $new_hashed_validator,
            "expires" => time() + REMEMBER_ME_DURATION,
        ];
        save_auth_tokens($tokens); // Save the updated tokens.
        set_remember_me_cookie($new_selector, $new_validator); // Set the new cookie.

        return true;
    }

    // If the validator is incorrect, it might be a stolen cookie.
    // For enhanced security, we remove the compromised token.
    unset($tokens[$selector]);
    save_auth_tokens($tokens);
    clear_remember_me_cookie();

    return false;
}

/**
 * The main function to check if a user is logged in, checking both session and cookie.
 * @return bool True if the user is authenticated, false otherwise.
 */
function is_logged_in(): bool
{
    return is_session_active() || login_via_cookie();
}

/**
 * A guard function to be placed at the top of any protected page.
 * It redirects to the login page if the user is not authenticated.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// --- "Remember Me" Token and Cookie Helpers ---

/**
 * Generates a secure, random token triplet for cookie authentication.
 * @return array Contains the selector, plain validator, and hashed validator.
 */
function generate_auth_token_triplet(): array
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));
    $hashed_validator = password_hash($validator, PASSWORD_DEFAULT);
    return [$selector, $validator, $hashed_validator];
}

/**
 * Retrieves and decodes the authentication tokens from the JSON file.
 * @return array The associative array of stored tokens.
 */
function get_auth_tokens(): array
{
    if (!file_exists(AUTH_TOKEN_FILE)) {
        return [];
    }
    $json_data = file_get_contents(AUTH_TOKEN_FILE);
    return json_decode($json_data, true) ?: [];
}

/**
 * Saves the provided tokens array to the JSON file and cleans up expired tokens.
 * @param array $tokens The array of tokens to save.
 */
function save_auth_tokens(array $tokens): void
{
    // Before saving, filter out any tokens that have expired.
    $valid_tokens = array_filter($tokens, function ($token) {
        return isset($token["expires"]) && $token["expires"] > time();
    });
    file_put_contents(
        AUTH_TOKEN_FILE,
        json_encode($valid_tokens, JSON_PRETTY_PRINT),
        LOCK_EX
    );
}

/**
 * Sets the secure "Remember Me" HTTPOnly cookie.
 * @param string $selector The public part of the token.
 * @param string $validator The secret part of the token.
 */
function set_remember_me_cookie(string $selector, string $validator): void
{
    $cookie_value = "$selector:$validator";
    setcookie(REMEMBER_ME_COOKIE_NAME, $cookie_value, [
        "expires" => time() + REMEMBER_ME_DURATION,
        "path" => "/",
        "domain" => $_SERVER["HTTP_HOST"],
        "secure" => isset($_SERVER["HTTPS"]),
        "httponly" => true,
        "samesite" => "Lax",
    ]);
}

/**
 * Clears the "Remember Me" cookie from the browser.
 */
function clear_remember_me_cookie(): void
{
    if (isset($_COOKIE[REMEMBER_ME_COOKIE_NAME])) {
        unset($_COOKIE[REMEMBER_ME_COOKIE_NAME]);
        setcookie(REMEMBER_ME_COOKIE_NAME, "", time() - 3600, "/");
    }
}

/**
 * Creates and stores a new "Remember Me" token for the current user.
 */
function remember_user(): void
{
    list(
        $selector,
        $validator,
        $hashed_validator,
    ) = generate_auth_token_triplet();
    $tokens = get_auth_tokens();
    $tokens[$selector] = [
        "hashed_validator" => $hashed_validator,
        "expires" => time() + REMEMBER_ME_DURATION,
    ];
    save_auth_tokens($tokens);
    set_remember_me_cookie($selector, $validator);
}

/**
 * Removes the user's "Remember Me" token from storage and clears the cookie.
 */
function forget_user(): void
{
    if (isset($_COOKIE[REMEMBER_ME_COOKIE_NAME])) {
        list($selector) = explode(":", $_COOKIE[REMEMBER_ME_COOKIE_NAME], 2);
        if ($selector) {
            $tokens = get_auth_tokens();
            unset($tokens[$selector]);
            save_auth_tokens($tokens);
        }
    }
    clear_remember_me_cookie();
}
