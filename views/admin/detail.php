<div class="container mt-4">
    <h2>Chi tiết khách hàng: <?= $customer['name'] ?></h2>
    <a href="<?= BASE_URL ?>?action=admin-list-categories" class="btn btn-success mb-3">Quay lại</a>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Thông tin khách hàng</div>
        <div class="card-body">
            <p><strong>ID:</strong> <?= $customer['id'] ?></p>
            <p><strong>Tên:</strong> <?= $customer['name'] ?></p>
            <p><strong>Giới tính:</strong> <?= $customer['gender'] ?></p>
            <p><strong>Hộ chiếu:</strong> <?= $customer['passport'] ?></p>
            <p><strong>Điện thoại:</strong> <?= $customer['phone'] ?></p>
            <p><strong>Yêu cầu đặc biệt:</strong> <?= $customer['request'] ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white">Thông tin Booking</div>
        <div class="card-body">
            <p><strong>Tên người liên hệ:</strong> <?= $customer['contact_name'] ?></p>
            <p><strong>Điện thoại booking:</strong> <?= $customer['booking_phone'] ?></p>
            <p><strong>Email:</strong> <?= $customer['email'] ?></p>
            <p><strong>Ngày bắt đầu:</strong> <?= $customer['start_date'] ?></p>
            <p><strong>Ngày kết thúc:</strong> <?= $customer['end_date'] ?></p>
            <p><strong>Tổng tiền:</strong> <?= number_format($customer['total_price'], 0, ",", ".") ?> VND</p>
        </div>
    </div>
</div>
