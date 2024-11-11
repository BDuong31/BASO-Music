<?php declare(strict_types=1); 
// models/User.php
require_once __DIR__ . '/../../config/db.php';

class User {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Tạo người dùng mới
    public function createUser($username, $password, $email, $birthday, $fullname, $sex) {
        $role = 0;
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, role_id, fullname, sex, birthday) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $username, $passwordHash, $email, $role, $fullname, $sex, $birthday);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Lấy thông tin người dùng bằng username
    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Lấy thông tin người dùng bằng email
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Đăng nhập
    public function login($identifier, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    // Cập nhật hồ sơ người dùng
    public function updateUserProfile($id, $fullname, $birthday, $profilePicture = null) {
        if ($profilePicture) {
            $stmt = $this->conn->prepare("UPDATE users SET fullname = ?, birthday = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("sssi", $fullname, $birthday, $profilePicture, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET fullname = ?, birthday = ? WHERE id = ?");
            $stmt->bind_param("ssi", $fullname, $birthday, $id);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>
