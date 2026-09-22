<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>  
    <h1>Products List</h1>
    <h1><?php echo htmlspecialchars($product['sku']); ?></h1>
    <p>
      <?php echo htmlspecialchars($product['name']); ?> - $<?php echo htmlspecialchars($product['price']); ?>
    </p>
    <p>
      <?php echo htmlspecialchars($product['product_type']); ?>
    </p>
    <p>
      <?php echo htmlspecialchars($product['product_attribute']); ?>
    </p>
    <a href="./index.php">Back to Products List</a>
</body>
</html>
