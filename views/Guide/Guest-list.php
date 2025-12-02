<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Danh sách khách hàng theo Tour</h3>
        
        <a href="index.php?action=guide-dashboard" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">
            &larr; Về Dashboard
        </a>
    </div>
    <?php if (isset($guests) && count($guests) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên khách</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Check in</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guests as $g): ?>
                    <tr>
                        <td><?= $g['id'] ?></td>
                        <td><?= $g['name'] ?></td>
                        <td><?= $g['phone'] ?></td>
                        <td><?= $g['email'] ?></td>

                        <td>
                            <?php if ($g['status'] == 2): ?>
                                <span style="color: green; font-weight: bold;">✅ Đã tham gia</span>
                            <?php else: ?>
                                <div>
                                    <?php
                                    if ($g['status'] == 1) echo '<span style="color:blue">Đã xác nhận</span>';
                                    else echo '<span style="color:red">Chờ xử lý</span>';
                                    ?>
                                </div>

                                <a href="index.php?action=check-in&id=<?= $g['id'] ?>&tour_id=<?= $_GET['tour_id'] ?? $g['tour_id'] ?>"
                                   class="btn btn-success btn-sm"
                                   style="margin-top: 5px; display: inline-block; padding: 5px 10px; background: #28a745; color: white; text-decoration: none; border-radius: 4px;"
                                   onclick="return confirm('Xác nhận khách <?= $g['name'] ?> đã có mặt?')">
                                    Check-in ngay
                                </a>
                            <?php endif; ?>
                        </td>

                        <td><?= $g['note'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có khách nào trong tour này.</p>
        <a href="index.php?action=guide-dashboard" class="btn btn-secondary">&larr; Quay lại Dashboard</a>
    <?php endif; ?>
</div>