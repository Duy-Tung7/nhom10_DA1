<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <?= isset($guide) ? 'CẬP NHẬT THÔNG TIN' : 'THÊM HƯỚNG DẪN VIÊN MỚI' ?>
            </h5>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <label>Ảnh đại diện</label> <br>
                            <?php if(isset($guide) && $guide['image']): ?>
                                <img src="uploads/<?= $guide['image'] ?>" class="img-thumbnail mb-2" width="150"><br>
                                <input type="hidden" name="current_image" value="<?= $guide['image'] ?>">
                            <?php endif; ?>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Họ tên:</label>
                                <input type="text" name="name" class="form-control" value="<?= $guide['name'] ?? '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label>Ngày sinh:</label>
                                <input type="date" name="dob" class="form-control" value="<?= $guide['dob'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Số điện thoại:</label>
                                <input type="text" name="phone" class="form-control" value="<?= $guide['phone'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control" value="<?= $guide['email'] ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label>Loại HDV:</label>
                            <select name="type" class="form-select">
                                <option value="Nội địa" <?= (isset($guide) && $guide['type']=='Nội địa') ? 'selected' : '' ?>>Nội địa</option>
                                <option value="Quốc tế" <?= (isset($guide) && $guide['type']=='Quốc tế') ? 'selected' : '' ?>>Quốc tế</option>
                            </select>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Ngôn ngữ:</label>
                                <input type="text" name="languages" class="form-control" value="<?= $guide['languages'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Kinh nghiệm (năm):</label>
                                <input type="number" name="experience" class="form-control" value="<?= $guide['experience'] ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Chứng chỉ:</label>
                                <input type="text" name="certificate" class="form-control" value="<?= $guide['certificate'] ?? '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label>Sức khỏe:</label>
                                <select name="health_status" class="form-select">
                                    <option value="Tốt">Tốt</option>
                                    <option value="Bình thường">Bình thường</option>
                                </select>
                            </div>
                        </div>
                        
                        <input type="hidden" name="rating" value="<?= $guide['rating'] ?? 5 ?>">

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Lưu thông tin
                        </button>
                        <a href="index.php?action=admin-list-guides" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>