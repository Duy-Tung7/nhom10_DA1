<h2>Thêm Booking</h2>

<?php if (!empty($message)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="index.php?action=booking-store" method="POST">

    <!-- Chọn Tour -->
    <label>Chọn Tour:</label>
    <select name="tour_id" required>
        <option value="">-- Chọn Tour --</option>
        <?php foreach ($tours as $tour): ?>
            <option value="<?= $tour['id'] ?>" 
                <?= (isset($_POST['tour_id']) && $_POST['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tour['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <!-- Khách hàng chính -->
    <label>Tên khách hàng:</label>
    <input type="text" name="contact_name" required value="<?= htmlspecialchars($_POST['contact_name'] ?? '') ?>">
    <br><br>

    <label>Số điện thoại:</label>
    <input type="text" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <br><br>

    <!-- Số người -->
    <label>Số người:</label>
    <input type="number" name="num_people" min="1" required value="<?= htmlspecialchars($_POST['num_people'] ?? 1) ?>">
    <br><br>

    <!-- Tổng tiền -->
    <label>Tổng tiền:</label>
    <input type="number" name="total_price" step="0.01" required value="<?= htmlspecialchars($_POST['total_price'] ?? 0) ?>">
    <br><br>

    <!-- Ngày tháng -->
    <label>Ngày đi:</label>
    <input type="date" name="start_date" required value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">
    <br><br>

    <label>Ngày đến:</label>
    <input type="date" name="end_date" required value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
    <br><br>

    <label>Ngày kết thúc:</label>
    <input type="date" name="finish_date" value="<?= htmlspecialchars($_POST['finish_date'] ?? '') ?>">
    <br><br>

    <!-- Chọn Hướng dẫn viên -->
    <label>Chọn hướng dẫn viên:</label>
    <select name="guide_id">
        <option value="">-- Chọn hướng dẫn viên --</option>
        <?php foreach ($guides as $guide): ?>
            <option value="<?= $guide['guide_id'] ?>"
                <?= (isset($_POST['guide_id']) && $_POST['guide_id'] == $guide['guide_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($guide['full_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <!-- Chọn nhiều khách hàng -->
    <label>Chọn khách hàng (nếu có):</label><br>

    <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $customer): ?>

            <?php
                $cid  = $customer['id']; 
                $cname = $customer['full_name'] ?? $customer['name'] ?? '';
                $cphone = $customer['phone'] ?? '';
            ?>

            <input
                type="checkbox"
                name="customer_ids[]"
                value="<?= $cid ?>"
                <?= (!empty($_POST['customer_ids']) && in_array($cid, $_POST['customer_ids'])) ? 'checked' : '' ?>
            >
            <?= htmlspecialchars($cname) ?> (<?= htmlspecialchars($cphone) ?>)
            <br>

        <?php endforeach; ?>
    <?php else: ?>
        <p>Chưa có khách hàng nào.</p>
    <?php endif; ?>

    <br><br>

    <!-- Ghi chú -->
    <label>Ghi chú:</label>
    <input type="text" name="note" value="<?= htmlspecialchars($_POST['note'] ?? '') ?>">
    <br><br>

    <button type="submit">Lưu Booking</button>
</form>
