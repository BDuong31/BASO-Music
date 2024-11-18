<?php declare(strict_types=1); 
require_once __DIR__ . '/../../config/db.php';
function logError($message) {
    $logFile = __DIR__ . '/error_log.txt';
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}

// set_error_handler(function ($errno, $errstr, $errfile, $errline) {
//     logError("Error $errno: $errstr in $errfile on line $errline");
//     http_response_code(500);
//     echo json_encode(['status' => 'error', 'message' => 'Internal Server Error']);
//     exit();
// });

class History {
    private $conn;
    private const MAX_HISTORY = 50;

    // Constructor khởi tạo kết nối cơ sở dữ liệu
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Thêm bài hát vào danh sách lịch sử
    public function addHistory(int $userId, string $trackId): bool {
        // Kiểm tra nếu bài hát đã tồn tại trong lịch sử của người dùng
        if ($this->checkTrackExists($userId, $trackId)) {
            // Nếu đã tồn tại, xóa bài hát cũ
            $this->deleteHistory($userId, $trackId);
        }

        // Kiểm tra nếu lịch sử đã đạt số lượng tối đa, nếu có, xóa bài hát cũ nhất
        if ($this->getTotalHistory($userId) >= self::MAX_HISTORY) {
            $this->deleteOldestHistory($userId);
        }

        // Thêm bài hát mới vào lịch sử
        $stmt = $this->conn->prepare("INSERT INTO historys (user_id, song_id) VALUES (?, ?)");
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

    // Kiểm tra xem bài hát đã tồn tại trong lịch sử hay chưa
    public function checkTrackExists(int $userId, string $trackId): bool {
        $stmt = $this->conn->prepare("SELECT id FROM historys WHERE user_id = ? AND song_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param("is", $userId, $trackId);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Xóa bài hát cũ nhất trong lịch sử của người dùng
    private function deleteOldestHistory(int $userId): bool {
        $stmt = $this->conn->prepare("DELETE FROM historys WHERE user_id = ? ORDER BY created_at ASC LIMIT 1");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $userId);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    // Lấy danh sách các bài hát đã nghe của người dùng
    public function getHistory(int $userId): array {
        $stmt = $this->conn->prepare("SELECT song_id FROM historys WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tracks = [];
        while ($row = $result->fetch_assoc()) {
            $tracks[] = $row['song_id'];
        }
        $stmt->close();
        return $tracks;
    }

    // Xóa bài hát khỏi danh sách lịch sử
    public function deleteHistory(int $userId, string $trackId): bool {
        $stmt = $this->conn->prepare("DELETE FROM historys WHERE user_id = ? AND song_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param("is", $userId, $trackId);
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        return $result;
    }

    // Lấy tổng số bài hát đã nghe của người dùng
    public function getTotalHistory(int $userId): int {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM historys WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['total'];
    }
}
?>
