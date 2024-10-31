<?php

require_once dirname(__DIR__) . '/src/controllers/FamiliaController.php'; // Mudar para FamiliaController
$familiaController = new FamiliaController();

$ordernar_por = $_GET['ordernar_por'] ?? 'familia_nome'; // Altera para familia_nome
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
                        <a class="btn btn-primary btn-sm custom-nav" href="home.php" role="button"><i class="fa-solid fa-house"></i> Início</a>
                    </div>
                </div>
                <div class="card mb-2 card_description">
                    <div class="card-header bg-primary text-white px-2 py-1 card-background">Famílias</div> <!-- Mudar header -->
                    <div class="card-body p-2">
                        <p class="card-text mb-2">Gerencie as famílias da igreja de forma eficiente. No módulo de pessoas, você poderá associar cada indivíduo à sua respectiva família, garantindo um cadastro organizado e completo.</p>
                        <p class="card-text mb-0">Por favor, insira o nome da família, como "Família Santos" ou "Família Ferreira Silva". <b>Todos os campos são obrigatórios.</b></p>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                            $dados = [
                                'familia_nome' => $_POST['familia_nome'], // Mudar para familia_nome
                            ];

                            $result = $familiaController->criar($dados); // Alterar para familiaController

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-12 col-12">
                                <input type="text" class="form-control form-control-sm" name="familia_nome" placeholder="Nome da família" required> <!-- Mudar nome do campo -->
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
                                    <option value="familia_nome" <?php echo ($ordernar_por == 'familia_nome') ? 'selected' : ''; ?>>Nome</option> <!-- Mudar nome do campo -->
                                    <option value="familia_adicionada_em" <?php echo ($ordernar_por == 'familia_adicionada_em') ? 'selected' : ''; ?>>Data de criação</option> <!-- Mudar nome do campo -->
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
                                        <td style="white-space: nowrap;">Família</td> <!-- Mudar cabeçalho -->
                                        <td style="white-space: nowrap;">Criado em</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $busca = $familiaController->listar($termo, $ordernar_por,  $ordem); // Alterar para familiaController
                                    if ($busca['status'] == 'success') {
                                        foreach ($busca['dados'] as $familia) { // Mudar para familia
                                            echo '<tr>';
                                            echo '<td style="white-space: nowrap;"><a href="editar-familia.php?familia=' . $familia['familia_id'] . '">' . $familia['familia_nome'] . '</a></td>'; // Mudar para familia_nome
                                            echo '<td style="white-space: nowrap;">' . date('d/m/Y | H:i', strtotime($familia['familia_adicionada_em'])) . '</td>'; // Mudar para familia_adicionada_em
                                            echo '</tr>';
                                        }
                                    } else if ($busca['status'] == 'empty') {
                                        echo '<tr><td colspan="2">' . $busca['message'] . '</td></tr>'; // Mudar para 2 colunas
                                    } else if ($busca['status'] == 'error') {
                                        echo '<tr><td colspan="2">' . $busca['message'] . '</td></tr>'; // Mudar para 2 colunas
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