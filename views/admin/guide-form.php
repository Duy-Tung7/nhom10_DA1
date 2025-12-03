<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="m-0"><?= isset($guide['id']) ? 'Cập nhật HDV' : 'Thêm mới HDV' ?></h5>
        </div>
        <div class="card-body">
            <form action="index.php?action=admin-store-guide" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $guide['id'] ?? '' ?>">
                <input type="hidden" name="current_avatar" value="<?= $guide['avatar'] ?? '' ?>">

                <div class="row">
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary mb-3">Thông tin cơ bản</h6>
                        
                        <div class="mb-3">
                            <label>User ID (*)</label>
                            <input type="number" name="user_id" class="form-control" placeholder="Nhập ID tài khoản User" value="<?= $guide['user_id'] ?? '' ?>" required>
                            <small class="text-muted">Nhập ID của tài khoản User tương ứng</small>
                        </div>

                        <div class="mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= $guide['phone'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Ngày sinh (Birthday)</label>
                            <input type="date" name="birthday" class="form-control" value="<?= $guide['birthday'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Ảnh đại diện (Avatar)</label>
                            <input type="file" name="avatar" class="form-control">
                            <?php if(!empty($guide['avatar'])): ?>
                                <img src="assets/uploads/<?= $guide['avatar'] ?>" class="mt-2 rounded" width="80">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Hồ sơ chi tiết</h6>

                        <div class="mb-3">
                            <label>Ngôn ngữ (Languages)</label>
                            <input type="text" name="languages" class="form-control" placeholder="Anh, Pháp, Nhật..." value="<?= $guide['languages'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Số năm kinh nghiệm</label>
                            <input type="number" name="experience_years" class="form-control" value="<?= $guide['experience_years'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Tình trạng sức khỏe (Health Status)</label>
                            <input type="text" name="health_status" class="form-control" value="<?= $guide['health_status'] ?? '' ?>">
                        </div>

                        <div class="mb-3">
                            <label>Chứng chỉ (Certifications)</label>
                            <textarea name="certifications" class="form-control" rows="3"><?= $guide['certifications'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success px-5"><i class="fas fa-save"></i> Lưu dữ liệu</button>
                    
                    <a href="index.php?action=admin-list-guides" class="btn btn-secondary px-3">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>