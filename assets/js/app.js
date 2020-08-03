'use strict'

// Iniciar funciones al cargar documento
window.onload = function () {
    mostrarCategorias();
    mostrarBusqueda();
    cerrarMenu();
    cerrarBusqueda();
    gestionarBusqueda();
}

// Función para mostrar el menú de categorías

var contador = 0;

function mostrarCategorias() {
    document.getElementById('btn-categorias').addEventListener('click', function () {
        let menu = document.getElementById('menu-categorias');
        if (contador == 0) {
            menu.classList.add('h-100vh', 'pb-2p7');
            contador += 1;
        } else {
            menu.classList.remove('h-100vh', 'pb-2p7');
            menu.display = 'none';
            contador -= 1;
        }
    });
}

// Función para cerrar el menú de categorías desde el botón Cerrar

function cerrarMenu() {
    document.getElementById('close-btn').addEventListener('click', function () {
        let menu = document.getElementById('menu-categorias');
        menu.classList.remove('h-100vh', 'pb-2p7');
        menu.display = 'none';
        contador -= 1;
    });
}

// Función para mostrar/ocultar el submenú de categorías

function subMenu(event) {
    let id = event.id;
    let submenu = document.getElementById('sub' + id).classList;
    if (submenu.contains('none')) {
        submenu.remove('none');
        submenu.add('block');
    } else {
        submenu.remove('block');
        submenu.add('none');
    }
}

// Función para mostrar/ocultr la barra de búsqueda

var contadorBusqueda = 0;

function mostrarBusqueda() {
    let busqueda = document.getElementById('btn-busqueda');
    busqueda.addEventListener('click', function () {

        let menu = document.getElementById('search-box');
        if (contadorBusqueda == 0) {
            menu.classList.remove('none');
            contadorBusqueda += 1;
        } else {
            menu.classList.add('none');
            contadorBusqueda -= 1;
        }

    });

}

// Función para cerrar la barra de búsqueda con botón Cerrar

function cerrarBusqueda() {
    document.getElementById('close-search').addEventListener('click', function () {
        let menu = document.getElementById('search-box');
        menu.classList.add('none');
        contadorBusqueda -= 1;
    });
}


// Función para buscar resultados en la base de datos y recuperarlos en la barra de búsqueda - AJAX 

function gestionarBusqueda() {
    $('#search-input').keyup(function () {
        $.ajax({
            type: "POST",
            url: "php/busqueda.php",
            data: 'keyword=' + $(this).val(),
            success: function (data) {
                $("#suggestion-box").show();
                $("#suggestion-box").html(data);
            }
        });
    });
}

// Función para mostrar resultados en la barra de búsqueda - AJAX 

function seleccionarBusqueda(val) {
    $("#search-input").val(val);
    $("#suggestion-box").hide();
}

// Función para mostrar resultados en la barra de búsqueda - AJAX

