<?php
    
    include '../config/config.php';

    class controller extends Connection{

        public function managecontroller(){

            if (isset($_POST['add'])) {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];

                $images = $_FILES['images']['name'];
                $tmp = $_FILES['images']['tmp_name'];
                move_uploaded_file($tmp, '../images/'.$images);

                $sql = "SELECT name FROM products WHERE name = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$name]);

                if ($stmt->rowcount() > 0) {

                    echo "<script type='text/javascript'>alert('Data already Exist');</script>";

                } else {

                    $sql = "INSERT INTO products (images,name,description,price,quantity) VALUES (?,?,?,?,?)";
                    $stmt = $this->conn()->prepare($sql);
                    $stmt->execute([$images,$name,$description,$price,$quantity]);

                    echo "<script type='text/javascript'>alert('Successfully Add Data');</script>";

                }

                echo "<script>window.location.href='../admin/inventory.php';</script>";

            }

            if (isset($_POST['edit'])) {            
      
                $id = $_POST['id'];

                $name = $_POST['name'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $price = $_POST['price'];

                $images = $_FILES['images']['name'];
                $tmp = $_FILES['images']['tmp_name'];
                move_uploaded_file($tmp, '../images/'.$images);

                    
                $sql = "UPDATE products SET ";
                $params = [];

                if ($images != '') {
                    $sql .= "images = ?, ";
                    $params[] = $images;
                }

                $sql .= "name = ?, description = ?, quantity = ?, price = ? WHERE id = ?";

                $params[] = $name;
                $params[] = $description;
                $params[] = $quantity;
                $params[] = $price;
                $params[] = $id;

                $stmt = $this->conn()->prepare($sql);
                $stmt->execute($params);

                echo "<script type='text/javascript'>alert('Successfully Edit Data');</script>";
                echo "<script>window.location.href='../admin/inventory.php';</script>";

            }

            if (isset($_POST['delete'])) {

                $id = $_POST['id'];

                $sql = "DELETE FROM products WHERE id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$id]);

                echo "<script type='text/javascript'>alert('Successfully Deleted Data');</script>";
                echo "<script>window.location.href='../admin/inventory.php';</script>";

            }

        }

    }

    $controller = new controller();
    $controller->managecontroller();

?>
