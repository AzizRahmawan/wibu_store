<?php
session_start();

function checkLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        header('Location: ../index.php'); 
        exit();
    }
}

checkLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Distro Wibu</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/index.css" rel="stylesheet">
</head>
<body class="login-bg row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Daftar - 夢 Store</h2>
                        <form action="../routes/daftar_route.php" method="post">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </form>
                        <span class="text-center d-block mt-3">Sudah punya akun? <a href="./login.php"> Login</a></span>
                        <?php
                        if(isset($_SESSION['error_message'])) {
                            echo "<p class='text-danger text-center mt-2'>" . $_SESSION['error_message'] . "</p>";
                            unset($_SESSION['error_message']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
