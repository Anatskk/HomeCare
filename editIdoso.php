<?php
include_once('cadastro.php');

// Verifica se o ID do usuário foi passado na URL
if(isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    // Consulta o banco de dados para obter as informações do usuário com base no ID
    $sqlSelect = "SELECT * FROM usuarios WHERE idUsuario=$idUsuario";
    $result = $conexao->query($sqlSelect);

    // Verifica se encontrou o usuário
    if($result->num_rows > 0) {
        $userData = $result->fetch_assoc();

        // Preenche as variáveis com as informações do usuário
        $email = $userData['email'];
        $password = $userData['senha'];
        $nome = $userData['nome'];
    } else {
        // Usuário não encontrado, redireciona para a página de erro ou faça algo apropriado
        header("Location: erro.php");
        exit();
    }
} else {
    // ID do usuário não foi passado na URL, redireciona para a página de erro ou faça algo apropriado
    header("Location: erro.php");
    exit();
}

// Restante do seu código HTML e formulário de edição aqui
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
                      <p class="login">Editar Usuario</p>
                    </center>
                  </div>
                  <form action="saveEdit.php" id="signupForm" method="post">
                  <input type="hidden" name="idUsuario" value="<?php echo $idUsuario ?>">
                      <div class="form-group first">
                      <label for="nomeIdoso">Digite o Nome do idoso</label>
                      <input type="text" class="form-control" id="nomeIdoso" value="<?php echo $nome ?>" name="nome" required>
                    </div>
                    <div class="form-group last mb-4">
                      <label for="username">Digite o User para login do idoso</label>
                      <input type="text" class="form-control" id="username" name="username" value="<?php echo $email ?>" required>
                    </div>
                    <div class="form-group last mb-4">
                      <label for="password">Digite a Senha do idoso</label>
                      <input type="text" class="form-control" id="password" name="password" value="<?php echo $password ?>" required>
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
                    <button type="submit" class="btn btn-pill text-white btn-block btn-primary" name="update">Salvar</button>
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
