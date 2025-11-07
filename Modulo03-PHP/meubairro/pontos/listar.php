<?php
session_start();
require_once '../config/database.php';

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Buscar pontos do usuário
$sql = "SELECT * FROM pontos WHERE usuario_id = ? ORDER BY data_criacao DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_id]);
$pontos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Deletar ponto
if (isset($_GET['deletar'])) {
    $ponto_id = $_GET['deletar'];
    
    // Verificar se o ponto pertence ao usuário
    $sql_verificar = "SELECT usuario_id FROM pontos WHERE id = ?";
    $stmt_verificar = $pdo->prepare($sql_verificar);
    $stmt_verificar->execute([$ponto_id]);
    $ponto = $stmt_verificar->fetch();
    
    if ($ponto && $ponto['usuario_id'] == $usuario_id) {
        $sql_deletar = "DELETE FROM pontos WHERE id = ?";
        $stmt_deletar = $pdo->prepare($sql_deletar);
        $stmt_deletar->execute([$ponto_id]);
        
        header("Location: listar.php?sucesso=Ponto+deletado+com+sucesso!");
        exit;
    } else {
        header("Location: listar.php?erro=Ponto+não+encontrado+ou+sem+permissão!");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pontos - MeuBairro</title>
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
            max-width: 1000px;
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
            text-align: center;
        }

        .stats-card h3 {
            color: #4a5568;
            margin-bottom: 15px;
        }

        .menu {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            background: white;
            color: #4a5568;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
            color: white;
        }

        .pontos-grid {
            display: grid;
            gap: 25px;
        }

        .ponto-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ponto-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .ponto-card.bazar { border-left-color: #9f7aea; }
        .ponto-card.evento { border-left-color: #38a169; }
        .ponto-card.servico { border-left-color: #ed8936; }
        .ponto-card.alerta { border-left-color: #e53e3e; }

        .ponto-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .ponto-title {
            color: #2d3748;
            font-size: 1.3rem;
            margin: 0;
        }

        .tipo-badge {
            background: #edf2f7;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #4a5568;
            font-weight: 600;
        }

        .ponto-descricao {
            color: #4a5568;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .ponto-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            color: #718096;
            font-size: 0.9rem;
        }

        .ponto-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .action-edit {
            background: #bee3f8;
            color: #2c5282;
        }

        .action-edit:hover {
            background: #90cdf4;
        }

        .action-delete {
            background: #fed7d7;
            color: #c53030;
        }

        .action-delete:hover {
            background: #feb2b2;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin-bottom: 15px;
            color: #4a5568;
        }

        .alert {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .alert-success {
            background: #c6f6d5;
            color: #276749;
            border-left: 4px solid #48bb78;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        @media (max-width: 768px) {
            .ponto-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .ponto-actions {
                width: 100%;
            }
            
            .action-btn {
                flex: 1;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Meus Pontos</h1>
            <p>Gerencie os pontos que você cadastrou no bairro <?= htmlspecialchars($bairro) ?></p>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">
                ✅ <?= $_GET['sucesso'] ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-error">
                ❌ <?= $_GET['erro'] ?>
            </div>
        <?php endif; ?>

        <div class="stats-card">
            <h3>Seus Pontos Cadastrados</h3>
            <p>Você tem <strong><?= count($pontos) ?> ponto(s)</strong> cadastrado(s) no bairro <?= htmlspecialchars($bairro) ?></p>
        </div>

        <div class="menu">
            <a href="criar.php" class="btn btn-primary">
                ➕ Cadastrar Novo Ponto
            </a>
            <a href="../dashboard.php" class="btn btn-secondary">
                ← Voltar para o Dashboard
            </a>
        </div>

        <?php if ($pontos): ?>
            <div class="pontos-grid">
                <?php foreach($pontos as $ponto): ?>
                <div class="ponto-card <?= $ponto['tipo'] ?>">
                    <div class="ponto-header">
                        <h3 class="ponto-title"><?= htmlspecialchars($ponto['titulo']) ?></h3>
                        <span class="tipo-badge">
                            <?php 
                            $tipos_pontos = [
                                'bazar' => '🏪 Bazar',
                                'evento' => '🎉 Evento',
                                'servico' => '🔧 Serviço',
                                'alerta' => '⚠️ Alerta'
                            ];
                            echo $tipos_pontos[$ponto['tipo']] ?? $ponto['tipo'];
                            ?>
                        </span>
                    </div>
                    
                    <p class="ponto-descricao"><?= htmlspecialchars($ponto['descricao']) ?></p>
                    
                    <div class="ponto-info">
                        <?php if ($ponto['endereco']): ?>
                            <span>🏠 <?= htmlspecialchars($ponto['endereco']) ?></span>
                        <?php endif; ?>
                        
                        <?php if ($ponto['data_evento']): ?>
                            <span>📅 <?= date('d/m/Y', strtotime($ponto['data_evento'])) ?></span>
                        <?php endif; ?>
                        
                        <?php if ($ponto['hora_evento']): ?>
                            <span>⏰ <?= $ponto['hora_evento'] ?></span>
                        <?php endif; ?>
                        
                        <span>📆 Cadastrado em: <?= date('d/m/Y', strtotime($ponto['data_criacao'])) ?></span>
                    </div>
                    
                    <div class="ponto-actions">
                        <a href="editar.php?id=<?= $ponto['id'] ?>" class="action-btn action-edit">
                            ✏️ Editar
                        </a>
                        <a href="listar.php?deletar=<?= $ponto['id'] ?>" class="action-btn action-delete" 
                           onclick="return confirm('Tem certeza que deseja deletar este ponto?')">
                            🗑️ Deletar
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="icon">📍</div>
                <h3>Nenhum ponto cadastrado</h3>
                <p>Você ainda não cadastrou nenhum ponto no bairro <?= htmlspecialchars($bairro) ?>.</p>
                <a href="criar.php" class="btn btn-primary" style="margin-top: 20px;">
                    ➕ Cadastrar Meu Primeiro Ponto
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>