<div class="container-fluid mt-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold"><i class="fas fa-id-card-alt"></i> QUẢN LÝ HỒ SƠ HƯỚNG DẪN VIÊN</h5>
            <a href="index.php?action=admin-create-guide" class="btn btn-light btn-sm text-primary font-weight-bold">
                <i class="fas fa-plus"></i> Thêm HDV Mới
            </a>
        </div>
        
        <div class="card-body">
            <form action="" method="GET" class="row mb-4 align-items-end">
                <input type="hidden" name="action" value="admin-list-guides">
                
                <div class="col-md-4">
                    <label class="form-label small text-muted">Từ khóa tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên, SĐT, Email..." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small text-muted">Phân loại</label>
                    <select class="form-select">
                        <option>-- Tất cả phân loại --</option>
                        <option value="International">Quốc tế</option>
                        <option value="Domestic">Nội địa</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 font-weight-bold"><i class="fas fa-filter"></i> Lọc dữ liệu</button>
                </div>
                
                <?php if(isset($_GET['keyword']) && $_GET['keyword'] != ''): ?>
                <div class="col-md-2">
                    <a href="index.php?action=admin-list-guides" class="btn btn-secondary w-100">Hủy tìm</a>
                </div>
                <?php endif; ?>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">#ID</th>
                            <th width="30%">Thông tin cá nhân & Liên hệ</th>
                            <th width="20%">Chuyên môn</th>
                            <th width="25%">Năng lực & Đánh giá</th>
                            <th width="10%">Sức khoẻ</th>
                            <th width="10%">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($guides)): ?>
                            <?php foreach ($guides as $guide): ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $guide['id'] ?></td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php 
                                            // Kiểm tra ảnh, nếu không có dùng ảnh mặc định
                                            $imgSrc = !empty($guide['image']) ? 'uploads/' . $guide['image'] : 'https://via.placeholder.com/150'; 
                                        ?>
                                        <img src="<?= $imgSrc ?>" class="rounded-circle me-3 border" width="60" height="60" style="object-fit: cover;" alt="Avatar">
                                        <div>
                                            <strong class="text-primary"><?= $guide['name'] ?></strong><br>
                                            <small class="text-muted"><i class="fas fa-birthday-cake"></i> <?= date('d/m/Y', strtotime($guide['dob'])) ?></small><br>
                                            <small><i class="fas fa-phone"></i> <?= $guide['phone'] ?></small><br>
                                            <small class="text-danger"><i class="fas fa-envelope"></i> <?= $guide['email'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <?php if($guide['type'] == 'Quốc tế'): ?>
                                        <span class="badge bg-primary mb-1"><i class="fas fa-globe"></i> Quốc tế</span>
                                    <?php else: ?>
                                        <span class="badge bg-success mb-1"><i class="fas fa-map-marker-alt"></i> Nội địa</span>
                                    <?php endif; ?>
                                    <br>
                                    <small><strong>Ngôn ngữ:</strong></small><br>
                                    <?php 
                                        // Tách chuỗi ngôn ngữ thành các thẻ nhỏ cho đẹp (VD: Anh, Pháp -> [Anh][Pháp])
                                        $langs = explode(',', $guide['languages']);
                                        foreach($langs as $lang) {
                                            echo '<span class="badge bg-info text-dark me-1">'.trim($lang).'</span>';
                                        }
                                    ?>
                                    <br>
                                    <small class="mt-1 d-block"><strong>Chứng chỉ:</strong> <span class="text-muted"><?= $guide['certificate'] ?></span></small>
                                </td>
                                
                                <td>
                                    <small><i class="fas fa-briefcase"></i> Kinh nghiệm: <strong><?= $guide['experience'] ?> năm</strong></small><br>
                                    <div class="text-warning my-1">
                                        <?php 
                                        $rating = floatval($guide['rating']); 
                                        for($i = 1; $i <= 5; $i++) {
                                            if($i <= $rating) echo '<i class="fas fa-star"></i>';
                                            elseif ($i - 0.5 <= $rating) echo '<i class="fas fa-star-half-alt"></i>';
                                            else echo '<i class="far fa-star text-secondary"></i>';
                                        }
                                        ?>
                                        <span class="text-dark small">(<?= $guide['rating'] ?>/5)</span>
                                    </div>
                                    <a href="#" class="text-decoration-none small"><i class="fas fa-history"></i> Xem lịch sử dẫn tour</a>
                                </td>
                                
                                <td class="text-center">
                                    <?php if($guide['health_status'] == 'Tốt'): ?>
                                        <span class="badge rounded-pill bg-success"><i class="fas fa-check-circle"></i> Tốt</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-warning text-dark"><?= $guide['health_status'] ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="index.php?action=admin-detail-guide&id=<?= $guide['id'] ?>" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        <div class="btn-group w-100">
                                            <a href="index.php?action=admin-edit-guide&id=<?= $guide['id'] ?>" class="btn btn-warning btn-sm" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=admin-delete-guide&id=<?= $guide['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?');" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4">Không tìm thấy dữ liệu</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php 
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $nextPage = $currentPage + 1;
                        $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
                    ?>
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?action=admin-list-guides&page=<?= $prevPage ?>">Trước</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#"><?= $currentPage ?></a></li>
                    <li class="page-item"><a class="page-link" href="index.php?action=admin-list-guides&page=<?= $nextPage ?>">Sau</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>