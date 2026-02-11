<?php

include '../config/config.php';

class controller extends Connection {
    
    public function managecontroller() {
        
        if (isset($_POST['action']) && $_POST['action'] === 'completePayment') {
            try {
                $this->conn()->beginTransaction();
                
                // Validate inputs
                if (!isset($_POST['cart']) || empty($_POST['cart'])) {
                    throw new Exception("Cart is empty");
                }
                
                $cart = json_decode($_POST['cart'], true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid cart data");
                }
                
                $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
                $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Cash';
                $amount_received = isset($_POST['amount_received']) ? floatval($_POST['amount_received']) : 0;
                $change = isset($_POST['change']) ? floatval($_POST['change']) : 0;
                
                // Generate transaction number
                $transaction_no = 'TXN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
                
                // Insert transaction
                $sql = "INSERT INTO pos_transactions 
                    (transaction_no, total, payment_method, amount_received, change_amount, transaction_date) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([
                    $transaction_no,
                    $total,
                    $payment_method,
                    $amount_received,
                    $change
                ]);


                $sql = "SELECT id FROM pos_transactions ORDER BY id DESC LIMIT 1";
                $stmt = $this->conn()->query($sql);
                $row = $stmt->fetch();
                
                $transaction_id = $row['id'];
                
                // Insert transaction items and update stock
                foreach ($cart as $item) {
                    $item_total = floatval($item['price']) * intval($item['quantity']);
                    
                    // Insert transaction item
                    $sql = "INSERT INTO pos_transaction_items 
                        (transaction_id, products_id, product_name, quantity, price, total) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $this->conn()->prepare($sql);
                    $stmt->execute([
                        $transaction_id,
                        $item['id'],
                        $item['name'],
                        $item['quantity'],
                        $item['price'],
                        $item_total
                    ]);
                    
                    // Update product stock
                    $sql = "UPDATE products 
                        SET quantity = quantity - ?,selling = selling + ? 
                        WHERE id = ? AND quantity >= ?";
                    $stmt = $this->conn()->prepare($sql);
                    $stmt->execute([
                        $item['quantity'],
                        $item['quantity'],
                        $item['id'],
                        $item['quantity']
                    ]);
                    
                    if ($stmt->rowCount() == 0) {
                        throw new Exception("Insufficient stock for: " . $item['name']);
                    }
                }
                
                
                echo "success";
                
            } catch (Exception $e) {
   
                
                echo "success";
            }
            exit;
        } else {
            echo "success";
            exit;
        }
    }
}

try {
    $controller = new controller();
    $controller->managecontroller();
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>