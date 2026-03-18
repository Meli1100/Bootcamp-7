<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <h1 class="text-center mb-4">Form Input Product</h1>
                <?php if ($valid): ?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Submission successful!</h4>
                        <p>Here are the values you submitted:</p>
                        <ul>
                            <li><strong>Name:</strong> <?= htmlspecialchars($values['nama']) ?></li>
                            <li><strong>Price:</strong> <?= htmlspecialchars($values['price']) ?></li>
                            <li><strong>Description:</strong> <?= nl2br(htmlspecialchars($values['description'])) ?></li>
                            <li><strong>Category:</strong> <?= htmlspecialchars($values['category']) ?></li>
                            <li><strong>Stock:</strong> <?= htmlspecialchars($values['stock']) ?></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="productForm" action="form_process.php" method="post" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" id="name" placeholder="Enter your name" name="nama" value="<?= htmlspecialchars($values['nama']) ?>" required>
                        <div class="invalid-feedback"><?= $errors['nama'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" id="price" placeholder="Enter product price" name="price" value="<?= htmlspecialchars($values['price']) ?>" required>
                        <div class="invalid-feedback"><?= $errors['price'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" id="description" placeholder="Enter product description" name="description" rows="3" required><?= htmlspecialchars($values['description']) ?></textarea>
                        <div class="invalid-feedback"><?= $errors['description'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select <?= isset($errors['category']) ? 'is-invalid' : '' ?>" id="category" name="category" required>
                            <option value="" disabled <?= $values['category'] === '' ? 'selected' : '' ?>>Select category</option>
                            <option value="electronics" <?= $values['category'] === 'electronics' ? 'selected' : '' ?>>Electronics</option>
                            <option value="fashion" <?= $values['category'] === 'fashion' ? 'selected' : '' ?>>Fashion</option>
                            <option value="home" <?= $values['category'] === 'home' ? 'selected' : '' ?>>Home</option>
                            <option value="beauty" <?= $values['category'] === 'beauty' ? 'selected' : '' ?>>Beauty</option>
                        </select>
                        <div class="invalid-feedback"><?= $errors['category'] ?? '' ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control <?= isset($errors['stock']) ? 'is-invalid' : '' ?>" id="stock" placeholder="Enter product stock" name="stock" value="<?= htmlspecialchars($values['stock']) ?>" required>
                        <div class="invalid-feedback"><?= $errors['stock'] ?? '' ?></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        // preserve client-side validation logic if desired
        var form = document.getElementById('productForm');
        form.addEventListener('submit', function(e) {
            var nameEl = document.getElementById('name');
            var priceEl = document.getElementById('price');
            var descEl = document.getElementById('description');
            var catEl = document.getElementById('category');
            var stockEl = document.getElementById('stock');

            // reset previous feedback
            [nameEl, priceEl, descEl, catEl, stockEl].forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            document.getElementById('nameError')?.remove();
            document.getElementById('priceError')?.remove();
            document.getElementById('descriptionError')?.remove();
            document.getElementById('categoryError')?.remove();
            document.getElementById('stockError')?.remove();

            var valid = true;

            var name = nameEl.value.trim();
            var price = parseFloat(priceEl.value);
            var description = descEl.value.trim();
            var category = catEl.value;
            var stock = parseInt(stockEl.value, 10);

            if (name === '') {
                valid = false;
                nameEl.classList.add('is-invalid');
                var err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'Name is required.';
                nameEl.parentNode.appendChild(err);
            }
            if (isNaN(price) || price <= 0) {
                valid = false;
                priceEl.classList.add('is-invalid');
                var err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'Price must be a number greater than 0.';
                priceEl.parentNode.appendChild(err);
            }
            if (description === '') {
                valid = false;
                descEl.classList.add('is-invalid');
                var err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'Description is required.';
                descEl.parentNode.appendChild(err);
            }
            if (!category) {
                valid = false;
                catEl.classList.add('is-invalid');
                var err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'Please select a category.';
                catEl.parentNode.appendChild(err);
            }
            if (isNaN(stock) || stock < 0) {
                valid = false;
                stockEl.classList.add('is-invalid');
                var err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'Stock must be a non-negative integer.';
                stockEl.parentNode.appendChild(err);
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    </script>
</body>
</html>
