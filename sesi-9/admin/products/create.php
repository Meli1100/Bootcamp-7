<?php
require_once '../../koneksi.php';
require_once '../../components/template.php';

$feedback = '';
$error = '';
$name = '';
$category = '';
$price = '';
$description = '';
$imageFileName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '' || $category === '' || $price === '') {
        $error = 'Name, category, and price are required.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Price must be a valid positive number.';
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please upload a valid image file.';
    } else {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        $mimeType = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($mimeType, $allowed)) {
            $error = 'Only jpg/png/gif image types are allowed.';
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageFileName = uniqid('product_', true) . '.' . $ext;
            $targetDir = dirname(__DIR__, 2) . '/image';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $targetPath = $targetDir . '/' . $imageFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param('ssiss', $name, $category, $price, $description, $imageFileName);
                    if ($stmt->execute()) {
                        $feedback = 'Product has been saved successfully.';
                        $name = $category = $price = $description = '';
                        $imageFileName = '';
                    } else {
                        $error = 'Save failed: ' . $stmt->error;
                        unlink($targetPath);
                    }
                    $stmt->close();
                } else {
                    $error = 'Prepare failed: ' . $conn->error;
                    unlink($targetPath);
                }
            } else {
                $error = 'Failed to save uploaded image.';
            }
        }
    }
}

render_header('Create Product');
render_navbar('create');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="text-center mb-4">Form Input Product</h1>
                    <?php if ($feedback): ?>
                        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <form method="post" action="create.php" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label class="form-label" for="image">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="../../home.php" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
render_footer();
