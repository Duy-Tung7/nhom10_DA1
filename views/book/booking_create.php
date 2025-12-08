<style>
    /* Khung chứa form */
    form {
        width: 600px;
        margin: 30px auto;
        padding: 25px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        font-family: Arial, sans-serif;
    }

    /* Tiêu đề */
    h2 {
        text-align: center;
        font-size: 26px;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    /* Label */
    form label {
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 6px;
    }

    /* Input, Select */
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

    /* Input focus */
    form input:focus,
    form select:focus {
        border-color: #3498db;
        box-shadow: 0 0 6px rgba(52, 152, 219, 0.25);
        outline: none;
    }

    /* Checkbox danh sách khách hàng */
    form input[type="checkbox"] {
        margin-right: 5px;
    }

    /* Nút submit */
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

    /* Hover */
    form button:hover {
        background: #2980b9;
    }

    /* Thông báo lỗi */
    p.error-message {
        text-align: center;
        color: #e74c3c;
        font-weight: bold;
        font-size: 16px;
    }
</style>
<h2>Thêm Booking</h2>

<?php if (!empty($message)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="index.php?action=book-booking_store" method="POST">

    <!-- Chọn Tour -->
    <label>Chọn Tour:</label>
    <select name="tour_id" required>
        <option value="">-- Chọn Tour --</option>
        <?php foreach ($tours as $tour): ?>
            <option value="<?= $tour['id'] ?>" <?= (isset($_POST['tour_id']) && $_POST['tour_id'] == $tour['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tour['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <!-- Thông tin khách hàng chính -->
    <label>Tên khách hàng:</label>
    <input type="text" name="contact_name" required value="<?= htmlspecialchars($_POST['contact_name'] ?? '') ?>">
    <br><br>

    <label>Số điện thoại:</label>
    <input type="text" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <br><br>

    <!-- Số người & tổng tiền -->
    <label>Số người:</label>
    <input type="number" name="num_people" required min="1" value="<?= htmlspecialchars($_POST['num_people'] ?? 1) ?>">
    <br><br>

    <label>Tổng tiền:</label>
    <input type="number" name="total_price" required step="0.01" value="<?= htmlspecialchars($_POST['total_price'] ?? 0) ?>">
    <br><br>

 <label>Ngày đi:</label>
<input type="date" id="start_date" name="start_date" placeholder="dd/mm/yyyy" required
       value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">

<label>Ngày đến:</label>
<input type="date" id="end_date" name="end_date" placeholder="dd/mm/yyyy" required
       value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">

<label>Ngày kết thúc:</label>
<input type="date" id="finish_date" name="finish_date" placeholder="dd/mm/yyyy"
       value="<?= htmlspecialchars($_POST['finish_date'] ?? '') ?>">


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
    <input type="text" name="note" value="<?= htmlspecialchars($_POST['note'] ?? '') ?>">
    <br><br>
    <a href="<?= BASE_URL ?>?action=book-booking_list" class="btn btn-success mb-3">Quay lại</a>
    <button type="submit">Lưu Booking</button>
</form>