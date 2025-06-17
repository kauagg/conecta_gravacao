

    const clienteEmail = "cliente@exemplo.com";

    let eventos = [
      {
        idEvento: 1,
        nome_evento: "Festa de Aniversário",
        data_evento: "2025-05-25T18:00",
        status_evento: "confirmado",
        descricao_evento: "Celebração especial com amigos e familiares.",
        FKemail_cliente: clienteEmail
      },
      {
        idEvento: 2,
        nome_evento: "Reunião Corporativa",
        data_evento: "2025-06-10T14:00",
        status_evento: "cancelado",
        descricao_evento: "Reunião anual sobre objetivos da empresa.",
        FKemail_cliente: clienteEmail
      },
      {
        idEvento: 3,
        nome_evento: "Workshop de Tecnologia",
        data_evento: "2025-07-15T09:00",
        status_evento: "pendente",
        descricao_evento: "Discussões sobre inovações tecnológicas.",
        FKemail_cliente: clienteEmail
      }
    ];

    let nextId = 4;
    let editingEventId = null;

    document.addEventListener('DOMContentLoaded', function() {
      loadEvents();
    });

    function loadEvents() {
      const eventList = document.getElementById('eventList');
      const clienteEventos = eventos.filter(evento => evento.FKemail_cliente === clienteEmail);
      
      if (clienteEventos.length === 0) {
        eventList.innerHTML = `
          <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>Nenhum evento encontrado</h3>
            <p>Comece criando seu primeiro evento clicando no botão "Novo Evento"</p>
          </div>
        `;
        return;
      }

      eventList.innerHTML = clienteEventos.map(evento => `
        <article class="event-card" data-event-id="${evento.idEvento}">
          <div class="event-status ${evento.status_evento}">${capitalizeFirst(evento.status_evento)}</div>
          <div class="event-title">${evento.nome_evento}</div>
          <div class="event-date">
            <i class="fas fa-calendar"></i>
            ${formatDateTime(evento.data_evento)}
          </div>
          <div class="event-description">${evento.descricao_evento}</div>
          <div class="event-actions">
            <button class="btn-action btn-edit" onclick="editEvent(${evento.idEvento})">
              <i class="fas fa-edit"></i> Editar
            </button>
            <button class="btn-action btn-delete" onclick="deleteEvent(${evento.idEvento})">
              <i class="fas fa-trash"></i> Excluir
            </button>
          </div>
        </article>
      `).join('');
    }

    function openEventModal(eventId = null) {
      const modal = new bootstrap.Modal(document.getElementById('eventModal'));
      const modalTitle = document.getElementById('eventModalLabel');
      const form = document.getElementById('eventForm');
      
      form.reset();
      editingEventId = eventId;

      if (eventId) {
        const evento = eventos.find(e => e.idEvento === eventId);
        if (evento) {
          modalTitle.innerHTML = '<i class="fas fa-edit"></i> Editar Evento';
          document.getElementById('eventId').value = evento.idEvento;
          document.getElementById('nomeEvento').value = evento.nome_evento;
          document.getElementById('dataEvento').value = evento.data_evento;
          document.getElementById('statusEvento').value = evento.status_evento;
          document.getElementById('descricaoEvento').value = evento.descricao_evento;
        }
      } else {
        modalTitle.innerHTML = '<i class="fas fa-calendar-plus"></i> Novo Evento';
        document.getElementById('eventId').value = '';
      }

      modal.show();
    }

    function saveEvent() {
      const form = document.getElementById('eventForm');
      
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const eventData = {
        nome_evento: document.getElementById('nomeEvento').value.trim(),
        data_evento: document.getElementById('dataEvento').value,
        status_evento: document.getElementById('statusEvento').value,
        descricao_evento: document.getElementById('descricaoEvento').value.trim(),
        FKemail_cliente: clienteEmail
      };

      if (editingEventId) {
        const index = eventos.findIndex(e => e.idEvento === editingEventId);
        if (index !== -1) {
          eventos[index] = { ...eventos[index], ...eventData };
          showNotification('Evento atualizado com sucesso!', 'success');
        }
      } else {
        const newEvent = {
          idEvento: nextId++,
          ...eventData
        };
        eventos.push(newEvent);
        showNotification('Evento criado com sucesso!', 'success');
      }

      const modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
      modal.hide();
      loadEvents();
    }

    function editEvent(eventId) {
      openEventModal(eventId);
    }

    function deleteEvent(eventId) {
      const evento = eventos.find(e => e.idEvento === eventId);
      if (!evento) return;

      if (confirm(`Tem certeza que deseja excluir o evento "${evento.nome_evento}"?`)) {
        eventos = eventos.filter(e => e.idEvento !== eventId);
        showNotification('Evento excluído com sucesso!', 'success');
        loadEvents();
      }
    }

    function formatDateTime(dateTimeString) {
      const date = new Date(dateTimeString);
      return date.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    function capitalizeFirst(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.className = `alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
      notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;

      document.body.appendChild(notification);

      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove();
        }
      }, 3000);
    }

    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("collapsed");
      document.getElementById("content").classList.toggle("collapsed");
    }

    function sair() {
      if (confirm("Tem certeza que deseja sair?")) {
        alert("Logout realizado!");
      }
    }

    document.addEventListener('keydown', function(e) {
      if (e.ctrlKey && e.key === 'n') {
        e.preventDefault();
        openEventModal();
      }
    });