<?php
include '../config/config.php';
include '../includes/session.php';

class SupplierAction extends Connection {
    public function handleRequest() {
        if (isset($_POST['add_supplier'])) {
            $name = $_POST['name'];
            $contact_person = $_POST['contact_person'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            try {
                $sql = "INSERT INTO suppliers (name, contact_person, email, phone, address) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$name, $contact_person, $email, $phone, $address]);

                header("Location: suppliers.php?success=1");
                exit();
            } catch (PDOException $e) {
                header("Location: suppliers.php?error=" . urlencode($e->getMessage()));
                exit();
            }
        }

        // Handle Delete
        if (isset($_GET['delete_id'])) {
            $id = $_GET['delete_id'];
            try {
                $sql = "DELETE FROM suppliers WHERE id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$id]);
                header("Location: suppliers.php?deleted=1");
                exit();
            } catch (PDOException $e) {
                header("Location: suppliers.php?error=" . urlencode($e->getMessage()));
                exit();
            }
        }
    }
}

$action = new SupplierAction();
$action->handleRequest();
?>