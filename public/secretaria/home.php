<?php
$pageTitle = 'Home';
include dirname(__DIR__, 2) . '/public/includes/header.php'; // Inclua o header apenas uma vez
?>

<body>
    <div class="d-flex" id="wrapper">
        <?php include dirname(__DIR__, 2) . '/public/includes/side_bar.php'; ?>
        <div id="page-content-wrapper">
            <?php include dirname(__DIR__, 2) . '/public/includes/top_menu.php' ?>
            <div class="container-fluid">
                pagina home secretearia
            </div>
        </div>
    </div>
    <?php include dirname(__DIR__, 2) . '/public/includes/footer.php' ?>
</body>

</html>