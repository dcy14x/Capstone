<?php 
  include '../config/config.php';
  include '../includes/session.php';
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
        .page-header {
            background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%);
            padding: 60px 0;
            border-radius: 0 0 40px 40px;
        }
        .cart-item-card {
            border-radius: 20px;
            transition: all 0.3s;
        }
        .cart-item-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }
        .qty-control {
            background: #f8fafc;
            border-radius: 50px;
            padding: 5px;
        }
        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 50% !important;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Modern Page Header -->
    <div class="page-header text-dark mb-5 shadow-sm">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">Shopping Cart</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-dark text-decoration-none fw-semibold">Home</a></li>
                    <li class="breadcrumb-item active text-dark opacity-75" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <?php 
                $overall = 0;
                $total_items = 0;
                $sql = "SELECT *,c.id AS c_id FROM cart c INNER JOIN products p ON c.products_id=p.id WHERE users_id = '".$_SESSION['users_id']."'";
                $stmt = $this->conn()->query($sql);
                $items = $stmt->fetchAll();
                
                if (count($items) > 0) {
                    foreach ($items as $row) {
                        $overall += $row['cart_qty'] * $row['price'];
                        $total_items++;
                ?>
                    <div class="card border-0 shadow-sm mb-3 cart-item-card">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <img src="../images/<?= $row['images'] ?>" class="rounded-4 w-100" style="height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h6 class="fw-bold mb-1"><?= $row['name'] ?></h6>
                                    <span class="text-muted small">₱<?= number_format($row['price'], 2) ?> / unit</span>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <div class="d-inline-flex align-items-center qty-control">
                                        <button class="qty-btn text-dark" onclick="updateQty(<?= $row['products_id'] ?>, 'minus')"><i class="bi bi-dash"></i></button>
                                        <span class="px-3 fw-bold"><?= $row['cart_qty'] ?></span>
                                        <button class="qty-btn text-dark" onclick="updateQty(<?= $row['products_id'] ?>, 'plus')"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3 mb-md-0 text-md-end">
                                    <span class="fw-bold fs-5 text-primary">₱<?= number_format($row['cart_qty'] * $row['price'], 2); ?></span>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form method="POST" action="../controller/cartController.php">
                                        <input type="hidden" name="c_id" value="<?= $row['c_id']; ?>">
                                        <button type="submit" name="action" class="btn btn-light rounded-circle p-2" value="delete_cart">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            
                <div class="d-flex justify-content-between mt-4">
                    <a href="products.php" class="btn btn-light px-4 rounded-pill fw-bold">
                        <i class="bi bi-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <form method="POST" action="../controller/cartController.php">
                        <button type="submit" value="clear_cart" name="action" class="btn btn-outline-danger px-4 rounded-pill fw-bold">Clear Cart</button>
                    </form>
                </div>
                <?php } else { ?>
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                        <i class="bi bi-cart-x display-1 text-muted mb-4 d-block"></i>
                        <h4 class="fw-bold">Your cart is empty</h4>
                        <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                        <a href="products.php" class="btn btn-primary px-5 rounded-pill fw-bold">Start Shopping</a>
                    </div>
                <?php } ?>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm cart-item-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal (<?= $total_items; ?> items)</span>
                            <span class="fw-bold">₱<?= number_format($overall, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success fw-bold">FREE</span>
                        </div>
                        <hr class="my-4">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">₱<?= number_format($overall, 2); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold py-3 shadow-sm <?= $total_items == 0 ? 'disabled' : '' ?>">
                            Proceed to Checkout
                        </a>
                        <div class="text-center mt-4">
                            <img src="../images/payment-methods.png" class="img-fluid opacity-50" style="max-height: 30px;" onerror="this.style.display='none'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script>
        function updateQty(id, type) {
            // This would normally be an AJAX call to update the cart quantity
            location.reload(); 
        }
    </script>
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>