<?php declare(strict_types=1);
require_once __DIR__ . '/../models/FavoriteSong.php';

class FavoritesService{
    private $favoriteModel;

    public function __construct(){
        $this->favoriteModel = new Favorite();
    }

    // Thêm bài hát vào danh sách yêu thích
    public function addFavorite($userId, $trackId){
        if (empty($userId) || empty($trackId)) {
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }
        try {
            $result = $this->favoriteModel->addFavorite((int)$userId, (string)$trackId);
            return $result
               ? ['status' => '00', 'message' => 'Thêm bài hát vào danh sách yêu thích thành công']
               : ['status' => '02', 'message' => 'Thêm bài hát vào danh sách yêu thích thất bại'];
        } catch (Exception $e){
            return ['status' => '02', 'message' => 'Loi: '. $e->getMessage()];
        }
    }

    // Xóa bài hát kh��i danh sách yêu thích
    public function removeFavorite($userId, $trackId){
        $result = $this->favoriteModel->removeFavorite((int)$userId, (string)$trackId);
        if($result){
            return ['status' => '00', 'message' => 'Xóa bài hát kh��i danh sách yêu thích thành công'];
        } else {
            return ['status' => '02', 'message' => 'Xóa bài hát kh��i danh sách yêu thích thất bại'];
        }
    }

    // Lấy danh sách bài hát yêu thích
    public function getFavorites($userId){
        if (empty($userId)) {
            return ['status' => '01', 'message' => 'Dữ liệu không hợp lệ'];
        }
        $tracks = $this->favoriteModel->getFavorites((int)$userId);
        if($tracks){
            return ['status' => '00', 'tracks' => $tracks];
        } else {
            return ['status' => '01', 'message' => 'Không tìm thấy bài hát yêu thích'];
        }
    }

}

?>
