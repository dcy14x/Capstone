<?php 
  include '../config/config.php';
  class data extends Connection{ 
    public function managedata(){ 
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <div class="bg-light py-4">
        <div class="container">
            <h1 class="fw-bold mb-0">All Products</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container my-5">
        <div class="row">
            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="row g-4">
                    <?php $sql = "SELECT * FROM products WHERE quantity > 0";
                    $stmt = $this->conn()->query($sql);
                    while ($row = $stmt->fetch()) { ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="bg-secondary" style="height: 200px;">
                                    <img src="../images/<?= $row['images'] ?>" width="100%" height="100%">
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title fw-bold"><?= $row['name']; ?></h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold fs-5">₱<?= $row['price']; ?></span>
                                        
                                        <?php if(isset($_SESSION['users_id'])): ?>
                                            <button value="<?= $row['id'] ?>" class="addtocart btn btn-sm btn-primary">Add to Cart</button>
                                        <?php else: ?>
                                            <a href="../auth/login.php" class="btn btn-sm btn-primary">Add to Cart</a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>