<?php
session_start();
require_once '../config/database.php';

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if ($_POST) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $tipo = $_POST['tipo'];
    $endereco = $_POST['endereco'];
    $data_evento = $_POST['data_evento'];
    $hora_evento = $_POST['hora_evento'];
    
    try {
        $sql = "INSERT INTO pontos (usuario_id, titulo, descricao, tipo, endereco, data_evento, hora_evento) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $titulo, $descricao, $tipo, $endereco, $data_evento, $hora_evento]);
        
        header("Location: ../dashboard.php?sucesso=Ponto+cadastrado+com+sucesso!");
        exit;
    } catch(PDOException $e) {
        $erro = "Erro ao cadastrar ponto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Ponto - MeuBairro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #2d3748;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .form-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #4a5568;
            font-weight: 600;
            font-size: 1.1rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
            margin-right: 15px;
        }

        .btn-secondary:hover {
            box-shadow: 0 10px 20px rgba(113, 128, 150, 0.3);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        .alert-success {
            background: #c6f6d5;
            color: #276749;
            border-left: 4px solid #48bb78;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .type-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .type-option:hover {
            border-color: #667eea;
            background: #f7fafc;
        }

        .type-option input[type="radio"] {
            width: auto;
            margin-right: 10px;
        }

        .bairro-info {
            background: #e6fffa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #38b2ac;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Cadastrar Ponto</h1>
            <p>Compartilhe algo importante com o bairro <?= htmlspecialchars($bairro) ?></p>
        </div>
    </div>

    <div class="container">
        <div class="bairro-info">
            <h3>Publicando no bairro <?= htmlspecialchars($bairro) ?></h3>
            <p>Este ponto será visível para todos os moradores do seu bairro.</p>
        </div>

        <div class="form-card">
            <?php if (isset($erro)): ?>
                <div class="alert alert-error">
                    <?= $erro ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">
                    <?= $_GET['sucesso'] ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Tipo de Ponto</label>
                    <div class="type-option">
                        <input type="radio" name="tipo" value="bazar" id="bazar" required>
                        <label for="bazar" style="display: inline; margin: 0;">🏪 Comércio</label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="tipo" value="evento" id="evento">
                        <label for="evento" style="display: inline; margin: 0;">🎉 Evento Comunitário</label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="tipo" value="servico" id="servico">
                        <label for="servico" style="display: inline; margin: 0;">🔧 Serviço</label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="tipo" value="alerta" id="alerta">
                        <label for="alerta" style="display: inline; margin: 0;">⚠️ Alerta</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" placeholder="Ex: Bazar de Roupas Infantis" required>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" placeholder="Descreva detalhadamente o que é este ponto..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço (Opcional)</label>
                    <input type="text" name="endereco" id="endereco" placeholder="Ex: Rua das Flores, 123">
                </div>

                <div class="form-group">
                    <label for="data_evento">Data do Evento (Se aplicável)</label>
                    <input type="date" name="data_evento" id="data_evento">
                </div>

                <div class="form-group">
                    <label for="hora_evento">Hora do Evento (Se aplicável)</label>
                    <input type="time" name="hora_evento" id="hora_evento">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Cadastrar Ponto</button>
                    <a href="../dashboard.php" class="btn btn-secondary">← Voltar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>