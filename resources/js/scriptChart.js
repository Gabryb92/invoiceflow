import Chart from "chart.js/auto";

document.addEventListener("livewire:init", () => {
    let chart = null; //Inizializziamo una variabile per contenere il grafico

    //Ascoltiamo l'evento updateChart che arriva dal componente
    Livewire.on("updateChart", (event) => {
        // Prendiamo il canvas
        const CTX = document.getElementById("revenueChart").getContext("2d");

        //Se il grafico esiste gia lo distruggiamo
        if (chart) {
            chart.destroy();
        }

        //Creiamo una nuova istanza di Chart.js:

        chart = new Chart(CTX, {
            type: "bar", //a barre
            data: {
                labels: event.data.labels, //Usiamo le etichette che riceviamo dal componente
                datasets: [
                    {
                        label: event.data.dataset_label,
                        data: event.data.data, //dati ricevuti dall'evento
                        backgroundColor: "rgba(96, 228, 142, 0.5)", //Colore delle barre,
                        borderColor: "rgba(96, 228, 142, 1)", // Colore del bordo
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "#fff", // Colore per l'asse Y (grigio chiaro)
                        },
                        grid: {
                            color: "#374151", // Colore delle linee della griglia
                        },
                    },
                    x: {
                        ticks: {
                            color: "#fff", // Colore per l'asse X (i mesi)
                        },
                        grid: {
                            color: "#374151", // Colore delle linee della griglia
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: "#fff", // Colore per la legenda (es. "Fatturato Mensile")
                        },
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    });
});
