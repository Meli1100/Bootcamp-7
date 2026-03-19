<?php
$active = isset($activePage) ? $activePage : '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/Bootcamp-7/sesi-9/home.php">Drew Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="#mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active === 'home' ? 'active' : ''); ?>" aria-current="page" href="/Bootcamp-7/sesi-9/home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active === 'products' ? 'active' : ''); ?>" href="/Bootcamp-7/sesi-9/admin/products/index.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active === 'create' ? 'active' : ''); ?>" href="/Bootcamp-7/sesi-9/admin/products/create.php">Create</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active === 'cart' ? 'active' : ''); ?>" href="/Bootcamp-7/sesi-9/cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($active === 'about' ? 'active' : ''); ?>" href="/Bootcamp-7/sesi-9/about.php">About</a>
                </li>
            </ul>
            <form class="d-flex" role="search" method="get" action="home.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-light btn-sm" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
