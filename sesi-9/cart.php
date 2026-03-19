<?php
session_start();
require_once 'koneksi.php';
require_once 'components/template.php';

$cart = $_SESSION['cart'] ?? [];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $id => $qty) {
            $id = intval($id);
            $qty = max(0, intval($qty));
            if (isset($cart[$id])) {
                if ($qty <= 0) {
                    unset($cart[$id]);
                } else {
                    $cart[$id]['quantity'] = $qty;
                }
            }
        }
        $_SESSION['cart'] = $cart;
        $message = 'Cart updated.';
    }
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
        $cart = [];
        $message = 'Cart cleared.';
    }
    if (isset($_POST['checkout'])) {
        header('Location: checkout.php');
        exit;
    }
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

render_header('Shopping Cart');
render_navbar('cart');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h4 mb-0">Shopping Cart</h1>
                            <small class="text-muted">Kelola keranjang belanja sebelum checkout.</small>
                        </div>
                        <a href="home.php" class="btn btn-secondary btn-sm">Continue Shopping</a>
                    </div>

                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endif; ?>

                    <?php if (empty($cart)): ?>
                        <div class="alert alert-info">Cart is empty. Add products from home page.</div>
                    <?php else: ?>
                        <form method="post" action="cart.php">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($cart as $id => $item): ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                                    <small class="text-muted">ID: <?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?></small>
                                                </td>
                                                <td style="width:120px;"><input type="number" name="quantity[<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>]" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" min="0" class="form-control"></td>
                                                <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                                <td>Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" name="update_cart" class="btn btn-primary btn-sm">Update Cart</button>
                                    <button type="submit" name="clear_cart" class="btn btn-outline-danger btn-sm">Clear Cart</button>
                                </div>
                                <div class="text-end">
                                    <h5>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></h5>
                                    <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php render_footer();
