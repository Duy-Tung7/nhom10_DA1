<h2>Thêm Booking</h2>

<?php if (!empty($message)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="index.php?action=booking-store" method="POST">
    <input type="hidden" name="tour_type" value="<?= htmlspecialchars($_GET['tour_type'] ?? '') ?>">

    <label>Chọn Tour:</label>
    <select name="tour_id" required>
        <option value="">-- Chọn Tour --</option>
        <?php foreach ($tours as $tour): ?>
            <option value="<?= $tour['id'] ?>" <?= (isset($_GET['tour_type']) && $_GET['tour_type']==$tour['type']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tour['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Tên khách hàng:</label>
    <input type="text" name="contact_name" required>
    <br><br>

    <label>Số điện thoại:</label>
    <input type="text" name="phone" required>
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" required>
    <br><br>

    <label>Số người:</label>
    <input type="number" name="num_people" required min="1">
    <br><br>

    <label>Tổng tiền:</label>
    <input type="number" name="total_price" required step="0.01">
    <br><br>

    <label>Ngày đi:</label>
    <input type="date" name="start_date" required>
    <br><br>

    <label>Ngày đến:</label>
    <input type="date" name="end_date" required>
    <br><br>

    <label>Ngày kết thúc:</label>
    <input type="date" name="finish_date">
    <br><br>

    <!-- ĐÃ SỬA Ở ĐÂY -->
    <select name="guide_name">
        <option value="">-- Chọn hướng dẫn viên --</option>
        <?php foreach ($guides as $guide): ?>
            <option value="<?= htmlspecialchars($guide['name']) ?>">
                <?= htmlspecialchars($guide['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Ghi chú:</label>
    <input type="text" name="note">
    <br><br>

    <button type="submit">Lưu Booking</button>
</form>
