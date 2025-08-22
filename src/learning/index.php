<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knine Login</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <video id="bg-video" autoplay muted loop>
        <source src="assets/video/background.mp4" type="video/mp4">
    </video>
    <div class="loginForm" id="loginForm">
        <form id="formLogin" method="post">
            <h1 style="text-align: center">Login</h1>
            <div class="form-group">
                <label>Username</label>
                <input type="username" class="form-control" id="username" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="rememberCheck" name="remember">
                <label class="form-check-label">Remember me</label>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
            <div class="have-account">
                <p class="mt-3 text-center">
                    Don't have an account yet? <a href="signup.php">Register</a>
                </p>
            </div>
        </form>
    </div>

    <div class="login-success">
        <div class="sucess">
            <h2>Login Success!</h2>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            require_once 'config/conn.php';

            $query = "SELECT username,password 
                      FROM master.dbo.users 
                      WHERE username = '" . addslashes($username) . "' 
                      AND password = '" . addslashes($password) . "'";

            $result = mssql_query($query, $link);

            if ($result && mssql_num_rows($result) > 0) {

                $row = mssql_fetch_array($result);

                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                echo "
                <script>
                    // เริ่ม animate form ออก
                    const loginForm = document.getElementById('loginForm');
                    loginForm.classList.add('animate-out');

                    // รอจน animation ของ form จบ
                    loginForm.addEventListener('animationend', () => {
                        // ซ่อน login form
                        loginForm.style.display = 'none';

                        // แสดง login success
                        const success = document.querySelector('.login-success');
                        success.style.display = 'flex';
                        success.classList.add('animate-in');

                        // redirect หลัง 3 วินาที
                        // setTimeout(() => {
                        //     window.location.href = 'home.php';
                        // }, 3000);
                    });
                </script>
                ";
            } else {
                echo "<div class='alert alert-danger mt-3'>Invalid username or password.</div>";
            }
        }
    }
    ?>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>