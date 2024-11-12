<?php declare(strict_types=1); 
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../app/controllers/AuthController.php';

$authController = new AuthController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;
if ($action == ''){
    echo json_encode(['status' => 'error', 'message' => 'Hành đ��ng không h��p lệ']);
    exit();  // Stop the script here if no action is provided.
} else {
switch ($action) {
    case 'register':
        if ($requestMethod === 'POST') {
            $response = $authController->register($data);
            if ($response['status']==00){
                return $response;

            } else {
                return $response;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
        }
        break;

    case 'login':
        if ($requestMethod === 'POST') {
            $response = $authController->login($data);
            if ($response['status']==00){
                //echo json_encode($response);
                $_SESSION['user'] = $response['user']['id']; // Store user data in session for future use.
                return $response;

            } else {
                return $response;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ']);
        break;
}
}
?>
