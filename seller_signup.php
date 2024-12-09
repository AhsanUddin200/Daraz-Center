<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Signup</title>
</head>
<body>
    <h1>Seller Signup</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="signup">Signup</button>
    </form>
    <?php
    if (isset($_POST['signup'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO daraz_seller (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($sql)) {
            echo "Seller registered successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>
</body>
</html>
