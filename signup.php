<?php include 'db.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
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
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
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
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        label {
            margin-top: 10px;
            display: block; /* Ensure label is on a new line */
        }
    </style>
</head>
<body>
    <header>
        <img src="https://5.imimg.com/data5/SELLER/Default/2022/2/FF/QN/KT/24057945/plain-white-paper-shopping-bag-500x500.jpg" alt="ShopZone Logo">
        <h1>ShopZone</h1>
    </header>
    <div class="form-container">
        <h1>Signup</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <label for="profile_image">Upload Profile Image:</label>
            <input type="file" name="profile_image" accept="image/*">
            <button type="submit" name="signup">Signup</button>
        </form>
        
        <?php
        if (isset($_POST['signup'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            // Handle Image Upload
            $profile_image = "default.png"; // Default image
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $profile_image = $target_dir . basename($_FILES['profile_image']['name']);
                move_uploaded_file($_FILES['profile_image']['tmp_name'], $profile_image);
            }

            $sql = "INSERT INTO daraz_user (username, email, password, profile_image) VALUES ('$username', '$email', '$password', '$profile_image')";
            if ($conn->query($sql)) {
                echo "<p style='color: green; text-align: center;'>Signup successful!</p>";
                header("Location: login.php");
                exit();
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }
        ?>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>