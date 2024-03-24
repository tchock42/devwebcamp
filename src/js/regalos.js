(function () {

  const grafica = document.getElementById('regalos-grafica');
  if (grafica) {

    obtenerDatos();

    async function obtenerDatos() {
      const url = `${location.origin}/api/regalos`;
      const respuesta = await fetch(url);
      const resultado = await respuesta.json();

      
      new Chart(grafica, { //crea instancia Chart
        type: 'bar', //grafica tipo barra 
        data: {
          labels: resultado.map(regalo => regalo.nombre),
          datasets: [{
            data: resultado.map(regalo => regalo.total),
            backgroundColor: [
              '#ea580c', '#84cc16', '#22d3ee', '#a855f7', '#ef4444', '#14b8a6', '#db2777', '#e11d48', '#7e22ce'
            ]
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    }

  }
})();