const temaSelect = document.getElementById('tema');

    function aplicarTema(tema) {
      document.body.classList.remove('tema-claro', 'tema-escuro');
      if (tema === 'claro') {
        document.body.classList.add('tema-claro');
      } else if (tema === 'escuro') {
        document.body.classList.add('tema-escuro');
      }
    }

    // Carregar tema salvo
    const temaSalvo = localStorage.getItem('tema');
    if (temaSalvo) {
      temaSelect.value = temaSalvo;
      aplicarTema(temaSalvo);
    }

    temaSelect.addEventListener('change', () => {
      const temaSelecionado = temaSelect.value;
      localStorage.setItem('tema', temaSelecionado);
      aplicarTema(temaSelecionado);
    });