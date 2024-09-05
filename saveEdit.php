<?php
include_once('cadastro.php');

if(isset($_POST['update'])) {
    $idUsuario = $_POST['idUsuario'];
    $email = mysqli_real_escape_string($conexao, $_POST['username']);
    $password = mysqli_real_escape_string($conexao, $_POST['password']);
    $passwordConfirmation = mysqli_real_escape_string($conexao, $_POST['passwordConfirmation']);
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);

    // Verifica se as senhas são iguais
    if ($password !== $passwordConfirmation) {
        $error = "As senhas não são iguais.";
    } else {
        // Atualiza os dados do usuário no banco de dados
        $sqlUpdate = "UPDATE usuarios SET email='$email', senha='$password', nome='$nome' WHERE idUsuario=$idUsuario";
        if ($conexao->query($sqlUpdate) === TRUE) {
            $success = "Dados atualizados com sucesso!";
        } else {
            $error = "Erro ao atualizar os dados: " . $conexao->error;
        }
    }
}
header("Location: listaIdoso.php");
exit();

$conexao->close();
?>
