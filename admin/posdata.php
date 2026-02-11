<?php 
include '../config/config.php';
include '../includes/session.php';

class data extends Connection { 
    public function managedata() { 
        $search = $_POST['search'] ?? '';
        $sql = "SELECT * FROM products WHERE quantity > 0";
        
        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
        }
        
        $stmt = $this->conn()->prepare($sql);
        
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }
        
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            echo '
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-box-seam fs-1"></i>
                <h5 class="mt-3">Product Not found</h5>
            </div>';
            return;
        }
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $productId = $row['id'];
            $productName = htmlspecialchars($row['name']);
            $productPrice = number_format($row['price'], 2, '.', '');
            $productImage = htmlspecialchars($row['images']);
            $displayPrice = number_format($row['price'], 2);
?>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm product-card" 
                     style="cursor: pointer;" 
                     onclick="addToCart(<?= $productId ?>, '<?= $productName ?>', <?= $productPrice ?>, '<?= $productImage ?>')">
                    <div class="card-body text-center">
                        <div class="bg-light rounded p-3 mb-2">
                            <img src="../images/<?= $productImage ?>" 
                                 style="width: 100%; height: 120px; object-fit: cover;" 
                                 alt="<?= $productName ?>">
                        </div>
                        <h6 class="card-title mb-1"><?= $productName ?></h6>
                        <p class="fw-bold text-primary mb-0">₱<?= $displayPrice ?></p>
                        <small class="text-muted">Stock: <?= $row['quantity'] ?></small>
                    </div>
                </div>
            </div>                      
<?php 
        } 
    } 
} 

$data = new data(); 
$data->managedata(); 
?>