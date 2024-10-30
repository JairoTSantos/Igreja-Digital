<?php

require_once dirname(__DIR__) . '/src/controllers/FamiliaController.php'; // Mudei o controller
$familiaController = new FamiliaController(); // Instanciando o novo controller

?>

<!DOCTYPE html>
<html lang="pt-BR"> <!-- Mudei para o português -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Igreja Digital | Famílias</title> <!-- Título atualizado -->
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
                        <a class="btn btn-primary btn-sm custom-nav " href="home.php" role="button"><i class="fa-solid fa-house"></i> Início</a>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                            $dados = [
                                'familia_nome' => htmlspecialchars($_POST['familia_nome'], ENT_QUOTES, 'UTF-8'), // Mudança do nome do campo
                            ];

                            $result = $familiaController->criar($dados); // Chamando o método criar do FamiliaController

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-12 col-12">
                                <input type="text" class="form-control form-control-sm" name="familia_nome" placeholder="Nome da família" required> <!-- Mudança do nome do campo -->
                            </div>
                            <div class="col-md-12 col-12">
                                <textarea class="form-control form-control-sm" name="familia_descricao" rows="5" placeholder="Descrição da família"></textarea> <!-- Campo removido conforme o controlador atual -->
                            </div>
                            <div class="col-md-4 col-6">
                                <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered mb-0 custom_table">
                                <thead>
                                    <tr>
                                        <td style="white-space: nowrap;">Família</td> <!-- Título atualizado -->
                                        <td style="white-space: nowrap;">Criado em</td> <!-- Removido Descrição conforme o modelo -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $busca = $familiaController->listar(); // Chamando o método listar do FamiliaController
                                    if ($busca['status'] == 'success') {
                                        foreach ($busca['dados'] as $familia) { // Mudança para familia
                                            echo '<tr>';
                                            echo '<td style="white-space: nowrap;"><a href="editar-familia.php?familia=' . $familia['familia_id'] . '">' . $familia['familia_nome'] . '</a></td>'; // Mudança para familia
                                            echo '<td style="white-space: nowrap;">' . date('d/m/Y | H:i', strtotime($familia['familia_adicionada_em'])) . '</td>'; // Removido campo de descrição
                                            echo '</tr>';
                                        }
                                    } else if ($busca['status'] == 'vazio') { // Mudança de 'empty' para 'vazio'
                                        echo '<tr><td colspan="2">' . $busca['message'] . '</td></tr>'; // Mudança do colspan
                                    } else if ($busca['status'] == 'error') {
                                        echo '<tr><td colspan="2">' . $busca['message'] . '</td></tr>'; // Mudança do colspan
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 2000);
        });
    </script>
</body>

</html>