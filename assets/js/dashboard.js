const animateCount = (id, target) => {
    let count = 0;
    const speed = target / 60;
    const el = document.getElementById(id);
    const interval = setInterval(() => {
      count += speed;
      if (count >= target) {
        el.innerText = target;
        clearInterval(interval);
      } else {
        el.innerText = Math.floor(count);
      }
    }, 20);
  };

  animateCount('ganhos', 8500);
  animateCount('clientes', 120);
  animateCount('pedidos', 65);

  const ctx = document.getElementById('graficoGanhos').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
      datasets: [{
        label: 'Ganhos (R$)',
        data: [1200, 1900, 3000, 2500, 3200, 4500],
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              return `Total: R$ ${context.raw.toFixed(2)}`;
            }
          }
        }
      }
    }
  });

  function baixarRelatorio() {
    const texto = `Relatório de Ganhos\nTotal: R$ 8500.00\nClientes: 120\nPedidos: 65`;
    const blob = new Blob([texto], { type: 'text/plain' });
    const zip = new JSZip();
    zip.file("relatorio.txt", blob);
    zip.generateAsync({ type: "blob" }).then(function(content) {
      const a = document.createElement('a');
      a.href = URL.createObjectURL(content);
      a.download = "relatorio.zip";
      a.click();
    });
  }