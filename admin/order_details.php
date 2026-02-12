<?php
include '../config/config.php';

class OrderDetails extends Connection {
    public function getDetails() {
        if (isset($_POST['order_number'])) {
            $order_number = $_POST['order_number'];
            $type = $_POST['type'];

            if ($type == 'online') {
                // Fetch Customer Info
                $sql_customer = "SELECT u.name, u.email, u.contact, u.address, u.city, u.region, ci.notes 
                                 FROM customers_info ci 
                                 JOIN users u ON ci.users_id = u.users_id 
                                 WHERE ci.order_number = ?";
                $stmt_customer = $this->conn()->prepare($sql_customer);
                $stmt_customer->execute([$order_number]);
                $customer = $stmt_customer->fetch();

                // Fetch Order Items
                $sql = "SELECT o.*, p.name as product_name, p.price, p.images 
                        FROM orders o 
                        JOIN products p ON o.products_id = p.id 
                        WHERE o.order_number = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$order_number]);
                $items = $stmt->fetchAll();

                if ($customer) {
                    echo '<div class="row g-4 mb-4">';
                    echo '<div class="col-md-6">';
                    echo '    <div class="p-3 rounded-4 bg-light h-100">';
                    echo '        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Customer Info</h6>';
                    echo '        <div class="mb-2"><span class="text-muted small d-block">Name</span><span class="fw-bold">' . $customer['name'] . '</span></div>';
                    echo '        <div class="mb-2"><span class="text-muted small d-block">Email</span><span class="fw-bold">' . $customer['email'] . '</span></div>';
                    echo '        <div class="mb-0"><span class="text-muted small d-block">Contact</span><span class="fw-bold">' . $customer['contact'] . '</span></div>';
                    echo '    </div>';
                    echo '</div>';
                    echo '<div class="col-md-6">';
                    echo '    <div class="p-3 rounded-4 bg-light h-100">';
                    echo '        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Shipping Details</h6>';
                    echo '        <div class="mb-2"><span class="text-muted small d-block">Address</span><span class="fw-bold">' . $customer['address'] . '</span></div>';
                    echo '        <div class="mb-0"><span class="text-muted small d-block">Location</span><span class="fw-bold">' . $customer['city'] . ', ' . $customer['region'] . '</span></div>';
                    if(!empty($customer['notes'])) {
                        echo '    <div class="mt-2 p-2 bg-white rounded-3 small border-start border-warning border-4"><strong>Note:</strong> ' . $customer['notes'] . '</div>';
                    }
                    echo '    </div>';
                    echo '</div>';
                    echo '</div>';
                }

                if ($items) {
                    echo '<h6 class="fw-bold mb-3 px-2">Order Items</h6>';
                    echo '<div class="table-responsive">';
                    echo '<table class="table align-middle">';
                    echo '<thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>';
                    echo '<tbody>';
                    $grand_total = 0;
                    foreach ($items as $item) {
                        $total = $item['qty'] * $item['price'];
                        $grand_total += $total;
                        echo "<tr>
                                <td>
                                    <div class='d-flex align-items-center'>
                                        <img src='../images/{$item['images']}' width='45' height='45' class='rounded-3 me-3' style='object-fit: cover;'>
                                        <div class='fw-bold'>{$item['product_name']}</div>
                                    </div>
                                </td>
                                <td class='text-center fw-bold'>{$item['qty']}</td>
                                <td class='text-end fw-bold'>₱" . number_format($item['price'], 2) . "</td>
                                <td class='text-end fw-bold text-primary'>₱" . number_format($total, 2) . "</td>
                              </tr>";
                    }
                    echo '</tbody>';
                    echo '<tfoot>';
                    echo '    <tr class="border-top-0">';
                    echo '        <td colspan="3" class="text-end border-0 pt-4"><h5 class="fw-bold mb-0">Grand Total:</h5></td>';
                    echo '        <td class="text-end border-0 pt-4"><h5 class="fw-bold text-primary mb-0">₱' . number_format($grand_total, 2) . '</h5></td>';
                    echo '    </tr>';
                    echo '</tfoot>';
                    echo '</table>';
                    echo '</div>';
                }
            } else {
                // POS Details
                $sql = "SELECT pti.* FROM pos_transaction_items pti 
                        JOIN pos_transactions pt ON pti.transaction_id = pt.id 
                        WHERE pt.transaction_no = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$order_number]);
                $items = $stmt->fetchAll();

                if ($items) {
                    echo '<div class="p-3 rounded-4 bg-light mb-4 d-flex align-items-center">';
                    echo '    <div class="icon-shape bg-primary bg-opacity-10 text-primary me-3"><i class="bi bi-shop fs-4"></i></div>';
                    echo '    <div><h6 class="fw-bold mb-0">POS Walk-in Transaction</h6><p class="text-muted small mb-0">Direct store purchase</p></div>';
                    echo '</div>';
                    
                    echo '<div class="table-responsive">';
                    echo '<table class="table align-middle">';
                    echo '<thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>';
                    echo '<tbody>';
                    $grand_total = 0;
                    foreach ($items as $item) {
                        $grand_total += $item['total'];
                        echo "<tr>
                                <td class='fw-bold'>{$item['product_name']}</td>
                                <td class='text-center fw-bold'>{$item['quantity']}</td>
                                <td class='text-end fw-bold'>₱" . number_format($item['price'], 2) . "</td>
                                <td class='text-end fw-bold text-primary'>₱" . number_format($item['total'], 2) . "</td>
                              </tr>";
                    }
                    echo '</tbody>';
                    echo '<tfoot>';
                    echo '    <tr class="border-top-0">';
                    echo '        <td colspan="3" class="text-end border-0 pt-4"><h5 class="fw-bold mb-0">Grand Total:</h5></td>';
                    echo '        <td class="text-end border-0 pt-4"><h5 class="fw-bold text-primary mb-0">₱' . number_format($grand_total, 2) . '</h5></td>';
                    echo '    </tr>';
                    echo '</tfoot>';
                    echo '</table>';
                    echo '</div>';
                }
            }
        }
    }
}

$details = new OrderDetails();
$details->getDetails();
?>