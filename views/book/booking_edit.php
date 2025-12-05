<style>
/* ---- CSS GIỮ NGUYÊN NHƯ FORM THÊM ---- */

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
form input[type="text"],
form input[type="email"],
form input[type="number"],
form input[type="date"],
form select {
    width: 100%;
    padding: 10px;
    border: 1px solid #bbb;
    border-radius: 5px;
    font-size: 15px;
    margin-bottom: 15px;
    transition: 0.3s;
}
form input:focus,
form select:focus {
    border-color: #3498db;
    box-shadow: 0 0 6px rgba(52, 152, 219, 0.25);
    outline: none;
}
form button {
    width: 100%;
    padding: 12px;
    background: #3498db;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 17px;
    cursor: pointer;
    transition: 0.3s;
}
form button:hover {
    background: #2980b9;
}
</style>


<h2>Sửa Booking</h2>

<?php if (!empty($message)): ?>
    <p style="color:red; font-weight:bold;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="index.php?action=booking-update" method="POST">

    <!-- ID Booking -->
    <input type="hidden" name="id" value="<?= $booking['id'] ?>">

    <!-- Chọn Tour -->
    <label>Chọn Tour:</label>
    <select name="tour_id" required>
        <?php foreach ($tours as $tour): ?>
            <option value="<?= $tour['id'] ?>" 
                <?= ($booking['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tour['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Tên khách -->
    <label>Tên khách hàng:</label>
    <input type="text" name="contact_name" required 
           value="<?= htmlspecialchars($booking['contact_name'] ?? '') ?>">

    <!-- Phone -->
    <label>Số điện thoại:</label>
    <input type="text" name="phone" required 
           value="<?= htmlspecialchars($booking['phone'] ?? '') ?>">

    <!-- Email -->
    <label>Email:</label>
    <input type="email" name="email" required 
           value="<?= htmlspecialchars($booking['email'] ?? '') ?>">

    <!-- Số người -->
    <label>Số người:</label>
    <input type="number" name="num_people" min="1" required
           value="<?= htmlspecialchars($booking['num_people'] ?? 1) ?>">

    <!-- Tổng tiền -->
    <label>Tổng tiền:</label>
    <input type="number" name="total_price" step="0.01" required
           value="<?= htmlspecialchars($booking['total_price'] ?? 0) ?>">

    <!-- Ngày đi -->
    <label>Ngày đi:</label>
    <input type="date" name="start_date" required
           value="<?= htmlspecialchars($booking['start_date'] ?? '') ?>">

    <!-- Ngày đến -->
    <label>Ngày đến:</label>
    <input type="date" name="end_date" required
           value="<?= htmlspecialchars($booking['end_date'] ?? '') ?>">

    <!-- Ngày kết thúc -->
    <label>Ngày kết thúc:</label>
    <input type="date" name="finish_date"
           value="<?= htmlspecialchars($booking['finish_date'] ?? '') ?>">

    <!-- Chọn HDV -->
    <label>Chọn hướng dẫn viên:</label>
    <select name="guide_id">
        <option value="">-- Chọn hướng dẫn viên --</option>
        <?php foreach ($guides as $guide): ?>
            <option value="<?= $guide['guide_id'] ?>" <?= (isset($_POST['guide_id']) && $_POST['guide_id'] == $guide['guide_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($guide['full_name'] ?? '') ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <!-- Chọn nhiều khách hàng -->
    <label>Chọn khách hàng (nếu có):</label><br>
    <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $customer): ?>
            <?php 
                $cid = $customer['customer_id'] ?? $customer['id'];
                $cname = $customer['full_name'] ?? $customer['name'] ?? '';
                $cphone = $customer['phone'] ?? '';
            ?>
            <input type="checkbox" name="customer_ids[]" value="<?= $cid ?>" 
                <?= (isset($_POST['customer_ids']) && in_array($cid, $_POST['customer_ids'])) ? 'checked' : '' ?>>
            <?= htmlspecialchars($cname) ?> (<?= htmlspecialchars($cphone) ?>)<br>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Chưa có khách hàng nào.</p>
    <?php endif; ?>
    <br>

    <!-- Ghi chú -->
    <label>Ghi chú:</label>
    <input type="text" name="note" value="<?= htmlspecialchars($booking['note'] ?? '') ?>">

    <button type="submit">Cập nhật Booking</button>
</form>
