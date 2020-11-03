<?php

include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/producto.php";
include_once "entidades/cliente.php";

$venta = new Venta();
$array_ventas = $venta->cargarGrilla();

$producto = new Producto();
$array_productos = $producto->obtenerTodos();

$cliente = new Cliente();
$array_clientes = $cliente->obtenerTodos();

include_once "header.php"
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado ventas</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<form action="" method="">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1>Ventas</h1>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a href="venta-formulario.php" class="btn btn-primary">Nuevo</a>
                </div>
            </div>

            <table class="table table-hover border">
                <tr>
                    <th>Fecha</th>
                    <th>Cantidad</th>
                    <th>Producto</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>

                <?php foreach ($array_ventas as $venta) { ?>
                    <tr>
                    <td><?php echo date_format(date_create($venta->fecha), "d/m/Y H:i"); ?></td>
                        <td><?php echo $venta->cantidad;?></td>
                        <td><?php echo $venta->nombre_producto;?></td>
                        <td><?php echo $venta->nombre_cliente;?></td>
                        <td>$<?php echo number_format($venta->preciototal, 2, ",",".");?></td>
                        <td><a href="venta-formulario.php?id=<?php echo $venta->idventa;?>"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php } ?>
            </table>

        </div>
    </form>


<?php include_once "footer.php"?>    
</body>
</html>