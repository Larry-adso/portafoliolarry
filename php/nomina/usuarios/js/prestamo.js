document.addEventListener('DOMContentLoaded', (event) => {
    function actualizarFechaHora() {
      const campoFecha = document.getElementById('fechaActual');
      const ahora = new Date();
      
      // Formatear fecha y hora para que se ajuste al valor esperado por input[type="datetime-local"]
      const year = ahora.getFullYear();
      const month = String(ahora.getMonth() + 1).padStart(2, '0');
      const day = String(ahora.getDate()).padStart(2, '0');
      const hours = String(ahora.getHours()).padStart(2, '0');
      const minutes = String(ahora.getMinutes()).padStart(2, '0');
      const seconds = String(ahora.getSeconds()).padStart(2, '0');

      const fechaHoraActual = `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;

      campoFecha.value = fechaHoraActual;
    }

    // Actualizar la fecha y hora inmediatamente y luego cada segundo
    actualizarFechaHora();
    setInterval(actualizarFechaHora, 1000);
  });


