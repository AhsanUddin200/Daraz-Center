<?php include 'db.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        header {
            background-color: #2c3e50;
            padding: 10px;
            display: flex;
            align-items: center;
            color: white;
            justify-content: center;
        }
        header img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .form-container {
            max-width: 400px;
            margin: 40px auto; /* Center the form with margin */
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="email"],
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Ensure padding is included in width */
        }
        button {
            background-color: #2c3e50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <img src="https://5.imimg.com/data5/SELLER/Default/2022/2/FF/QN/KT/24057945/plain-white-paper-shopping-bag-500x500.jpg" alt="ShopZone Logo">
        <h1>ShopZone</h1>
    </header>
    <div class="form-container">
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        
        <?php
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check user credentials
            $sql = "SELECT * FROM daraz_user WHERE email = '$email'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<p style='color: red; text-align: center;'>Invalid password.</p>";
                }
            } else {
                echo "<p style='color: red; text-align: center;'>No user found with that email.</p>";
            }
        }
        ?>

        <div class="signup-link">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        </div>
    </div>
</body>
</html>