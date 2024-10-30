<?php

require_once dirname(__DIR__) . '/src/controllers/PessoaController.php';
$pessoaController = new PessoaController();

require_once dirname(__DIR__) . '/src/controllers/CargoController.php';
$cargoController = new CargoController();

require_once dirname(__DIR__) . '/src/controllers/PessoaSituacaoController.php';
$pessoaSituacaoController = new PessoaSituacaoController();

require_once dirname(__DIR__) . '/src/controllers/FamiliaController.php';
$familiaController = new FamiliaController();

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
                    </div>
                </div>
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

                            $dados = [
                                'pessoa_nome' => !empty($_POST['pessoa_nome']) ? htmlspecialchars($_POST['pessoa_nome'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_cpf' => !empty($_POST['pessoa_cpf']) ? htmlspecialchars($_POST['pessoa_cpf'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_email' => !empty($_POST['pessoa_email']) ? htmlspecialchars($_POST['pessoa_email'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_aniversario' => !empty($_POST['pessoa_aniversario']) ? htmlspecialchars($_POST['pessoa_aniversario'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_familia' => !empty($_POST['pessoa_familia']) ? htmlspecialchars($_POST['pessoa_familia'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_endereco' => !empty($_POST['pessoa_endereco']) ? htmlspecialchars($_POST['pessoa_endereco'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_municipio' => !empty($_POST['pessoa_municipio']) ? htmlspecialchars($_POST['pessoa_municipio'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_estado' => !empty($_POST['pessoa_estado']) ? htmlspecialchars($_POST['pessoa_estado'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_telefone_celular' => !empty($_POST['pessoa_telefone_celular']) ? htmlspecialchars($_POST['pessoa_telefone_celular'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_telefone_fixo' => !empty($_POST['pessoa_telefone_fixo']) ? htmlspecialchars($_POST['pessoa_telefone_fixo'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_instagram' => !empty($_POST['pessoa_instagram']) ? htmlspecialchars($_POST['pessoa_instagram'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_facebook' => !empty($_POST['pessoa_facebook']) ? htmlspecialchars($_POST['pessoa_facebook'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_data_conversao' => !empty($_POST['pessoa_data_conversao']) ? htmlspecialchars($_POST['pessoa_data_conversao'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_data_batismo' => !empty($_POST['pessoa_data_batismo']) ? htmlspecialchars($_POST['pessoa_data_batismo'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_batizada_local' => !empty($_POST['pessoa_batizada_local']) ? htmlspecialchars($_POST['pessoa_batizada_local'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_cargo' => !empty($_POST['pessoa_cargo']) ? htmlspecialchars($_POST['pessoa_cargo'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_situacao' => !empty($_POST['pessoa_situacao']) ? htmlspecialchars($_POST['pessoa_situacao'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_informacoes' => !empty($_POST['pessoa_informacoes']) ? htmlspecialchars($_POST['pessoa_informacoes'], ENT_QUOTES, 'UTF-8') : null,
                                'pessoa_foto' => !empty($_POST['pessoa_foto']) ? htmlspecialchars($_POST['pessoa_foto'], ENT_QUOTES, 'UTF-8') : null,
                            ];


                            $result = $pessoaController->criar($dados);

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-4 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_nome" placeholder="Nome" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_cpf" data-mask="000.000.000-00" placeholder="CPF" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <input type="email" class="form-control form-control-sm" name="pessoa_email" placeholder="Email">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_aniversario" data-mask="00/00" placeholder="Aniversário (dd/mm)" required>
                            </div>
                            <div class="col-md-2 col-12">
                                <select class="form-select form-select-sm" name="pessoa_familia" id="familia">
                                    <?php
                                    $busca_familia = $familiaController->listar();
                                    if ($busca_familia['status'] == 'success') {
                                        foreach ($busca_familia['dados'] as $familia) {
                                            if ($cargo['familia_id'] == 1) {
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
                            <div class="col-md-7 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_endereco" placeholder="Endereço">
                            </div>
                            <div class="col-md-1 col-12">
                                <select class="form-select form-select-sm" name="pessoa_estado" id="estado" required>
                                    <option value="" selected>UF</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <select class="form-select form-select-sm" name="pessoa_municipio" id="municipio" required>
                                    <option value="" selected>Município</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_telefone_celular" data-mask="(00) 00000-0000" placeholder="Telefone Celular (xx) xxxxx-xxxx">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_telefone_fixo" data-mask="(00) 00000-0000" placeholder="Telefone Fixo (xx) xxxxx-xxxx">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_instagram" placeholder="@instagram">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_facebook" placeholder="@facebook">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_data_conversao" data-mask="00/00/0000" placeholder="Data de Conversão (dd/mm/aaaa)">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="texte" class="form-control form-control-sm" name="pessoa_data_batismo" data-mask="00/00/0000" placeholder="Data de Batismo (dd/mm/aaaa)">
                            </div>
                            <div class="col-md-2 col-12">
                                <select class="form-select form-select-sm" name="pessoa_batizada_local">
                                    <option value="1">Batizado na PIB</option>
                                    <option value="0">Batizado em outra igreja</option>
                                    <option value="3" selected>Não foi batizado</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-12">
                                <select class="form-select form-select-sm" name="pessoa_cargo" id="cargo" required>
                                    <?php
                                    $busca_cargo = $cargoController->listar();
                                    if ($busca_cargo['status'] == 'success') {
                                        foreach ($busca_cargo['dados'] as $cargo) {
                                            if ($cargo['cargo_id'] == 1) {
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
                            <div class="col-md-6 col-12">
                                <select class="form-select form-select-sm" name="pessoa_situacao" id="situacao" required>
                                    <?php
                                    $busca_situacao = $pessoaSituacaoController->listar();
                                    if ($busca_situacao['status'] == 'success') {
                                        foreach ($busca_situacao['dados'] as $situacao) {
                                            if ($situacao['situacao_id'] == 1) {
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
                            <div class="col-md-12 col-12">
                                <textarea class="form-control form-control-sm" name="pessoa_informacoes" rows="5" placeholder="Informações da pessoa"></textarea>
                            </div>
                            <div class="col-md-4 col-6">
                                <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="fa-regular fa-floppy-disk"></i> Salvar</button>
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
            carregarEstados();
            setTimeout(function() {
                $(".alert").fadeOut("slow");
            }, 2000);
        });


        function carregarEstados() {
            $.getJSON('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome', function(data) {
                const selectEstado = $('#estado');
                selectEstado.empty();
                selectEstado.append('<option value="" selected>UF</option>');
                data.forEach(estado => {
                    selectEstado.append(`<option value="${estado.sigla}">${estado.sigla}</option>`);
                });
            });
        }

        function carregarMunicipios(estadoId) {
            $.getJSON(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios?orderBy=nome`, function(data) {
                const selectMunicipio = $('#municipio');
                selectMunicipio.empty();
                selectMunicipio.append('<option value="" selected>Município</option>');
                data.forEach(municipio => {
                    selectMunicipio.append(`<option value="${municipio.nome}">${municipio.nome}</option>`);
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

        $('#cargo').change(function() {
            if ($('#cargo').val() == '+') {
                if (window.confirm("Você realmente deseja inserir um novo cargo?")) {
                    window.location.href = "cargos.php";
                }
            }
        });

        $('#situacao').change(function() {
            if ($('#situacao').val() == '+') {
                if (window.confirm("Você realmente deseja inserir uma nova situação?")) {
                    window.location.href = "pessoas-situacoes.php";
                }
            }
        });
    </script>
</body>


</html>