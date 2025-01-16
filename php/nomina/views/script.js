// script.js
document.addEventListener('DOMContentLoaded', function() {
    const burger = document.getElementById('burger');
    const menus = document.querySelectorAll('.menu');

    burger.addEventListener('click', function() {
        menus.forEach(menu => menu.classList.toggle('active'));
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            menus.forEach(menu => menu.classList.remove('active'));
        }
    });
});
