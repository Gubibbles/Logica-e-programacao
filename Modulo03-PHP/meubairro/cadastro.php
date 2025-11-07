<?php
session_start();
require_once 'config/database.php';

if ($_POST) {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $cep = $_POST['cep'];
    
    // Buscar bairro pelo CEP usando ViaCEP
    function buscarEnderecoPeloCEP($cep) {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $endereco = json_decode($response, true);
        
        if (isset($endereco['erro'])) {
            return 'Bairro não encontrado';
        }
        
        return $endereco['bairro'] ?? 'Bairro não informado';
    }

    $bairro = buscarEnderecoPeloCEP($cep);

    try {
        $sql = "INSERT INTO usuarios (cpf, nome, email, celular, cep, bairro) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cpf, $nome, $email, $celular, $cep, $bairro]);

        // Buscar o id do usuário recém-cadastrado
        $usuario_id = $pdo->lastInsertId();
        
        // Criar sessão
        $_SESSION['usuario_id'] = $usuario_id;
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['bairro'] = $bairro;
        
        // Redireciona para o dashboard
        header("Location: dashboard.php");
        exit;
    } catch(PDOException $e) {
        $erro = "Erro no cadastro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - MeuBairro</title>
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

        .cadastro-container {
            background: white;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        .logo {
            text-align: center;
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
            margin-bottom: 20px;
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
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
        }

        .links {
            text-align: center;
            margin-top: 25px;
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

        .cep-info {
            background: #e6fffa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            border-left: 4px solid #38b2ac;
            display: none;
        }

        .loading {
            color: #667eea;
            text-align: center;
            padding: 10px;
            display: none;
        }

        .note {
            background: #edf2f7;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #4a5568;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <div class="logo">
            <h1>MeuBairro</h1>
            <p>Junte-se à sua comunidade</p>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="formCadastro">
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" name="cpf" id="cpf" placeholder="Digite seu CPF" required>
            </div>

            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" name="nome" id="nome" placeholder="Seu nome completo" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>

            <div class="form-group">
                <label for="celular">Celular</label>
                <input type="text" name="celular" id="celular" placeholder="(11) 99999-9999" required>
            </div>

            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" placeholder="00000-000" required>
                <div id="loadingCep" class="loading">🔍 Buscando endereço...</div>
                <div id="cepInfo" class="cep-info">
                    <strong>Bairro encontrado:</strong> <span id="nomeBairro"></span>
                </div>
            </div>

            <button type="submit" class="btn">Cadastrar e Entrar</button>
        </form>

        <div class="links">
            <p>Já tem cadastro? <a href="login.php">Faça login aqui</a></p>
            <p><a href="index.php">← Voltar para a página inicial</a></p>
        </div>

        <div class="note">
            💡 O CEP determina automaticamente seu bairro.
        </div>
    </div>

    <script>
        // Máscaras
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });

        document.getElementById('celular').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });

        document.getElementById('cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });

        // Buscar CEP
        document.getElementById('cep').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            const cepInfo = document.getElementById('cepInfo');
            const nomeBairro = document.getElementById('nomeBairro');
            const loading = document.getElementById('loadingCep');

            if (cep.length === 8) {
                loading.style.display = 'block';
                cepInfo.style.display = 'none';

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        loading.style.display = 'none';
                        
                        if (data.erro) {
                            nomeBairro.textContent = 'CEP não encontrado';
                            cepInfo.style.background = '#fed7d7';
                            cepInfo.style.borderLeftColor = '#f56565';
                        } else {
                            nomeBairro.textContent = data.bairro || 'Bairro não informado';
                            cepInfo.style.background = '#e6fffa';
                            cepInfo.style.borderLeftColor = '#38b2ac';
                        }
                        cepInfo.style.display = 'block';
                    })
                    .catch(error => {
                        loading.style.display = 'none';
                        nomeBairro.textContent = 'Erro ao buscar CEP';
                        cepInfo.style.background = '#fed7d7';
                        cepInfo.style.borderLeftColor = '#f56565';
                        cepInfo.style.display = 'block';
                    });
            }
        });
    </script>
</body>
</html>