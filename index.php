<?php
include_once "auth.php";
include_once "get.php";

// Create PDO connection (replace with actual database connection)
$pdo = new PDO('mysql:host=localhost;dbname=testdb', 'username', 'password');
$get = new Get($pdo);
$auth = new Authentication($pdo);

// Sample request handling
$request = explode('/', $_SERVER['REQUEST_URI']);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Main request handler
switch ($requestMethod) {
    case "GET":
        if ($auth->isAuthorized()) {  // Check if the user is authorized
            switch ($request[1] ?? '') {
                case "carparts":
                    $dataString = json_encode($get->getCarParts($request[2] ?? null));
                    echo $dataString;
                    break;

                case "accounts":
                    $dataString = json_encode($get->getaccounts($request[2] ?? null));
                    echo $dataString;
                    break;

                case "log":
                    echo json_encode($get->getLogs($request[2] ?? date("Y-m-d")));
                    break;

                default:
                    http_response_code(404);
                    echo "Endpoint not found.";
            }
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "Unauthorized",
                "message" => "You must be logged in to access this resource."
            ]);
        }
        break;

    // Handle other methods (POST, PUT, DELETE, etc.) if necessary

    default:
        http_response_code(405);
        echo json_encode([
            "status" => "Method Not Allowed",
            "message" => "This method is not supported."
        ]);
}
?>