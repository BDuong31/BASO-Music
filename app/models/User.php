<?php declare(strict_types=1); 
// models/User.php
require_once __DIR__ . '/../../config/db.php';

class User {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function createUser($username, $password, $email, $birthday, $fullname, $sex) {
        $role = 0;
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, role_id, fullname, sex, birthday) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $username, $passwordHash, $email, $role, $fullname, $sex, $birthday);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function login($identifier, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return null;
    }

    public function updateUserProfile($id, $username, $email, $fullname, $birthday, $sex) {
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, email = ?, fullname = ?, birthday = ?, sex = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $username, $email, $fullname, $birthday, $sex, $id);
        $result = $stmt->execute();
        $stmt->close();
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    public function updateYourAvatar($userId, $avatarBase64){
        $stmt = $this->conn->prepare("UPDATE users SET img = ? WHERE id = ?");
        $stmt->bind_param("si", $avatarBase64, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getUser($userId){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id =?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
