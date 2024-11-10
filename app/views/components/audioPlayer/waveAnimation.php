<?php declare(strict_types=1); 
function renderWaveAnimation($isPlaying) {
    // Đặt lớp cho wave boxes dựa trên trạng thái isPlaying
    $waveClass = $isPlaying ? "box active" : "box";
    
    // Các box bạn muốn hiển thị
    $boxes = ["box1", "box2", "box3", "box4", "box5", "box6", "box7", "box2", "box3", "box4", "box5", "box6", "box7"];
    
    // Hiển thị các box
    echo '<div class="box-container flex">';
    foreach ($boxes as $box) {
        echo '<div class="' . $waveClass . ' ' . $box . '"></div>';
    }
    echo '</div>';
}
?>