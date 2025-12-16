<?php
// Giữ nguyên logic xử lý dữ liệu của bạn
$bookingsByType = [
    'domestic' => [],
    'international' => [],
    'custom' => []
];

foreach ($bookings as $b) {
    $name = strtolower($b['tour_name']);
    
    // Danh sách tour trong nước
    $domestic = ["hạ long", "cát bà", "đà nẵng", "hội an", "phú quốc"]; // Thêm ví dụ để bắt tốt hơn
    
    // Danh sách tour nước ngoài
    $international = ["singapore", "malaysia", "thái lan", "nhật bản"];

    $type = "custom"; // Mặc định

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

<style>
    .booking-content .menu { width: 100%; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
    .booking-content .menu ul { list-style: none; margin: 0; padding: 0; display: flex; }
    .booking-content .menu li { padding: 10px 20px; cursor: pointer; position: relative; border: 1px solid transparent; border-bottom: none; }
    .booking-content .menu li:hover { background-color: #f9f9f9; }
    .booking-content .menu li.active { border-color: #ccc; border-bottom-color: white; font-weight: bold; }
    
    .mini-btn { margin-left: 10px; font-size: 0.8em; padding: 2px 5px; }
    
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
    th { background-color: #f0f0f0; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0 bg-dark" style="min-height: 100vh;">
            <?php require_once 'views/admin/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10 p-4 booking-content">
            <h3 class="mb-4">Quản lý Booking</h3>
            <a href="<?= BASE_URL ?>?action=booking-careate" class="btn btn-primary btn-sm">Thêm mới</a>
            <div class="menu">
                <ul>
                    <li onclick="showTour('domestic')" id="tab-domestic">
                        Tour trong nước
                        <button class="btn btn-sm btn-primary mini-btn" onclick="event.stopPropagation(); openForm('domestic')">+</button>
                    </li>
                    <li onclick="showTour('international')" id="tab-international">
                        Tour quốc tế
                        <button class="btn btn-sm btn-primary mini-btn" onclick="event.stopPropagation(); openForm('international')">+</button>
                    </li>
                    <li onclick="showTour('custom')" id="tab-custom">
                        Tour theo yêu cầu
                        <button class="btn btn-sm btn-primary mini-btn" onclick="event.stopPropagation(); openForm('custom')">+</button>
                    </li>
                </ul>
            </div>

            <?php
            $labels = [
                'domestic' => 'Tour trong nước',
                'international' => 'Tour quốc tế',
                'custom' => 'Tour theo yêu cầu'
            ];

            foreach ($labels as $type => $label): 
            ?>
                <div id="<?= $type ?>" class="tourTable" style="<?= $type == 'domestic' ? '' : 'display:none;' ?>">
                    <h4><?= $label ?></h4>

                    <?php if (!empty($bookingsByType[$type])): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
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
                                            <td><?= htmlspecialchars($b['note'] ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="alert alert-warning mt-3">Chưa có booking nào.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
</div>

<script>
function showTour(type) {
    // Ẩn tất cả các bảng
    document.querySelectorAll('.tourTable').forEach(tbl => tbl.style.display = 'none');
    // Hiển thị bảng được chọn
    document.getElementById(type).style.display = 'block';
    
    // Xử lý active tab (tùy chọn để đẹp hơn)
    // Reset style các tab (nếu cần thêm logic highlight tab)
}

function openForm(type) {
    window.location.href = "index.php?action=booking-create&tour_type=" + type;
}
</script>