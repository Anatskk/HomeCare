<?php
if(!empty($_GET['idRelatorio'])){
    include_once('cadastro.php');

    $idRelatorio = $_GET['idRelatorio']; // Alteração aqui

    $sqlDelete = "DELETE FROM relatorios WHERE idRelatorio=$idRelatorio"; // Alteração aqui
    $resultDelete = $conexao->query($sqlDelete);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório excluído</title>
    <link rel="stylesheet" href="css/delete.css">
</head>
<body>
    <div class="containerMain">
        <div class="sombra">
            <div class="titulo">
                <p>Relatório excluído com sucesso</p>
            </div>
            <div class="botao">
                <a href="admDashboard.php"><button>Voltar para página ADM</button></a>
            </div>
        </div>
    </div>
</body>
</html>
