<?php
require_once('php/bdconexion.php');
require_once('php/funciones.php');

// Petición para obtener los productos en ofertas

$peticion = "
    SELECT productos.id AS idproducto, productos.subcategoria AS subcategoria, productos.nombre AS nombre,
    productos.precio AS precio,
    productos.contenido AS contenido,
    productos.um AS um,
    productos.stock AS stock,
    productos_ofertas.precio_oferta AS oferta,
    productos_ofertas.estado AS estado
    FROM productos
    LEFT JOIN productos_ofertas ON productos.id = productos_ofertas.id_producto
    WHERE estado = 'Activado'
    LIMIT 8
    ";
$resultado = $conn->query($peticion);

// Petición para obtener los productos agregados recientemente

$consultaRecientemente = "SELECT * FROM productos WHERE stock > 0 ORDER BY id DESC LIMIT 6";
$resultadoRecientemente = $conn->query($consultaRecientemente);

// Petición para mostrar las categorías en  el menú

$mostrarCategorias = "SELECT * FROM categorias";
$resultadoCategorias = $conn->query($mostrarCategorias);
?>

<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='UTF-8'/>
    <meta name='robots' content='index'/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hipermercado a tu medida | Merkar</title>
    <link rel='stylesheet' href='assets/css/app.css'>
    <link href='https://fonts.googleapis.com/css2?family=Pacifico&family=Rubik:ital,wght@0,400;0,500;1,400&display=swap'
          rel='stylesheet'>
</head>

