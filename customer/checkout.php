<?php 
  include '../config/config.php';
  include '../includes/session.php';
  class data extends Connection{ 
    public function managedata(){ 

        $sql = "SELECT * FROM users WHERE users_id = '".$_SESSION['users_id']."'";
        $stmt = $this->conn()->query($sql);
        $row = $stmt->fetch();
        $name = $row['name'];
        $email = $row['email'];
        $contact = $row['contact'];
        $address = $row['address'];
        $city = $row['city'];
        $region = $row['region'];

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
        .checkout-card {
            border-radius: 20px;
        }
        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-weight: bold;
            z-index: 2;
            position: relative;
        }
        .payment-option {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #f1f5f9;
        }
        .payment-option:hover {
            border-color: var(--primary-color);
            background: rgba(255, 193, 7, 0.05);
        }
        .form-check-input:checked + .payment-option {
            border-color: var(--primary-color);
            background: rgba(255, 193, 7, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Modern Page Header -->
    <div class="page-header text-dark mb-5 shadow-sm">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">Checkout</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-dark text-decoration-none fw-semibold">Home</a></li>
                    <li class="breadcrumb-item"><a href="cart.php" class="text-dark text-decoration-none fw-semibold">Cart</a></li>
                    <li class="breadcrumb-item active text-dark opacity-75" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Checkout Content -->
    <div class="container my-5">
        <form method="POST" action="../controller/checkoutController.php">
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Shipping Address -->
                    <div class="card border-0 shadow-sm mb-4 checkout-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Shipping Information</h5>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">Full Name</label>
                                    <input type="text" name="name" value="<?= $name; ?>" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Email Address</label>
                                    <input type="email" value="<?= $email; ?>" class="form-control rounded-3 bg-light border-0 px-3 py-2" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Phone Number</label>
                                    <input type="tel" value="<?= $contact; ?>" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Street Address</label>
                                    <input type="text" value="<?= $address; ?>" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">City</label>
                                    <input type="text" value="<?= $city; ?>" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Region</label>
                                    <input type="text" value="<?= $region; ?>" class="form-control rounded-3 bg-light border-0 px-3 py-2" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm mb-4 checkout-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4"><i class="bi bi-credit-card-fill text-primary me-2"></i>Payment Method</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" class="form-check-input d-none" name="payment_method" id="cod" value="COD" checked>
                                    <label class="payment-option d-block p-4 rounded-4 text-center h-100" for="cod">
                                        <i class="bi bi-cash-coin fs-1 text-success mb-2 d-block"></i>
                                        <span class="fw-bold">Cash on Delivery</span>
                                        <p class="text-muted small mb-0 mt-1">Pay when you receive</p>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="form-check-input d-none" name="payment_method" id="gcash" value="Gcash">
                                    <label class="payment-option d-block p-4 rounded-4 text-center h-100" for="gcash">
                                        <i class="bi bi-phone-fill fs-1 text-info mb-2 d-block"></i>
                                        <span class="fw-bold">GCash</span>
                                        <p class="text-muted small mb-0 mt-1">Fast & Secure mobile pay</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="card border-0 shadow-sm checkout-card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-chat-left-text-fill text-primary me-2"></i>Order Notes</h5>
                            <textarea name="notes" class="form-control rounded-4 bg-light border-0 p-3" rows="3" placeholder="Any special instructions?"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm checkout-card position-sticky" style="top: 100px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Order Summary</h5>
                            
                            <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                                <?php 
                                $overall = 0;
                                $sql = "SELECT *,c.id AS c_id FROM cart c INNER JOIN products p ON c.products_id=p.id WHERE users_id = '".$_SESSION['users_id']."'";
                                $stmt = $this->conn()->query($sql);
                                while ($row = $stmt->fetch()) {
                                    $overall += $row['cart_qty'] * $row['price'];
                                ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="../images/<?= $row['images'] ?>" class="rounded-3 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 small fw-bold"><?= $row['name'] ?></h6>
                                            <span class="text-muted small">Qty: <?= $row['cart_qty'] ?></span>
                                        </div>
                                        <span class="fw-bold small">₱<?= number_format($row['cart_qty'] * $row['price'], 2); ?></span>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
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

                            <button type="submit" value="checkout" name="action" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold py-3 shadow-sm">
                                Place Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>