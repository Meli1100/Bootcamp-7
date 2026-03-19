<?php
require_once 'koneksi.php';
require_once 'components/template.php';

$transactionId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($transactionId <= 0) {
    header('Location: home.php');
    exit;
}

$stmt = $conn->prepare('SELECT * FROM transactions WHERE id = ?');
$stmt->bind_param('i', $transactionId);
$stmt->execute();
$res = $stmt->get_result();
$transaction = $res->fetch_assoc();
$stmt->close();

if (!$transaction) {
    header('Location: home.php');
    exit;
}

$itemsStmt = $conn->prepare('SELECT tp.*, p.name FROM transaction_products tp JOIN products p ON tp.product_id = p.id WHERE tp.transaction_id = ?');
$itemsStmt->bind_param('i', $transactionId);
$itemsStmt->execute();
$itemsRes = $itemsStmt->get_result();
$items = [];
while ($row = $itemsRes->fetch_assoc()) {
    $items[] = $row;
}
$itemsStmt->close();

render_header('Transaction Status');
render_navbar('');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4">Transaction Status</h1>
                    <div class="mb-3">
                        <span class="badge bg-info">Status: <?php echo htmlspecialchars($transaction['status'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <p class="mt-2"><strong>ID:</strong> <?php echo htmlspecialchars($transaction['id'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($transaction['user_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($transaction['address'], ENT_QUOTES, 'UTF-8')); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($transaction['phone'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($transaction['date_time'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Total:</strong> Rp <?php echo number_format($transaction['total'], 0, ',', '.'); ?></p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($item['total_product'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>Rp <?php echo number_format($item['total_price'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="home.php" class="btn btn-primary">Back to Home</a>
                        <?php
                        $whatsappNumber = '6281234567890';
                        $whatsappMessage = urlencode("Halo, saya ingin konfirmasi transaksi #{$transaction['id']} (nama: {$transaction['user_name']}, total: Rp " . number_format($transaction['total'], 0, ',', '.') . ").");
                        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";
                        ?>
                        <a href="<?php echo $whatsappUrl; ?>" target="_blank" class="btn btn-success">Konfirmasi via WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php render_footer();
