<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Booking</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; display: flex; gap: 20px; }
        .menu { width: 250px; border: 1px solid #ccc; border-radius: 5px; }
        .menu h3 { margin: 0; padding: 10px; background-color: #f0f0f0; border-bottom: 1px solid #ccc; }
        .menu ul { list-style: none; margin: 0; padding: 0; }
        .menu li { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; position: relative; }
        .menu li:hover { background-color: #f9f9f9; }
        .mini-btn { position: absolute; right: 10px; top: 10px; cursor: pointer; }
        .content { flex: 1; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>

<div class="menu">
    <h3>Quản lý Booking</h3>
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
