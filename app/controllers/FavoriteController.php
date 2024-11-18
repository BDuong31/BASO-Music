<?php declare(strict_types=1);
// controllers/FavoriteController.php
require_once __DIR__ . '/../services/FavoritesService.php';

class FavoriteController {
    private $favoriteService;

    public function __construct() {
        $this->favoriteService = new FavoritesService();
    }

    // Thêm bài hát vào danh sách yêu thích
    public function addFavorite($data) {
        if (isset($data['userId'], $data['trackId'])) {
            $response = $this->favoriteService->addFavorite((int)$data['userId'], (string)$data['trackId']);
        } else {
            $response = ['status' => '03', 'message' => 'Thiếu tham số userId hoặc trackId'];
        }
        echo json_encode($response);
        return $response;
    }

    // Xóa bài hát khỏi danh sách yêu thích
    public function removeFavorite($data) {
        if (isset($data['userId'], $data['trackId'])) {
            $response = $this->favoriteService->removeFavorite((int)$data['userId'], (string)$data['trackId']);
        } else {
            $response = ['status' => '03', 'message' => 'Thiếu tham số userId hoặc trackId'];
        }
        echo json_encode($response);
        return $response;
    }

    // Lấy danh sách bài hát yêu thích
    public function getFavorites($userId) {
        if (isset($userId)) {
            $response = $this->favoriteService->getFavorites($userId);
        } else {
            $response = ['status' => '03', 'message' => 'Thiếu tham số userId'];
        }
        return $response;
    }

}
?>
