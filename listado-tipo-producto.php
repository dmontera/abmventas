<?php

include_once "config.php";
include_once "entidades/tipo-producto.php";

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
}

if ($_POST) {

    if (isset($_POST["btnCerrar"])) {
        session_destroy();
        header("location:login.php");
    }
}

$tipoProducto = new TipoProducto();
$array_tipo_producto = $tipoProducto->obtenerTodos(); 

include_once "header.php";


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado tipo de productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
<form action="" method="">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1>Tipos de producto</h1>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a href="tipo-producto-formulario.php" class="btn btn-primary">Nuevo</a>
                </div>
            </div>

            <table class="table table-hover border">
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>

                <?php foreach ($array_tipo_producto as $tipoProducto) { ?>
                    <tr>
                        <td><?php echo $tipoProducto->nombre; ?></td>
                        <td><a href="tipo-producto-formulario.php?id=<?php echo $tipoProducto->idtipoproducto;?>"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php }; ?>

            </table>

        </div>
    </form>
    <?php include_once "footer.php"; ?>
</body>

</html>