<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp Knine</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <video id="bg-video" autoplay muted loop>
        <source src="assets/video/background.mp4" type="video/mp4">
    </video>
    <div class="signUpForm" id="signUpForm">
        <form method="post">
            <h1 style="text-align: center">Sign Up</h1>
            <div class="form-group">
                <label>Username</label>
                <input type="username" class="form-control" id="username" name="signup-username" placeholder="Username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="password" name="signup-password" placeholder="Password">
            </div>
            <div class="form-group" id="conForm">
                <label>Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password"
                    placeholder="Confirm Password">
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
            </div>
            <div class="have-account">
                <p class="mt-3 text-center">
                    Already have an account yet? <a href="index.php">Login</a>
                </p>
            </div>
        </form>
    </div>

    <div class="conPass-error">
        <div class="error">
            <h2>Passwords mismatch!</h2>
        </div>
    </div>

    <div class="login-success">
        <div class="sucess">
            <h2>Signup Success!</h2>
        </div>
    </div>

    <?php
    function generateSalt($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $salt = '';
        for ($i = 0; $i < $length; $i++) {
            $salt .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $salt;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['signup-username']) && !empty($_POST['signup-password']) && !empty($_POST['confirm-password'])) {
            $username = $_POST['signup-username'];
            $password = $_POST['signup-password'];
            $conpassword = $_POST['confirm-password'];

            echo "username:" . $username . "<br>";
            echo "password:" . $password . "<br>";
            echo "conpassword:" . $conpassword . "<br>";

            require_once 'config/conn.php';

            if ($password !== $conpassword) {
                echo "<script>
                    const signupForm = document.getElementById('signUpForm');
                    signupForm.classList.add('animate-out');
                    signupForm.addEventListener('animationend',() => {

                    signupForm.style.display = 'none';

                    const conPassError = document.querySelector('.conPass-error');
                    conPassError.style.display = 'flex';
                    conPassError.classList.add('animate-in');

                    setTimeout(() => {
                        window.location.href = window.location.href; // reload หน้าเดิม
                    }, 2000);
                });
                </script>
                ";
            } else {
                $salt = generateSalt(16);
                $hashedPassword = hash('sha256', $salt . $password);

                echo 'salt pass: ' . $salt . '<br>';
                echo 'hashed pass: ' . $hashedPassword;

                $query = "INSERT INTO master.dbo.users (username, password, salt) 
          VALUES ('$username', '$hashedPassword', '$salt')";

                $result = mssql_query($query, $link);

                if ($result) {
                    echo "
                <script>
                    // เริ่ม animate form ออก
                    const signupForm = document.getElementById('signUpForm');
                    signupForm.classList.add('animate-out');

                    // รอจน animation ของ form จบ
                    signupForm.addEventListener('animationend', () => {
                        // ซ่อน signup form
                        signupForm.style.display = 'none';

                        // แสดง signup success
                        const success = document.querySelector('.login-success');
                        success.style.display = 'flex';
                        success.classList.add('animate-in');

                        // redirect หลัง 3 วินาที
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 3000);
                    });
                </script>
                ";
                }
            }
        }
    }
    ?>
</body>

</html>