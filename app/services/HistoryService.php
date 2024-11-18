<?php declare(strict_types=1);
require_once __DIR__ . '/../models/HistorySong.php';

class HistoryService {
    private $historyModel;
    private const MAX_HISTORY = 50;

    public function __construct() {
        $this->historyModel = new History();
    }

    // Thêm bài hát vào lịch sử nghe nhạc của người dùng
    public function addHistory($userId, $trackId) {
        if (empty($userId) || empty($trackId)) {
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }

        try {
            $result = $this->historyModel->addHistory((int) $userId, (string) $trackId);
            return $result 
                ? ['status' => '00', 'message' => 'Thêm vào lịch sử thành công'] 
                : ['status' => '01', 'message' => 'Thêm vào lịch sử thất bại'];
        } catch (Exception $e) {
            return ['status' => '01', 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    // Lấy danh sách bài hát đã nghe của người dùng
    public function getHistory($userId) {
        if (empty($userId)) {
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }
        $tracks = $this->historyModel->getHistory((int)$userId);
        if($tracks){
            return ['status' => '00', 'tracks' => $tracks];
        } else {
            return ['status' => '01', 'message' => 'Không tìm thấy lịch sử'];
        }
    }

    // Xóa bài hát khỏi danh sách lịch sử của người dùng
    public function deleteHistory($userId, $trackId) {
        if (empty($userId) || empty($trackId)) {
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }

        try {
            $result = $this->historyModel->deleteHistory((int) $userId, (string) $trackId);
            return $result 
                ? ['status' => '00', 'message' => 'Xóa thành công'] 
                : ['status' => '01', 'message' => 'Không thể xóa bài hát'];
        } catch (Exception $e) {
            return ['status' => '01', 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }

    // Lấy số lượng bài hát đã nghe của người dùng
    public function getTotalHistory($userId) {
        if (empty($userId)) {
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }

        try {
            $total = $this->historyModel->getTotalHistory((int) $userId);
            return ['status' => '00', 'total' => $total];
        } catch (Exception $e) {
            return ['status' => '01', 'message' => 'Lỗi: ' . $e->getMessage()];
        }
    }
}

?>
