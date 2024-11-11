<?php declare(strict_types=1); 
// controllers/UserController.php
require_once __DIR__ . '/../services/AuthService.php';

class AuthController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    // API đăng ký người dùng
    public function register($data) {
        $response = $this->userService->register(
            $data['username'],
            $data['password'],
            $data['email'],
            $data['birthday'],
            $data['fullname'],
            $data['sex']
        );
        return $response;
    }

    // API đăng nhập người dùng
    public function login($data) {
        $response = $this->userService->login($data['identifier'], $data['password']);
        return ($response);
    }

    // API cập nhật hồ sơ người dùng
    public function updateProfile($data) {
        $response = $this->userService->updateProfile(
            $data['id'],
            $data['fullname'],
            $data['birthday'],
            $data['profile_picture'] ?? null
        );
        echo json_encode($response);
    }
}
?>
