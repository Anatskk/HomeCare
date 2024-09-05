<?php
session_start();

include_once('cadastro.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['isAdm'] !== 'sim') {
    header('Location: index.php');
    exit();
}

// Verifica se existe um parâmetro de pesquisa
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $sql = "SELECT * FROM relatorios WHERE nomeUsuario LIKE '%$search%' OR nomeIdoso LIKE '%$search%' OR data LIKE '%$search%' ORDER BY data DESC";
} else {
    $sql = "SELECT * FROM relatorios ORDER BY data DESC";
}

$result = $conexao->query($sql);
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/admDashboard.css">
  <title>Admin Cuidando Bem</title>
  <style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
    }
    .selectNome {
    display: flex;
    align-items: center;
}

.selectNome select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 10px;
}

.selectNome .enviarBtn {
    width: 100px;
    height: 40px;
    background-color: #2271B3;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 5px;
    font-size: 18px;
}

/* Adicione este CSS ao seu arquivo CSS */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.relatoriosTodos {
    padding: 20px;
}

.relatorios {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.table {
    width: 100%;
    max-width: 600px;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.table .descricao {
    font-weight: bold;
}

.imagemRelatorio {
    max-width: 100%;
    height: auto;
    display: block;
}

.formContainer {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.formDelete,
.formEnviar {
    display: flex;
    justify-content: space-between;
}

.selectNome select {
    width: 100%;
    padding: 8px;
    margin-top: 10px;
}

.excluir_btn,
.enviarBtn {
    padding: 10px 20px;
    border: none;
    color: #fff;
    background-color: #007bff;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.excluir_btn:hover,
.enviarBtn:hover {
    background-color: #0056b3;
}

@media (max-width: 768px) {
    .table {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .formContainer {
        flex-direction: column;
        gap: 10px;
    }

    .formDelete,
    .formEnviar {
        flex-direction: column;
        gap: 10px;
    }

    .selectNome select {
        margin-top: 0;
    }
}

    .baixo{
      width: 100%;
     height: auto;
     display: flex;
      gap:10px;

      }
    .comeco {
        background-color: #f4f4f9;
        color: #333;
        text-align: center;
    }
    .tituloPag p {
        font-weight: 700;
        padding-left:70px;
    }
    .btnCima {
        display: flex;
        justify-content: flex-end;
        padding: 10px;
    }
    .btnCima a button {
        margin: 0 10px;
        padding: 10px 20px;
        width: 120px;
        height: 50px;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .btnCima a button:nth-child(1) {
        background-color: #2271B3;
    }
    .btnCima a button:nth-child(2) {
        background-color: rgb(218, 161, 167);
    }
    .mainConteudo {
        padding: 20px;
    }
    .filtroCerto {
        display: flex;
        align-items: center;
        padding-bottom: 20px;
        padding-left: 50px;

    }
    .filtroCerto input {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 10px;
    }
    .filtroCerto button {
        padding: 10px;
        background-color: rgb(218, 161, 167);
        border: none;
        border-radius: 5px;
        cursor: pointer;
        color: white;
    }
    .relatoriosTodos {
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    .table {
        width: 100%;
        max-width: 1000px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .table th, .table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }
    .table th {
        background-color: #f8f8f8;
    }
    .table tr:hover {
        background-color: #f1f1f1;
    }
    .table img {
        max-width: 200px;
        border-radius: 5px;
    }
    .excluir_btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .enviarBtn{
      width: 100px;
      height: 40px;
      background-color: #2271B3;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 5px;
        font-size: 18px;

    }

    .botaoAdd button{
      background-color: rgb(218, 161, 167) !important;
    }
  </style>
</head>
<body>
    <div class="comeco">
      <div class="tituloPag">
        <p>Página ADM</p>
      </div>
      <div class="btnCima">
        <div class="btnEditar">
          <a href="listaIdoso.php"><button>Editar/Ver Perfil</button></a>
        </div>
        <div class="botaoAdd">
          <a href="addIdoso.php"><button>Adicionar Idoso</button></a>
        </div>
      </div>
    </div>
    <div class="mainConteudo">
      <div class="filtro">
        <div class="filtroCerto">
          <input type="search" class="formControl" placeholder="Pesquisar" id="pesquisar">
          <button onClick="searchData()" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>
          </button>
        </div>
      </div>
      <div class="relatoriosTodos">
    <div class="relatorios">
    <?php
        while($userData = mysqli_fetch_assoc($result)){
            echo "<table class='table'>";
            echo "<tr><td><div class='descricao'>Nome do usuário:</div></td><td>".$userData['nomeUsuario']."</td></tr>";
            echo "<tr><td><div class='descricao'>Nome do idoso:</div></td><td>".$userData['nomeIdoso']."</td></tr>";
            echo "<tr><td><div class='descricao'>Data:</div></td><td>".$userData['data']."</td></tr>";
            echo "<tr><td><div class='descricao'>Hora:</div></td><td>".$userData['hora']."</td></tr>";
            echo "<tr><td><div class='descricao'>Relatórios:</div></td><td>".$userData['relatorios']."</td></tr>";
            echo "<tr><td><div class='descricao'>#</div></td><td class='idRelatorio'>".$userData['idRelatorio']."</td></tr>";
            if (!empty($userData['imagem'])) {
                echo "<tr><td><div class='descricao'>Imagem:</div></td><td><img src='".$userData['imagem']."' alt='Imagem do Relatório' class='imagemRelatorio'></td></tr>";
            }
            echo "<tr><td colspan='2'>";
            echo "<div class='formContainer'>";
            echo "<form action='delete.php' method='GET' class='formDelete'>";
            echo "<input type='hidden' name='idRelatorio' value='".$userData['idRelatorio']."'>";
            echo "<button type='submit' class='excluir_btn'>Excluir</button>";
            echo "</form>";

            echo "<form action='enviar.php' method='POST' class='formEnviar'>";
            echo "<input type='hidden' name='idRelatorio' value='".$userData['idRelatorio']."'>";
            echo "<div class='selectNome'><select name='idUsuario'></div>";
            $usersQuery = "SELECT idUsuario, nome FROM usuarios WHERE isAdm = 'user'";
            $usersResult = $conexao->query($usersQuery);
            while ($user = mysqli_fetch_assoc($usersResult)) {
                echo "<option value='".$user['idUsuario']."'>".$user['nome']."</option>";
            }
            echo "</select>";
            echo "<button type='submit' class='enviarBtn'>Enviar</button>";
            echo "</form></div>";

            echo "</td></tr>";
            echo "</table>";
            echo "<div class='containerLinha'><div class='linha'></div></div>";
        }
    ?>
    </div>
</div>

    </div>
</body>
</html>

<script>
  var search = document.getElementById('pesquisar');

  search.addEventListener("keydown", function(event){
    if (event.key === "Enter"){
      searchData();
    }
  });

  function searchData(){
    window.location = 'admDashboard.php?search=' + search.value;
  }
</script>
