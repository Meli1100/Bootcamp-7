<?php
// process product form submission
$errors = [];
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['nama'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? '';

    // validation
    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = 'Price must be a number greater than 0.';
    }
    if ($description === '') {
        $errors[] = 'Description is required.';
    }
    if ($category === '' || $category === null) {
        $errors[] = 'Please select a category.';
    }
    if (!is_numeric($stock) || (int)$stock < 0) {
        $errors[] = 'Stock must be a non-negative integer.';
    }

    if (empty($errors)) {
        // successful validation - prepare result array
        $result = [
            'Name' => htmlspecialchars($name),
            'Price' => number_format((float)$price, 2),
            'Description' => nl2br(htmlspecialchars($description)),
            'Category' => htmlspecialchars($category),
            'Stock' => (int)$stock
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="tugas-7.php" class="btn btn-secondary">Back to form</a>
        <?php elseif ($result !== null): ?>
            <h2>Submitted Data</h2>
            <table class="table table-bordered">
                <?php foreach ($result as $key => $val): ?>
                    <tr>
                        <th><?php echo $key; ?></th>
                        <td><?php echo $val; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="tugas-7.php" class="btn btn-primary">Enter another product</a>
        <?php else: ?>
            <!-- no POST yet -->
            <p>No data submitted.</p>
            <a href="tugas-7.php" class="btn btn-secondary">Go to form</a>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
