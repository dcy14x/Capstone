<?php
include '../config/config.php';

class controller extends Connection {
    
    public function managecontroller() {
        if(isset($_POST['action'])) {
            
            if($_POST['action'] == 'update_profile') {

                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $fullname = $firstname . ' ' . $lastname;
                $email = $_POST['email'];
                $contact = $_POST['contact'];
                $address = $_POST['address'];
                $city = $_POST['city'];
                $region = $_POST['region'];
                $users_id = $_SESSION['users_id'];
                
                $sql = "UPDATE users SET name = ?, email = ?, contact = ?, address = ?, city = ?, region = ? WHERE users_id = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$fullname,$email,$contact,$address,$city,$region,$users_id]);
                
                if($stmt->execute()) {

                    echo "<script type='text/javascript'>alert('Profile updated successfully!');</script>";

                } else {

                    echo "<script type='text/javascript'>alert('Failed to update profile!');</script>";
              
                }

                echo "<script>window.location.href='../customer/profile.php';</script>";

            }
           
        }
    }
}

$controller = new controller();
$controller->managecontroller();
?>