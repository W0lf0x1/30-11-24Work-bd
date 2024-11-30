<?php
$conn = new mysqli('192.168.199.13', 'learn', 'learn', 'learn_base30-11_horochev364');
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}

$category = $_GET['category'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$search_name = $_GET['search_name'] ?? '';

$query = "SELECT * FROM products WHERE 1";

if (!empty($category)) {
    $query .= " AND category = '" . $conn->real_escape_string($category) . "'";
}
if (!empty($min_price)) {
    $query .= " AND price >= " . (float)$min_price;
}
if (!empty($max_price)) {
    $query .= " AND price <= " . (float)$max_price;
}
if (!empty($search_name)) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($search_name) . "%'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product filtering</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Product filtering</h1>
    <form method="GET" class="filter-form">
        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="">All Category</option>
            <option value="Electronics" <?= $category == 'Electronics' ? 'selected' : '' ?>>Electronics</option>
            <option value="Clothes" <?= $category == 'Clothes' ? 'selected' : '' ?>>Clothes</option>
            <option value="Furniture" <?= $category == 'Furniture' ? 'selected' : '' ?>>Furniture</option>
        </select>

        <label for="min_price">Minimum price:</label>
        <input type="number" name="min_price" id="min_price" value="<?= htmlspecialchars($min_price) ?>">

        <label for="max_price">Maximum price:</label>
        <input type="number" name="max_price" id="max_price" value="<?= htmlspecialchars($max_price) ?>">

        <label for="search_name">Search by name:</label>
        <input type="text" name="search_name" id="search_name" value="<?= htmlspecialchars($search_name) ?>">

        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No products were found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>