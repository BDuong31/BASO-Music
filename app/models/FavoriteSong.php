<?php declare(strict_types=1); 
require_once __DIR__ . '/../../config/db.php';

class Favorite{
    private $conn;

    public function __construct(){
        global $conn;
        $this->conn = $conn;
    }

    // Thêm bài hát vào danh sách yêu thích
    public function addFavorite(int $userId, string $trackId): bool {
        $stmt = $this->conn->prepare("INSERT INTO favorites (user_id, song_id) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param("is", $userId, $trackId); // "s" vì trackId là chuỗi
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    // Xóa bài hát khỏi danh sách yêu thích
    public function removeFavorite(int $userId, string $trackId): bool{
        $stmt = $this->conn->prepare("DELETE FROM favorites WHERE user_id =? AND song_id =?");
        $stmt->bind_param("is", $userId, $trackId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    // Lấy danh sách bài hát yêu thích của người dùng
    public function getFavorites(int $userId) : array{
        $stmt = $this->conn->prepare("SELECT song_id FROM favorites WHERE user_id =?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $favorites = [];
        while($row = $result->fetch_assoc()){
            $favorites[] = $row['song_id'];
        }
        $stmt->close();
        return $favorites;
    }

    // Kiểm tra xem bài hát đã được thêm vào danh sách yêu thích của người dùng chưa
    public function isFavorite(int $userId,string $trackId): bool{
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id =? AND song_id =?");
        $stmt->bind_param("is", $userId, $trackId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] > 0;
    }

    // Tính số bài hát yêu thích của người dùng
    public function getFavoriteCount(int $userId) : int{
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id =?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['count'];
    }

    
}
?>

