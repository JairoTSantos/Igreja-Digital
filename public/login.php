<?php
$pageTitle = 'Login';
include dirname(__DIR__) . '/public/includes/header.php'; // Inclua o header apenas uma vez
?>



<body class="login-screen">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="centralizada text-center">
            <img src="img/logo.png" alt="" class="img_logo" />
            <h2 class="login_title">Igreja Digital</h2>
            <h6 class="host">Sistema de gestão integrada para igrejas</h6>
            <form id="form_login" method="post" enctype="application/x-www-form-urlencoded" class="form-group">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="E-mail | Celular" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" required>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" name="btn_login" class="btn"><i class="fa-solid fa-door-open"></i> Entrar</button>
                </div>
            </form>
            <p class="mt-3 link">Esqueceu a senha?</p>
            <p class="mt-3 copyright">2024 | JS Digital System</p>
        </div>
    </div>
    <?php include dirname(__DIR__) . '/public/includes/footer.php'; ?>
</body>

</html>