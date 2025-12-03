<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="min-height: 100vh;">
    <a href="index.php?action=admin-dashboard" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="fas fa-user-cog fa-2x me-2"></i>
        <span class="fs-4">Admin Panel</span>
    </a>
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="index.php?action=admin-dashboard" class="nav-link text-white <?php echo (isset($_GET['action']) && $_GET['action'] == 'admin-dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="index.php?action=admin-list-categories" class="nav-link text-white <?php echo (isset($_GET['action']) && $_GET['action'] == 'admin-list-categories') ? 'active' : ''; ?>">
                <i class="fas fa-list me-2"></i> QL Danh mục
            </a>
        </li>

        <?php
            // Lấy danh sách danh mục để làm menu con cho Tour
            // Kiểm tra class tồn tại để tránh lỗi nếu chưa include file Model
            $categories = [];
            if (class_exists('Category')) {
                $catModel = new Category();
                $categories = $catModel->getList();
            }
        ?>
        <li class="nav-item">
            <a href="#tourSubmenu" data-bs-toggle="collapse" class="nav-link text-white dropdown-toggle">
                <i class="fas fa-plane me-2"></i> QL Tour
            </a>
            <div class="collapse <?php echo (isset($_GET['action']) && strpos($_GET['action'], 'tour') !== false) ? 'show' : ''; ?>" id="tourSubmenu">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4 bg-secondary rounded mt-1">
                    <li><a href="index.php?action=admin-list-tour" class="link-light rounded text-decoration-none d-block p-2">Tất cả Tour</a></li>
                    <?php foreach ($categories as $cat): ?>
                    <li><a href="index.php?action=admin-list-tour&category_id=<?= $cat['id'] ?>" class="link-light rounded text-decoration-none d-block p-2"><?= $cat['name'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="index.php?action=admin-list-guides" class="nav-link text-white <?php echo (isset($_GET['action']) && $_GET['action'] == 'admin-list-guides') ? 'active' : ''; ?>">
                <i class="fas fa-id-card-alt me-2"></i> QL Hướng dẫn viên
            </a>
        </li>
    </ul>
    
    <hr>
    
    <div class="dropdown">
        <a href="index.php?action=home" class="d-flex align-items-center text-white text-decoration-none btn btn-danger w-100 justify-content-center">
            <i class="fas fa-sign-out-alt me-2"></i>
            <strong>Đăng xuất</strong>
        </a>
    </div>
</div>