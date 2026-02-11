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
            $address = $_POST['address'];
            $city = $_POST['city'];
            $region = $_POST['region'];
            
            $image = $user['images'];
            if (!empty($_FILES['image']['name'])) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $image);
            }

            $update_sql = "UPDATE users SET name = ?, email = ?, contact = ?, address = ?, city = ?, region = ?, images = ? WHERE users_id = ?";
            $update_stmt = $this->conn()->prepare($update_sql);
            $update_stmt->execute([$name, $email, $contact, $address, $city, $region, $image, $users_id]);
            header("Location: profile.php?success=1");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <h4 class="fw-bold mb-4">My Profile</h4>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Profile updated successfully!</div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4">
                    <div class="mb-3">
                        <?php if (!empty($user['images'])): ?>
                            <img src="../images/<?= $user['images'] ?>" class="rounded-circle border" width="150" height="150" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center text-white" style="width: 150px; height: 150px; font-size: 64px;">
                                <i class="bi bi-person"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold"><?= $user['name'] ?></h5>
                    <p class="text-muted"><?= $user['email'] ?></p>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact" class="form-control" value="<?= $user['contact'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="image" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control" value="<?= $user['address'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="<?= $user['city'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Region</label>
                                    <input type="text" name="region" class="form-control" value="<?= $user['region'] ?>">
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" name="update_profile" class="btn btn-primary px-4">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>