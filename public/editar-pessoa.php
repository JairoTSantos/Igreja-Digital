<?php

require_once dirname(__DIR__) . '/src/controllers/PessoaController.php';
$pessoaController = new PessoaController();

require_once dirname(__DIR__) . '/src/controllers/CargoController.php';
$cargoController = new CargoController();

require_once dirname(__DIR__) . '/src/controllers/PessoaSituacaoController.php';
$pessoaSituacaoController = new PessoaSituacaoController();

require_once dirname(__DIR__) . '/src/controllers/FamiliaController.php';
$familiaController = new FamiliaController();


$pessoaId = $_GET['pessoa'];
$buscaPessoa = $pessoaController->buscar($pessoaId);

if ($buscaPessoa['status'] == 'empty' || $buscaPessoa['status'] == 'error') {
    header('Location: pessoas.php');
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
    <title>Igreja Digital | Pessoas</title>
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
                        <a class="btn btn-success btn-sm custom-nav" href="pessoas.php" role="button"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                    </div>
                </div>
                <div class="card mb-2 shadow-sm">
                    <div class="card-body p-1">
                        <div class="btn-group" role="group" aria-label="Navegação">
                            <a class="btn btn-outline-primary btn-sm custom-nav" href="cargos.php" role="button" id=btn-cargos><i class="fa-solid fa-plus"></i> cargo</a>
                            <a class="btn btn-outline-secondary btn-sm custom-nav" href="familias.php" role="button" id=btn-familias><i class="fa-solid fa-plus"></i> família</a>
                            <a class="btn btn-outline-success btn-sm custom-nav" href="pessoas-situacoes.php" role="button" id=btn-situacoes><i class="fa-solid fa-plus"></i> situação</a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_atualizar'])) {

                            $dados = [
                                'pessoa_nome' => $_POST['pessoa_nome'] ?? null,
                                'pessoa_cpf' => $_POST['pessoa_cpf'] ?? null,
                                'pessoa_email' => $_POST['pessoa_email'] ?? null,
                                'pessoa_aniversario' => $_POST['pessoa_aniversario'] ?? null,
                                'pessoa_familia' => $_POST['pessoa_familia'] ?? null,
                                'pessoa_endereco' => $_POST['pessoa_endereco'] ?? null,
                                'pessoa_municipio' => $_POST['pessoa_municipio'] ?? null,
                                'pessoa_estado' => $_POST['pessoa_estado'] ?? null,
                                'pessoa_telefone_celular' => $_POST['pessoa_telefone_celular'] ?? null,
                                'pessoa_telefone_fixo' => $_POST['pessoa_telefone_fixo'] ?? null,
                                'pessoa_instagram' => $_POST['pessoa_instagram'] ?? null,
                                'pessoa_facebook' => $_POST['pessoa_facebook'] ?? null,
                                'pessoa_data_conversao' => $_POST['pessoa_data_conversao'] ?? null,
                                'pessoa_data_batismo' => $_POST['pessoa_data_batismo'] ?? null,
                                'pessoa_batizada_local' => $_POST['pessoa_batizada_local'] ?? null,
                                'pessoa_cargo' => $_POST['pessoa_cargo'] ?? null,
                                'pessoa_situacao' => $_POST['pessoa_situacao'] ?? null,
                                'pessoa_informacoes' => $_POST['pessoa_informacoes'] ?? null,
                                'foto' => $_FILES['foto'],
                            ];

                            $result = $pessoaController->atualizar($pessoaId, $dados);

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                                $buscaPessoa = $pessoaController->buscar($pessoaId);
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_apagar'])) {
                            $result = $pessoaController->apagar($pessoaId);
                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '. Aguarde...</div>';
                                echo '<script>
                                        setTimeout(function(){
                                            window.location.href = "pessoas.php";
                                        }, 1000);
                                    </script>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="multipart/form-data">
                            <div class="col-md-4 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_nome" placeholder="Nome" value="<?php echo $buscaPessoa['dados']['pessoa_nome'] ?>" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_cpf" data-mask="000.000.000-00" placeholder="CPF" value="<?php echo $buscaPessoa['dados']['pessoa_cpf'] ?>" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <input type="email" class="form-control form-control-sm" name="pessoa_email" placeholder="Email" value="<?php echo $buscaPessoa['dados']['pessoa_email'] ?>">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_aniversario" data-mask="00/00" placeholder="Aniversário (dd/mm)" value="<?php echo date('d/m', strtotime($buscaPessoa['dados']['pessoa_aniversario'])) ?>" required>
                            </div>
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="pessoa_familia" id="familia">
                                    <?php
                                    $busca_familia = $familiaController->listar();
                                    if ($busca_familia['status'] == 'success') {
                                        foreach ($busca_familia['dados'] as $familia) {
                                            if ($familia['familia_id'] == $buscaPessoa['dados']['pessoa_familia']) {
                                                echo '<option value="' . $familia['familia_id'] . '" selected>' . $familia['familia_nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $familia['familia_id'] . '">' . $familia['familia_nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                    <option value="+"> + nova família</option>
                                </select>
                            </div>
                            <div class="col-md-7 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_endereco" placeholder="Endereço" value="<?php echo $buscaPessoa['dados']['pessoa_endereco'] ?>">
                            </div>
                            <div class="col-md-1 col-6">
                                <select class="form-select form-select-sm" name="pessoa_estado" id="estado" required>
                                    <option value="" selected>UF</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <select class="form-select form-select-sm" name="pessoa_municipio" id="municipio" required>
                                    <option value="" selected>Município</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_telefone_celular" data-mask="(00) 00000-0000" placeholder="Telefone Celular (xx) xxxxx-xxxx" value="<?php echo $buscaPessoa['dados']['pessoa_telefone_celular'] ?>">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_telefone_fixo" data-mask="(00) 00000-0000" placeholder="Telefone Fixo (xx) xxxxx-xxxx" value="<?php echo $buscaPessoa['dados']['pessoa_telefone_fixo'] ?>">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_instagram" placeholder="@instagram" value="<?php echo $buscaPessoa['dados']['pessoa_instagram'] ?>">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_facebook" placeholder="@facebook" value="<?php echo $buscaPessoa['dados']['pessoa_facebook'] ?>">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_data_conversao" data-mask="00/00/0000" placeholder="Data de Conversão (dd/mm/aaaa)" value="<?php echo isset($buscaPessoa['dados']['pessoa_data_conversao']) && !empty($buscaPessoa['dados']['pessoa_data_conversao']) ? date('d/m/Y', strtotime($buscaPessoa['dados']['pessoa_data_conversao'])) : ''; ?>">
                            </div>
                            <div class="col-md-2 col-12">
                                <select class="form-select form-select-sm" name="pessoa_batizada_local">
                                    <option value="1" <?php echo ($buscaPessoa['dados']['pessoa_batizada_local'] == 1) ? 'selected' : ''; ?>>Batizado na PIB</option>
                                    <option value="2" <?php echo ($buscaPessoa['dados']['pessoa_batizada_local'] == 2) ? 'selected' : ''; ?>>Batizado em outra igreja</option>
                                    <option value="3" <?php echo ($buscaPessoa['dados']['pessoa_batizada_local'] == 3) ? 'selected' : ''; ?>>Não foi batizado</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="texte" class="form-control form-control-sm" name="pessoa_data_batismo" data-mask="00/00/0000" placeholder="Data de Batismo (dd/mm/aaaa)" value="<?php echo isset($buscaPessoa['dados']['pessoa_data_conversao']) && !empty($buscaPessoa['dados']['pessoa_data_batismo']) ? date('d/m/Y', strtotime($buscaPessoa['dados']['pessoa_data_batismo'])) : ''; ?>">
                            </div>
                            <div class="col-md-4 col-12">
                                <select class="form-select form-select-sm" name="pessoa_cargo" id="cargo" required>
                                    <?php
                                    $busca_cargo = $cargoController->listar();
                                    if ($busca_cargo['status'] == 'success') {
                                        foreach ($busca_cargo['dados'] as $cargo) {
                                            if ($cargo['cargo_id'] == $buscaPessoa['dados']['pessoa_cargo']) {
                                                echo '<option value="' . $cargo['cargo_id'] . '" selected>' . $cargo['cargo_nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $cargo['cargo_id'] . '">' . $cargo['cargo_nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                    <option value="+"> + novo cargo</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <select class="form-select form-select-sm" name="pessoa_situacao" id="situacao" required>
                                    <?php
                                    $busca_situacao = $pessoaSituacaoController->listar();
                                    if ($busca_situacao['status'] == 'success') {
                                        foreach ($busca_situacao['dados'] as $situacao) {
                                            if ($situacao['situacao_id'] == $buscaPessoa['dados']['pessoa_situacao']) {
                                                echo '<option value="' . $situacao['situacao_id'] . '" selected>' . $situacao['situacao_nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $situacao['situacao_id'] . '">' . $situacao['situacao_nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                    <option value="+"> + nova situação</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="file-upload">
                                    <input type="file" id="file-input" name="foto" style="display: none;" />
                                    <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="fa-regular fa-image"></i> Escolher Foto</button>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <textarea class="form-control form-control-sm" name="pessoa_informacoes" rows="5" placeholder="Informações da pessoa"><?php echo $buscaPessoa['dados']['pessoa_informacoes'] ?></textarea>
                            </div>
                            <div class="col-md-4 col-6">
                                <button type="submit" class="btn btn-success btn-sm" name="btn_atualizar"><i class="fa-regular fa-floppy-disk"></i> Atualizar</button>
                                <button type="submit" class="btn btn-danger btn-sm" name="btn_apagar"><i class="fa-solid fa-trash-can"></i> Apagar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php' ?>

    <script>
        $(document).ready(function() {
            carregarEstados();
        });


        function carregarEstados() {
            $.getJSON('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome', function(data) {
                const selectEstado = $('#estado');
                selectEstado.empty();
                selectEstado.append('<option value="" selected>UF</option>');
                data.forEach(estado => {
                    if (estado.sigla === "<?php echo $buscaPessoa['dados']['pessoa_estado'] ?>") {
                        setTimeout(function() {
                            selectEstado.append(`<option value="${estado.sigla}" selected>${estado.sigla}</option>`).change();
                        }, 500);

                    } else {
                        setTimeout(function() {
                            selectEstado.append(`<option value="${estado.sigla}">${estado.sigla}</option>`);
                        }, 500);
                    }
                });
            });
        }

        function carregarMunicipios(estadoId) {
            $.getJSON(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios?orderBy=nome`, function(data) {
                const selectMunicipio = $('#municipio');
                selectMunicipio.empty();
                selectMunicipio.append('<option value="" selected>Município</option>');
                data.forEach(municipio => {
                    if (municipio.nome === "<?php echo $buscaPessoa['dados']['pessoa_municipio'] ?>") {
                        selectMunicipio.append(`<option value="${municipio.nome}" selected>${municipio.nome}</option>`);
                    } else {
                        selectMunicipio.append(`<option value="${municipio.nome}">${municipio.nome}</option>`);
                    }
                });
            });
        }


        $('#estado').change(function() {
            const estadoId = $(this).val();
            if (estadoId) {
                $('#municipio').empty().append('<option value="">Aguarde...</option>');
                carregarMunicipios(estadoId);
            } else {
                $('#municipio').empty().append('<option value="" selected>Município</option>');
            }
        });


        $('#familia').change(function() {
            if ($('#familia').val() == '+') {
                if (window.confirm("Você realmente deseja inserir uma nova família?")) {
                    window.location.href = "familias.php";
                }
            }
        });

        $('#btn-familias').click(function() {

            if (window.confirm("Você realmente deseja inserir uma nova família?")) {
                window.location.href = "familias.php";
            }else{
                return false;
            }

        });

        $('#cargo').change(function() {
            if ($('#cargo').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo cargo?")) {
                    window.location.href = "cargos.php";
                }
            }
        });


        $('#btn-cargos').click(function() {
            if (window.confirm("Você realmente deseja inserir um novo cargo?")) {
                window.location.href = "cargos.php";
            }else{
                return false;
            }

        });

        $('#situacao').change(function() {
            if ($('#situacao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir uma nova situação?")) {
                    window.location.href = "pessoas-situacoes.php";
                }
            }
        });

        $('#btn-situacoes').click(function() {

            if (window.confirm("Você realmente deseja inserir uma nova situação?")) {
                window.location.href = "pessoas-situacoes.php";
            }else{
                return false;
            }

        });

        $('#file-button').on('click', function() {
            $('#file-input').click();
        });

        $('#file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-button').html(fileName ? '<i class="fa-regular fa-circle-check"></i> Foto selecionada' : 'Nenhuma foto selecionada');
        });
    </script>
</body>


</html>