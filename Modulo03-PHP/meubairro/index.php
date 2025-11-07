<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeuBairro</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: segoe ui, Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            color: #4a5568;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .feature-card p {
            color: #718096;
            line-height: 1.6;
        }

        .cta-buttons {
            text-align: center;
            margin: 50px 0;
        }

        .btn {
            display: inline-block;
            padding: 15px 35px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: #48bb78;
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-primary:hover {
            background: #38a169;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        .how-it-works {
            background: white;
            padding: 80px 20px;
            border-radius: 20px;
            margin: 60px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .how-it-works h2 {
            text-align: center;
            color: #4a5568;
            margin-bottom: 50px;
            font-size: 2.5rem;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }

        .step {
            padding: 20px;
        }

        .step-number {
            background: #667eea;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .footer {
            text-align: center;
            color: white;
            padding: 40px 20px;
            margin-top: 60px;
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .btn {
                display: block;
                margin: 10px auto;
                max-width: 250px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="hero">
            <h1>MeuBairro</h1>
            <p>Conectando pessoas e fortalecendo comunidades locais em Mogi das Cruzes. Descubra o que acontece no seu bairro!</p>
            <div class="cta-buttons">
                <a href="cadastro.php" class="btn btn-primary">Cadastre-se</a>
                <a href="login.php" class="btn btn-secondary">Entrar</a>
            </div>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">📢</div>
                <h3>Avisos Locais</h3>
                <p>Fique por dentro de tudo que acontece no seu bairro: obras, eventos, alertas e muito mais.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📍</div>
                <h3>Pontos de Interesse</h3>
                <p>Descubra pontos de comércio, eventos e serviços perto de você. Ou cadastre já os seus!</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Comunidade</h3>
                <p>Conecte-se com seus vizinhos e fortaleça a rede local do seu bairro.</p>
            </div>
        </div>

        <div class="how-it-works">
            <h2>Como Funciona?</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Cadastre-se</h3>
                    <p>Faça seu cadastro informando seu CEP para ser direcionado ao seu bairro</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Explore</h3>
                    <p>Veja os avisos e pontos de interesse do seu bairro</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Compartilhe</h3>
                    <p>Cadastre seus próprios eventos e ajude a comunidade</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2024 MeuBairro. Conectando comunidades em Mogi das Cruzes.</p>
        </div>
    </div>
</body>
</html>