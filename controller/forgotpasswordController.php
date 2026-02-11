<?php
    use PHPMailer\PHPMailer\PHPMailer;

    require_once "../sendphpmailer/PHPMailer.php";
    require_once "../sendphpmailer/SMTP.php";
    require_once "../sendphpmailer/Exception.php";


    include '../config/config.php';

    class controller extends Connection{

        public function managecontroller(){

                $email = $_POST['email'];
                $code = rand(000000,999999);

                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $this->conn()->prepare($sql);
                $stmt->execute([$email]);

                if ($stmt->rowcount() > 0) {

                    $row = $stmt->fetch();


                    $sql = "UPDATE users SET code = ? WHERE email = '".$email."'";
                    $stmt = $this->conn()->prepare($sql);
                    $stmt->execute([$code]);

                    $mail = new PHPMailer();

                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = "asskillia@gmail.com";
                    $mail->Password = 'wlnnyntbhngrjqyz';
                    $mail->Port = 587;
                    $mail->SMTPSecure = "tls";

                    $mail->isHTML(true);
                    $mail->setFrom('asskillia@gmail.com', 'Change Password');     
                    $mail->addAddress($email);
                    $mail->Subject = "Change Password";
                    $mail->Body    = '
                          <div style="font-size:20px;">
                          Good day!<br><br>
                          Click <a href="localhost/m633/auth/changepassword.php?code='.$code.'">here</a> to Change Your Password</div></div>';
                    $mail->send();

                    echo "<script type='text/javascript'>alert('Successfully Send Email');</script>";
                    echo "<script>window.location.href='../auth/login.php';</script>";
                  

                } else {

                    echo "<script type='text/javascript'>alert('Invalid Email');</script>";
                    echo "<script>window.location.href='../auth/forgotpassword.php';</script>";

                } 
               
            

        }

    }

    $controllerrun = new controller();
    $controllerrun->managecontroller();

?>
