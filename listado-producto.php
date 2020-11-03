<?php

include_once "config.php";
include_once "entidades/producto.php";


$producto = new Producto();
$array_productos = $producto->obtenerTodos();
//print_r($array_productos);

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
}

if ($_POST) {

    if (isset($_POST["btnCerrar"])) {
        session_destroy();
        header("location:login.php");
    }
}


include_once "header.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de productos</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <form action="" method="">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1>Listado de productos</h1>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a href="producto-formulario.php" class="btn btn-primary">Nuevo</a>
                </div>
            </div>

            <table class="table table-hover border">
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>

                <?php foreach ($array_productos as $producto) { ?>
                    <tr>
                        <td><img src="archivos/<?php echo $producto->imagen ?>" class="img-thumbnail" id="foto"></td>
                        <td><?php echo $producto->nombre; ?></td>
                        <td><?php echo $producto->cantidad; ?></td>
                        <td>$<?php echo number_format($producto->precio, 2, ",",".");?></td>
                        <td><a href="producto-formulario.php?id=<?php echo $producto->idproducto;?>"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php }; ?>

            </table>

        </div>
    </form>

    <?php include_once "footer.php"; ?>
</body>

</html>