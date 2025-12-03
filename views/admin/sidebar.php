    <?php
    $categoryModel = new Category();
    $categories = $categoryModel->getList();
    ?>
        <a href="<?= BASE_URL ?>?action=home" class="btn btn-primary">Đăng xuất</a>
    <hr>

    <div class="list-group">
        <a href="<?= BASE_URL ?>?action=admin-list-tour" class="list-group-item list-group-item-action">Dashboard</a>
        <a href="<?= BASE_URL ?>?action=admin-list-categories" class="list-group-item list-group-item-action">Quản lý danh mục</a>
        <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#categorySubmenu" role="button" aria-expanded="false" aria-controls="categorySubmenu">
        Quản lý tour
        </a>

        <div class="collapse list-group-flush" id="categorySubmenu">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>?action=admin-list-category&id=<?= $cat['id'] ?>" class="list-group-item list-group-item-action ps-4">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="<?= BASE_URL ?>?action=admin-list-tour" class="list-group-item list-group-item-action">Danh sách tất cả các Tour</a>
    </div>  