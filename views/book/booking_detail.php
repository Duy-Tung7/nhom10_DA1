<style>
.detail-box {
    width: 700px;
    margin: 30px auto;
    padding: 25px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 12px rgba(0,0,0,0.15);
    font-family: Arial;
}
.detail-box h2 {
    text-align: center;
}
.detail-box p {
    font-size: 16px;
    margin: 6px 0;
}
</style>

<div class="detail-box">
    <h2>Chi tiết Booking</h2>

    <p><b>Tour:</b> <?= htmlspecialchars($booking['tour_name']) ?></p>
    <p><b>Tên khách:</b> <?= htmlspecialchars($booking['contact_name']) ?></p>
    <p><b>Điện thoại:</b> <?= htmlspecialchars($booking['phone']) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($booking['email']) ?></p>
    <p><b>Số người:</b> <?= $booking['num_people'] ?></p>
    <p><b>Tổng tiền:</b> <?= number_format($booking['total_price'], 0, ',', '.') ?> VNĐ</p>
    <p><b>Ngày đi:</b> <?= $booking['start_date'] ?></p>
    <p><b>Ngày đến:</b> <?= $booking['end_date'] ?></p>
    <p><b>Ngày kết thúc:</b> <?= $booking['finish_date'] ?></p>
    <p><b>Hướng dẫn viên:</b> <?= htmlspecialchars($booking['guide_name'] ?? 'Chưa có') ?></p>
    <p><b>Ghi chú:</b> <?= htmlspecialchars($booking['note']) ?></p>

    <hr>
    <h3>Danh sách khách đi cùng</h3>

  <?php if (!empty($booking['customers'])): ?>
    <?php foreach ($booking['customers'] as $c): ?>
        <li>
            <?= htmlspecialchars($c['name'] ?? $c['name'] ?? '') ?>
            - <?= htmlspecialchars($c['phone'] ?? '') ?>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <p>Không có khách đi kèm.</p>
<?php endif; ?>

    <br>
    <a href="index.php?action=booking-list">
        <button>Quay lại</button>
    </a>
</div>
