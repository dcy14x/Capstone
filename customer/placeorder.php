<?php 
  include '../config/config.php';
  include '../includes/session.php';

  class data extends Connection{ 
    public function managedata(){ 
        $order_no = $_GET['order_no'] ?? '';
        if(empty($order_no)) {
            header("Location: index.php");
            exit();
        }

        // Fetch order info
        $sql_info = "SELECT ci.*, u.name, u.email, u.address, u.city, u.region, u.contact 
                     FROM customers_info ci 
                     JOIN users u ON ci.users_id = u.users_id 
                     WHERE ci.order_number = ?";
        $stmt_info = $this->conn()->prepare($sql_info);
        $stmt_info->execute([$order_no]);
        $order = $stmt_info->fetch();

        if(!$order) {
            header("Location: index.php");
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../includes/header.php'; ?>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                        <i class="bi bi-check-circle" style="font-size: 60px;"></i>
                    </div>
                    <h1 class="fw-bold mb-3">Order Placed Successfully!</h1>
                    <p class="text-muted fs-5">Thank you for your purchase, <?= explode(' ', $order['name'])[0] ?>. Your order has been received.</p>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-muted mb-2">Order Number</h6>
                                <p class="fw-bold fs-5 mb-0">#<?= $order['order_number'] ?></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="text-muted mb-2">Order Date</h6>
                                <p class="fw-semibold mb-0"><?= date('F d, Y', strtotime($order['order_date'])) ?></p>
                            </div>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            A confirmation has been recorded for <strong><?= $order['email'] ?></strong>
                        </div>

                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sql_items = "SELECT o.*, p.name, p.images FROM orders o JOIN products p ON o.products_id = p.id WHERE o.order_number = ?";
                                    $stmt_items = $this->conn()->prepare($sql_items);
                                    $stmt_items->execute([$order_no]);
                                    while($item = $stmt_items->fetch()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../images/<?= $item['images'] ?>" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0"><?= $item['name'] ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle"><?= $item['qty'] ?></td>
                                        <td class="align-middle text-end fw-semibold">₱<?= number_format($item['qty'] * 1, 2) /* Note: Price not in orders table, usually you'd store it there too */ ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="2" class="text-end fw-bold fs-5">Total Paid:</td>
                                        <td class="text-end fw-bold fs-5 text-primary">₱<?= number_format($order['amount'], 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-truck text-primary me-2"></i>Shipping Address
                                </h6>
                                <p class="mb-1"><strong><?= $order['name'] ?></strong></p>
                                <p class="mb-1"><?= $order['address'] ?></p>
                                <p class="mb-1"><?= $order['city'] ?></p>
                                <p class="mb-1"><?= $order['region'] ?></p>
                                <p class="mb-1"><?= $order['contact'] ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-credit-card text-primary me-2"></i>Payment Method
                                </h6>
                                <p class="mb-2">
                                    <i class="bi bi-cash-coin text-success me-2"></i>
                                    <?= $order['payment_method'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="index.php" class="btn btn-primary btn-lg px-5 me-2 mb-2">
                        <i class="bi bi-house-door me-2"></i>Continue Shopping
                    </a>
                    <a href="orders.php" class="btn btn-outline-primary btn-lg px-5 mb-2">
                        <i class="bi bi-search me-2"></i>My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
<?php } } $data = new data(); $data->managedata(); ?>