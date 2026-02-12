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
        .pos-product-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #f1f5f9;
        }
        .pos-product-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.1);
        }
        .cart-item {
            border-bottom: 1px solid #f1f5f9;
            padding: 12px 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="flex-grow-1" style="margin-left: 260px;">
        <?php include '../includes/navbar.php'; ?>

        <div class="p-4">            
            <div class="row g-4">
                <!-- Products Section -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Point of Sale</h5>
                                <div class="search-bar d-flex align-items-center">
                                    <i class="bi bi-search text-muted"></i>
                                    <input type="text" id="search" placeholder="Search products...">
                                </div>
                            </div>

                            <div id="posdata" class="row g-3" style="max-height: 650px; overflow-y: auto; padding-right: 5px;">
                                <!-- Products will be loaded here via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Section -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 p-4 pb-0">
                            <h5 class="fw-bold mb-0"><i class="bi bi-cart3 text-primary me-2"></i>Current Order</h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="cartItems" style="min-height: 200px; max-height: 350px; overflow-y: auto;">
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-cart-x fs-1 mb-2 d-block"></i>
                                    No items in cart
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold" id="subtotal">₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-bold fs-5">Total</span>
                                    <span class="fw-bold fs-5 text-primary" id="totalAmount">₱0.00</span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Payment Method</label>
                                    <select class="form-select rounded-3" id="paymentMethod">
                                        <option value="Cash" selected>Cash</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Credit Card">Credit Card</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Amount Received</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">₱</span>
                                        <input type="number" class="form-control border-0 bg-light" id="amountReceived" placeholder="0.00" step="0.01">
                                    </div>
                                </div>

                                <div class="mb-4" id="changeSection" style="display: none;">
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-success fw-bold small">Change</span>
                                            <span class="text-success fw-bold fs-5" id="changeAmount">₱0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-sm" id="completePayment">
                                        Complete Payment
                                    </button>
                                    <button class="btn btn-light rounded-pill py-2 text-danger" id="cancelOrder">
                                        Cancel Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>