<body class='hiddenX relative'>
<div id='contenedor'>
    <!-- Header -->
    <header class='flex space-between w-100 bg-white centerX centerY pd-1 row nowrap sticky index-1 bb-1-lightgray'>
        <div class='logo'>
            <h1 class='logo-merkar purple pacifico weight-n size-2p4'>merkar</h1>
            <p class='slogan purple rubik style-i size-p65'>Hipermercado a tu medida</p>
        </div>
        <div class='menu ml-1'>
            <ul class='no-style flex flex-end nowrap'>
                <li class='pd-p8'><img class='img-menu opacity-p6' src='assets/img/wishlist.svg' alt='Imagen'></li>
                <li class='pd-p8'><img class='img-menu opacity-p6' src='assets/img/user.svg' alt='Imagen'></li>
                <li class='pd-p8'><img class='img-menu opacity-p6' src='assets/img/cart.svg' alt='Imagen'></li>
            </ul>
        </div>
    </header>
    <!-- Barra de Búsqueda -->
    <div id='search-box' class='search-box rubik pd-1 bg-white bb-1-lightgray none'>
        <p class='flex'>
            <label for='search-input' class='sr-only'>¿Qué estás buscando?</label><input id='search-input'
                                                                                         class='search-input bd-lightgray br-p40 w-100 size-1  gray'
                                                                                         type='search'
                                                                                         placeholder='¿Qué estás buscando?'><span
                    id='close-search' class='close-search bg-gblue white pd-p3 br-p40 ml-p5 pointer'><i
                        class='fi-xnsux2-times-solid'></i></span></p>
        <p id='suggestion-box'></p>
    </div>
    <!-- Menú de Categorías y Sub-categorísas -->
    <nav id='menu-categorias' class='menu-categorias bg-white rubik sticky w-100 h-0 index-1 scrollY transition-p5'
         aria-label='Menú de categorías y subcategorías de productos.' aria-hidden='true'>
        <p id='close-btn' class='close-btn white pd-1 center size-1p2 pointer bg-gblue'>Cerrar <i
                    class='fi-xnsuxl-times-solid'></i></p>
        <ul>
            <?php while ($fila = $resultadoCategorias->fetch_assoc()) { ?>
                <li class='pdY-p5 pdX-1 bt-1-lightgray'>
                <a id='menu<?php echo $fila['id'] ?>' class='color-in decoration-in flex' href='#'
                   onclick='subMenu(this)'><?php echo $fila['nombre'];
                // Petición para verificar si existen subcategorias
                $contarSubcategorias = " SELECT COUNT(*) AS total FROM subcategorias WHERE id_categoria = '" . $fila['id'] . "' ";
                $resultadoSubcategorias = $conn->query($contarSubcategorias);

                while ($fila2 = $resultadoSubcategorias->fetch_assoc()) {
                    if ($fila2['total'] > 0) { ?>
                        <span class='menu-arrow flex mx-1 br-50 ml-auto'><i class='fas fa-angle-down'></i></span></a>
                        <ul id='submenu<?php echo $fila['id'] ?>' class='no-style mg-p5 none'>
                            <?php
                            // Petición para mostrar las subcategorías en el menú
                            $mostrarSubcategorias = " SELECT * FROM subcategorias WHERE id_categoria = '" . $fila['id'] . "' ";
                            $consultaSubcategorias = $conn->query($mostrarSubcategorias);
                            while ($fila3 = $consultaSubcategorias->fetch_assoc()) { ?>
                                <li class='pdX-1 pdY-p7 bl-1-lightgray'><?php echo $fila3['nombre'] ?></li>
                            <?php } ?>
                        </ul>
                        </li>
                    <?php } else {
                        echo "</a> </li>" ?>
                    <?php }
                }
            } ?>
        </ul>
    </nav>
    <!-- Main -->
    <main class='w-100 bg-white pd-1'>
        <!-- Sección: En Ofertas -->
        <section class='rubik'>
            <div class='flex row wrap'>
                <h2 class='section-title basis-70 weight-n pd-p5'>En Oferta</h2>
                <button class='purple-btn bd-0 mas-productos'>Más productos</button>
            </div>
            <div class='flex row wrap justify-center'>
                <?php while ($fila = $resultado->fetch_assoc()) {
                    // Petición para obtener las imagenes de productos
                    $peticion2 = "SELECT * FROM imagenes_productos WHERE id_producto = " . $fila['idproducto'] . " LIMIT 1";
                    $resultado2 = $conn->query($peticion2); ?>
                    <article class='producto relative pd-p5 mg-p4 br-p5 bg-white bd-lightgray'>
                        <div class='discounted absolute rubik weight-b white pd-p5 br-50 bg-purple'><?php echo round(((($fila['precio'] - $fila['oferta']) * 100) / $fila['precio'])) ?>
                            %
                        </div>
                        <?php while ($fila2 = $resultado2->fetch_assoc()) { ?>
                            <picture>
                                <source srcset='assets/photo/<?php echo $fila2['imagen'] ?>' type='image/webp'>
                                <img src='img/imagen.jpg' alt='Imagen'>
                            </picture>
                        <?php } ?>
                        <p class='gray height-2 size-p75'><?php echo $fila['subcategoria'] ?></p>
                        <h3 class='wbreak height-3p7 size-p9 weight-n'><?php echo $fila['nombre'] ?>
                            x <?php echo $fila['contenido'] . $fila['um'] ?></h3>
                        <p class='center weight-b purple size-p75 pdY-p8'>Ahorra
                            $<?php echo number_format(($fila['precio'] - $fila['oferta']), 0, ",", ".") ?></p>
                        <p class='center purple weight-b'>
                            <span class='gray ahorro-item weight-n'>$<?php echo number_format($fila['precio'], 0, ",", ".") ?></span>
                            $<?php echo number_format($fila['oferta'], 0, ",", ".") ?>
                        </p>
                        <p class='gray center size-p75 pdY-p6'>Gramo a
                            $<?php echo number_format(($fila['precio'] / $fila['contenido']), 2, ",", ".") ?></p>
                        <p class='center'><label for='oferta<?= $fila['idproducto'] ?>' class='sr-only'>Cantidad de
                                artículos:</label><input id='oferta<?= $fila['idproducto'] ?>'
                                                         class='cantidad-productos bd-0 bg-lightgray w-40 pd-p7 '
                                                         type='number' value='1' min=1 max=5>
                            <button value='oferta<?php echo $fila['idproducto'] ?>'
                                    class='add-to-cart bd-0  pd-p7 bg-purple white' aria-label='Agregar al carrito'><i
                                        class='fas fa-cart-plus fa-lg'></i></button>
                        </p>
                    </article>
                <?php } ?>
            </div>
        </section>
        <!-- Sección: Agregados recientemente -->
        <section class='rubik mt-1'>
            <div class='flex row wrap'>
                <h2 class='section-title basis-70 weight-n pd-p5'>Agregados recientemente</h2>
                <button class='purple-btn bd-0 mas-productos'>Más productos</button>
            </div>
            <div class='flex row wrap justify-center'>
                <?php while ($fila = $resultadoRecientemente->fetch_assoc()) {

                    // Petición para obtener las imagenes de productos
                    $peticion2 = "SELECT * FROM imagenes_productos WHERE id_producto = " . $fila['id'] . " LIMIT 1";
                    $resultado2 = $conn->query($peticion2); ?>
                    <article class='producto relative pd-p5 mg-p4 br-p5 bg-white bd-lightgray'>
                        <?php while ($fila2 = $resultado2->fetch_assoc()) { ?>
                            <picture>
                                <source srcset='assets/photo/<?php echo $fila2['imagen'] ?>' type='image/webp'>
                                <img src='img/imagen.jpg' alt='Imagen'>
                            </picture>
                        <?php } ?>
                        <p class='gray height-2 size-p75'><?php echo $fila['subcategoria'] ?></p>
                        <h3 class='wbreak height-3p7 size-p9 weight-n'><?php echo $fila['nombre'] ?>
                            x <?php echo $fila['contenido'] . $fila['um'] ?></h3>
                        <p class='center weight-b purple size-p75 pdY-p8'></p>
                        <p class='center purple weight-b'>
                            $<?php echo number_format($fila['precio'], 0, ",", ".") ?>
                        </p>
                        <p class='gray center size-p75 pdY-p6'>Gramo a
                            $<?php echo number_format(($fila['precio'] / $fila['contenido']), 2, ",", ".") ?></p>
                        <p class='center'><label for='cantidad-productos<?= $fila['id'] ?>' class='sr-only'>Cantidad de
                                artículos:</label><input id='cantidad-productos<?= $fila['id'] ?>'
                                                         class='cantidad-productos bd-0 bg-lightgray w-40 pd-p7 '
                                                         type='number' value='1' min=1 max=5>
                            <button value='<?php echo $fila['id'] ?>' class='add-to-cart bd-0  pd-p7 bg-purple white'
                                    aria-label='Agregar al carrito'><i class='fas fa-cart-plus fa-lg'></i></button>
                        </p>
                    </article>
                <?php } ?>
            </div>
        </section>
    </main>
    <!-- Navegación Inferior (WhatsApp, Categorías y Búsqueda) -->
    <nav class='atajos bg-white w-100 pointer'
         aria-label='Menú inferior para acceder a WhatsApp, Categorías y Barra de Búsqueda.'>
        <ul class='flex no-style space-around center rubik gray'>
            <li class='enlace-atajos flex-1 pdY-p6 size-p75 bt-1-lightgray br-1-lightgray'>
                <a class='color-in decoration-in'
                   href='https://api.whatsapp.com/send?phone=573155528177&text=%c2%a1Hola%21%20Me%20gustar%c3%ada%20Merkar%20%f0%9f%8d%b1&source=&data=&app_absent='
                   target='_blank' aria-label='Ir al chat de WhatsApp en una ventana nueva.'>
                    <i class="fab fa-whatsapp fa-2x" aria-label='WhatsApp'></i>
                </a>
            </li>
            <li id='btn-categorias' class='enlace-atajos flex-1 pdY-p6 size-p75 bt-1-lightgray br-1-lightgray'>
                <a class='color-in decoration-in' href='#' aria-label='Expandir o contraer el menú de categorías.'>
                    <i class="fi-xwlux2-three-bars-wide" aria-label='Menú de categorías'></i>
                </a>
            </li>
            <li id='btn-busqueda' class='enlace-atajos flex-1 pdY-p6 size-p75 bt-1-lightgray'>
                <a class='color-in decoration-in' href='#'
                   aria-label='Mostrar u ocultar la barra de búsqueda de productos.'>
                    <i class="fi-xwlux2-magnifying-glass-wide" aria-label='Barra de Búsqueda'></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- Footer -->
    <footer class='pdX-1 bg-white rubik size-p9 w-100 bt-1-lightgray'>
        <ul class='flex no-style center row wrap'>
            <li class='menu-items pdY-1'>Preguntas frecuentes</li>
            <li class='menu-items pdY-1'>Contáctanos</li>
            <li class='menu-items pdY-1'>Términos y Condiciones</li>
            <li class='menu-items pdY-1'>Privacidad</li>
            <li class='menu-items pdY-1'>Acerca de Nosotros</li>
        </ul>
    </footer>
</div>
<!-- Scripts -->
<script defer src="assets/js/app.js"></script>
<script defer src="assets/js/jquery.js"></script>
<script defer src="https://kit.fontawesome.com/c094c4f2ad.js" crossorigin="anonymous"></script>
<script defer src="https://friconix.com/cdn/friconix.js"></script>
</body>

</html>
