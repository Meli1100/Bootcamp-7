<?php
require_once 'koneksi.php';
require_once 'components/template.php';

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

render_header('Products');
render_navbar('products');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3">Products</h1>
                <a href="admin/products/create.php" class="btn btn-success">Create Product</a>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (empty($products)): ?>
                        <div class="alert alert-info">No products available yet.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th width="170">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $p): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($p['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php if (!empty($p['image']) && file_exists(__DIR__ . '/image/' . $p['image'])): ?>
                                                    <img src="image/<?php echo htmlspecialchars($p['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?>" style="max-width: 90px; max-height: 75px; object-fit: cover;" class="rounded">
                                                <?php else: ?>
                                                    <span class="text-muted">No image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="admin/products/edit.php?id=<?php echo urlencode($p['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <form method="post" action="admin/products/delete.php" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
render_footer();
