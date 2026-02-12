<?php 
    include '../config/config.php';
    include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 
        $users_id = $_SESSION['users_id'];
        $sql = "SELECT * FROM users WHERE users_id = ?";
        $stmt = $this->conn()->prepare($sql);
        $stmt->execute([$users_id]);
        $user = $stmt->fetch();

        if (isset($_POST['update_profile'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            
            $image = $user['images'];
            if (!empty($_FILES['image']['name'])) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $image);
            }

            $update_sql = "UPDATE users SET name = ?, email = ?, contact = ?, images = ? WHERE users_id = ?";
            $update_stmt = $this->conn()->prepare($update_sql);
            $update_stmt->execute([$name, $email, $contact, $image, $users_id]);
            header("Location: profile.php?success=1");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <link rel="stylesheet" href="../asset/css/modern-admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 260px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">My Profile</h4>
                    <p class="text-muted small mb-0">Manage your personal information and account settings</p>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>Profile updated successfully!
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm text-center p-4 h-100">
                        <div class="mb-4 position-relative d-inline-block mx-auto">
                            <?php if (!empty($user['images'])): ?>
                                <img src="../images/<?= $user['images'] ?>" class="rounded-circle border border-4 border-white shadow-sm" width="150" height="150" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary mx-auto shadow-sm" style="width: 150px; height: 150px; font-size: 64px;">
                                    <i class="bi bi-person"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold mb-1"><?= $user['name'] ?></h5>
                        <p class="text-muted small mb-4"><?= $user['email'] ?></p>
                        <div class="p-3 bg-light rounded-4 text-start">
                            <div class="mb-2 small"><span class="text-muted">Role:</span> <span class="fw-bold text-primary">Administrator</span></div>
                            <div class="small"><span class="text-muted">Member since:</span> <span class="fw-bold">2024</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Account Information</h5>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Full Name</label>
                                        <input type="text" name="name" class="form-control rounded-3 bg-light border-0 px-3 py-2" value="<?= $user['name'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Email Address</label>
                                        <input type="email" name="email" class="form-control rounded-3 bg-light border-0 px-3 py-2" value="<?= $user['email'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Contact Number</label>
                                        <input type="text" name="contact" class="form-control rounded-3 bg-light border-0 px-3 py-2" value="<?= $user['contact'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Profile Image</label>
                                        <input type="file" name="image" class="form-control rounded-3 bg-light border-0 px-3 py-2">
                                    </div>
                                    <div class="col-12 mt-5">
                                        <button type="submit" name="update_profile" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                                            Update Profile
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
<?php 
    }
} 
$data = new data(); 
$data->managedata(); 
?>