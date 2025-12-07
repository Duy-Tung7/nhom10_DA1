<style>
form {
    width: 600px;
    margin: 30px auto;
    padding: 25px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 12px rgba(0,0,0,0.15);
    font-family: Arial, sans-serif;
}

h2 {
    text-align: center;
    font-size: 26px;
    color: #2c3e50;
    margin-bottom: 20px;
}

form label {
    font-weight: bold;
    color: #333;
    display: block;
    margin-bottom: 6px;
}

form input {
    width: 100%;
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 5px;
    font-size: 15px;
    margin-bottom: 15px;
    background: #f3f3f3;
}

.back-btn {
    width: 100%;
    padding: 12px;
    background: #7f8c8d;
    color: #fff;
    border-radius: 6px;
    font-size: 17px;
    border: none;
    text-align: center;
    cursor: pointer;
    text-decoration: none;
    display: block;
}
</style>

<h2>Chi Tiết Booking</h2>

<form>

    <label>Tour:</label>
    <input type="text" value="<?= htmlspecialchars($booking['tour_name'] ?? '') ?>" readonly>

    <label>Tên khách hàng:</label>
    <input type="text" value="<?= htmlspecialchars($booking['contact_name'] ?? '') ?>" readonly>

    <label>Số điện thoại:</label>
    <input type="text" value="<?= htmlspecialchars($booking['phone'] ?? '') ?>" readonly>

    <label>Email:</label>
    <input type="email" value="<?= htmlspecialchars($booking['email'] ?? '') ?>" readonly>

    <label>Số người:</label>
    <input type="number" value="<?= htmlspecialchars($booking['num_people'] ?? '') ?>" readonly>

    <label>Tổng tiền:</label>
    <input type="number" value="<?= htmlspecialchars($booking['total_price'] ?? '') ?>" readonly>

    <label>Ngày đi:</label>
    <input type="date" value="<?= htmlspecialchars($booking['start_date'] ?? '') ?>" readonly>

    <label>Ngày về:</label>
    <input type="date" value="<?= htmlspecialchars($booking['end_date'] ?? '') ?>" readonly>

    <label>Ngày kết thúc:</label>
    <input type="date" value="<?= htmlspecialchars($booking['finish_date'] ?? '') ?>" readonly>

    <label>Hướng dẫn viên:</label>
    <input type="text" value="<?= htmlspecialchars($booking['guide_name'] ?? 'Chưa phân công') ?>" readonly>

    <label>Danh sách khách đi kèm:</label>
    <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $c): ?>
            <input type="text" value="<?= htmlspecialchars($c['full_name'] . ' - ' . $c['phone']) ?>" readonly>
        <?php endforeach; ?>
    <?php else: ?>
        <input type="text" value="Không có khách đi kèm" readonly>
    <?php endif; ?>

    <label>Ghi chú:</label>
    <input type="text" value="<?= htmlspecialchars($booking['note'] ?? '') ?>" readonly>

    <a href="index.php?action=booking_list" class="back-btn">Quay lại danh sách</a>

</form>
