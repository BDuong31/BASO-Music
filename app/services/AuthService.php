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
        return $user ? ['status' => '00', 'user' => $user] : ['status' => '01', 'message' => 'Sai thông tin đăng nhập'];
    }

    // Cập nhật thông tin hồ sơ
    public function updateProfile($id, $fullname, $birthday, $profilePicture = null) {
        $result = $this->userModel->updateUserProfile($id, $fullname, $birthday, $profilePicture);
        return $result ? ['status' => '00', 'message' => 'Cập nhật thành công'] : ['status' => '01', 'message' => 'Không thể cập nhật hồ sơ'];
    }
}
?>
