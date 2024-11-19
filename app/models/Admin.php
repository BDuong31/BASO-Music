<?php declare(strict_types=1); 
require_once __DIR__ . '/../../config/db.php';

class Admin{
    private $conn;
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    public function getUserCount(): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE role_id = ?");
        $role = 0;
        $stmt->bind_param("i", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int) $row['count'];
    }
    
    public function getUsers(): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE role_id = ?");
        $role = 0;
        $stmt->bind_param("i", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
    
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    
        $stmt->close();
        return $users;
    }    
}
?>

