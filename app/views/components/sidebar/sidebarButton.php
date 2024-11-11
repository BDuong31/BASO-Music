<?php declare(strict_types=1); 
// Hàm render nút sidebar
function renderSidebarButton($title, $icon, $to) {
    // Lấy đường dẫn hiện tại từ PHP
    $currentPath = $_SERVER['REQUEST_URI'];
    $currentPath = $_SERVER['REQUEST_URI'];

    // Sử dụng parse_url để chỉ lấy phần đường dẫn
    $path = parse_url($currentPath, PHP_URL_PATH);
    
    // Sử dụng basename để lấy tên file và tham số truy vấn
    $fileWithQuery = basename($currentPath);
    // Kiểm tra nếu đường dẫn hiện tại khớp với $to
    $isActive = ($fileWithQuery === $to);
    $btnClass = $isActive ? "btn-body active" : "btn-body";
    ?>
    <a href="<?php echo $to; ?>">
        <div class="<?php echo $btnClass; ?>">
            <i class="btn-icon <?php echo $icon; ?>"></i>
            <p class="btn-title"><?php echo $title; ?></p>
        </div>
    </a>
<?php } ?>
