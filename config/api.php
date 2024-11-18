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
                $_SESSION['user'] = $response['user']; // Store user data in session for future use.
                return $response;

            } else {
                return $response;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
        }
        break;

    case 'update-profile':
        if ($requestMethod === 'POST') {
            $response = $authController->updateProfile($data);
            if ($response['status']==00){
                $_SESSION['user'] = $response['user']; 
                return $response;

            } else {
                return $response;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Phương thức không h��p lệ']);
        }
        break;
    
    case 'update-avatar':
        if ($requestMethod === 'POST') {
            if (!isset($_SESSION['user'])) {
                echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập']);
                exit();
            }
        
                // Lấy user ID từ session
            $userId = $_SESSION['user']['id'];
            
                    // Kiểm tra tệp tải lên
            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['status' => 'error', 'message' => 'Không có tệp được tải lên hoặc có lỗi']);
                exit();
            }
            
                // Gọi hàm xử lý avatar
            $response = $authController->updateYourAvatar($userId, $_FILES['avatar']);
            return $response;
        } else {
                echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
        }
        break;
    case 'add-history':
        if ($requestMethod === 'POST') {
            require_once __DIR__ . '/../app/controllers/HistoryController.php';
            $historyController = new HistoryController();
            
            // Kiểm tra dữ liệu đầu vào
            if (!isset($data['trackId']) || empty($data['trackId'])) {
                echo json_encode(['status' => '01', 'message' => 'Track ID không hợp lệ']);
                exit();
            }
            
                    // $userId = $_SESSION['user']['id'] ?? null;
                    // if (!$userId) {
                    //     echo json_encode(['status' => '01', 'message' => 'Bạn chưa đăng nhập']);
                    //     exit();
                    // }
            
                    // Gọi controller để thêm lịch sử
            $response = $historyController->addHistory($data);
            
                    // Trả về phản hồi JSON
            return $response;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ']);
        }
        break;
    
    case 'add-favorite':
        if ($requestMethod === 'POST') {
            require_once __DIR__ . '/../app/controllers/FavoriteController.php';
            $favoriteController = new FavoriteController();

            // Kiểm tra dữ liệu đầu vào
            if (!isset($data['trackId']) || empty($data['trackId'])) {
                echo json_encode(['status' => '01', 'message' => 'Track ID không h��p lệ']);
                exit();
            }

            // $userId = $_SESSION['user']['id']?? null;
            // if (!$userId) {
            //     echo json_encode(['status' => '01', 'message' => 'Bạn chưa đăng nhập']);
            //     exit();
            // }

            // Gọi controller để thêm yêu thích
            $response = $favoriteController->addFavorite($data);

            // Trả về phản hồi JSON
            return $response;
        }
        break;
    
    case 'delete-favorite':
        if ($requestMethod === 'POST') {
            require_once __DIR__ . '/../app/controllers/FavoriteController.php';
            $favoriteController = new FavoriteController();

            // Kiểm tra dữ liệu đầu vào
            if (!isset($data['trackId']) || empty($data['trackId'])) {
                echo json_encode(['status' => '01', 'message' => 'Track ID không h��p lệ']);
                exit();
            }

            // $userId = $_SESSION['user']['id']?? null;
            // if (!$userId) {
            //     echo json_encode(['status' => '01', 'message' => 'Bạn chưa đăng nhập']);
            //     exit();
            // }

            // Gọi controller để xóa yêu thích
            $response = $favoriteController->removeFavorite($data);

            // Trả về phản hồi JSON
            return $response;
        }
    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ']);
        break;
}
}
?>
