<?php
require_once("bdconexion.php");
if( !empty( $_POST["keyword"] ) ) {
$peticion = "SELECT * FROM productos WHERE nombre LIKE '%" . $_POST["keyword"] . "%' ORDER BY nombre LIMIT 0,6";
$result = $conn->query($peticion);
if( !empty($result )) {
?>
<ul id="search-list">
<?php
foreach($result as $search) {
?>
<li onClick="seleccionarBusqueda('<?php echo $search["nombre"] . " x " . $search["contenido"] . $search["um"]; ?>');"><?php echo $search["nombre"] . " x " . $search["contenido"] . $search["um"]; ?></li>
<?php } ?>
</ul>
<?php } } ?>