<?php 
    include '../config/config.php';
    include '../includes/session.php';
?>
<?php 
  class data extends Connection{ 
    public function managedata(){ 
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body class="bg-light">

    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-grow-1" style="margin-left: 250px;">

        <!-- Navbar -->
        <?php include '../includes/navbar.php'; ?>

        <!-- Page Content -->
        <div class="p-4">            

            <!-- POS -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold">POS</h5>
                    </div>                        

                    <!-- POS -->
                    <div class="row">
                    <!-- Products Section -->
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="input-group form-control py-0 ">
                                            <input type="text" id="search" class="form-control p-0 m-0 border-0 shadow-none" placeholder="Search products...">
                                            <button class="btn shadow-none"><i class="bi bi-search"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Grid -->
                                <div id="posdata" class="row g-3" style="max-height: 500px; overflow-y: auto;">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Section -->
                    <div class="col-md-5">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-cart3 me-2"></i>Current Order</h5>
                            </div>
                            <div class="card-body">
                                <!-- Cart Items -->
                                <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                                    <p class="text-center text-muted">No items in cart</p>
                                </div>

                                <!-- Summary -->
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span class="fw-bold" id="subtotal">₱0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 pt-2 border-top">
                                        <span class="fs-5 fw-bold">Total:</span>
                                        <span class="fs-5 fw-bold text-primary" id="totalAmount">₱0.00</span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select class="form-select" id="paymentMethod">
                                            <option value="Cash" selected>Cash</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Debit Card">Debit Card</option>
                                            <option value="E-Wallet">E-Wallet</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Amount Received</label>
                                        <input type="number" class="form-control" id="amountReceived" placeholder="0.00" step="0.01">
                                    </div>

                                    <div class="mb-3" id="changeSection" style="display: none;">
                                        <div class="alert alert-success">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Change:</span>
                                                <span class="fw-bold fs-5" id="changeAmount">₱0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success btn-lg" id="completePayment">
                                            <i class="bi bi-check-circle me-2"></i>Complete Payment
                                        </button>
                                        <button class="btn btn-outline-danger" id="cancelOrder">
                                            <i class="bi bi-x-circle me-2"></i>Cancel Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>              
                </div>
            </div>
        </div>
    </div>

    <?php include 'modal/inventory.php'; ?>
    <?php include 'modal/delete.php'; ?>
    <?php include '../includes/footer.php'; ?>

    <script>
        $('.edit').click(function(){
            $('#edit_id').val($(this).data('edit_id'))
            $('#edit_name').val($(this).data('edit_name'))
            $('#edit_description').val($(this).data('edit_description'))
            $('#edit_price').val($(this).data('edit_price'))
            $('#edit_quantity').val($(this).data('edit_quantity'))
        })
    </script>

</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>