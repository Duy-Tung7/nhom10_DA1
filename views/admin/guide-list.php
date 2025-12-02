<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Quản lý Hướng Dẫn Viên</h2>
        <a href="index.php?action=admin-create-guide" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm HDV mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách HDV hiện có</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">STT</th>
                            <th width="10%">Ảnh</th>
                            <th width="15%">User ID / Phone</th>
                            <th width="20%">Ngôn ngữ</th>
                            <th width="35%">Kinh nghiệm / Chứng chỉ</th>
                            <th class="text-center" width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($guides) && is_array($guides) && count($guides) > 0): ?>
                            <?php foreach ($guides as $key => $guide): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $key + 1 ?></td>
                                <td class="text-center align-middle">
                                    <img src="<?= !empty($guide['avatar']) ? 'assets/uploads/' . $guide['avatar'] : 'https://via.placeholder.com/60' ?>" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" 
                                         class="border shadow-sm" alt="Img">
                                </td>
                                <td class="align-middle">
                                    <strong>ID: <?= htmlspecialchars($guide['user_id']) ?></strong><br>
                                    <small class="text-muted"><i class="fas fa-phone"></i> <?= htmlspecialchars($guide['phone']) ?></small>
                                </td>
                                <td class="align-middle text-primary">
                                    <?= htmlspecialchars($guide['languages']) ?>
                                </td>
                                <td class="align-middle small">
                                    <strong>Kinh nghiệm:</strong> <?= htmlspecialchars($guide['experience_years']) ?> năm<br>
                                    <strong>Chứng chỉ:</strong> <?= htmlspecialchars(substr($guide['certifications'] ?? '', 0, 50)) ?>...
                                </td>
                                <td class="text-center align-middle">
                                    <a href="index.php?action=admin-edit-guide&id=<?= $guide['id'] ?>" class="btn btn-warning btn-sm me-1" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?action=admin-delete-guide&id=<?= $guide['id'] ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa HDV này?');" 
                                       class="btn btn-danger btn-sm" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-3x mb-3"></i><br>
                                    Chưa có hướng dẫn viên nào.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>