<?php
session_start();
require_once 'koneksi.php';
require_once 'components/template.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$error = '';
$success = '';
$user_name = '';
$email = '';
$address = '';
$phone = '';

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = trim($_POST['user_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($user_name === '' || $email === '' || $address === '' || $phone === '') {
        $error = 'Please fill all fields including email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare('INSERT INTO transactions (user_name, email, address, phone, total, status) VALUES (?, ?, ?, ?, ?, ?)');
        $status = 'menunggu pembayaran';
        $stmt->bind_param('ssssds', $user_name, $email, $address, $phone, $total, $status);
        if ($stmt->execute()) {
            $transactionId = $stmt->insert_id;
            $stmt->close();

            $insertItem = $conn->prepare('INSERT INTO transaction_products (transaction_id, product_id, total_price, total_product) VALUES (?, ?, ?, ?)');
            foreach ($cart as $item) {
                $productTotal = $item['price'] * $item['quantity'];
                $insertItem->bind_param('iiii', $transactionId, $item['id'], $productTotal, $item['quantity']);
                $insertItem->execute();
            }
            $insertItem->close();
            unset($_SESSION['cart']);
            header('Location: transaction_status.php?id=' . $transactionId);
            exit;
        } else {
            $error = 'Failed to save transaction: ' . $stmt->error;
        }
    }
}

render_header('Checkout');
render_navbar('cart');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4">Checkout</h1>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
                        <a href="home.php" class="btn btn-primary">Back to Home</a>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <strong>Cart Summary</strong>
                            <ul>
                                <?php foreach ($cart as $item): ?>
                                    <li><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> x <?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?> = Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <p><strong>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></p>
                        </div>
                        <form method="post" action="checkout.php">
                            <div class="mb-3">
                                <label class="form-label" for="user_name">Name</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success">Submit Transaction</button>
                            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php render_footer();
