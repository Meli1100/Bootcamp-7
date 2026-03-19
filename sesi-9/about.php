<?php
require_once 'components/template.php';

render_header('About');
render_navbar('about');
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-3">About Drew Store</h1>
                    <p>Ini adalah contoh aplikasi PHP sederhana dengan Bootstrap untuk belajar CRUD.</p>
                    <p>Gunakan menu <strong>Products</strong> untuk melihat daftar produk dan <strong>Create Product</strong> untuk menambahkan produk baru.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
render_footer();
