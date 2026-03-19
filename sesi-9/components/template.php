<?php
function render_header($title = 'My Website') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <style>
            :root {
                --bs-body-bg: #f5f0e8;
                --bs-body-color: #382d23;
                --bs-primary: #a57644;
                --bs-secondary: #d1b89f;
                --bs-success: #7a9b5f;
                --bs-info: #b08b6c;
                --bs-light: #f8f3eb;
                --bs-dark: #3d2c24;
            }
            body {
                background: radial-gradient(circle at top, #fff9ef 0%, #f2e6d7 100%);
                color: #382d23;
            }
            .card {
                background: #fffdf7;
                border-color: #d8c3a5;
            }
            .navbar {
                background: #8c6743 !important;
            }
            .navbar .nav-link,
            .navbar .navbar-brand {
                color: #fff !important;
            }
            .btn-primary {
                background-color: #8c6743;
                border-color: #8c6743;
            }
            .btn-primary:hover {
                background-color: #7b5936;
                border-color: #7b5936;
            }
            .btn-secondary {
                background-color: #a98f6f;
                border-color: #a98f6f;
            }
        </style>
    </head>
    <body class="bg-light">
    <?php
}

function render_navbar($activePage = 'home') {
    $activePage = htmlspecialchars($activePage, ENT_QUOTES, 'UTF-8');
    require __DIR__ . '/navbar.php';
}

function render_footer() {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </body>
    </html>
    <?php
}
