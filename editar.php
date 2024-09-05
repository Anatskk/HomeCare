<?php
session_start();

include_once('cadastro.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['isAdm'] !== 'nao') {
    header('Location: index.php');
    exit();
}

$idUsuario = $_SESSION['idUsuario']; // Certifique-se de que o idUsuario está sendo armazenado na sessão

// Consulta para buscar os relatórios do usuário logado
$sql = "SELECT * FROM relatorios WHERE idUsuario = ? ORDER BY data DESC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Relatórios</title>
    <link rel="stylesheet" href="css/editar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault(); // Impede o envio normal do formulário

            var formData = new FormData(this);

            $.ajax({
                url: 'editar_relatorio.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('Relatório atualizado com sucesso!');
                    // Atualize a página ou faça qualquer outra ação necessária
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Erro ao atualizar o relatório: ' + textStatus, errorThrown);
                    alert('Erro ao atualizar o relatório.');
                }
            });
        });
    });
    </script>
</head>
<body>
    <div class="comeco">
        <div class="titulo">
            <p>Editar Relatórios Anteriores</p>
        </div>
        <div class="btnAdd">
        <div class="botaoAdd">
            <a href="geralDashboard.php"><button>Voltar</button></a>
        </div>
        </div>
    </div>
    <div class="linha"></div>

    <?php
    while($userData = mysqli_fetch_assoc($result)){
        echo "<br>";
        echo "<form>";
        echo "<table class='table'>";
        echo "<tr><td><div class='descricao'>Nome do usuário:</div></td><td>".$userData['nomeUsuario']."</td></tr>";
        echo "<tr><td><div class='descricao'>Nome do idoso:</div></td><td><input type='text' name='nomeIdoso' value='".$userData['nomeIdoso']."'></td></tr>";
        echo "<tr><td><div class='descricao'>Data:</div></td><td><input type='date' name='data' value='".$userData['data']."'></td></tr>";
        echo "<tr><td><div class='descricao'>Hora:</div></td><td><input type='text' name='hora' value='".$userData['hora']."'></td></tr>";
        echo "<tr><td><div class='descricao'>Relatórios:</div></td><td><textarea name='relatorios'>".$userData['relatorios']."</textarea></td></tr>";
        echo "<tr><td><div class='descricao'>Imagem Atual:</div></td><td><img src='".$userData['imagem']."' alt='Imagem do Relatório' style='max-width: 200px;'></td></tr>";
        echo "<tr><td><div class='descricao'>Alterar Imagem:</div></td><td><input type='file' name='imagem'></td></tr>";
        echo "<tr><td colspan='2'>";
        echo "<input type='hidden' name='idRelatorio' value='".$userData['idRelatorio']."'>";
        echo "<input type='hidden' name='imagemAtual' value='".$userData['imagem']."'>";
        echo "<button type='submit' class='editar_btn'>Salvar Alterações</button>";
        echo "</td></tr>";
        echo "</table>";
        echo "<div class='linha'></div>";
        echo "</form>";
    }
    ?>
    <style>
        .editar_btn {
            cursor: pointer;
        }
        .btnAdd{
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: row;
            align-items: end;
            justify-content: end;
        }

        .botaoAdd{
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: end;
            padding-right: 30px;
            padding-top: 40px;
            cursor: pointer;
}

@media (max-width: 700px) {
    .comeco{
        padding-left: 15px;
    }

    .titulo{
        padding-left: 15px;
    }

    .linha {
        width: 30px;
    }
    
  }

  .botaoAdd button{
    width: 100px;
    height: 50px;
    background-color: #f00;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;

    }
    </style>
</body>
</html>
