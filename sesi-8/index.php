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
                <!-- Form Input Product -->
                <form id="productForm" action="form_process.php" method="post" novalidate enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your name" name="nama" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" placeholder="Enter product price" name="price" required>
                        <div class="invalid-feedback" id="priceError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" placeholder="Enter product description" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="" disabled selected>Select category</option>
                            <option value="electronics">Electronics</option>
                            <option value="fashion">Fashion</option>
                            <option value="home">Home</option>
                            <option value="beauty">Beauty</option>
                        </select>
                        <div class="invalid-feedback" id="categoryError"></div>
                    </div>
                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <div class="invalid-feedback" id="imageError"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        // client-side validation for product form with Bootstrap feedback
        var form = document.getElementById('productForm');
        form.addEventListener('submit', function(e) {
            var nameEl = document.getElementById('name');
            var priceEl = document.getElementById('price');
            var descEl = document.getElementById('description');
            var catEl = document.getElementById('category');
            var imageEl = document.getElementById('image');

            // reset previous feedback
            [nameEl, priceEl, descEl, catEl, stockEl, imageEl].forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            document.getElementById('nameError').textContent = '';
            document.getElementById('priceError').textContent = '';
            document.getElementById('descriptionError').textContent = '';
            document.getElementById('categoryError').textContent = '';
            document.getElementById('imageError').textContent = '';

            var valid = true;

            var name = nameEl.value.trim();
            var price = parseFloat(priceEl.value);
            var description = descEl.value.trim();
            var category = catEl.value;
            var image = imageEl.files[0];

            if (name === '') {
                valid = false;
                nameEl.classList.add('is-invalid');
                document.getElementById('nameError').textContent = 'Name is required.';
            }
            if (isNaN(price) || price <= 0) {
                valid = false;
                priceEl.classList.add('is-invalid');
                document.getElementById('priceError').textContent = 'Price must be a number greater than 0.';
            }
            if (description === '') {
                valid = false;
                descEl.classList.add('is-invalid');
                document.getElementById('descriptionError').textContent = 'Description is required.';
            }
            if (!category) {
                valid = false;
                catEl.classList.add('is-invalid');
                document.getElementById('categoryError').textContent = 'Please select a category.';
            }
            if (!image) {
                valid = false;
                imageEl.classList.add('is-invalid');
                document.getElementById('imageError').textContent = 'Please upload a product image.';
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>