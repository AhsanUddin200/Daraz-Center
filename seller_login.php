<?php include 'db.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Login</title>
</head>
<body>
    <h1>Seller Login</h1>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <?php
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM daraz_seller WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $seller = $result->fetch_assoc();
            if (password_verify($password, $seller['password'])) {
                session_start();
                $_SESSION['seller_id'] = $seller['id']; // Set seller ID in session
                echo "Login successful!";
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Seller not found!";
        }
    }
    ?>
</body>
</html>
