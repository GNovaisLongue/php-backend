<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
</head>
<body>
    <h1>Products List</h1>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?php echo htmlspecialchars($product['sku']); ?> - 
                <?php echo htmlspecialchars($product['name']); ?> - 
                $<?php echo htmlspecialchars($product['price']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
