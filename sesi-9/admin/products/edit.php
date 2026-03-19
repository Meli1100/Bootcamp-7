<?php
require_once '../../koneksi.php';
require_once '../../components/template.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: ../../products.php');
    exit;
}

$name = '';
$category = '';
$price = '';
$description = '';
$error = '';
$success = '';

$stmt = $conn->prepare('SELECT * FROM products WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: ../../products.php');
    exit;
}

$name = $product['name'];
$category = $product['category'];
$price = $product['price'];
$description = $product['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $category === '' || $price === '') {
        $error = 'Name, category, and price are required.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Price must be a valid positive number.';
    } else {
        $stmt = $conn->prepare('UPDATE products SET name = ?, category = ?, price = ?, description = ? WHERE id = ?');
        $stmt->bind_param('ssisi', $name, $category, $price, $description, $id);
        if ($stmt->execute()) {
            $success = 'Product updated successfully.';
        } else {
            $error = 'Update failed: ' . $stmt->error;
        }
        $stmt->close();
    }
}

render_header('Edit Product');
render_navbar('products');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h4 mb-0">Edit Product</h1>
                        <a href="../../products.php" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <form method="post" action="edit.php?id=<?php echo urlencode($id); ?>">
                        <div class="mb-3">
                            <label class="form-label" for="name">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Minuman" <?php echo ($category === 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                                <option value="Makanan" <?php echo ($category === 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                                <option value="Lainnya" <?php echo ($category === 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required value="<?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
render_footer();
