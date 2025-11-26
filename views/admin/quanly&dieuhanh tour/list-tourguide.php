<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Hướng dẫn viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .avatar-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e9ecef;
        }
        .badge-language {
            background-color: #17a2b8;
            font-size: 0.8em;
        }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body class="bg-light">

    <?php
    $list_hdv = [
        [
            'id' => 1,
            'hoten' => 'Nguyễn Văn A',
            'ngaysinh' => '1990-05-15',
            'anh' => 'https://i.pravatar.cc/150?img=11', // Ảnh mẫu
            'sdt' => '0987654321',
            'email' => 'vana@example.com',
            'phanloai' => 'Quốc tế',
            'ngonngu' => ['Tiếng Anh', 'Tiếng Pháp'],
            'kinhnghiem' => '5 năm',
            'danhgia' => 4.5, // Trên thang 5
            'suckhoe' => 'Tốt'
        ],
        [
            'id' => 2,
            'hoten' => 'Trần Thị B',
            'ngaysinh' => '1995-08-20',
            'anh' => 'https://i.pravatar.cc/150?img=5',
            'sdt' => '0912345678',
            'email' => 'tranb@example.com',
            'phanloai' => 'Nội địa',
            'ngonngu' => ['Tiếng Việt'],
            'kinhnghiem' => '2 năm',
            'danhgia' => 4.8,
            'suckhoe' => 'Tốt'
        ],
        [
            'id' => 3,
            'hoten' => 'Lê Hoàng C',
            'ngaysinh' => '1988-12-01',
            'anh' => 'https://i.pravatar.cc/150?img=3',
            'sdt' => '0909090909',
            'email' => 'hoangc@example.com',
            'phanloai' => 'Chuyên tuyến Tây Bắc',
            'ngonngu' => ['Tiếng Anh', 'Tiếng Trung'],
            'kinhnghiem' => '8 năm',
            'danhgia' => 5.0,
            'suckhoe' => 'Tốt'
        ]
    ];
    ?>

    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>QUẢN LÝ DANH SÁCH HƯỚNG DẪN VIÊN</h5>
                <a href="create-tour.php" class="btn btn-light btn-sm text-primary fw-bold">
                    <i class="fas fa-plus"></i> Thêm HDV Mới
                </a>
            </div>
            
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Tìm kiếm tên, SĐT...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="">-- Chọn phân loại --</option>
                            <option value="noidia">HDV Nội địa</option>
                            <option value="quocte">HDV Quốc tế</option>
                            <option value="chuyentuyen">Chuyên tuyến</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100"><i class="fas fa-filter"></i> Lọc</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>Thông tin cá nhân</th>
                                <th>Phân loại & Ngôn ngữ</th>
                                <th>Kinh nghiệm & Đánh giá</th>
                                <th>Tình trạng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list_hdv as $hdv): ?>
                            <tr>
                                <td class="text-center fw-bold text-secondary">#<?= $hdv['id'] ?></td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $hdv['anh'] ?>" alt="Avatar" class="avatar-img me-3">
                                        <div>
                                            <div class="fw-bold text-primary"><?= $hdv['hoten'] ?></div>
                                            <small class="text-muted"><i class="fas fa-birthday-cake"></i> <?= date('d/m/Y', strtotime($hdv['ngaysinh'])) ?></small><br>
                                            <small class="text-muted"><i class="fas fa-phone"></i> <?= $hdv['sdt'] ?></small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-success mb-1"><?= $hdv['phanloai'] ?></span><br>
                                    <?php foreach ($hdv['ngonngu'] as $nn): ?>
                                        <span class="badge badge-language me-1"><?= $nn ?></span>
                                    <?php endforeach; ?>
                                </td>

                                <td>
                                    <div><i class="fas fa-briefcase text-secondary"></i> <?= $hdv['kinhnghiem'] ?></div>
                                    <div class="text-warning">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            if($i <= $hdv['danhgia']) echo '<i class="fas fa-star"></i>';
                                            else if($i - 0.5 == $hdv['danhgia']) echo '<i class="fas fa-star-half-alt"></i>';
                                            else echo '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                        <span class="text-dark small">(<?= $hdv['danhgia'] ?>)</span>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge rounded-pill bg-info text-dark">
                                        <i class="fas fa-heartbeat"></i> <?= $hdv['suckhoe'] ?>
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-outline-primary" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa HDV này?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>