<div class="container">
    <div class="row">
        <div class="col-12">


            <a href="<?= BASE_URL ?>?action=admin-list-categories" class="btn btn-success mb-3">Quay lại</a>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Tour</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Thời lượng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listData as $tour): ?>
                        <tr>
                            <td><?= $tour['id'] ?></td>
                            <td><?= $tour['name'] ?></td>
                            <td><?= $tour['category_id'] ?></td>
                            <td><?= number_format($tour['base_price']) ?> VND</td>
                            <td><?= $tour['duration'] ?> ngày</td>
                            <td>
                                <a href="<?= BASE_URL ?>?action=admin-update-tour&id=<?= $tour['id'] ?>" class="btn btn-success btn-sm">Sửa</a>
                                <a href="<?= BASE_URL ?>?action=admin-delete-tour&id=<?= $tour['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">Xóa</a>
                                <a href="<?= BASE_URL ?>?action=admin-tour-detail&id=<?= $tour['id'] ?>" class="btn btn-info btn-sm">Chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>