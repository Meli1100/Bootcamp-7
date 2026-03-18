<?php
require_once 'koneksi.php';

// get filter/search input
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// build query with search and filter safely
$sql = "SELECT * FROM products";
$where = [];
$params = [];

if ($search !== '') {
    $where[] = "name LIKE ?";
    $params[] = "%$search%";
}
if ($category !== '' && $category !== 'all') {
    $where[] = "category = ?";
    $params[] = $category;
}

if (count($where) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

if (count($params) > 0) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// fetch categories for filter select
$catResult = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
$categories = [];
if ($catResult) {
    while ($row = $catResult->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h1 class="mb-4">Product List</h1>
                    <form class="row g-2 mb-3" method="get" action="home.php">
                        <div class="col-12 col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="col-12 col-md-4">
                            <select name="category" class="form-select">
                                <option value="all">All categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($cat === $category) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="home.php" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                    <?php if (empty($products)):?>
                        <div class="alert alert-info" role="alert">
                            No products found.
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($products as $product): ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                            <h6 class="card-subtitle mb-2 text-muted">Category: <?php echo htmlspecialchars($product['category'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                            <p class="card-text"><?php echo nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="badge bg-primary fs-6">Price: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                                <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
</html>
