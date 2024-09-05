<?php
session_start();
include_once('cadastro.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idRelatorio = $_POST['idRelatorio'];
    $nomeIdoso = $_POST['nomeIdoso'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $relatorios = $_POST['relatorios'];

    // Verifique se uma nova imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagemPath = 'uploads/' . basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagemPath);
    } else {
        $imagemPath = $_POST['imagemAtual']; // Use a imagem atual se nenhuma nova foi enviada
    }

    // Atualize o relatório no banco de dados
    $sql = "UPDATE relatorios SET nomeIdoso = ?, data = ?, hora = ?, relatorios = ?, imagem = ? WHERE idRelatorio = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('sssssi', $nomeIdoso, $data, $hora, $relatorios, $imagemPath, $idRelatorio);

    if ($stmt->execute()) {
        echo 'Relatório atualizado com sucesso!';
    } else {
        http_response_code(500);
        echo 'Erro ao atualizar o relatório.';
    }
}
?>
