<?php

    include '../config/config.php';

    class controller extends Connection{

        public function managecontroller(){

            $code = $_POST['code'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $confirmpassword = $_POST['confirmpassword'];
            $passwordtxt = $_POST['password'];

            if ($_POST['password'] != $confirmpassword) {

                echo "<script type='text/javascript'>alert('Password Not Match');</script>";
                echo "<script>window.location.href='../auth/changepassword.php?code=".$code."';</script>";

            } else {

                $sql = "SELECT * FROM users WHERE code = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$code]);

                if ($stmt->rowcount() > 0) {

                    $sql = "UPDATE users SET password = ? WHERE code = ?";
                    $stmt = $this->conn()->prepare($sql);
                    $stmt->execute([$password,$code]);

                    echo "<script type='text/javascript'>alert('Successfully Change Password');</script>";
                    echo "<script>window.location.href='../auth/login.php';</script>";

                } else {

                    echo "<script type='text/javascript'>alert('Invalid Code');</script>";
                    echo "<script>window.location.href='../admin/changepassword.php?code=".$code."';</script>";

                } 

            }

        }

    }

    $controllerrun = new controller();
    $controllerrun->managecontroller();

?>
