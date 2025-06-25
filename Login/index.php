<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <style>
        .password-field {
            position: relative;
            width: 100%;
        }
        .password-field input {
            width: calc(100% - 30px); /* Ajusta o espaço para o ícone */
            padding-right: 69px; /* Espaço para o ícone */
        }
        .password-field .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color:#877f7f; /* Cor laranja para o ícone */
        }

        /* Cor laranja para o botão de login */
        button {
            background-color: #FFA500; /* Laranja */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ff7f00; /* Laranja mais escuro para hover */
        }
    </style>
</head>

<body>
    <div class="login-form">
        <div style="overflow-x: auto; width: 300px;">
            <img src="logo.jpeg" alt="Login" style="width: 130px; height: 130px;">
        </div>
        
        <form onsubmit="return login(event)">
            <div class="field">
                <div class="fas fa-envelope"></div>
                <input type="text" id="usuario" placeholder="Usuário" required>
            </div>

            <div class="field password-field">
                <div class="fas fa-lock"></div>
                <input type="password" id="senha" placeholder="Senha" required>
                <i class="far fa-eye eye-icon" id="togglePassword"></i>
            </div>
            <br>
            <button type="submit">LOGIN</button>
        </form>
    </div>

    <script>
        // Alternar visibilidade da senha
        document.getElementById('togglePassword').addEventListener('click', function() {
            const senhaInput = document.getElementById('senha');
            const type = senhaInput.type === 'password' ? 'text' : 'password';
            senhaInput.type = type;
            this.classList.toggle('fa-eye-slash');
        });

        // Função de login - apenas cria novo token sem verificar existência
        async function login(event) {
            event.preventDefault();

            const usuario = document.getElementById('usuario').value;
            const senha = document.getElementById('senha').value;

            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ usuario, senha })
                });

                const data = await response.json();

                if (response.ok && data.token) {
                    // Armazena o novo token e redireciona
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('lastActivity', Date.now());
                    window.location.href = '../Dashboard/index.php';
                } else {
                    alert(data.erro || 'Erro desconhecido.');
                    
                    // Limpa campos conforme instrução do servidor
                    if (data.resetar_campos) {
                        document.getElementById('usuario').value = '';
                        document.getElementById('senha').value = '';
                    } else {
                        document.getElementById('senha').value = '';
                    }
                    document.getElementById('usuario').focus();
                }
            } catch (error) {
                alert('Erro de conexão com o servidor.');
            }
        }
    </script>
</body>
</html>