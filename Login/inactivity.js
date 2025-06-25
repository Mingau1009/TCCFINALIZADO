// inactivity.js - Controle centralizado de inatividade

class InactivityManager {
    constructor(options = {}) {
        this.options = {
            timeout: 1200000, // 2 minutos em milissegundos
            tokenKey: 'token',
            activityKey: 'lastActivity',
            loginUrl: 'index.php',
            tokenCheckUrl: 'verificar_token.php',
            ...options
        };

        this.timer = null;
        this.initialize();
    }

    initialize() {
        // Verificar token ao carregar a página
        if (!this.checkToken()) {
            this.redirectToLogin();
            return;
        }

        this.startTracking();
    }

    checkToken() {
        const token = localStorage.getItem(this.options.tokenKey);
        if (!token) return false;

        // Verificar se o token está expirado baseado na última atividade
        const lastActivity = localStorage.getItem(this.options.activityKey);
        if (!lastActivity) return false;

        const currentTime = Date.now();
        const elapsed = currentTime - parseInt(lastActivity);
        
        return elapsed < this.options.timeout;
    }

    startTracking() {
        this.resetTimer();

        // Eventos que resetam o timer
        const events = ['mousemove', 'mousedown', 'touchstart', 'click', 'keypress'];
        events.forEach(event => {
            window.addEventListener(event, () => this.resetTimer());
        });
    }

    resetTimer() {
        clearTimeout(this.timer);
        localStorage.setItem(this.options.activityKey, Date.now());
        
        this.timer = setTimeout(() => {
            this.verifyWithServer();
        }, this.options.timeout);
    }

    async verifyWithServer() {
        const token = localStorage.getItem(this.options.tokenKey);
        const lastActivity = localStorage.getItem(this.options.activityKey);

        if (!token || !lastActivity) {
            this.redirectToLogin();
            return;
        }

        try {
            const response = await fetch(this.options.tokenCheckUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'X-Last-Activity': lastActivity
                }
            });

            const data = await response.json();

            if (!data || data.valido === false) {
                this.redirectToLogin();
            }
        } catch (error) {
            console.error('Erro ao verificar token:', error);
            this.redirectToLogin();
        }
    }

    redirectToLogin() {
        localStorage.removeItem(this.options.tokenKey);
        localStorage.removeItem(this.options.activityKey);
        window.location.href = "../Login/index.php"
    }
}

// Inicializa o gerenciador de inatividade quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new InactivityManager();
});