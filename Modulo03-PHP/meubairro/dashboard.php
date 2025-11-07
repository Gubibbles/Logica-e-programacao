<?php
session_start();
require_once 'config/database.php';

//Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$bairro = $_SESSION['bairro'];
$usuario_nome = $_SESSION['usuario_nome'];

//Buscar avisos do bairro do usuário
$sql_avisos = "SELECT * FROM avisos_bairro 
               WHERE bairro = ? 
               AND (data_validade IS NULL OR data_validade >= CURDATE())
               ORDER BY data_publicacao DESC 
               LIMIT 5";
$stmt_avisos = $pdo->prepare($sql_avisos);
$stmt_avisos->execute([$bairro]);
$avisos = $stmt_avisos->fetchAll(PDO::FETCH_ASSOC);

//Buscar pontos do bairro do usuário logado
$sql_pontos = "SELECT p.*, u.nome FROM pontos p 
               JOIN usuarios u ON p.usuario_id = u.id 
               WHERE u.bairro = ? 
               ORDER BY p.data_criacao DESC 
               LIMIT 10";
$stmt_pontos = $pdo->prepare($sql_pontos);
$stmt_pontos->execute([$bairro]);
$pontos = $stmt_pontos->fetchAll(PDO::FETCH_ASSOC);

//Contar quantos usuários tem no bairro
$sql_contagem = "SELECT COUNT(*) as total FROM usuarios WHERE bairro = ?";
$stmt_contagem = $pdo->prepare($sql_contagem);
$stmt_contagem->execute([$bairro]);
$contagem_bairro = $stmt_contagem->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuBairro - <?= htmlspecialchars($bairro) ?></title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo h1 {
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

        .user-info {
            background: rgba(255,255,255,0.2);
            padding: 12px 20px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
        }

        .logout {
            color: #fed7d7;
            text-decoration: none;
            margin-left: 10px;
            transition: color 0.3s ease;
        }

        .logout:hover {
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .stats-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #667eea;
        }

        .stats-card h3 {
            color: #4a5568;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .menu a {
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

        .menu a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background: #667eea;
            color: white;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 25px;
            margin-top: 50px;
        }

        .section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: fit-content;
            min-height: 400px;
        }

        .section h3 {
            color: #4a5568;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .aviso, .ponto, .noticia {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            margin: 15px 0;
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .aviso:hover, .ponto:hover, .noticia:hover {
            transform: translateX(5px);
        }

        .aviso.obra { border-left-color: #e53e3e; }
        .aviso.alerta { border-left-color: #dd6b20; }
        .aviso.informacao { border-left-color: #3182ce; }
        .aviso.evento { border-left-color: #38a169; }

        .ponto.bazar { border-left-color: #9f7aea; }
        .ponto.evento { border-left-color: #38a169; }
        .ponto.servico { border-left-color: #ed8936; }
        .ponto.alerta { border-left-color: #e53e3e; }

        .noticia {
            border-left-color: #d69e2e;
            background: #fffaf0;
        }

        .aviso h4, .ponto h4, .noticia h4 {
            color: #2d3748;
            margin-bottom: 10px;
        }

        .tipo-badge {
            background: #edf2f7;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: #4a5568;
            font-weight: 600;
        }

        .noticia-badge {
            background: #fef5e7;
            color: #d69e2e;
            border: 1px solid #fbd38d;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #718096;
        }

        .empty-state .icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .map-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        .map-section h3 {
            color: #4a5568;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .map-note {
            background: #e6fffa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            border-left: 4px solid #38b2ac;
            font-size: 0.9rem;
            color: #4a5568;
        }

        @media (max-width: 1024px) {
            .grid-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .menu {
                justify-content: center;
            }
            
            .menu a {
                flex: 1;
                min-width: 200px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="logo">
                <h1>MeuBairro</h1>
                <p><?= htmlspecialchars($bairro) ?></p>
            </div>
            <div class="user-info">
                👋 Olá, <strong><?= htmlspecialchars($usuario_nome) ?></strong>!
                <a href="logout.php" class="logout">Sair</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Card de informações do bairro -->
        <div class="stats-card">
            <h3>Seu Bairro</h3>
            <p><strong>👥 Vizinhos no MeuBairro:</strong> <?= $contagem_bairro['total'] ?> pessoa(s)</p>
            <p><strong>Comunidade ativa:</strong> Tudo que acontece no bairro <?= htmlspecialchars($bairro) ?> aparece aqui!</p>
        </div>

        <!-- Menu de navegação -->
        <div class="menu">
            <a href="pontos/criar.php">
                ➕ Cadastrar Ponto
            </a>
            <a href="pontos/listar.php">
                Meus Pontos
            </a>
            <a href="index.php">
                Página Inicial
            </a>
        </div>

        <div class="grid-container">
            <!-- Coluna 1: avisos do bairro -->
            <div class="section">
                <h3>📢 Avisos do Bairro</h3>
                
                <?php if ($avisos): ?>
                    <?php foreach($avisos as $aviso): ?>
                    <div class="aviso <?= $aviso['tipo'] ?>">
                        <h4><?= htmlspecialchars($aviso['titulo']) ?></h4>
                        <p><?= htmlspecialchars($aviso['mensagem']) ?></p>
                        <small>
                            <span class="tipo-badge">
                                <?php 
                                $tipos_avisos = [
                                    'obra' => '🚧 Obra',
                                    'alerta' => '⚠️ Alerta', 
                                    'informacao' => 'ℹ️ Informação',
                                    'evento' => '🎉 Evento'
                                ];
                                echo $tipos_avisos[$aviso['tipo']] ?? $aviso['tipo'];
                                ?>
                            </span>
                            • <?= date('d/m/Y', strtotime($aviso['data_publicacao'])) ?>
                            <?php if ($aviso['data_validade']): ?>
                                • Válido até: <?= date('d/m/Y', strtotime($aviso['data_validade'])) ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon">📭</div>
                        <h4>Nenhum aviso no momento</h4>
                        <p>Quando houver avisos importantes para o bairro <?= htmlspecialchars($bairro) ?>, eles aparecerão aqui.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Coluna 2: pontos do bairro -->
            <div class="section">
                <h3>📍 Pontos do Bairro</h3>
                
                <?php if ($pontos): ?>
                    <?php foreach($pontos as $ponto): ?>
                    <div class="ponto <?= $ponto['tipo'] ?>">
                        <h4><?= htmlspecialchars($ponto['titulo']) ?></h4>
                        <p><?= htmlspecialchars($ponto['descricao']) ?></p>
                        <small>
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
                            • Por: <?= htmlspecialchars($ponto['nome']) ?>
                            <?php if ($ponto['data_evento']): ?>
                                • 📅 <?= date('d/m/Y', strtotime($ponto['data_evento'])) ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon">📍</div>
                        <h4>Nenhum ponto cadastrado</h4>
                        <p>Seja o primeiro a cadastrar um ponto no bairro <?= htmlspecialchars($bairro) ?>!</p>
                        <a href="pontos/criar.php" style="background: #48bb78; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; display: inline-block; margin-top: 15px; font-weight: 600;">
                            ➕ Cadastrar Primeiro Ponto
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Coluna 3: Notícias da Cidade -->
            <div class="section">
                <h3>📰 Notícias de Mogi das Cruzes</h3>
                
                <div class="empty-state">
                    <div class="icon">📰</div>
                    <h4>Em breve!</h4>
                    <p>As notícias e novidades da cidade de Mogi das Cruzes aparecerão aqui.</p>
                    <div style="background: #fef5e7; padding: 15px; border-radius: 10px; margin-top: 15px; border-left: 4px solid #d69e2e;">
                        <strong>💡 Espaço reservado</strong><br>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <div class="map-section">
            <h3>Mapa de Mogi das Cruzes</h3>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58570.115161552!2d-46.22860035!3d-23.52250005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cdcc953c151c9f%3A0xcd82d8145d42e7e0!2sMogi%20das%20Cruzes%2C%20SP!5e0!3m2!1spt-BR!2sbr!4v1700000000000&zoom=13"
                width="100%" 
                height="300" 
                style="border:0; border-radius: 10px;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
                title="Mapa de Mogi das Cruzes">
            </iframe>
            <div class="map-note">
                💡 Mapa da cidade de Mogi das Cruzes.
            </div>
        </div>
    </div>
</body>
</html>