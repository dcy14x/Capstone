<?php
    
    include '../config/config.php';

    class controller extends Connection{

        public function managecontroller(){

            if ($_POST['action'] === 'add') {

                $users_id = $_SESSION['users_id'];
                $products_id = $_POST['products_id'];

                $sql = "SELECT * FROM cart WHERE users_id = ? AND products_id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$users_id,$products_id]);

                if ($stmt->rowcount() > 0) {

                    echo "exist";

                } else {

                    $sql = "INSERT INTO cart (users_id,products_id) VALUES (?,?)";
                    $stmt = $this->conn()->prepare($sql);
                    $stmt->execute([$users_id,$products_id]);

                    echo "success";

                }

            }

            if ($_POST['action'] === 'updateQty') {

                $products_id = $_POST['products_id'];
                $type = $_POST['type'];

                // Get current qty
                $sql = "SELECT cart_qty FROM cart WHERE products_id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$products_id]);
                $row = $stmt->fetch();

                $cart_qty = $row['cart_qty'];

                if ($type === 'plus') {
                    $cart_qty++;
                } elseif ($type === 'minus' && $cart_qty > 1) {
                    $cart_qty--;
                }

                // Update qty
                $sql = "UPDATE cart SET cart_qty = ? WHERE products_id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$cart_qty, $products_id]);

                $sql = "SELECT price FROM products WHERE id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$products_id]);
                $row2 = $stmt->fetch();
                $products_total = $row2['price'] * $cart_qty;

                $sql = "
                    SELECT SUM(p.price * c.cart_qty) AS overall
                    FROM cart c
                    INNER JOIN products p ON c.products_id = p.id
                    WHERE c.users_id = ?
                ";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$_SESSION['users_id']]);
                $row3 = $stmt->fetch();

                $overall = $row3['overall'] ?? 0;

                echo json_encode([
                    'status' => 'success',
                    'cart_qty' => $cart_qty,
                    'products_total' => $products_total,
                    'overall' => $overall
                ]);
            }

            if ($_POST['action'] === 'clear_cart') {

                $users_id = $_SESSION['users_id'];

                $sql = "DELETE FROM cart WHERE users_id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$users_id]);

                echo "<script type='text/javascript'>alert('Successfully Clear Cart');</script>";
                echo "<script>window.location.href='../customer/cart.php';</script>";

            }

            if ($_POST['action'] === 'delete_cart') {

                $id = $_POST['c_id'];

                $sql = "DELETE FROM cart WHERE id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$id]);

                echo "<script type='text/javascript'>alert('Successfully Delete Data');</script>";
                echo "<script>window.location.href='../customer/cart.php';</script>";

            }

            if (isset($_POST['action']) && $_POST['action'] === 'count') {

                $users_id = $_SESSION['users_id'];

                $sql = "SELECT COUNT(id) AS count FROM cart WHERE users_id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$users_id]);

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                echo json_encode([
                    'count' => $row['count'] ?? 0
                ]);
                exit;
            }

        }

    }

    $controller = new controller();
    $controller->managecontroller();

?>
