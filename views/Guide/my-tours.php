<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Lịch Tour của tôi</h3>
        <a href="index.php?action=guide-dashboard" class="btn btn-secondary" style="text-decoration: none; border: 1px solid #ccc; padding: 5px 10px; color: #333; border-radius: 5px;">
            &larr; Về Dashboard
        </a>
    </div>

    <table class="table table-bordered" border="1" style="width: 100%; border-collapse: collapse; text-align: center; margin-top: 10px;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px;">ID Tour</th>
                <th style="padding: 10px;">Tên Tour</th>
                <th style="padding: 10px;">Ngày đi</th>
                <th style="padding: 10px;">Giá vé</th>
                <th style="padding: 10px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($tours) && is_array($tours) && count($tours) > 0): ?>
                <?php foreach ($tours as $t): ?>
                    <tr>
                        <td style="padding: 10px;"><?= $t['id'] ?></td>
                        <td style="padding: 10px; font-weight: bold;"><?= $t['name'] ?></td>
                        <td style="padding: 10px;"><?= $t['start_date'] ?? 'Đang cập nhật' ?></td>
                        <td style="padding: 10px; color: #d32f2f;"><?= number_format($t['price'] ?? 0) ?> VNĐ</td>
                        
                        <td style="padding: 10px;">
                            <a href="index.php?action=guide-guests&tour_id=<?= $t['id'] ?>" 
                               class="btn btn-primary"
                               style="background: #007bff; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px;">
                               Xem danh sách khách
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #666;">
                        Bạn chưa được phân công tour nào.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>