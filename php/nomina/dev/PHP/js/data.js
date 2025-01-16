document.addEventListener("DOMContentLoaded", function() {
    var menuToggle = document.getElementById("menu_toggle");
    var menuClose = document.getElementById("menu_close");
    var menuSide = document.getElementById("menu_side");
    var body = document.getElementById("body");

    // Evento para mostrar/ocultar el menú al hacer clic en el icono de menú hamburguesa
    menuToggle.addEventListener("click", function() {
        body.classList.toggle("body_move");
        menuSide.classList.toggle("menu__side_move");
    });

    // Evento para cerrar el menú al hacer clic en el icono de cerrar
    menuClose.addEventListener("click", function() {
        body.classList.remove("body_move");
        menuSide.classList.remove("menu__side_move");
    });

    var menuOptions = document.querySelectorAll(".options__menu a");
    menuOptions.forEach(function(option) {
        option.addEventListener("mouseenter", open_menu);
        option.addEventListener("mouseleave", close_menu);
    });

    function open_menu() {
        if (window.innerWidth > 760) {
            body.classList.add("body_move");
            menuSide.classList.add("menu__side_move");
        }
    }

    function close_menu() {
        if (window.innerWidth > 760) {
            body.classList.remove("body_move");
            menuSide.classList.remove("menu__side_move");
        }
    }

    // Si el ancho de la página es menor a 760px, ocultará el menú al recargar la página
    if (window.innerWidth < 760) {
        body.classList.add("body_move");
        menuSide.classList.add("menu__side_move");
    }

    // Haciendo el menú responsive (adaptable)
    window.addEventListener("resize", function() {
        if (window.innerWidth > 760) {
            body.classList.remove("body_move");
            menuSide.classList.remove("menu__side_move");
        }
        if (window.innerWidth < 760) {
            body.classList.add("body_move");
            menuSide.classList.add("menu__side_move");
        }
    });
});
