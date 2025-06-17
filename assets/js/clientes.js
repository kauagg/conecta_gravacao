function abrirModal() {
  document.getElementById('modalCliente').style.display = 'flex';
}

function fecharModal() {
  document.getElementById('modalCliente').style.display = 'none';
}

function adicionarCliente() {
  const nome = document.getElementById('nome').value.trim();
  const email = document.getElementById('email').value.trim();
  const telefone = document.getElementById('telefone').value.trim();

  if (nome && email && telefone) {
    const container = document.getElementById('clientesContainer');
    const card = document.createElement('div');
    card.classList.add('cliente-card');
    card.innerHTML = `
      <button class="delete-btn" onclick="removerCliente(this)">🗑️</button>
      <strong>${nome}</strong>
      <p>Email: ${email}</p>
      <p>Telefone: ${telefone}</p>
    `;
    container.appendChild(card);

    document.getElementById('nome').value = '';
    document.getElementById('email').value = '';
    document.getElementById('telefone').value = '';
    fecharModal();
  } else {
    alert("Preencha todos os campos!");
  }
}

function removerCliente(botao) {
  const card = botao.parentElement;
  card.remove();
}

window.onclick = function(e) {
  const modal = document.getElementById('modalCliente');
  if (e.target === modal) fecharModal();
}