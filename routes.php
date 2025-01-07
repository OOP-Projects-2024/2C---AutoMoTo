<?php
require_once "./config/database.php";
require_once "./modules/Get.php";
require_once "./modules/Post.php";
require_once "./modules/Patch.php";
require_once "./modules/Delete.php";
require_once "./modules/auth.php";  // include the auth module
require_once "./modules/Crypt.php";

$db = new Connection();
$pdo = $db->connect();

$get = new Get($pdo);
$post = new Post($pdo);
$patch = new Patch($pdo);
$delete = new Delete($pdo);
$auth = new Authentication($pdo);  // create an instance of the Authentication class
$crypt = new Crypt();

if (isset($_REQUEST['request'])) {
    $request = explode("/", $_REQUEST['request']);
} else {
    echo "URL does not exist.";
}

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        if ($auth->isAuthorized()) {  // check if the user is authorized
            switch ($request[0]) {
               
                case "carparts":  // handle the "carparts" endpoint
                    $dataString = json_encode($get->getCarParts($request[1] ?? null));
                    echo $crypt->encryptData ($dataString);
                    break;
                
                case "accounts":
                    $dataString = json_encode($get->getaccounts($request[1] ?? null));
                    echo $crypt->encryptData ($dataString);
                    break;
    
                    case "log":
                        echo json_encode($get->getLogs($request[1] ?? date("Y-m-d")));
                        break;

                default:
                    http_response_code(404);
                    echo "Endpoint not found.";
            }
        } else {
            http_response_code(401); 
            echo json_encode([
            "status" => "You cannot access this data!",
            "message" => "You must login first."
        ]);
        }
        break;

    case "POST":
        $body = json_decode(file_get_contents("php://input"), true);
            switch ($request[0]) {
                case "carparts":
                    echo json_encode($post->postCarParts($body));
                break;
                case "register":
                    echo json_encode($auth->addAccount($body));
                break;
                case "login":
                    echo json_encode($auth->login($body));
                break;
                case "decrypt":
                    echo $crypt->decryptData($body);
                    
                break;
                default:
                http_response_code(404);
                echo "Endpoint not found.";
        }
    break;

    case "PATCH":
        $body = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case "carparts":
                echo json_encode($patch->patchCarParts($body, $request[1]));
                break;
            case "patchuser":
                echo json_encode($patch->patchUsers($body, $request[1]));
                break;
            default:
                echo json_encode(["error" => "Invalid request type"]);
                break;
        }   
        break;
    

     case "DELETE":
        switch ($request[0]) {
            case "carparts":
            echo json_encode($delete->deleteCarParts($request[1]));
                break;
            case "deleteuser":
                echo json_encode($delete->deleteUsers($request[1]));
                break;
            default:
                http_response_code(400);
                echo json_encode(["error" => "Invalid request type for DELETE."]);
                break;
        }
        break;    

    default:
    http_response_code(400);
    echo "Invalid Request Method.";
    break;
}

$pdo = null;
?>