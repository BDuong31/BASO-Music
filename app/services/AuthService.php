<?php declare(strict_types=1); 
// services/UserService.php
require_once __DIR__ . '/../models/User.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Xử lý đăng ký
    public function register($username, $password, $email, $birthday, $fullname, $sex) {
        if ($this->userModel->getUserByUsername($username)) {
            return ['status' => '01', 'message' => 'Username đã tồn tại'];
        }
        if ($this->userModel->getUserByEmail($email)) {
            return ['status' => '02', 'message' => 'Email đã tồn tại'];
        }
        $result = $this->userModel->createUser($username, $password, $email, $birthday, $fullname, $sex);
        return $result ? ['status' => '00', 'message' => 'Đăng ký thành công'] : ['status' => '03', 'message' => 'Đăng ký thất bại'];
    }

    // Xử lý đăng nhập
    public function login($identifier, $password) {
        $user = $this->userModel->login($identifier, $password);
        $response = $user ? ['status' => '00', 'user' => $user] : ['status' => '01', 'message' => 'Sai thông tin đăng nhập'];
        unset($response['user']['password']);
        
        return $response;
    }

    // Cập nhật thông tin hồ sơ
    public function updateProfile($id, $username, $email, $fullname, $birthday, $sex) {
        $result = $this->userModel->updateUserProfile($id, $username, $email, $fullname, $birthday, $sex);
        $response = $result ? ['status' => '00', 'user' => $result] : ['status' => '01', 'message' => 'Không thể cập nhật hồ sơ'];
        if (isset($response['user']['password'])) {
            unset($response['user']['password']);
        }
        
        return $response;
    }

    public function updateAvatar($id, $file) {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $file['tmp_name'];
    
            // Kiểm tra định dạng file
            $fileType = mime_content_type($fileTmpPath);
            if (strpos($fileType, 'image/') !== 0) {
                return ['status' => '03', 'message' => 'Tệp không phải ảnh'];
            }
    
            // Giới hạn kích thước file (2MB)
            $maxFileSize = 2 * 1024 * 1024;
            if ($file['size'] > $maxFileSize) {
                return ['status' => '04', 'message' => 'Kích thước tệp quá lớn (tối đa 2MB)'];
            }
    
            // Chuyển file sang Base64
            $imageData = file_get_contents($fileTmpPath);
            $base64 = base64_encode($imageData);
            $avatarBase64 = "data:$fileType;base64,$base64";
    
            // Cập nhật vào database
            $result = $this->userModel->updateYourAvatar($id, $avatarBase64);
            if ($result) {
                $_SESSION['user']['img'] = $avatarBase64;
            }
            return $result
                ? ['status' => '00', 'message' => 'Cập nhật avatar thành công']
                : ['status' => '01', 'message' => 'Không thể cập nhật avatar'];
        }
    
        return ['status' => '02', 'message' => 'Không có tệp được chọn'];
    }
    
}
?>
