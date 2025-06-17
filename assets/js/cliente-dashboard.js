  function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("collapsed");
            // Trigger chart resize after sidebar toggle
            setTimeout(() => {
                if (window.gastosChart) {
                    window.gastosChart.resize();
                }
            }, 300); // Match the transition duration
        }

        function sair() {
            alert("Logout realizado!");
        }

        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("gastosChart").getContext("2d");
            if (!ctx) {
                console.error("Elemento canvas não encontrado!");
                return;
            }

            window.gastosChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
                    datasets: [
                        {
                            label: "Eventos",
                            data: [2000, 2500, 3000, 2800, 3500, 4000, 4200, 3800, 3600, 3400, 3900, 4100],
                            backgroundColor: "#3b82f6",
                            borderColor: "#2563eb",
                            borderWidth: 1
                        },
                        {
                            label: "Orçamentos",
                            data: [1500, 1800, 2200, 2000, 2500, 3000, 3200, 2800, 2600, 2400, 2900, 3100],
                            backgroundColor: "#f87171",
                            borderColor: "#ef4444",
                            borderWidth: "1"
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { ticks: { color: "#e5e7eb" } },
                        y: { 
                            beginAtZero: true, 
                            ticks: { color: "#e5e7eb" },
                            suggestedMax: 5000
                        }
                    },
                    plugins: {
                        legend: { labels: { color: "#e5e7eb" } }
                    }
                }
            });

            // Handle window resize and zoom changes
            window.addEventListener('resize', () => {
                if (window.gastosChart) {
                    window.gastosChart.resize();
                }
            });
        });
