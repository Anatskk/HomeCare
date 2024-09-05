<?php
if (isset($_POST['submit']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    include_once('cadastro.php');
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Escapando caracteres especiais para evitar SQL injection
    $username = mysqli_real_escape_string($conexao, $username);
    $password = mysqli_real_escape_string($conexao, $password);

    // Consultando o banco de dados para verificar se o usuário existe
    $query = "SELECT * FROM usuarios WHERE email = '$username' AND senha = '$password'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) > 0) {
        // Usuário encontrado, redirecionar para a página principal
        header('Location: dashboard.php');
        exit();
    } else {
        // Usuário não encontrado, redirecionar de volta para a página de login com mensagem de erro
        header('Location: index.php?error=Usuário não encontrado ou senha incorreta');
        exit();
    }

    // Fechando a conexão
    mysqli_close($conexao);
} else {
    header('Location: index.php');
    exit();
}
?>
