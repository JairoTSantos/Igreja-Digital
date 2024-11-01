<head>
    <?php include dirname(__DIR__, 2) . '/public/includes/header.php'; ?>
    <title><?= $appConfig['app']['app_name'] ?> :: Home</title>
</head>


<body>
    <div class="d-flex" id="wrapper">
        <?php include dirname(__DIR__, 2) . '/public/includes/side_bar.php'; ?>

        <div id="page-content-wrapper">
            <?php include dirname(__DIR__, 2) . '/public/includes/top_menu.php' ?>
            <div class="container-fluid">

            </div>
        </div>
    </div>
    <?php include dirname(__DIR__, 2) . '/public/includes/footer.php' ?>
</body>

</html>