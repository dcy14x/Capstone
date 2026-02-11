<?php
    
    include '../config/config.php';

    class controller extends Connection{

        public function managecontroller(){ 
         
                $email = $_POST['email'];
                $password = $_POST['password'];

                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$email]);

                if ($stmt->rowcount() > 0) {

                    $row = $stmt->fetch();

                    if (password_verify($password, $row['password'])) {

                        $_SESSION['users_id'] = $row['users_id'];

                        if ($row['role'] == 1) {
                            
                            header('location:../admin/dashboard.php');
                        
                        } else {

                            header('location:../customer/index.php');

                        }
                        
                    } else {

                        echo "<script type='text/javascript'>alert('Invalid Email And Password');</script>";
                        echo "<script>window.location.href='../auth/login.php';</script>";

                    } 
                      
                } else {

                    echo "<script type='text/javascript'>alert('Invalid Email And Password');</script>";
                    echo "<script>window.location.href='../auth/login.php';</script>";

                } 
               
        }

    }

    $controllerrun = new controller();
    $controllerrun->managecontroller();

?>
