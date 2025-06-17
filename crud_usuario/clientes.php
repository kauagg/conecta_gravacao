<?php
$conn = new mysqli("localhost", "root", "", "conecta_festas");
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_POST['criar'])) {
    $email = $_POST['email_cliente'];
    $senha = password_hash($_POST['senha_cliente'], PASSWORD_DEFAULT); // Sempre hashear senhas!
    $cpf = $_POST['cpf_cliente'];
    $nome = $_POST['nome_cliente'];
    $end = $_POST['end_cliente'];
    $tel = $_POST['tel_cliente'];
    $data = $_POST['data_nasc'];

    $stmt = $conn->prepare("INSERT INTO Cliente (email_cliente, senha_cliente, cpf_cliente, nome_cliente, end_cliente, tel_cliente, data_nasc) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $email, $senha, $cpf, $nome, $end, $tel, $data);
    $stmt->execute();
}

if (isset($_POST['excluir'])) {
    $id = $_POST['idCliente'];
    $conn->query("DELETE FROM Cliente WHERE idCliente = $id");
}

if (isset($_POST['salvar'])) {
    $id = $_POST['idCliente'];
    $cpf = $_POST['cpf_cliente'];
    $nome = $_POST['nome_cliente'];
    $end = $_POST['end_cliente'];
    $tel = $_POST['tel_cliente'];
    $data = $_POST['data_nasc'];

    $stmt = $conn->prepare("UPDATE Cliente SET cpf_cliente=?, nome_cliente=?, end_cliente=?, tel_cliente=?, data_nasc=? WHERE idCliente=?");
    $stmt->bind_param("sssssi", $cpf, $nome, $end, $tel, $data, $id);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM Cliente");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff4f2;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #e06a5f;
            font-size: 2em;
            margin-bottom: 30px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo-container img {
            width: 300px;
            max-width: 90%;
        }

        .cliente {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(255, 105, 97, 0.2);
            border: 1px solid #ffe1db;
        }

        button {
            background-color: #ff8474;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            margin: 6px 6px 0 0;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e06a5f;
        }

        .excluir-btn {
            background-color: #ff4f4f;
        }

        .excluir-btn:hover {
            background-color: #cc2f2f;
        }

        .form-alterar,
        .detalhes {
            background-color: #fff7f6;
            border-left: 4px solid #ff8474;
            border-radius: 12px;
            padding: 15px;
            margin-top: 15px;
            display: none;
        }

        input[type="text"],
        input[type="date"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 6px 0 12px;
            border: 1px solid #ffd1c7;
            border-radius: 8px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 8px;
            color: #555;
        }

        .form-alterar button {
            background-color: #6ec177;
        }

        .form-alterar button:hover {
            background-color: #52a15c;
        }
    </style>

</head>
<body>
    <div class="logo-container">
        <img src="2Logo.png" alt="Logo Conecta Festas">
    </div>
    
    <h1>Usuários</h1>
    
    <button onclick="mostrarFormCriar()" style="margin-bottom: 20px;">Criar Usuário</button>

    <div id="formCriar" class="form-alterar" style="display: none;">
        <h2>Novo Usuário</h2>
        <form method="post">
            <label>Email:</label>
            <input type="text" name="email_cliente" required>

            <label>Senha:</label>
            <input type="text" name="senha_cliente" required>

            <label>CPF:</label>
            <input type="text" name="cpf_cliente">

            <label>Nome:</label>
            <input type="text" name="nome_cliente">

            <label>Endereço:</label>
            <input type="text" name="end_cliente">

            <label>Telefone:</label>
            <input type="text" name="tel_cliente">

            <label>Data de Nascimento:</label>
            <input type="date" name="data_nasc">

            <button type="submit" name="criar">Salvar Usuário</button>
        </form>
    </div>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="cliente">
            <div>
                <strong>ID:</strong> <?= $row['idCliente'] ?> |
                <strong>Email:</strong> <?= htmlspecialchars($row['email_cliente']) ?>
            </div>
            <div class="buttons">
                <form method="post" style="display: inline;">
                    <input type="hidden" name="idCliente" value="<?= $row['idCliente'] ?>">
                    <button type="button" onclick="toggle(<?= $row['idCliente'] ?>)">Consultar</button>
                    <button type="button" onclick="alterar(<?= $row['idCliente'] ?>)">Alterar</button>
                    <button class="excluir-btn" type="submit" name="excluir" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
            </div>

            <div id="detalhes_<?= $row['idCliente'] ?>" class="detalhes">
                <p><strong>CPF:</strong> <?= $row['cpf_cliente'] ?></p>
                <p><strong>Nome:</strong> <?= $row['nome_cliente'] ?></p>
                <p><strong>Endereço:</strong> <?= $row['end_cliente'] ?></p>
                <p><strong>Telefone:</strong> <?= $row['tel_cliente'] ?></p>
                <p><strong>Data de Nascimento:</strong> <?= $row['data_nasc'] ?></p>
            </div>

            <div id="form_<?= $row['idCliente'] ?>" class="form-alterar">
                <form method="post">
                    <input type="hidden" name="idCliente" value="<?= $row['idCliente'] ?>">
                    <label>CPF:</label>
                    <input type="text" name="cpf_cliente" value="<?= $row['cpf_cliente'] ?>">

                    <label>Nome:</label>
                    <input type="text" name="nome_cliente" value="<?= $row['nome_cliente'] ?>">

                    <label>Endereço:</label>
                    <input type="text" name="end_cliente" value="<?= $row['end_cliente'] ?>">

                    <label>Telefone:</label>
                    <input type="text" name="tel_cliente" value="<?= $row['tel_cliente'] ?>">

                    <label>Data de Nascimento:</label>
                    <input type="date" name="data_nasc" value="<?= $row['data_nasc'] ?>">

                    <button type="submit" name="salvar">Salvar Alterações</button>
                </form>
            </div>
        </div>
    <script>
        
        function mostrarFormCriar() {
                const form = document.getElementById("formCriar");
                form.style.display = form.style.display === "block" ? "none" : "block";
        }

        function toggle(id) {
            const detalhes = document.getElementById("detalhes_" + id);
            const form = document.getElementById("form_" + id);

            if (detalhes.style.display === "block") {
                detalhes.style.display = "none";
            } else {
                detalhes.style.display = "block";
                form.style.display = "none"; // Garante que o formulário de alteração feche
            }
        }

        function alterar(id) {
            const form = document.getElementById("form_" + id);
            const detalhes = document.getElementById("detalhes_" + id);

            if (form.style.display === "block") {
                form.style.display = "none";
            } else {
                form.style.display = "block";
                detalhes.style.display = "none"; // fecha a consulta se estiver aberta
            }
        }

    </script>
    <?php endwhile; ?>

</body>
</html>