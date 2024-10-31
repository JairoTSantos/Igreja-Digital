<?php

require_once dirname(__DIR__) . '/src/controllers/CargoController.php';
$cargoController = new CargoController();

$ordernar_por = $_GET['ordernar_por'] ?? 'cargo_nome';
$ordem = $_GET['ordem'] ?? 'asc';
$termo = $_GET['termo'] ?? null;


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
                <div class="card mb-2 card_description">
                    <div class="card-header bg-primary text-white px-2 py-1  card-background">Cargos</div>
                    <div class="card-body p-2">
                        <p class="card-text mb-2">Gerencie os cargos da igreja de forma eficiente.</p>
                        <p class="card-text mb-0">Por favor, insira o nome do cargo e uma breve descrição de suas responsabilidades. <b>Todos os campos são obrigatórios.</b></p>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                            $dados = [
                                'cargo_nome' => $_POST['cargo_nome'],
                                'cargo_descricao' => $_POST['cargo_descricao'],
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

                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <form class="row g-2 form_custom" method="GET" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="ordernar_por">
                                    <option value="orgao_nome" <?php echo ($ordernar_por == 'orgao_nome') ? 'selected' : ''; ?>>Nome</option>
                                    <option value="orgao_adicionado_em" <?php echo ($ordernar_por == 'orgao_adicionado_em') ? 'selected' : ''; ?>>Data de criação</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="ordem">
                                    <option value="ASC" <?php echo ($ordem == 'ASC') ? 'selected' : ''; ?>>Crescente</option>
                                    <option value="DESC" <?php echo ($ordem == 'DESC') ? 'selected' : ''; ?>>Decrescente</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-10">
                                <input type="text" class="form-control form-control-sm" name="termo" placeholder="Buscar..." value="<?php echo $termo; ?>">
                            </div>

                            <div class="col-md-4 col-2">
                                <button type="submit" class="btn btn-success btn-sm" name="btn_buscar" onclick="this.name='';"><i class="fa-solid fa-magnifying-glass"></i></button>
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
                                    $busca = $cargoController->listar($termo, $ordernar_por,  $ordem);
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
    <?php include 'includes/footer.php' ?>

</body>

</html>