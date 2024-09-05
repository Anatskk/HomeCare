<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['isAdm'] !== 'user') {
    header('Location: index.php');
    exit();
}

include_once('cadastro.php');

// Consulta SQL para obter os relatórios enviados
$sql = "SELECT * FROM relatorios";
$result = $conexao->query($sql);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/user.css">
  <title>Cuidando Bem</title>
  <style>
    .relatorio {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 20px;
    }
    .relatorio p {
      margin: 5px 0;
    }
    .relatorio img {
      max-width: 200px;
      height: auto;
    }
    .containerLinha{
    width: 100%;
    height: auto;
    display: flex;
    flex-direction: row;
    align-items: start;
    justify-content: center;
  }

  .linha {
    width: 100%;
    height: auto;
    border-bottom: 1px solid ;
    border-color: #b3b3b3;
    padding: 10px 20px 0px 20px;
}

  </style>
</head>
<body>
  <div class="content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 contents">
          <div class="comeco">
            <p>Bem Vindo ao Site HomeCare</p>
            <div class="containerLinha">
            <div class="linha"></div>
            </div>
          </div>
          <div class="conteudo">
            <div class="titulo">
              <p>Veja aqui os relatórios adicionados recentes:</p>
            </div>
            <div class="relatorios">
              <?php
              if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      echo "<div class='relatorio'>";
                      echo "<p>Cuidadora responsável: " . $row['nomeUsuario'] . "</p>";
                      echo "<p>Data: ".$row['data']."</p>";
                      echo "<p>Relatório: " . $row['relatorios'] . "</p>";
                      if (!empty($row['imagem'])) {
                          echo "<p>Imagem:</p><img src='".$row['imagem']."' alt='Imagem do Relatório'></td></tr>";
                      }
                      echo "</div>";
                  }
              } else {
                  echo "Nenhum relatório encontrado.";
              }
              ?>
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
