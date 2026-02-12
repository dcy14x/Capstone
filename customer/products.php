<?php 
  include '../config/config.php';
  class data extends Connection{ 
    public function managedata(){ 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <link rel="stylesheet" href="../asset/css/modern-admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 20px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        .page-header {
            background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%);
            padding: 60px 0;
            border-radius: 0 0 40px 40px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Modern Page Header -->
    <div class="page-header text-dark mb-5 shadow-sm">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">Our Collection</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-dark text-decoration-none fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark opacity-75" aria-current="page">All Products</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container my-5">
        <div class="row g-4">
            <?php 
            $sql = "SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC";
            $stmt = $this->conn()->query($sql);
            while ($row = $stmt->fetch()) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 product-card bg-white">
                        <div class="position-relative p-3">
                            <div class="rounded-4 overflow-hidden" style="height: 220px;">
                                <img src="../images/<?= $row['images'] ?>" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <?php if($row['quantity'] < 5): ?>
                                <span class="position-absolute top-4 start-4 badge bg-danger rounded-pill m-4">Low Stock</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body pt-0 px-4 pb-4">
                            <h6 class="card-title fw-bold mb-1 text-dark"><?= $row['name'] ?></h6>
                            <p class="text-muted small mb-3 text-truncate"><?= $row['description'] ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div>
                                    <span class="text-muted small d-block">Price</span>
                                    <span class="fw-bold fs-5 text-dark">₱<?= number_format($row['price'], 2) ?></span>
                                </div>

                                <?php if(isset($_SESSION['users_id'])): ?>
                                    <button value="<?= $row['id'] ?>" class="addtocart btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                        <i class="bi bi-cart-plus fs-5"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="../auth/login.php" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                        <i class="bi bi-cart-plus fs-5"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
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