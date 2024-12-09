<?php 
require_once 'db.php'; 
session_start(); 

// Prevent direct access to the file
if (!$conn) {
    die("Database connection failed");
}

// Search functionality
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM daraz_products";
$where_clauses = [];
$params = [];

// If search query is not empty, add search conditions
if (!empty($search_query)) {
    $where_clauses[] = "(name LIKE ? OR category LIKE ?)";
    $search_param = "%{$search_query}%";
    $params[] = $search_param;
    $params[] = $search_param;
}

// Combine SQL with WHERE clause if needed
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopZone - Your Online Marketplace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --background-color: #ecf0f1;
            --text-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .logo i {
            margin-right: 10px;
        }

        .search-container {
            display: flex;
            align-items: center;
            flex-grow: 1;
            max-width: 600px;
            margin: 0 20px;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
            font-size: 16px;
        }

        .search-button {
            padding: 10px 15px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: var(--primary-color);
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-icons a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s ease;
        }

        .nav-icons a:hover {
            color: var(--secondary-color);
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-details {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .product-info {
            margin-bottom: 10px;
        }

        .cart-form {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .add-to-cart {
            width: 100%;
            padding: 10px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart:hover {
            background-color: var(--primary-color);
        }

        .login-prompt {
            width: 100%;
            display: inline-block;
            text-align: center;
            padding: 10px;
            background-color: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .quantity-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .no-results {
            text-align: center;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-shopping-bag"></i> ShopZone
            </div>
            
            <form action="" method="GET" class="search-container">
                <input type="text" name="search" placeholder="Search products..." 
                       class="search-input" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="nav-icons">
                <a href="seller_signup.php"><i class="fas fa-store"></i> Become a Seller</a>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    
                <?php else: ?>
                    <a href="logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="products">
            <?php
            // Prepare statement to prevent SQL injection
            $stmt = $conn->prepare($sql);
            
            // Bind parameters if search query exists
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
            }
            
            // Execute the prepared statement
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any products
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Sanitize output to prevent XSS
                    $name = htmlspecialchars($row['name']);
                    $price = htmlspecialchars($row['price']);
                    $category = htmlspecialchars($row['category']);
                    $stock = htmlspecialchars($row['stock']);
                    $image = htmlspecialchars($row['image']);
                    $productId = htmlspecialchars($row['id']);

                    echo "<div class='product-card'>";
                    echo "<img src='" . $image . "' alt='" . $name . "' class='product-image'>";
                    echo "<div class='product-details'>";
                    echo "<h2 class='product-name'>" . $name . "</h2>";
                    echo "<div class='product-info'>";
                    echo "<p>Price: ₹" . $price . "</p>";
                    echo "<p>Category: " . $category . "</p>";
                    echo "<p>Stock: " . $stock . "</p>";
                    echo "</div>";

                    // Check if user is logged in
                    if (isset($_SESSION['user_id'])) {
                        // Show Add to Cart button for logged-in users
                        echo "<form method='POST' action='cart.php' class='cart-form'>
                                <input type='hidden' name='product_id' value='" . $productId . "'>
                                <input type='number' name='quantity' min='1' max='" . $stock . "' 
                                       placeholder='Quantity' required class='quantity-input'>
                                <button type='submit' name='add_to_cart' class='add-to-cart'>
                                    Add to Cart
                                </button>
                              </form>";
                    } else {
                        // Redirect to signup if user is not logged in
                        echo "<a href='signup.php?redirect=cart.php' class='login-prompt'>
                                Add to Cart
                              </a>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-results'>";
                if (!empty($search_query)) {
                    echo "<p>No products found matching your search: '" . htmlspecialchars($search_query) . "'</p>";
                } else {
                    echo "<p>No products available at the moment.</p>";
                }
                echo "</div>";
            }

            // Close the statement
            $stmt->close();
            ?>
        </div>
    </div>
</body>
</html>