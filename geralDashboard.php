<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['isAdm'] !== 'nao') {
    header('Location: index.php');
    exit();
}

include_once('cadastro.php'); // Certifique-se de que este arquivo contém a conexão com o banco de dados

$query = "SELECT nome FROM usuarios WHERE isAdm = 'user'";
$result = mysqli_query($conexao, $query);

if ($result) {
    $idosos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $idosos[] = $row['nome'];
    }
} else {
    $error = "Erro ao buscar os nomes dos idosos: " . mysqli_error($conexao);
}

if (isset($_POST['submit'])) {
    $idUsuario = $_SESSION['idUsuario']; // Obtém o id do usuário logado

    $relatorios = mysqli_real_escape_string($conexao, $_POST['relatorio']);
    $nomeCuidadora = mysqli_real_escape_string($conexao, $_POST['nomeCuidadora']);
    $nomeIdoso = mysqli_real_escape_string($conexao, $_POST['nomeIdoso']);
    $data = mysqli_real_escape_string($conexao, $_POST['data']);
    $hora = mysqli_real_escape_string($conexao, $_POST['hora']);

    // Inicialização do caminho da imagem
    $image_path = null;

    // Verifica se um arquivo de imagem foi enviado
    if (!empty($_FILES['imagem']['name'])) {
        $target_dir = "uploads/"; // Diretório onde as imagens serão armazenadas

        // Verifica se o diretório existe, se não, cria o diretório
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["imagem"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o arquivo é uma imagem real
        $check = getimagesize($_FILES["imagem"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "Arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verifica se o arquivo já existe
        if (file_exists($target_file)) {
            $error = "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo
        if ($_FILES["imagem"]["size"] > 500000) { // Tamanho máximo de 500KB
            $error = "Desculpe, o arquivo é muito grande.";
            $uploadOk = 0;
        }

        // Permitir certos formatos de arquivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

        // Verifica se $uploadOk está definido como 0 por algum erro
        if ($uploadOk == 0) {
            $error = "Desculpe, seu arquivo não foi enviado.";
        } else {
            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
                // Arquivo enviado com sucesso
                $image_path = $target_file;
            } else {
                $error = "Desculpe, houve um erro ao fazer o upload da sua imagem.";
            }
        }
    }

    if (empty($relatorios) || empty($nomeCuidadora) || empty($nomeIdoso) || empty($data) || empty($hora)) {
        $error = "Preencha tudo antes de mandar!";
    } else {
        if ($image_path) {
            $stmt = $conexao->prepare("INSERT INTO relatorios (relatorios, nomeUsuario, nomeIdoso, data, hora, imagem, idUsuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", $relatorios, $nomeCuidadora, $nomeIdoso, $data, $hora, $image_path, $idUsuario);
        } else {
            $stmt = $conexao->prepare("INSERT INTO relatorios (relatorios, nomeUsuario, nomeIdoso, data, hora, idUsuario) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $relatorios, $nomeCuidadora, $nomeIdoso, $data, $hora, $idUsuario);
        }

        if ($stmt->execute()) {
            $success = "Relatório enviado com sucesso!";
        } else {
            $error = "Erro ao realizar o cadastro: " . $stmt->error;
        }

        $stmt->close();
        $conexao->close();
    }
    
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <title>Cuidando Bem</title>
    <link rel="stylesheet" href="css/relatorio.css">
    <style>
        .alert {
            padding: 10px;
            margin: 20px auto;
            width: 50%;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
        }
        
        .btnEnviar{
            font-size: 18px;
        }
    </style>
</head>
<body>
    <main>
        <div class="containerMain"> 
            <div class="relatorios">
                <center>
                    <div class="containerTlttle">
                        <p>Relatório Diário de Plantão</p>
                    </div>
                </center>
                <form action="geralDashboard.php" id="signupForm" method="post" enctype="multipart/form-data"> <!-- Verifique se o caminho aqui está correto -->
                    <div class="conteinerConteudo">
                        <div class="cuidadora">
                            <p>Cuidadora responsável:</p>
                            <input type="text" name="nomeCuidadora">
                        </div>
                        <div class="idoso">
                            <p>Idoso atendido:</p>
                            
                                <select name="nomeIdoso" id="pet-select">
                                    <option value="">--Selecione o nome--</option>
                                    <?php foreach ($idosos as $idoso): ?>
                                        <option value="<?= $idoso ?>"><?= $idoso ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <div class="doisJunto">
                            <div class="data">
                                <p>Data do atendimento:</p>
                                <input type="date" name="data">
                            </div>
                            <div class="cargaHoraria">
                                <p>Carga horária atendida:</p>
                                <input type="text" name="hora">
                            </div>
                        </div>
                        <div class="relatorioTodo">
                            <p>Relatório:</p>
                            <textarea name="relatorio"></textarea>
                        </div>
                        <div class="imagem">
                            <p>Imagem (opcional):</p>
                            <input type="file" name="imagem">
                            <style>
                                .imagem{
                                    width: 100%;
                                    height: auto;
                                    display: flex;
                                    flex-direction: row;
                                    padding-bottom: 20px;
                                    align-items: center;
                                    justify-content:start ;
                                    gap: 5px;
                                }
                            </style>
                        </div>
                        <div class="botao">
                            <div class="buttonEnviar">
                        <button type="submit" name="submit" class="btnEnviar">Enviar Relatório</button>
                        </div>
                        <div class="buttonEditar">
                        <a href="editar.php" class="btnEditar"><p>Editar Relatórios</p></a>
                        </div>
                        </div>
                        <style>
                            .botao{
                                width: 100%;
                                height: auto;
                                display: flex;
                                flex-direction: row;
                                align-items: center;
                                justify-content: center;
                                gap: 5px;
                            }

                            .btnEditar{
                                width: 170px;
                                height: 40px;
                                background-color: #2271B3;
                                border: none;
                                border-radius: 5px;
                                cursor: pointer;
                                display: flex;
                                flex-direction: row;
                                align-items: center;
                                justify-content: center; 
                            }

                            .btnEditar p{
                                width: 100%;
                                height: auto;
                                font-size: 18px;
                                color: #fff;
                                display: flex;
                                align-items: center;
                                justify-content: center; 
                                padding-left: 5px;
                                
                            }

                            .btnEditar:hover{
                                background-color: #003399;
                            }

                            .relatorios {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                padding: 35px 80px;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.6), 0 6px 20px rgba(0, 0, 0, 0.5);
                                border-radius: 10.4px;
                                gap: 10px;
                                margin: 20px;
                            }

                        </style>
                    </div>
                </form>
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
            </div> 
        </div>
    </main>
</body>
</html>

<style>
    .idoso{
    width: 100%;
    height: auto;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: start;
    gap: 10px;
    }

    .idoso select{
        font-family: "Roboto", sans-serif;
        width: 190px;
        height: 40px;
        border-radius: 5px;
        font-size: 18px;
    }
</style>
