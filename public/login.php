<head>
    <?php include dirname(__DIR__) . '/public/includes/header.php'; ?>
    <title><?= $appConfig['app']['app_name'] ?> :: Login</title>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="centralizada text-center">
            <img src="img/logo.png" alt="" class="img_logo" />
            <h2 class="login_title">Igreja Digital</h2>
            <h6 class="host"><?php echo $_SERVER['HTTP_HOST'] ?></h6>
            <form id="form_login" method="post" enctype="application/x-www-form-urlencoded" class="form-group">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" value="jairojeffersont@gmail.com" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" value="intell01" required>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" name="btn_login" class="btn">Entrar</button>
                </div>
            </form>
            <p class="mt-3 link">Esqueceu a senha? | <a href="#" data-toggle="modal" data-target="#cadastroModal">Faça seu cadastro</a></p>
            <p class="mt-3 copyright">2024 | JS Digital System</p>
        </div>
    </div>
    </div>
    <?php include dirname(__DIR__) . '/public/includes/footer.php' ?>
</body>

</html>