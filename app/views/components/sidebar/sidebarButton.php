<?php declare(strict_types=1);

function renderSidebarButton($title, $icon, $to) {
    // Lấy đường dẫn hiện tại từ PHP
    $currentPath = $_SERVER['REQUEST_URI'];
    
    // Sử dụng parse_url để chỉ lấy phần đường dẫn
    $path = parse_url($currentPath, PHP_URL_PATH);
    
    // Đặt mặc định nút "Library" khi ở trang chủ "/"
    $isActive = ($path === $to) || ($to === '/home/library' && $path === '/home');
    
    $btnClass = $isActive ? "btn-body active" : "btn-body";
    ?>
    <a href="<?php echo $to; ?>">
        <div class="<?php echo $btnClass; ?>">
            <i class="btn-icon <?php echo $icon; ?>"></i>
            <p class="btn-title"><?php echo $title; ?></p>
        </div>
    </a>
<?php } ?>
