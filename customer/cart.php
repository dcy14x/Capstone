<?php 
  include '../config/config.php';
  include '../includes/session.php';
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
            <h1 class="fw-bold mb-0">Shopping Cart</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <?php 
                $overall = 0;
                $total_items = 0;
                $sql = "SELECT *,c.id AS c_id FROM cart c INNER JOIN products p ON c.products_id=p.id WHERE users_id = '".$_SESSION['users_id']."'";
                $stmt = $this->conn()->query($sql);
                while ($row = $stmt->fetch()) {
                    $overall += $row['cart_qty'] * $row['price'];
                    $total_items++;

                 ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <div class="bg-secondary" style="width: 100%; height: 100px;">
                                        <img src="../images/<?= $row['images'] ?>" width="100%" height="100%">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h6 class="fw-bold mb-1"><?= $row['name'] ?></h6>
                                </div>
                                <div class="col-md-2 mb-3 mb-md-0">
                                    <div class="input-group input-group-sm">
                                        <button 
                                            class="btn btn-outline-secondary qty-btn" 
                                            data-type="minus" 
                                            data-id="<?= $row['products_id'] ?>">
                                            -
                                        </button>

                                        <input 
                                            type="text" 
                                            class="form-control text-center qty-input" 
                                            value="<?= $row['cart_qty'] ?>" 
                                            readonly>

                                        <button 
                                            class="btn btn-outline-secondary qty-btn" 
                                            data-type="plus" 
                                            data-id="<?= $row['products_id'] ?>">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0 text-center product_total_group">
                                    <span class="fw-bold fs-5 text-primary product_total">₱<?= number_format($row['cart_qty'] * $row['price'],2); ?></span>
                                </div>
                                <div class="col-md-1 text-end">
                                    <form method="POST" action="../controller/cartController.php">
                                        <input type="hidden" name="c_id" value="<?= $row['c_id']; ?>">
                                        <button type="submit" name="action" class="btn btn-sm btn-outline-danger" value="delete_cart">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            
                <div class="d-flex justify-content-between">
                    <a href="products.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                    <form method="POST" action="../controller/cartController.php">
                        <button type="submit" value="clear_cart" name="action" class="btn btn-outline-danger clear_cart">Clear Cart</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (<?= $total_items; ?> items)</span>
                            <span class="fw-semibold overall">₱<?= number_format($overall,2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary overall">₱<?= number_format($overall,2); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-100 btn-lg">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>