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
        .hero-section {
            background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%);
            border-radius: 0 0 50px 50px;
            padding: 100px 0;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 20px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        .btn-shop-now {
            background: #000;
            color: #FFC107;
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s;
        }
        .btn-shop-now:hover {
            background: #222;
            color: #FFC107;
            transform: scale(1.05);
        }
        .section-title {
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
        }
        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: #FFC107;
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Modern Hero Section -->
    <div class="hero-section text-dark mb-5 shadow-sm">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge bg-dark text-warning px-3 py-2 rounded-pill mb-3 fw-bold">NEW ARRIVALS 2024</span>
                    <h1 class="display-3 fw-bold mb-4">Elevate Your Lifestyle with <span class="text-white">M-633</span></h1>
                    <p class="lead mb-5 opacity-75">Discover a curated collection of premium products designed for quality and style. Experience the future of shopping today.</p>
                    <div class="d-flex gap-3">
                        <a href="products.php" class="btn btn-shop-now shadow-lg">Shop Collection</a>
                        <a href="#featured" class="btn btn-outline-dark rounded-pill px-4 fw-bold">Explore More</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg animate-bounce" style="width: 350px; height: 350px; backdrop-filter: blur(10px);">
                            <i class="bi bi-bag-heart-fill text-white" style="font-size: 180px;"></i>
                        </div>
                        <div class="position-absolute top-0 start-0 bg-white p-3 rounded-4 shadow-sm animate-float">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span class="fw-bold ms-2">Top Rated</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="py-5" id="featured">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold section-title">Featured Products</h2>
                <p class="text-muted">Handpicked favorites just for you</p>
            </div>
            
            <div class="row g-4">
                <?php 
                $sql = "SELECT * FROM products WHERE quantity > 0 LIMIT 8";
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
            
            <div class="text-center mt-5 pt-4">
                <a href="products.php" class="btn btn-dark btn-lg px-5 rounded-pill fw-bold shadow">View All Products</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-white py-5 border-top">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <i class="bi bi-truck fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">Fast Delivery</h5>
                    <p class="text-muted small">Quick and reliable shipping to your doorstep</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-shield-check fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">Secure Payment</h5>
                    <p class="text-muted small">100% secure payment processing</p>
                </div>
                <div class="col-md-4">
                    <i class="bi bi-headset fs-1 text-warning mb-3"></i>
                    <h5 class="fw-bold">24/7 Support</h5>
                    <p class="text-muted small">We're here to help you anytime</p>
                </div>
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