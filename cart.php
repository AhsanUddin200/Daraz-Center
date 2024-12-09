<?php 
require_once 'db.php'; 
session_start(); 

// Prevent direct access to the file
if (!$conn) {
    die("Database connection failed");
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php?redirect=cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle "Remove from Cart" functionality
if (isset($_POST['remove_from_cart'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_sql = "DELETE FROM daraz_cart WHERE id = ?";
    $stmt = $conn->prepare($delete_cart_sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    // Refresh the page to reflect changes
    header("Location: cart.php");
    exit();
}

// Retrieve all items in the cart
$cart_sql = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity 
             FROM daraz_cart c
             JOIN daraz_products p ON c.product_id = p.id
             WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
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
        }
        h1 {
            margin: 0;
            font-size: 24px;
            margin-right: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Cart</h1>
    </header>

    <h2>Your Cart Items</h2>
    <?php
    if ($cart_result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>";

        $total_price = 0;
        while ($row = $cart_result->fetch_assoc()) {
            $product_total = $row['price'] * $row['quantity'];
            $total_price += $product_total;

            echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['quantity']}</td>
                    <td>$product_total</td>
                    <td>
                        <form method='POST' action='cart.php'>
                            <input type='hidden' name='cart_id' value='{$row['cart_id']}'>
                            <button type='submit' name='remove_from_cart'>Remove</button>
                        </form>
                    </td>
                </tr>";
        }

        echo "</table>";
        echo "<h3>Total Price: $total_price</h3>";
        echo "<a href='checkout.php'><button>Proceed to Checkout</button></a>";
    } else {
        echo "<p>Your cart is empty!</p>";
    }
    ?>

    <div class="footer">
        <a href="index.php" style="text-decoration: none;">
            <button style="padding: 10px 20px; font-size: 16px;">Back to Home</button>
        </a>
    </div>
</body>
</html>