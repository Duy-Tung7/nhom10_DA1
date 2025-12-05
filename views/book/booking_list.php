<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Booking</title>
    <style>
.sidebar {
    width: 250px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Tiêu đề */
.sidebar .title {
    padding: 10px;
    background: #f0f0f0;
    cursor: pointer;
    border-bottom: 1px solid #ccc;
    position: relative;
}

/* Mặc định submenu ẩn */
.sidebar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: none;
}

/* Chỉ mở submenu khi hover vào .title */
.sidebar .title:hover + ul {
    display: block;
}

/* Giữ submenu mở khi hover trong chính submenu */
.sidebar ul:hover {
    display: block;
}

.sidebar li {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    position: relative;
}

.sidebar li:hover {
    background: #f9f9f9;
}

.mini-btn {
    position: absolute;
    right: 10px;
    top: 10px;
    cursor: pointer;
}
.tourTable table {
    width: 100%;
    border-collapse: collapse; /* gộp đường viền */
    margin-top: 10px;
}

.tourTable th, .tourTable td {
    border: 1px solid #ccc;
    padding: 8px 12px;
    text-align: left;
}

.tourTable th {
    background-color: #f0f0f0;
}

.tourTable tr:hover {
    background-color: #f9f9f9;
}
.tourTable {
    display: none; /* Ẩn mặc định */
}
</style>

<div class="sidebar">
    <div class="title">Quản lý Booking</div>
    <table class="table">
    <ul>
        <li onclick="showTour('domestic')">
            Tour trong nước
            <button class="mini-btn" onclick="event.stopPropagation(); openForm('domestic')">+</button>
        </li>

        <li onclick="showTour('international')">
            Tour quốc tế
            <button class="mini-btn" onclick="event.stopPropagation(); openForm('international')">+</button>
        </li>

        <li onclick="showTour('custom')">
            Tour theo yêu cầu
            <button class="mini-btn" onclick="event.stopPropagation(); openForm('custom')">+</button>
        </li>
    </ul>
    </table>
</div>


<div class="content">

<?php

$bookingsByType = [
    'domestic' => [],
    'international' => [],
    'custom' => []
];

foreach ($bookings as $b) {

    $name = strtolower($b['tour_name']);

    // Danh sách tour trong nước
    $domestic = [
        "hạ long", "cát bà"
    ];

    // Danh sách tour nước ngoài
    $international = [
        "singapore", "malaysia"
    ];

    $type = "custom"; // mặc định là theo yêu cầu

    foreach ($domestic as $loc) {
        if (str_contains($name, $loc)) {
            $type = "domestic";
            break;
        }
    }

    foreach ($international as $loc) {
        if (str_contains($name, $loc)) {
            $type = "international";
            break;
        }
    }

    $bookingsByType[$type][] = $b;
}
?>
<?php
// Danh sách nhãn hiển thị
$labels = [
    'domestic'      => 'Tour trong nước',
    'international' => 'Tour quốc tế',
    'custom'        => 'Tour theo yêu cầu'
];

foreach ($labels as $type => $label):
?>
    <div id="<?= $type ?>" class="tourTable" style="<?= $type=='domestic' ? '' : 'display:none;' ?>">
        <h2><?= $label ?></h2>

        <?php if (!empty($bookingsByType[$type])): ?>
        <table>
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Tên khách</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đi</th>
                    <th>Ngày đến</th>
                    <th>Ngày kết thúc</th>
                    <th>HDV</th>
                    <th>Ngày tạo</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
<?php foreach ($bookingsByType[$type] as $b): ?>
    <tr>
        <td><?= htmlspecialchars($b['tour_name'] ?? '') ?></td>
<td><?= htmlspecialchars($b['contact_name'] ?? '') ?></td>
<td><?= htmlspecialchars($b['phone'] ?? '') ?></td>
<td><?= htmlspecialchars($b['email'] ?? '') ?></td>
<td><?= (int)($b['num_people'] ?? 0) ?></td>
<td><?= number_format($b['total_price'] ?? 0, 0, ',', '.') ?></td>
<td><?= $b['start_date'] ?? '' ?></td>
<td><?= $b['end_date'] ?? '' ?></td>
<td><?= $b['finish_date'] ?? '' ?></td>
<td><?= htmlspecialchars($b['guide_name'] ?? '') ?></td>
<td><?= $b['created_at'] ?? '' ?></td>
<td><?= htmlspecialchars($b['note'] ?? '') ?></td>
<td>
    <a href="index.php?action=booking_edit&id=<?= $b['id'] ?>">
        <button style="padding:4px 8px; cursor:pointer;">Sửa</button>
    </a>
</td>


</tr>
<?php endforeach; ?>
</tbody>

        </table>

        <?php else: ?>
            <p>Chưa có booking.</p>
        <?php endif; ?>

    </div>

<?php endforeach; ?>

</div>

<script>
function showTour(type) {
    document.querySelectorAll('.tourTable').forEach(tbl => tbl.style.display = 'none');
    document.getElementById(type).style.display = 'block';
}

function openForm(type) {
    window.location.href = "index.php?action=booking-create&tour_type=" + type;
}
</script>

</body>
</html>
