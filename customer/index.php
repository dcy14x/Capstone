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

    <!-- Hero Section -->
    <div class="bg-primary text-white py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-3 fw-bold mb-4">Welcome to M-633</h1>
                    <p class="lead mb-4">Discover amazing products at unbeatable prices. Your one-stop shop for everything you need!</p>
                    <a href="products.php" class="btn btn-warning btn-lg px-5 fw-semibold">Shop Now</a>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg" style="width: 300px; height: 300px;">
                        <i class="bi bi-cart3 text-white" style="font-size: 150px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Featured Products</h2>
            <div class="row g-4">
                <?php 
                $sql = "SELECT * FROM products WHERE quantity > 0 LIMIT 8";
                $stmt = $this->conn()->query($sql);
                while ($row = $stmt->fetch()) { ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card border-0 shadow-sm h-100 product-card">
                            <div class="position-relative" style="height: 200px; overflow: hidden;">
                                <img src="../images/<?= $row['images'] ?>" class="card-img-top" style="object-fit: cover; height: 100%; width: 100%;">
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold mb-1"><?= $row['name'] ?></h6>
                                <p class="text-muted small mb-2 text-truncate"><?= $row['description'] ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold fs-5">₱<?= number_format($row['price'], 2) ?></span>

                                    <?php if(isset($_SESSION['users_id'])): ?>
                                        <button value="<?= $row['id'] ?>" class="addtocart btn btn-sm btn-primary">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="../auth/login.php" class="btn btn-sm btn-primary">
                                            <i class="bi bi-cart-plus"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="text-center mt-5">
                <a href="products.php" class="btn btn-outline-primary btn-lg px-5">View All Products</a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.addtocart').on('click', function() {
                const productId = $(this).val();
                $.ajax({
                    url: 'add_to_cart.php',
                    method: 'POST',
                    data: { product_id: productId },
                    success: function(response) {
                        alert('Product added to cart!');
                        location.reload();
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>