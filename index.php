<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    include_once('cadastro.php');
    $username = mysqli_real_escape_string($conexao, $_POST['username']);
    $password = mysqli_real_escape_string($conexao, $_POST['password']);

    // Consultando o banco de dados para verificar se o usuário existe
    $query = "SELECT * FROM usuarios WHERE email = '$username' AND senha = '$password'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Definir o idUsuario na sessão após o login bem-sucedido
        $_SESSION['idUsuario'] = $user['idUsuario'];

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['email'];
        $_SESSION['isAdm'] = $user['isAdm'];

        // Redirecionar com base no valor de isAdm
        if ($user['isAdm'] === 'sim') {
            header('Location: admDashboard.php');
        } elseif ($user['isAdm'] === 'nao') {
            header('Location: geralDashboard.php');
        } elseif ($user['isAdm'] === 'user') {
            header('Location: userDashboard.php');
        } else {
            // Se o valor de isAdm não for esperado, redirecionar para uma página de erro ou logout
            header('Location: error.php');
        }

        exit();
    } else {
        $error = "Usuário não encontrado ou senha incorreta";
    }

    mysqli_close($conexao);
}
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Login - Cuidando Bem</title>
</head>
<body>
  <div class="content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 contents">
          <div class="row justify-content-center">
            <div class="col-md-12">
              <div id="form" class="form-block">
                <div class="mb-4">
                  <center>
                    <p class="login">Login</p>
                  </center>
                </div>
                <form action="index.php" id="loginForm" method="post">
                  <div class="form-group first">
                    <label for="username">Digite seu Email</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                  </div>
                  <div class="form-group last mb-4">
                    <label for="password">Digite sua Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                      <?= htmlspecialchars($error) ?>
                    </div>
                  <?php endif; ?>
                  <button type="submit" class="btn btn-pill text-white btn-block btn-primary" name="submit">Entrar</button>
                  <span class="d-block text-center my-4 text-muted">Ainda não tem uma conta? <a href="cadastre.php">Cadastre-se</a></span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
