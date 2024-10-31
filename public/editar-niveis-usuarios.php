<?php

require_once dirname(__DIR__) . '/src/controllers/NivelUsuarioController.php';
$nivelUsuarioController = new NivelUsuarioController();

$nivelId = $_GET['nivel'];
$buscaNivel = $nivelUsuarioController->buscar($nivelId);

if ($buscaNivel['status'] == 'empty' || $buscaNivel['status'] == 'error') {
    header('Location: niveis-usuarios.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Igreja Digital | Níveis de Usuário</title>
    <?php include 'includes/header.php'; ?>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <?php include 'includes/side_bar.php'; ?>
        <div id="page-content-wrapper">
            <?php include 'includes/top_menu.php'; ?>
            <div class="container-fluid p-2">
                <div class="card mb-2 shadow-sm">
                    <div class="card-body p-1">
                        <div class="btn-group" role="group" aria-label="Navegação">
                            <a class="btn btn-primary btn-sm custom-nav" href="home.php" role="button"><i class="fa-solid fa-house"></i> Início</a>
                            <a class="btn btn-success btn-sm custom-nav" href="niveis-usuarios.php" role="button"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar'])) {
                            $dados = [
                                'nivel_nome' => $_POST['nivel_nome'],
                                'nivel_descricao' => $_POST['nivel_descricao'],
                            ];

                            $result = $nivelUsuarioController->atualizar($nivelId, $dados);

                            if ($result['status'] == 'success') {
                                $buscaNivel = $nivelUsuarioController->buscar($nivelId);
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                            $result = $nivelUsuarioController->apagar($nivelId);
                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '. Aguarde...</div>';
                                echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "niveis-usuarios.php";
                                        }, 1000);
                                    </script>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-12 col-12">
                                <input type="text" class="form-control form-control-sm" name="nivel_nome" placeholder="Nome do nível" value="<?php echo $buscaNivel['dados']['nivel_nome']; ?>" required>
                            </div>
                            <div class="col-md-12 col-12">
                                <textarea class="form-control form-control-sm" name="nivel_descricao" rows="5" placeholder="Descrição do nível"><?php echo $buscaNivel['dados']['nivel_descricao']; ?></textarea>
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
    <?php include 'includes/footer.php'; ?>
</body>

</html>