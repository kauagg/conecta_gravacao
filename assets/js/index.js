const usuariosCadastrados = [
    { email: "empresa@conecta.com", senha: "123456" },
    { email: "admin@conecta.com", senha: "admin" }
  ];

  document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const mensagemErro = document.getElementById('mensagemErro');

    const usuarioValido = usuariosCadastrados.find(user => user.email === email && user.senha === senha);

    if (usuarioValido) {
      localStorage.setItem("usuario", JSON.stringify(usuarioValido));
      window.location.href = "clientes.html";
    } else {
      mensagemErro.textContent = "Email ou senha incorretos.";
    }
  });