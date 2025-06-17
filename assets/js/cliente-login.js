 let modoCadastro = false;

    function alternarModo() {
      const titulo = document.getElementById("formTitle");
      const botao = document.getElementById("btnSubmit");
      const toggle = document.querySelectorAll(".toggle")[0];
      const campoNome = document.getElementById("campoNome");

      modoCadastro = !modoCadastro;

      if (modoCadastro) {
        titulo.textContent = "Cadastro de Cliente";
        botao.textContent = "Cadastrar";
        toggle.textContent = "Já tem conta? Faça login";
        campoNome.classList.remove("d-none");
      } else {
        titulo.textContent = "Login do Cliente";
        botao.textContent = "Entrar";
        toggle.textContent = "Ainda não tem conta? Cadastre-se";
        campoNome.classList.add("d-none");
      }

      document.getElementById("mensagemErro").textContent = "";
    }

    document.getElementById("clienteForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const senha = document.getElementById("senha").value;
      const nome = document.getElementById("nome")?.value || "";
      const mensagemErro = document.getElementById("mensagemErro");

      const url = modoCadastro ? "cadastrar-cliente.php" : "login-cliente.php";
      const dados = modoCadastro ? { nome, email, senha } : { email, senha };

      try {
        const resposta = await fetch(url, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(dados)
        });

        const resultado = await resposta.json();

        if (resposta.ok) {
          if (modoCadastro) {
            alert("Cadastro realizado com sucesso! Faça login.");
            alternarModo();
          } else {
            localStorage.setItem("clienteLogado", JSON.stringify(resultado.cliente));
            window.location.href = "cliente-dashboard.html";
          }
        } else {
          mensagemErro.textContent = resultado.erro || "Erro inesperado.";
        }
      } catch (erro) {
        mensagemErro.textContent = "Erro ao conectar com o servidor.";
      }
    });

    function abrirRecuperarSenha() {
      const modal = new bootstrap.Modal(document.getElementById('recuperarSenhaModal'));
      document.getElementById("mensagemRecuperar").textContent = "";
      document.getElementById("emailRecuperar").value = "";
      modal.show();
    }

    document.getElementById("formRecuperarSenha").addEventListener("submit", async function (e) {
      e.preventDefault();

      const email = document.getElementById("emailRecuperar").value;
      const mensagem = document.getElementById("mensagemRecuperar");

      try {
        const resposta = await fetch("recuperar-senha.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email })
        });

        const resultado = await resposta.json();

        if (resposta.ok) {
          mensagem.classList.remove("text-danger");
          mensagem.classList.add("text-success");
          mensagem.textContent = "Instruções enviadas para seu e-mail.";
        } else {
          mensagem.classList.remove("text-success");
          mensagem.classList.add("text-danger");
          mensagem.textContent = resultado.erro || "Erro ao enviar recuperação.";
        }
      } catch (erro) {
        mensagem.classList.add("text-danger");
        mensagem.textContent = "Erro ao conectar com o servidor.";
      }
    });