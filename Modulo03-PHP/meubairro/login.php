<?php
session_start();
require_once 'config/database.php';

if ($_POST) {
    $cpf_digitado = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    
    try {
        $sql = "SELECT * FROM usuarios WHERE REPLACE(REPLACE(cpf, '.', ''), '-', '') = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cpf_digitado]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            // Criar sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['bairro'] = $usuario['bairro'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $erro = "CPF não encontrado";
        }
    } catch(PDOException $e) {
        $erro = "Erro no login: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MeuBairro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #4a5568;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .logo p {
            color: #718096;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .links {
            margin-top: 30px;
            text-align: center;
        }

        .links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        .cpf-note {
            background: #edf2f7;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #4a5568;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>MeuBairro</h1>
            <p>Entre na sua comunidade</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" placeholder="Digite seu CPF" required>
            </div>

            <button type="submit" class="btn">Entrar na Comunidade</button>
        </form>

        <div class="links">
            <p>Não tem cadastro? <a href="cadastro.php">Cadastre-se aqui</a></p>
            <p><a href="index.php">← Voltar para a página inicial</a></p>
        </div>

        <div class="cpf-note">
            💡 Use o mesmo CPF que você cadastrou anteriormente
        </div>
    </div>

    <script>
        // Máscara para CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    </script>
</body>
</html>