const form = document.getElementById("formEvento");
const lista = document.getElementById("listaEventos");


form.addEventListener("submit", async (e) => {
  e.preventDefault();
  const nome = document.getElementById("nomeEvento").value;
  const status = document.getElementById("statusEvento").value;

  const resposta = await fetch("adicionar-evento.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nome, status })
  });

  const resultado = await resposta.json();

  if (resposta.ok) {
    form.reset();
    carregarEventos();
  } else {
    alert(resultado.erro || "Erro ao adicionar evento.");
  }
});


async function carregarEventos() {
  const resposta = await fetch("listar-eventos.php");
  const eventos = await resposta.json();

  lista.innerHTML = "";

  eventos.forEach(evento => {
    const li = document.createElement("li");
    li.className = `list-group-item d-flex justify-content-between align-items-center list-group-item-${evento.status}`;
    li.innerHTML = `
      <span>${evento.nome}</span>
      <div>
        <button class="btn btn-sm btn-outline-secondary me-2" onclick="alterarStatus(${evento.id}, '${evento.status}')">
          Alterar Status
        </button>
        <button class="btn btn-sm btn-outline-danger" onclick="excluirEvento(${evento.id})">
          Excluir
        </button>
      </div>
    `;
    lista.appendChild(li);
  });
}

async function alterarStatus(id, statusAtual) {
  const novoStatus = statusAtual === "success" ? "warning" : "success";

  const resposta = await fetch("alterar-status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, status: novoStatus })
  });

  const resultado = await resposta.json();

  if (resposta.ok) {
    carregarEventos();
  } else {
    alert(resultado.erro || "Erro ao alterar status.");
  }
}

async function excluirEvento(id) {
  if (!confirm("Tem certeza que deseja excluir este evento?")) return;

  const resposta = await fetch("excluir-evento.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const resultado = await resposta.json();

  if (resposta.ok) {
    carregarEventos();
  } else {
    alert(resultado.erro || "Erro ao excluir evento.");
  }
}

carregarEventos();
