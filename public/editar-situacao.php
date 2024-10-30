<?php

require_once dirname(__DIR__) . '/src/controllers/PessoaSituacaoController.php';
$pessoaSituacaoController = new PessoaSituacaoController();

$situacaoId = $_GET['situacao'] ?? null;
$buscaSituacao = $situacaoId ? $pessoaSituacaoController->buscar($situacaoId) : null;

if ($situacaoId && ($buscaSituacao['status'] == 'vazio' || $buscaSituacao['status'] == 'error')) {
    header('Location: pessoas_situacoes.php');
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Igreja Digital | Situações de Pessoas</title>
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
                            <a class="btn btn-success btn-sm custom-nav" href="pessoas-situacoes.php" role="button"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar'])) {
                            $dados = [
                                'situacao_nome' => htmlspecialchars($_POST['situacao_nome'], ENT_QUOTES, 'UTF-8'),
                                'situacao_descricao' => htmlspecialchars($_POST['situacao_descricao'], ENT_QUOTES, 'UTF-8'),
                            ];

                            $result = $pessoaSituacaoController->atualizar($situacaoId, $dados);

                            if ($result['status'] == 'success') {
                                $buscaSituacao = $pessoaSituacaoController->buscar($situacaoId);
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                            $result = $pessoaSituacaoController->apagar($situacaoId);
                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '. Aguarde...</div>';
                                echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "pessoas-situacoes.php";
                                        }, 1000);
                                    </script>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-12 col-12">
                                <input type="text" class="form-control form-control-sm" name="situacao_nome" placeholder="Nome da situação" value="<?php echo $buscaSituacao['dados']['situacao_nome'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-12 col-12">
                                <textarea class="form-control form-control-sm" name="situacao_descricao" rows="5" placeholder="Descrição da situação"><?php echo $buscaSituacao['dados']['situacao_descricao'] ?? ''; ?></textarea>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 2000);

            $('button[name="btn_apagar"]').on('click', function(event) {
                const confirmacao = confirm("Tem certeza que deseja apagar?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });

            $('button[name="btn_atualizar"]').on('click', function(event) {
                const confirmacao = confirm("Tem certeza que deseja atualizar?");
                if (!confirmacao) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>