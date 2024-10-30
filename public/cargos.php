<?php

require_once dirname(__DIR__) . '/src/controllers/CargoController.php';
$cargoController = new CargoController();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Igreja Digital | Cargos</title>
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
                                'cargo_nome' => htmlspecialchars($_POST['cargo_nome'], ENT_QUOTES, 'UTF-8'),
                                'cargo_descricao' => htmlspecialchars($_POST['cargo_descricao'], ENT_QUOTES, 'UTF-8'),
                            ];

                            $result = $cargoController->criar($dados);

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-12 col-12">
                                <input type="text" class="form-control form-control-sm" name="cargo_nome" placeholder="Nome do cargo" required>
                            </div>
                            <div class="col-md-12 col-12">
                                <textarea class="form-control form-control-sm" name="cargo_descricao" rows="5" placeholder="Descrição do cargo"></textarea>
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
                                        <td style="white-space: nowrap;">Cargo</td>
                                        <td style="white-space: nowrap;">Descrição</td>
                                        <td style="white-space: nowrap;">Criado em</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $busca = $cargoController->listar();
                                    if ($busca['status'] == 'success') {
                                        foreach ($busca['dados'] as $cargo) {
                                            echo '<tr>';
                                            echo '<td style="white-space: nowrap; "><a href="editar-cargo.php?cargo=' . $cargo['cargo_id'] . '">' . $cargo['cargo_nome'] . '</a></td>';
                                            echo '<td style="white-space: nowrap; ">' . $cargo['cargo_descricao'] . '</td>';
                                            echo '<td style="white-space: nowrap; ">' . date('d/m/Y | H:i', strtotime($cargo['cargo_adicionado_em'])) . '</td>';
                                            echo '</tr>';
                                        }
                                    } else if ($busca['status'] == 'empty') {
                                        echo '<tr><td colspan="3">' . $busca['message'] . '</td></tr>';
                                    } else if ($busca['status'] == 'error') {
                                        echo '<tr><td colspan="3">' . $busca['message'] . '</td></tr>';
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 2000);
        });
    </script>
</body>

</html>