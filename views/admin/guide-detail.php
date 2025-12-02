<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Chi tiết Hướng dẫn viên: <?= $guide['name'] ?></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="uploads/<?= $guide['image'] ?>" class="img-fluid rounded" style="max-height: 300px;">
                </div>
                <div class="col-md-8">
                    <p><strong>Email:</strong> <?= $guide['email'] ?></p>
                    <p><strong>SĐT:</strong> <?= $guide['phone'] ?></p>
                    <p><strong>Loại:</strong> <?= $guide['type'] ?></p>
                    <p><strong>Kinh nghiệm:</strong> <?= $guide['experience'] ?> năm</p>
                    <p><strong>Ngôn ngữ:</strong> <?= $guide['languages'] ?></p>
                    <a href="index.php?action=admin-list-guides" class="btn btn-primary">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</div>