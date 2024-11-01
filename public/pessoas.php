<?php

require_once dirname(__DIR__) . '/src/controllers/PessoaController.php';
$pessoaController = new PessoaController();

require_once dirname(__DIR__) . '/src/controllers/CargoController.php';
$cargoController = new CargoController();

require_once dirname(__DIR__) . '/src/controllers/PessoaSituacaoController.php';
$pessoaSituacaoController = new PessoaSituacaoController();

require_once dirname(__DIR__) . '/src/controllers/FamiliaController.php';
$familiaController = new FamiliaController();

$ordernar_por = $_GET['ordernar_por'] ?? 'pessoa_nome';
$ordem = $_GET['ordem'] ?? 'asc';
$termo = $_GET['termo'] ?? null;
$filtro = $_GET['filtro'] ?? null;

$paginaGet = $_GET['pagina'] ?? 1;
$limiteGet = $_GET['limite'] ?? 10;

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

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
                    <div class="card-header bg-primary text-white px-2 py-1 card-background">Pessoas</div>
                    <div class="card-body p-2">
                        <p class="card-text mb-2">Gerencie as pessoas vinculadas à igreja.</p>
                        <p class="card-text mb-2">
                            Por favor, preencha os campos obrigatórios:
                        <ul class="mb-1">
                            <li><b>Nome:</b> Nome completo da pessoa.</li>
                            <li><b>CPF:</b> CPF da pessoa (formato: 123.456.789-09).</li>
                            <li><b>E-mail:</b> Endereço de e-mail atualizado.</li>
                            <li><b>Data de Aniversário:</b> Data de nascimento (formato: dd/mm).</li>
                            <li><b>Município:</b> Município de residência.</li>
                            <li><b>Estado:</b> Sigla do estado (exemplo: SP, RJ).</li>
                            <li><b>Foto:</b> Foto recente da pessoa, preferencialmente em formato JPG ou PNG, que deve apresentar o rosto de forma clara e legível.</li>
                        </ul>
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
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {

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
                                'foto' => $_FILES['foto'] ?? null,
                            ];

                            $result = $pessoaController->criar($dados);

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            } else {
                                echo '<div class="alert alert-danger mb-2 py-1 px-2 custom_alert" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                        ?>

                        <form class="row g-2 form_custom" method="POST" enctype="multipart/form-data">
                            <div class="col-md-4 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_nome" placeholder="Nome" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_cpf" data-mask="000.000.000-00" placeholder="CPF" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <input type="email" class="form-control form-control-sm" name="pessoa_email" placeholder="Email">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_aniversario" data-mask="00/00" placeholder="Aniversário (dd/mm)" required>
                            </div>
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="pessoa_familia" id="familia">
                                    <?php
                                    $busca_familia = $familiaController->listar();
                                    if ($busca_familia['status'] == 'success') {
                                        foreach ($busca_familia['dados'] as $familia) {
                                            if ($familia['familia_id'] == 1) {
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
                                <input type="text" class="form-control form-control-sm" name="pessoa_endereco" placeholder="Endereço">
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
                                <input type="text" class="form-control form-control-sm" name="pessoa_telefone_celular" data-mask="(00) 00000-0000" placeholder="Telefone Celular (xx) xxxxx-xxxx">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_telefone_fixo" data-mask="(00) 00000-0000" placeholder="Telefone Fixo (xx) xxxxx-xxxx">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_instagram" placeholder="@instagram">
                            </div>
                            <div class="col-md-2 col-6">
                                <input type="text" class="form-control form-control-sm" name="pessoa_facebook" placeholder="@facebook">
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="text" class="form-control form-control-sm" name="pessoa_data_conversao" data-mask="00/00/0000" placeholder="Data de Conversão (dd/mm/aaaa)">
                            </div>
                            <div class="col-md-2 col-12">
                                <select class="form-select form-select-sm" name="pessoa_batizada_local">
                                    <option value="1">Batizado na PIB</option>
                                    <option value="2">Batizado em outra igreja</option>
                                    <option value="3" selected>Não foi batizado</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-12">
                                <input type="texte" class="form-control form-control-sm" name="pessoa_data_batismo" data-mask="00/00/0000" placeholder="Data de Batismo (dd/mm/aaaa)">
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
                            <div class="col-md-4 col-12">
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
                            <div class="col-md-2 col-12">
                                <div class="file-upload">
                                    <input type="file" id="file-input" name="foto" style="display: none;" />
                                    <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="fa-regular fa-image"></i> Escolher Foto</button>
                                </div>
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
                <div class="card shadow-sm mb-2">
                    <div class="card-body p-2">
                        <form class="row g-2 form_custom" method="GET" enctype="application/x-www-form-urlencoded">
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="ordernar_por">
                                    <option value="pessoa_nome" <?php echo ($ordernar_por == 'orgao_nome') ? 'selected' : ''; ?>>Nome</option>
                                    <option value="pessoa_adicionada_em" <?php echo ($ordernar_por == 'pessoa_adicionada_em') ? 'selected' : ''; ?>>Data de criação</option>
                                    <option value="pessoa_atualizada_em" <?php echo ($ordernar_por == 'pessoa_atualizada_em') ? 'selected' : ''; ?>>Última atualização</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="ordem">
                                    <option value="ASC" <?php echo ($ordem == 'ASC') ? 'selected' : ''; ?>>Crescente</option>
                                    <option value="DESC" <?php echo ($ordem == 'DESC') ? 'selected' : ''; ?>>Decrescente</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-6">
                                <select class="form-select form-select-sm" name="limite">
                                    <option value="5" <?php echo ($limiteGet == 5) ? 'selected' : ''; ?>>5 itens</option>
                                    <option value="10" <?php echo ($limiteGet == 10) ? 'selected' : ''; ?>>10 itens</option>
                                    <option value="50" <?php echo ($limiteGet == 50) ? 'selected' : ''; ?>>50 itens</option>
                                    <option value="100" <?php echo ($limiteGet == 100) ? 'selected' : ''; ?>>100 itens</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-6">
                                <select class="form-select form-select-sm" name="filtro" required>
                                    <option value="0">Todos</option>
                                    <?php
                                    $busca_situacao = $pessoaSituacaoController->listar();
                                    if ($busca_situacao['status'] == 'success') {
                                        foreach ($busca_situacao['dados'] as $situacao) {
                                            if ($situacao['situacao_id'] == $filtro) {
                                                echo '<option value="' . $situacao['situacao_id'] . '" selected>' . $situacao['situacao_nome'] . '</option>';
                                            } else {
                                                echo '<option value="' . $situacao['situacao_id'] . '">' . $situacao['situacao_nome'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-10">
                                <input type="text" class="form-control form-control-sm" name="termo" placeholder="Buscar..." value="<?php echo $termo; ?>">
                            </div>
                            <div class="col-md-3 col-2">
                                <button type="submit" class="btn btn-success btn-sm" name="btn_buscar" onclick="this.name='';"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-2">
                        <div class="table-responsive mb-2">
                            <table class="table table-striped table-hover table-bordered mb-0 custom_table">
                                <thead>
                                    <tr>
                                        <td style="white-space: nowrap;">Cargo</td>
                                        <td style="white-space: nowrap;">Email</td>
                                        <td style="white-space: nowrap;">Aniversário</td>
                                        <td style="white-space: nowrap;">Situação</td>
                                        <td style="white-space: nowrap;">Cargo</td>
                                        <td style="white-space: nowrap;">Família</td>
                                        <td style="white-space: nowrap;">Criada em</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $busca = $pessoaController->listar($termo, $ordernar_por,  $ordem, $filtro, $paginaGet, $limiteGet);

                                    //print_r($busca);

                                    if ($busca['status'] == 'success') {
                                        foreach ($busca['dados'] as $pessoa) {
                                            echo '<tr>';
                                            echo '<td style="white-space: nowrap; "><a href="editar-pessoa.php?pessoa=' . $pessoa['pessoa_id'] . '">' . $pessoa['pessoa_nome'] . '</a></td>';
                                            echo '<td style="white-space: nowrap; ">' . $pessoa['pessoa_email'] . '</td>';
                                            echo '<td style="white-space: nowrap; ">' . date('d/m', strtotime($pessoa['pessoa_aniversario'])) . '</td>';
                                            echo '<td style="white-space: nowrap; ">' . $pessoa['situacao_nome'] . '</td>';
                                            echo '<td style="white-space: nowrap; ">' . $pessoa['cargo_nome'] . '</td>';
                                            echo '<td style="white-space: nowrap; ">' . $pessoa['familia_nome'] . '</td>';
                                            echo '<td style="white-space: nowrap; ">' . date('d/m/Y | H:i', strtotime($pessoa['pessoa_adicionada_em'])) . '</td>';
                                            echo '</tr>';
                                        }
                                    } else if ($busca['status'] == 'empty') {
                                        echo '<tr><td colspan="7">' . $busca['message'] . '</td></tr>';
                                    } else if ($busca['status'] == 'error') {
                                        echo '<tr><td colspan="7">' . $busca['message'] . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination custom-pagination d-flex flex-wrap mb-0">
                                <?php if ($busca['total_paginas'] > 1) : ?>
                                    <li class="page-item <?= $paginaGet == 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?pagina=1&ordernar_por=<?= $ordernar_por ?>&ordem=<?= $ordem ?>&termo=<?= $termo ?>&filtro=<?= $filtro ?>&limite=<?= $limiteGet ?>" aria-label="Primeira">
                                            <span aria-hidden="true">Primeira</span>
                                        </a>
                                    </li>
                                    <li class="page-item <?= $paginaGet == 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $paginaGet - 1 ?>&ordernar_por=<?= $ordernar_por ?>&ordem=<?= $ordem ?>&termo=<?= $termo ?>&filtro=<?= $filtro ?>&limite=<?= $limiteGet ?>" aria-label="Anterior">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>

                                    <?php for ($i = 1; $i <= $busca['total_paginas']; $i++) : ?>
                                        <li class="page-item <?= $i == $paginaGet ? 'active' : '' ?>">
                                            <a class="page-link" href="?pagina=<?= $i ?>&ordernar_por=<?= $ordernar_por ?>&ordem=<?= $ordem ?>&termo=<?= $termo ?>&filtro=<?= $filtro ?>&limite=<?= $limiteGet ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <li class="page-item <?= $paginaGet == $busca['total_paginas'] ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $paginaGet + 1 ?>&ordernar_por=<?= $ordernar_por ?>&ordem=<?= $ordem ?>&termo=<?= $termo ?>&filtro=<?= $filtro ?>&limite=<?= $limiteGet ?>" aria-label="Próxima">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item <?= $paginaGet == $busca['total_paginas'] ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $busca['total_paginas'] ?>&ordernar_por=<?= $ordernar_por ?>&ordem=<?= $ordem ?>&termo=<?= $termo ?>&filtro=<?= $filtro ?>&limite=<?= $limiteGet ?>" aria-label="Última">
                                            <span aria-hidden="true">Última</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
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

        $('#btn-familias').click(function() {

            if (window.confirm("Você realmente deseja inserir uma nova família?")) {
                window.location.href = "familias.php";
            } else {
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
            } else {
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
            } else {
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