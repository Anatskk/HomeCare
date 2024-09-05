<?php
session_start();

include_once('cadastro.php');

// Verificar se o usuário está logado e se é administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['isAdm'] !== 'sim') {
    header('Location: index.php');
    exit();
}

$notification = '';

if (!empty($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    $sqlDelete = "DELETE FROM usuarios WHERE idUsuario = $idUsuario";
    $resultDelete = $conexao->query($sqlDelete);

    if ($resultDelete) {
        $notification = "Usuário com id $idUsuario excluído com sucesso.";
    } else {
        $notification = "Erro ao excluir usuário: " . $conexao->error;
    }
}

// Buscar usuários do tipo 'user'
$sql = "SELECT * FROM usuarios WHERE isAdm = 'user' ORDER BY idUsuario DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="css/lista.css">
    <style>
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px; /* Espaçamento entre as células */
            padding: 0px 10px;
        }

        th, td {
            border: 1px solid #ddd; /* Linha entre th e td */
            padding: 8px; /* Espaçamento interno das células */
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        hr {
            margin: 0; /* Remove margem padrão do hr */
            border: none; /* Remove borda padrão do hr */
            border-top: 2px solid #000; /* Linha personalizada */
        }
    </style>
    <script>
        // Função para mostrar notificação
        function showNotification(message) {
            // Verifica se o navegador suporta notificações
            if ("Notification" in window) {
                // Solicita permissão para mostrar notificações, se ainda não concedida
                if (Notification.permission === "granted") {
                    // Mostra a notificação
                    new Notification(message);
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            // Mostra a notificação
                            new Notification(message);
                        }
                    });
                }
            }
        }

        // Verifica se há uma mensagem de notificação para exibir
        <?php if (!empty($notification)): ?>
            document.addEventListener("DOMContentLoaded", function() {
                showNotification("<?php echo $notification; ?>");
            });
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="comeco">
        <div class="titulo">
            <a href="admDashboard.php"><p>Voltar</p></a>
        </div>
        <div class="linha"></div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">User Login</th>
                <th scope="col">Senha</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5"><hr></td>
            </tr>
            <?php
            while ($userData = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$userData['idUsuario']."</td>";
                echo "<td>".$userData['nome']."</td>";
                echo "<td>".$userData['email']."</td>";
                echo "<td>".$userData['senha']."</td>";
                echo "<td>
                <a class='btn btn-sm' href='editIdoso.php?idUsuario=$userData[idUsuario]' style='background-color: blue; border-radius: 3px; padding: 3px; display: inline-block;'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' class='bi bi-pencil' viewBox='0 0 16 16'>
                  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325' fill='white'/>
                </svg>
              </a>
              

                    <a class='btn btn-sm' href='?idUsuario=".$userData['idUsuario']."' style='background-color: red; border-radius: 3px; padding: 3px; display: inline-block;'>
                      <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                        <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0'/>
                      </svg>
                    </a>
                  </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
