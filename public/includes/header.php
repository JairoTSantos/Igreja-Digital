<?php

ob_start();

$config = include dirname(__DIR__, 2) . '/config/config.php';
$appConfig = $config;

$pageTitle = isset($pageTitle) ? $pageTitle : 'Título Padrão'; // Título padrão se não for definido

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="<?php echo $appConfig['app']['app_folder']; ?>/public/img/favicon.ico" />
    <link href="<?php echo $appConfig['app']['app_folder']; ?>/public/css/styles.css" rel="stylesheet" />
    <link href="<?php echo $appConfig['app']['app_folder']; ?>/public/css/custom.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/632988d903.js" crossorigin="anonymous"></script>
    <title><?= htmlspecialchars($appConfig['app']['app_name']) ?> :: <?= htmlspecialchars($pageTitle) ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $base_url; ?>/public/js/scripts.js"></script>
</head>