<?php
    
    include '../config/config.php';
    
    class controller extends Connection{

        public function managecontroller(){

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->conn()->prepare($sql);
            $stmt->execute([$email]);

            if ($stmt->rowcount() > 0) {

                echo "<script type='text/javascript'>alert('Account Already Exist');</script>";

            } else {

                $sql = "INSERT INTO users (name,email,password) VALUES (?,?,?)";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$name,$email,$password]);
                echo "<script type='text/javascript'>alert('Successfully Create Account');</script>";

            }

            echo "<script>window.location.href='../auth/register.php';</script>";

        }

    }

    $controllerrun = new controller();
    $controllerrun->managecontroller();

?>
