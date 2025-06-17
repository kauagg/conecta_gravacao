function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("collapsed");
      document.getElementById("content").classList.toggle("collapsed");
    }

    function sair() {
      alert("Logout realizado!");
    }

    const orcamentos = [
      { titulo: "Festa de Aniversário", data: "2025-06-12", valor: "R$ 1.500,00", status: "Aguardando aprovação" },
      { titulo: "Casamento", data: "2025-08-01", valor: "R$ 5.000,00", status: "Aprovado" },
      { titulo: "Evento Corporativo", data: "2025-09-20", valor: "R$ 3.200,00", status: "Recusado" }
    ];

    function renderOrcamentos() {
      const container = document.getElementById("listaOrcamentos");
      container.innerHTML = "";

      orcamentos.forEach(o => {
        const card = document.createElement("div");
        card.classList.add("orcamento-card");

        const statusClass = `status-${o.status.toLowerCase().replace(" ", "-")}`;
        
        card.innerHTML = `
          <div>
            <h5>${o.titulo}</h5>
            <small><strong>Data:</strong> ${o.data}</small>
            <small><strong>Valor:</strong> ${o.valor}</small>
            <small><strong>Status:</strong> <span class="status ${statusClass}">${o.status}</span></small>
          </div>
          <a href="#" class="btn-details">Detalhes</a>
        `;
        container.appendChild(card);
      });
    }

    document.addEventListener("DOMContentLoaded", renderOrcamentos);