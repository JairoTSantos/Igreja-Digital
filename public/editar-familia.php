<?php

require_once dirname(__DIR__) . '/src/controllers/FamiliaController.php'; // Mudar para FamiliaController
$familiaController = new FamiliaController();

$familiaId = $_GET['familia']; // Mudar para familia
$buscaFamilia = $familiaController->buscar($familiaId); // Mudar para familia

if ($buscaFamilia['status'] == 'empty' || $buscaFamilia['status'] == 'error') {
    header('Location: familias.php'); // Mudar para familias.php
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Igreja Digital | Famílias</title> <!-- Mudar título -->
    <?php include 'includes/header.php' ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_bar.php' ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php' ?>
            <div class="container-fluid p-2">
                <div class="card mb-2 shadow-sm">
                    <div class="card-body p-1">
                        <div class="btn-group" role="group" aria-label="Navegação">
                            <a class="btn btn-primary btn-sm custom-nav" href="home.php" role="button"><i class="fa-solid fa-house"></i> Início</a>
                            <a class="btn btn-success btn-sm custom-nav" href="familias.php" role="button"><i class="fa-solid fa-arrow-left"></i> Voltar</a> <!-- Mudar para familias.php -->
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar'])) {

                            $dados = [
                                'familia_nome' => $_POST['familia_nome'], // Mudar para familia_nome
                            ];

                            $result = $familiaController->atualizar($familiaId, $dados); // Alterar para familiaController

                            if ($result['status'] == 'success') {
                                $buscaFamilia = $familiaController->buscar($familiaId); // Mudar para familia
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                            $result = $familiaController->apagar($familiaId); // Alterar para familiaController
                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '. Aguarde...</div>';
                                echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "familias.php"; // Mudar para familias.php
                                        }, 1000);
                                    </script>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-12 col-12">
                                <input type="text" class="form-control form-control-sm" name="familia_nome" placeholder="Nome da família" value="<?php echo $buscaFamilia['dados']['familia_nome'] ?>" required> <!-- Mudar para familia_nome -->
                            </div>
                            
                            <div class="col-md-4 col-6">
                                <div class="btn-group" role="group" aria-label="Ações">
                                    <button type="submit" class="btn btn-success btn-sm" name="btn_atualizar"><i class="fa-regular fa-floppy-disk"></i> Atualizar</button>
                                    <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash-can"></i> Apagar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php' ?>
</body>

</html>
