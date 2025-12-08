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
            float: left;
            margin-right: 20px;
        }

        .sidebar .title {
            padding: 10px;
            background: #f0f0f0;
            cursor: pointer;
            border-bottom: 1px solid #ccc;
        }

        .sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: none;
        }

        .sidebar .title:hover+ul,
        .sidebar ul:hover {
            display: block;
        }

        .sidebar li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            position: relative;
        }

        .mini-btn {
            position: absolute;
            right: 10px;
            top: 10px;
        }

        .content {
            margin-left: 280px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
        }

        th {
            background: #f0f0f0;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .tourTable {
            display: none;
        }

        .actions a {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="title">Quản lý Booking</div>
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
            $name = strtolower($b['tour_name'] ?? '');

            $domestic = ["hạ long", "cát bà"];
            $international = ["singapore", "malaysia"];

            $type = "custom";

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

        $labels = [
            'domestic'      => 'Tour trong nước',
            'international' => 'Tour quốc tế',
            'custom'        => 'Tour theo yêu cầu'
        ];

        foreach ($labels as $type => $label):
        ?>
            <div id="<?= $type ?>" class="tourTable" style="<?= $type == 'domestic' ? '' : 'display:none;' ?>">
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
                                <th>Kết thúc</th>
                                <th>HDV</th>
                                <th>Ngày tạo</th>
                                <th>Ghi chú</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($bookingsByType[$type] as $b): ?>
                                <tr>
                                    <td><?= htmlspecialchars($b['tour_name']) ?></td>
                                    <td><?= htmlspecialchars($b['contact_name']) ?></td>
                                    <td><?= htmlspecialchars($b['phone']) ?></td>
                                    <td><?= htmlspecialchars($b['email']) ?></td>
                                    <td><?= (int)$b['num_people'] ?></td>
                                    <td><?= number_format($b['total_price'], 0, ',', '.') ?></td>
                                    <td><?= $b['start_date'] ?></td>
                                    <td><?= $b['end_date'] ?></td>
                                    <td><?= $b['finish_date'] ?></td>
                                    <td><?= htmlspecialchars($b['guide_name']) ?></td>
                                    <td><?= $b['created_at'] ?></td>
                                    <td><?= htmlspecialchars($b['note']) ?></td>

                                    <td class="actions">
                                        <a href="<?= BASE_URL ?>?action=book-booking_update&id=<?= $b['id'] ?>">Sửa</a>
                                        
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
            window.location.href = "index.php?action=book-booking_create&tour_type=" + type;
        }
    </script>

    <a href="<?= BASE_URL ?>?action=admin-list-categories" class="btn btn-success">Quay lại</a>

</body>

</html>