<?php

include_once('cadastro.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conexao, $_POST['username']);
    $password = mysqli_real_escape_string($conexao, $_POST['password']);
    $passwordConfirmation = mysqli_real_escape_string($conexao, $_POST['passwordConfirmation']);
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);

    if ($password !== $passwordConfirmation) {
        $error = "As senhas não são iguais.";
    } else {
        // Usando placeholders e prepared statements para evitar SQL injection
        $stmt = $conexao->prepare("INSERT INTO usuarios (email, senha, isAdm, nome) VALUES (?, ?, ?, ?)");
        $isAdm = "user"; // Definindo o valor padrão para isAdm
        $stmt->bind_param("ssss", $email, $password, $isAdm, $nome);

        if ($stmt->execute()) {
            $success = "Cadastro realizado com sucesso!";
        } else {
            $error = "Erro ao realizar o cadastro: " . $stmt->error;
        }

        $stmt->close();
        $conexao->close();
    }
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
    <title>Cuidando Bem</title>
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
                      <p class="login">Cadastrar Idoso</p>
                    </center>
                  </div>
                  <form action="addIdoso.php" id="signupForm" method="post">
                    <div class="form-group first">
                      <label for="nomeIdoso">Digite o Nome do idoso</label>
                      <input type="text" class="form-control" id="nomeIdoso" name="nome" required>
                    </div>
                    <div class="form-group last mb-4">
                      <label for="username">Digite o User para login do idoso</label>
                      <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group last mb-4">
                      <label for="password">Digite a Senha do idoso</label>
                      <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group last mb-4">
                      <label for="passwordConfirmation">Confirme a senha</label>
                      <input type="password" class="form-control" id="passwordConfirmation" name="passwordConfirmation" required>
                    </div>
                    <?php if (isset($error)): ?>
                      <div class="alert alert-danger">
                        <?= htmlspecialchars($error) ?>
                      </div>
                    <?php endif; ?>
                    <?php if (isset($success)): ?>
                      <div class="alert alert-success">
                        <?= htmlspecialchars($success) ?>
                      </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-pill text-white btn-block btn-primary" name="submit">Cadastrar</button>
                    <span class="d-block text-center my-4 text-muted">Voltar para a <a href="admDashboard.php">Página ADM</a></span>
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
