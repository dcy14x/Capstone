<?php
    
    include '../config/config.php';

    class controller extends Connection{

        public function managecontroller(){

            if (isset($_POST['action']) && $_POST['action'] === 'checkout') {

                function generateOrderNumber() {
                    $prefix = 'M';
                    $first  = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                    $year   = date('Y');
                    $last   = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

                    return $prefix . $first . '-' . $year . '-' . $last;
                }

                $order_number = generateOrderNumber();
                $users_id = $_SESSION['users_id'];
                $payment_method = $_POST['payment_method'];
                $notes = $_POST['notes'];

                $db = $this->conn();

                // Get cart items to calculate total and update products
                $sql_cart = "SELECT c.*, p.price FROM cart c JOIN products p ON c.products_id = p.id WHERE c.users_id = ?";
                $stmt_cart = $db->prepare($sql_cart);
                $stmt_cart->execute([$users_id]);
                $cart_items = $stmt_cart->fetchAll();

                $total_amount = 0;

                foreach ($cart_items as $item) {
                    $products_id = $item['products_id'];
                    $qty = $item['cart_qty'];
                    $price = $item['price'];
                    $total_amount += ($price * $qty);

                    // Insert into orders table
                    $sql_order_item = "INSERT INTO orders (order_number, users_id, products_id, qty) VALUES (?, ?, ?, ?)";
                    $db->prepare($sql_order_item)->execute([$order_number, $users_id, $products_id, $qty]);

                    // Update product quantity and selling count
                    $sql_update_prod = "UPDATE products SET quantity = quantity - ?, selling = selling + ? WHERE id = ?";
                    $db->prepare($sql_update_prod)->execute([$qty, $qty, $products_id]);
                }

                // Insert into customers_info (This updates the Admin Dashboard Sales)
                $sql_info = "INSERT INTO customers_info (users_id, order_number, payment_method, amount, notes, order_date) VALUES (?, ?, ?, ?, ?, NOW())";
                $db->prepare($sql_info)->execute([$users_id, $order_number, $payment_method, $total_amount, $notes]);

                // Clear cart
                $sql_clear = "DELETE FROM cart WHERE users_id = ?";
                $db->prepare($sql_clear)->execute([$users_id]);

                header("Location: ../customer/placeorder.php?order_no=" . $order_number);
                exit();
            }
        }
    }

    $controller = new controller();
    $controller->managecontroller();
?>