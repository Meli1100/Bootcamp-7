<?php
require_once '../../koneksi.php';
require_once '../../components/template.php';

// get all products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

render_header('Admin Product List');
render_navbar('products');
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="h3 mb-0">Product List (Admin)</h1>
                    <small class="text-muted">Kelola produk dengan edit / delete.</small>
                </div>
                <a href="create.php" class="btn btn-success">Create New</a>
            </div>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="productTable" class="table table-striped table-borderless mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th width="170">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No products found.</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <?php if (!empty($p['image']) && file_exists(dirname(__DIR__, 2) . '/image/' . $p['image'])): ?>
                                                <img src="../../image/<?php echo htmlspecialchars($p['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($p['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo urlencode($p['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <form method="post" action="delete.php" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#productTable').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 20, 50],
            order: [[0, 'desc']],
        });
    });
</script>
<?php render_footer();
