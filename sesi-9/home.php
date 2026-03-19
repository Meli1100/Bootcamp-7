<?php
session_start();
require_once 'koneksi.php';

$cartFeedback = '';
$cartError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    if ($productId <= 0 || $quantity <= 0) {
        $cartError = 'Invalid product or quantity.';
    } else {
        $stmtAdd = $conn->prepare('SELECT id, name, price, image FROM products WHERE id = ?');
        $stmtAdd->bind_param('i', $productId);
        $stmtAdd->execute();
        $prodRes = $stmtAdd->get_result();
        $item = $prodRes->fetch_assoc();
        $stmtAdd->close();

        if (!$item) {
            $cartError = 'Product not found.';
        } else {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                    'quantity' => $quantity,
                ];
            }
            $cartFeedback = 'Product added to cart.';
        }
    }
}

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
require_once 'components/template.php';

render_header('Home - My Shop');
render_navbar('home');
?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="mb-0">Product List</h1>
                            <small class="text-muted">Tambahkan ke keranjang belanja.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="admin/products/create.php" class="btn btn-success btn-sm">Add Product</a>
                            <a href="cart.php" class="btn btn-warning btn-sm">View Cart</a>
                        </div>
                    </div>
                    <?php if ($cartFeedback): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($cartFeedback, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <?php if ($cartError): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($cartError, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
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
                                                <form method="post" action="home.php" class="mt-2">
                                                    <input type="hidden" name="add_to_cart" value="1">
                                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" name="quantity" min="1" value="1" class="form-control" style="max-width: 90px;">
                                                        <button class="btn btn-sm btn-primary" type="submit">Add to Cart</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
render_footer();
