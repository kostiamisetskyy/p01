<?php
// --- SECURITY & HEADERS ---

// Set the content type of the response to JSON
header("Content-Type: application/json");
// Allow Cross-Origin Resource Sharing (CORS) for development.
// IMPORTANT: For a production environment, you should restrict this to your specific domain.
// Example: header('Access-Control-Allow-Origin: https://bkkstreetfood.ch');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle pre-flight CORS requests sent by browsers
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // A pre-flight request doesn't need a body, just the headers.
    exit(0);
}

// --- AUTHENTICATION ---
require_once "config.php";
require_login();

// --- CONFIGURATION ---

// Define the paths for the database file and the uploads directory.
$database_file_path = __DIR__ . "/database.json";
$uploads_directory_path = __DIR__ . "/uploads/";
// This is the relative URL path that the client-side will use to access the images.
$uploads_url_base = "uploads/";

// --- HELPER FUNCTIONS ---

/**
 * Sends a JSON-formatted response with a specific HTTP status code and then terminates the script.
 *
 * @param int $statusCode The HTTP status code to send (e.g., 200 for OK, 404 for Not Found).
 * @param array $data The associative array of data to be encoded into JSON.
 */
function send_json_response($statusCode, $data)
{
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

/**
 * Retrieves the contents of the database.json file. If the file doesn't exist,
 * it creates it with a default structure.
 *
 * @param string $file_path The full path to the database.json file.
 * @return array The decoded data from the JSON file.
 */
function get_database_contents($file_path)
{
    if (!file_exists($file_path)) {
        // If the database file doesn't exist, create it with a default empty structure.
        $default_data = [
            "menuTitle" => "MENU DE LA SEMAINE",
            "dishes" => [],
            "weeklyMenu" => new stdClass(), // Use an empty object for the menu
        ];
        // The LOCK_EX flag prevents anyone else from writing to the file at the same time.
        if (
            file_put_contents(
                $file_path,
                json_encode($default_data, JSON_PRETTY_PRINT),
                LOCK_EX
            ) === false
        ) {
            send_json_response(500, [
                "error" =>
                    "Failed to create database file. Please check server permissions.",
            ]);
        }
        return $default_data;
    }

    $json_data = file_get_contents($file_path);
    $data = json_decode($json_data, true); // `true` converts it to an associative array

    // Check if there was an error parsing the JSON file.
    if (json_last_error() !== JSON_ERROR_NONE) {
        send_json_response(500, [
            "error" => "Error reading database file: " . json_last_error_msg(),
        ]);
    }

    return $data;
}

// --- API ROUTING ---

$request_method = $_SERVER["REQUEST_METHOD"];
// Determine the action from the URL query string (e.g., api.php?action=upload)
$action = isset($_GET["action"]) ? $_GET["action"] : "";

if ($request_method === "GET") {
    // If it's a GET request, fetch and return all data.
    $data = get_database_contents($database_file_path);
    send_json_response(200, $data);
} elseif ($request_method === "POST") {
    if ($action === "save") {
        // Handles saving the entire menu state (dishes, weekly plan, title)
        $raw_post_data = file_get_contents("php://input");
        $data_to_save = json_decode($raw_post_data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            send_json_response(400, ["error" => "Invalid JSON data received."]);
        }

        // Write the new data to the file, overwriting the old content.
        if (
            file_put_contents(
                $database_file_path,
                json_encode($data_to_save, JSON_PRETTY_PRINT),
                LOCK_EX
            ) === false
        ) {
            send_json_response(500, [
                "error" =>
                    "Failed to write to database file. Please check server permissions.",
            ]);
        }

        send_json_response(200, [
            "success" => true,
            "message" => "Data saved successfully.",
        ]);
    } elseif ($action === "upload") {
        // Handles image file uploads
        if (
            !isset($_FILES["image"]) ||
            $_FILES["image"]["error"] !== UPLOAD_ERR_OK
        ) {
            send_json_response(400, [
                "error" =>
                    "No file was uploaded or an error occurred during upload.",
            ]);
        }

        $file = $_FILES["image"];

        // --- File Validation ---
        $max_file_size = 5 * 1024 * 1024; // 5MB
        $allowed_mime_types = [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp",
        ];

        if ($file["size"] > $max_file_size) {
            send_json_response(400, [
                "error" => "File is too large. The maximum size is 5MB.",
            ]);
        }

        // More reliable check of the file type using its content
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file["tmp_name"]);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_mime_types)) {
            send_json_response(400, [
                "error" =>
                    "Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.",
            ]);
        }

        // --- File Processing ---

        // Generate a unique filename to prevent overwriting existing files and avoid naming conflicts.
        $file_extension = strtolower(
            pathinfo($file["name"], PATHINFO_EXTENSION)
        );
        $new_filename = "dish_" . uniqid("", true) . "." . $file_extension;

        // Ensure the uploads directory exists.
        if (!is_dir($uploads_directory_path)) {
            if (!mkdir($uploads_directory_path, 0755, true)) {
                send_json_response(500, [
                    "error" =>
                        "Failed to create uploads directory. Please check server permissions.",
                ]);
            }
        }

        $destination_path = $uploads_directory_path . $new_filename;

        if (!move_uploaded_file($file["tmp_name"], $destination_path)) {
            send_json_response(500, [
                "error" => "Failed to move the uploaded file.",
            ]);
        }

        // Return the publicly accessible path to the file.
        $file_url = $uploads_url_base . $new_filename;
        send_json_response(200, ["success" => true, "filePath" => $file_url]);
    } else {
        // If the POST action is not 'save' or 'upload'
        send_json_response(400, [
            "error" =>
                "Invalid POST action specified. Use ?action=save or ?action=upload.",
        ]);
    }
} else {
    // If any other HTTP method is used
    send_json_response(405, ["error" => "Method not allowed."]);
}
