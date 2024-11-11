<?php
declare(strict_types=1); 
require_once 'controllers/AuthController.php';

$authController = new AuthController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? null;

// Lấy dữ liệu đầu vào từ JSON nếu không dùng form-data
$data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

switch ($action) {
    case 'register':
        if ($requestMethod === 'POST') {
            $registerData = [
                'username' => $data['username'] ?? '',
                'password' => $data['password'] ?? '',
                'email' => $data['email'] ?? '',
                'birthday' => $data['birthday'] ?? '',
                'fullname' => $data['fullname'] ?? '',
                'sex' => $data['sex'] ?? '',
            ];
            $authController->register($registerData);
        }
        break;
    case 'login':
        if ($requestMethod === 'POST') {
            $authController->login($data);
        }
        break;
    case 'updateProfile':
        if ($requestMethod === 'POST') {
            $authController->updateProfile($data);
        }
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ']);
}
?>
