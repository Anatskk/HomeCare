<?php
session_start();
include_once('cadastro.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['isAdm'] !== 'sim') {
    header('Location: index.php');
    exit();
}

$message = "";

if (isset($_POST['idRelatorio']) && isset($_POST['idUsuario'])) {
    $idRelatorio = $_POST['idRelatorio'];
    $idUsuario = $_POST['idUsuario'];

    $checkQuery = "SELECT * FROM permissoes_relatorios WHERE idRelatorio = ? AND idUsuario = ?";
    $stmt = $conexao->prepare($checkQuery);
    $stmt->bind_param("ii", $idRelatorio, $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $insertQuery = "INSERT INTO permissoes_relatorios (idRelatorio, idUsuario) VALUES (?, ?)";
        $stmt = $conexao->prepare($insertQuery);
        $stmt->bind_param("ii", $idRelatorio, $idUsuario);
        if ($stmt->execute()) {
            $novoId = $conexao->insert_id;
            $message = "Permissão concedida com sucesso. Novo ID: " . $novoId;
        } else {
            $message = "Erro ao conceder permissão.";
        }
    } else {
        $message = "A permissão já existe.";
    }
} else {
    $message = "Dados inválidos.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conceder Permissão</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .message {
            font-size: 24px; /* Aumentei o tamanho da fonte */
            margin-bottom: 20px;
        }
        .back-button {
            font-size: 20px; /* Aumentei o tamanho da fonte */
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message"><?php echo $message; ?></div>
        <a href="admDashboard.php" class="back-button">Voltar</a>
    </div>
</body>
</html>
