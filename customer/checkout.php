<?php 
  include '../config/config.php';
  include '../includes/session.php';
  class data extends Connection{ 
    public function managedata(){ 

        $sql = "SELECT * FROM users WHERE users_id = '".$_SESSION['users_id']."'";
        $stmt = $this->conn()->query($sql);
        $row = $stmt->fetch();
        $explode = explode(' ', $row['name']);    
        $firstname = $explode[0] ?? '';
        $lastname  = $explode[1] ?? '';
        $email = $row['email'];
        $contact = $row['contact'];
        $address = $row['address'];
        $city = $row['city'];
        $region = $row['region'];

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
            <h1 class="fw-bold mb-0">Checkout</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="cart.php" class="text-decoration-none">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Checkout Steps -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between mb-4">
                    <div class="text-center flex-fill">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <p class="small fw-semibold mb-0">Shopping Cart</p>
                    </div>
                    <div class="text-center flex-fill">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <p class="small fw-semibold mb-0">Checkout</p>
                    </div>
                    <div class="text-center flex-fill">
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <p class="small text-muted mb-0">Order Complete</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Form -->
    <div class="container my-5">
        <form method="POST" action="../controller/checkoutController.php">
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <!-- Shipping Address -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-geo-alt text-primary me-2"></i>Shipping Address
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">First Name</label>
                                    <input type="text" value="<?= $firstname; ?>" class="form-control" placeholder="Juan">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Last Name</label>
                                    <input type="text" value="<?= $lastname; ?>" class="form-control" placeholder="Dela Cruz">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" value="<?= $email; ?>" class="form-control" placeholder="juan@example.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" value="<?= $contact; ?>" class="form-control" placeholder="+63 912 345 6789">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Street Address</label>
                                    <input type="text" value="<?= $address; ?>" class="form-control" placeholder="123 Main Street">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">City</label>
                                    <input type="text" value="<?= $city; ?>" class="form-control" placeholder="Quezon City">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Region</label>
                                    <input type="text" value="<?= $region; ?>" class="form-control" placeholder="Region">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-credit-card text-primary me-2"></i>Payment Method
                            </h5>
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" value="COD" checked>
                                <label class="form-check-label fw-semibold" for="cod">
                                    <i class="bi bi-cash-coin text-success me-2"></i>Cash on Delivery
                                </label>
                            </div>
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" value="Gcash">
                                <label class="form-check-label fw-semibold" for="gcash">
                                    <i class="bi bi-phone text-info me-2"></i>GCash
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-chat-left-text text-primary me-2"></i>Order Notes (Optional)
                            </h5>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions for your order?"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm position-sticky" style="top: 100px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4">Order Summary</h5>
                            
                            <?php 
                            $overall = 0;
                            $total_items = 0;
                            $sql = "SELECT *,c.id AS c_id FROM cart c INNER JOIN products p ON c.products_id=p.id WHERE users_id = '".$_SESSION['users_id']."'";
                            $stmt = $this->conn()->query($sql);
                            while ($row = $stmt->fetch()) {
                                $overall += $row['cart_qty'] * $row['price'];
                                $total_items++;

                             ?>
                                <div class="mb-3 pb-3 border-bottom">
                                    <div class="d-flex mb-2">
                                        <div class="bg-secondary me-3" style="width: 60px; height: 60px;">
                                            <img src="../images/<?= $row['images'] ?>" width="100%" height="100%">
                                        </div>
                                        <div class="flex-fill">
                                            <h6 class="mb-1 small"><?= $row['name'] ?></h6>
                                            <p class="text-muted small mb-0">Qty: <?= $row['cart_qty'] ?></p>
                                            <p class="fw-semibold small mb-0">₱<?= number_format($row['cart_qty'] * $row['price'],2); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span class="fw-semibold">₱<?= number_format($overall,2); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Total</span>
                                <span class="fw-bold fs-5 text-primary">₱<?= number_format($overall,2); ?></span>
                            </div>

                            <button type="submit" value="checkout" name="action" class="btn btn-primary w-100 btn-lg mb-2">
                                Place Order
                            </button>
                            <a href="cart.php" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left"></i> Back to Cart
                            </a>
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