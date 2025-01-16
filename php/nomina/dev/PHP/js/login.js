document.addEventListener("DOMContentLoaded", function() {
    var modal = document.getElementById("termsModal");
    var btn = document.getElementById("viewTerms");
    var span = document.getElementsByClassName("close")[0];
    var acceptBtn = document.getElementById("acceptBtn");
    var declineBtn = document.getElementById("declineBtn");
    var checkbox = document.getElementById("acceptTerms");

    // Cuando el usuario hace clic en "ver términos", abre el modal
    if (btn) {
        btn.onclick = function() {
            modal.style.display = "block";
            modal.style.color = "white";
            modal.style.textDecoration = "none";

        }
    }

    // Cuando el usuario hace clic en "x", cierra el modal
    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    // Cuando el usuario hace clic en "Aceptar", cierra el modal y marca el checkbox
    if (acceptBtn) {
        acceptBtn.onclick = function() {
            modal.style.display = "none";
            checkbox.checked = true;
            checkbox.disabled = false;
        }
    }

    // Cuando el usuario hace clic en "Declinar", cierra el modal
    if (declineBtn) {
        declineBtn.onclick = function() {
            modal.style.display = "none";
        }
    }

    // Cuando el usuario hace clic fuera del modal, cierra el modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});

