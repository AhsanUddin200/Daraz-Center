<?php include 'db.php'; ?>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form method="POST" enctype="multipart/form-data"> <!-- Added enctype for file upload -->
        <input type="text" name="name" placeholder="Product Name" required><br>
        <input type="text" name="category" placeholder="Category" required><br>
        <input type="number" name="price" placeholder="Price" required><br>
        <input type="number" name="stock" placeholder="Stock Quantity" required><br>
        <input type="file" name="image" required><br> <!-- Added file input for image -->
        <button type="submit" name="add">Add Product</button>
    </form>
    <?php
    // Ensure the seller is logged in
    if (!isset($_SESSION['seller_id'])) {
        die("You must be logged in as a seller to add products.");
    }

    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $seller_id = $_SESSION['seller_id']; // Retrieve seller ID from session

        // Handle image upload
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image); // Specify the target directory

        // Debug to check seller_id
        if (!$seller_id) {
            die("Error: Seller ID not found in session.");
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO daraz_products (name, category, price, stock, image, seller_id) VALUES ('$name', '$category', '$price', '$stock', '$target', '$seller_id')";
            if ($conn->query($sql)) {
                echo "Product added successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    }
    ?>
</body>
</html>