<div class="row">
    <div class="col-3">
        <?php include PATH_VIEW . "admin/sidebar.php"; ?>
    </div>

    <div class="col-9">
        <h3><?= $title ?></h3>

        <?php if (!empty($showTours)): ?>
            <!-- Nếu đang ở phần quản lý tour -->
            <a href="<?= BASE_URL ?>?action=admin-create-tour" class="btn btn-success btn-sm mb-3">Thêm tour mới</a>
        <?php else: ?>
            <!-- Nếu đang ở phần quản lý danh mục -->
            <a href="<?= BASE_URL ?>?action=admin-create-categories" class="btn btn-success btn-sm mb-3">Thêm danh mục mới</a>
        <?php endif; ?>

        <?php if (!empty($listData)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <?php if (!empty($showTours)): ?>
                            <th>Tên Tour</th>
                            <th>Giá</th>
                            <th>Thời lượng</th>
                            <th>Hành động</th>
                        <?php else: ?>
                            <th>Tên Danh mục</th>
                            <th>Hành động</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listData as $key => $item): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <?php if (!empty($showTours)): ?>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= number_format($item['base_price']) ?> VND</td>
                                <td><?= $item['duration'] ?> ngày</td>
                                <td>
                                    <a href="<?= BASE_URL ?>?action=admin-update-tour&id=<?= $item['id'] ?>" class="btn btn-success btn-sm">Sửa</a>
                                    <a href="<?= BASE_URL ?>?action=admin-delete-tour&id=<?= $item['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">Xóa</a>
                                    <a href="<?= BASE_URL ?>?action=admin-tour-detail&id=<?= $item['id'] ?>" class="btn btn-info btn-sm">Chi tiết</a>
                                </td>

                            <?php else: ?>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>?action=admin-update-categories&id=<?= $item['id'] ?>" class="btn btn-success btn-sm">Sửa</a>

                                </td>



                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có dữ liệu.</p>
        <?php endif; ?>
    </div>
</div>