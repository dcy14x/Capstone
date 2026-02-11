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
                    echo '<div class="row mb-4">';
                    echo '<div class="col-md-6">';
                    echo '<h6 class="fw-bold text-primary">Customer Information</h6>';
                    echo '<p class="mb-1"><strong>Name:</strong> ' . $customer['name'] . '</p>';
                    echo '<p class="mb-1"><strong>Email:</strong> ' . $customer['email'] . '</p>';
                    echo '<p class="mb-1"><strong>Contact:</strong> ' . $customer['contact'] . '</p>';
                    echo '</div>';
                    echo '<div class="col-md-6">';
                    echo '<h6 class="fw-bold text-primary">Shipping Address</h6>';
                    echo '<p class="mb-1">' . $customer['address'] . '</p>';
                    echo '<p class="mb-1">' . $customer['city'] . ', ' . $customer['region'] . '</p>';
                    if(!empty($customer['notes'])) {
                        echo '<p class="mb-1 mt-2"><strong>Note:</strong> <span class="text-muted fst-italic">' . $customer['notes'] . '</span></p>';
                    }
                    echo '</div>';
                    echo '</div>';
                }

                if ($items) {
                    echo '<h6 class="fw-bold mb-3">Order Items</h6>';
                    echo '<table class="table table-sm table-bordered">';
                    echo '<thead class="table-light"><tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>';
                    echo '<tbody>';
                    $grand_total = 0;
                    foreach ($items as $item) {
                        $total = $item['qty'] * $item['price'];
                        $grand_total += $total;
                        echo "<tr>
                                <td>
                                    <div class='d-flex align-items-center'>
                                        <img src='../images/{$item['images']}' width='40' height='40' class='rounded me-2' style='object-fit: cover;'>
                                        {$item['product_name']}
                                    </div>
                                </td>
                                <td class='text-center align-middle'>{$item['qty']}</td>
                                <td class='text-end align-middle'>₱" . number_format($item['price'], 2) . "</td>
                                <td class='text-end align-middle'>₱" . number_format($total, 2) . "</td>
                              </tr>";
                    }
                    echo '</tbody>';
                    echo '<tfoot><tr><th colspan="3" class="text-end">Grand Total:</th><th class="text-end">₱' . number_format($grand_total, 2) . '</th></tr></tfoot>';
                    echo '</table>';
                }
            } else {
                // POS Details (Usually anonymous, but if we had customer data we'd show it)
                $sql = "SELECT pti.* FROM pos_transaction_items pti 
                        JOIN pos_transactions pt ON pti.transaction_id = pt.id 
                        WHERE pt.transaction_no = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$order_number]);
                $items = $stmt->fetchAll();

                if ($items) {
                    echo '<div class="alert alert-info mb-3"><i class="bi bi-shop me-2"></i>POS Walk-in Transaction</div>';
                    echo '<table class="table table-sm table-bordered">';
                    echo '<thead class="table-light"><tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>';
                    echo '<tbody>';
                    $grand_total = 0;
                    foreach ($items as $item) {
                        $grand_total += $item['total'];
                        echo "<tr>
                                <td>{$item['product_name']}</td>
                                <td class='text-center'>{$item['quantity']}</td>
                                <td class='text-end'>₱" . number_format($item['price'], 2) . "</td>
                                <td class='text-end'>₱" . number_format($item['total'], 2) . "</td>
                              </tr>";
                    }
                    echo '</tbody>';
                    echo '<tfoot><tr><th colspan="3" class="text-end">Grand Total:</th><th class="text-end">₱' . number_format($grand_total, 2) . '</th></tr></tfoot>';
                    echo '</table>';
                }
            }
        }
    }
}

$details = new OrderDetails();
$details->getDetails();
?>