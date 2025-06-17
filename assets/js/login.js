// Mostrar/Esconder Senha
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        this.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        this.textContent = '👁️';
    }
});

// Cadastro Modal
const registerModal = document.getElementById('registerModal');
const registerLink = document.getElementById('registerLink');
const closeRegister = document.getElementById('closeRegister');

registerLink.addEventListener('click', function(e) {
    e.preventDefault();
    registerModal.style.display = 'flex';
});

closeRegister.addEventListener('click', function() {
    registerModal.style.display = 'none';
});

// Recuperação de Senha Modal
const forgotPasswordModal = document.getElementById('forgotPasswordModal');
const forgotPasswordLink = document.getElementById('forgotPasswordLink');
const closeForgot = document.getElementById('closeForgot');

forgotPasswordLink.addEventListener('click', function(e) {
    e.preventDefault();
    forgotPasswordModal.style.display = 'flex';
});

closeForgot.addEventListener('click', function() {
    forgotPasswordModal.style.display = 'none';
});

// Fechar modais ao clicar fora
window.addEventListener('click', function(e) {
    if (e.target === registerModal) registerModal.style.display = 'none';
    if (e.target === forgotPasswordModal) forgotPasswordModal.style.display = 'none';
});