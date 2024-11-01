<!DOCTYPE html>

<head>
    <html lang="en">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="http://localhost:8888/igreja2/public/img/favicon.ico" />
    <link href="http://localhost:8888/igreja2/public/css/styles.css" rel="stylesheet" />
    <link href="http://localhost:8888/igreja2/public/css/custom.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/632988d903.js" crossorigin="anonymous"></script>
    <title>Igreja Digital :: Login</title>
</head>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="http://localhost:8888/igreja2/public/js/scripts.js"></script>

</body>

</html>