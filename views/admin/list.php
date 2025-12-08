
<div class="container mt-4">
    <h2>Danh sách khách hàng</h2>
     <a href="<?= BASE_URL ?>?action=admin-list-categories" class="btn btn-success mb-3">Quay lại</a>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên khách</th>
                <th>Giới tính</th>
                <th>Điện thoại</th>
                <th>Email booking</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Tổng tiền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($customers as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= $c['name'] ?></td>
                <td><?= $c['gender'] ?></td>
                <td><?= $c['phone'] ?></td>
                <td><?= $c['email'] ?></td>
                <td><?= $c['start_date'] ?></td>
                <td><?= $c['end_date'] ?></td>
                <td><?= number_format($c['total_price'],0,",",".") ?> VND</td>
                <td>
                    <a href="?action=customer-detail&id=<?= $c['id'] ?>" class="btn btn-info btn-sm">Xem</a>
                    
                    <a href="?action=customer-delete&id=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

