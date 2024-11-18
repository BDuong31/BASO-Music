<?php declare(strict_types=1);
require_once __DIR__ . '/../services/HistoryService.php';

class HistoryController {
    private $historyService;

    public function __construct() {
        $this->historyService = new HistoryService();
    }

    // API thêm bài hát vào lịch sử
    public function addHistory($data) {
        if (empty($data['userId']) || empty($data['trackId'])) {
            echo json_encode(['status' => '01', 'message' => 'Dữ liệu không hợp lệ']);
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }

        $response = $this->historyService->addHistory($data['userId'], $data['trackId']);
        echo json_encode($response);
        return $response;
    }

    // API lấy danh sách lịch sử nghe nhạc của người dùng
    public function getHistory($userId) {
        if (isset($userId)) {
            $response = $this->historyService->getHistory($userId);
        } else {
            $response = ['status' => '03', 'message' => 'Thiếu tham số userId'];
        }

        return $response;
    }

    // API xóa bài hát khỏi lịch sử
    public function deleteHistory($data) {
        if (empty($data['userId']) || empty($data['trackId'])) {
            echo json_encode(['status' => '01', 'message' => 'Dữ liệu không hợp lệ']);
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }

        $response = $this->historyService->deleteHistory($data['userId'], $data['trackId']);
        echo json_encode($response);
        return $response;
    }

    // API lấy tổng số bài hát trong lịch sử của người dùng
    public function getTotalHistory($userId) {
        if (empty($userId)) {
            echo json_encode(['status' => '01', 'message' => 'Dữ liệu không hợp lệ']);
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }

        $response = $this->historyService->getTotalHistory($userId);
        echo json_encode($response);
        return $response;
    }
}
?>
