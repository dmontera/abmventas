<?php
include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/producto.php";
include_once "entidades/cliente.php";

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
}

if ($_POST) {

    if (isset($_POST["btnCerrar"])) {
        session_destroy();
        header("location:login.php");
    }
}

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

$producto = new Producto();
$array_productos = $producto->obtenerTodos();

$cliente = new Cliente();
$array_clientes = $cliente->obtenerTodos();

if ($_POST) {
    $msg = "Venta guardada correctamente";
    $msg2 = "Venta borrada correctamente";
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {  
            $venta->actualizar();
        } else {
            $venta->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        $venta->eliminar();
    }
}

if (isset($_GET["do"]) && $_GET["do"] == "buscarProducto" && $_GET["id"] && $_GET["id"] > 0) {
    $idProducto = $_GET["id"];
    $producto = new Producto();
    $producto->idproducto = $idProducto;
    $producto->obtenerPorId();
    echo json_encode($producto->precio);
    exit;
} else if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $venta->obtenerPorId();
}

include_once "header.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta formulario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row pb-3">
            <div class="col-12">
                <h1>Ventas</h1>
            </div>
        </div>
        <form action="" method="POST">
            <?php if (isset($_POST["btnGuardar"])) { ?>
                <div class="alert alert-success">
                    <?php echo $msg; ?>
                </div>
            <?php } else if (isset($_POST["btnBorrar"])) { ?>
                <div class="alert alert-success">
                    <?php echo $msg2; ?>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-12">
                    <a href="listado-venta.php" class="btn btn-info mr-1">Listado</a>
                    <a href="venta-formulario.php" class="btn btn-primary mr-1">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-1" id="btnGuardar" name="btnGuardar" onclick="return confirmacion()">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar" onclick="return confirmacionBorrar()">Borrar</button>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6 form-group">
                    <label for="txtFecha">Fecha:</label>
                    <input type="date" name="txtFecha" id="txtFecha" class="form-control" required value="<?php echo date_format(date_create($venta->fecha), "Y-m-d"); ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtHora">Hora:</label>
                    <input type="time" name="txtHora" id="txtHora" class="form-control" required value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6 form-group">
                    <label for="lstCliente">Cliente:</label>
                    <select name="lstCliente" id="lstCliente" class="form-control">
                        <option value disabled selected>Seleccionar</option>
                        <?php foreach ($array_clientes as $cliente) : ?>
                            <?php if ($venta->fk_idcliente == $cliente->idcliente) : ?>
                                <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 form-group">
                    <label for="lstProducto">Producto:</label>
                    <select name="lstProducto" id="lstProducto" class="form-control" onchange="fBuscarPrecio();">
                        <option value disabled selected>Seleccionar</option>
                        <?php foreach ($array_productos as $producto) : ?>
                            <?php if ($venta->fk_idproducto == $producto->idproducto) : ?>
                                <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6 form-group">
                    <label for="txtPrecioUnitario">Precio unitario:</label>
                    <input type="text" name="txtPrecioUnitario" id="txtPrecioUnitario" class="form-control" required value="<?php echo $venta->preciounitario ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtCantidad">Cantidad:</label>
                    <input type="text" name="txtCantidad" id="txtCantidad" class="form-control" required onchange="fCalcularTotal();" value="<?php echo $venta->cantidad ?>">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6 form-group">
                    <label for="txtPrecioTotal">Total:</label>
                    <input type="text" name="txtPrecioTotal" id="txtPrecioTotal" class="form-control" required value="<?php echo $venta->preciototal ?>">
                </div>
            </div>
        </form>
    </div>

    <?php include_once("footer.php") ?>

    <script>
        function fBuscarPrecio() {
            var idProducto = $("#lstProducto option:selected").val();
            $.ajax({
                type: "GET",
                url: "venta-formulario.php?do=buscarProducto",
                data: {
                    id: idProducto
                },
                async: true,
                dataType: "json",
                success: function(respuesta) {
                    $("#txtPrecioUnitario").val(respuesta);
                }
            });

        }

        function fCalcularTotal() {
            var precio = $('#txtPrecioUnitario').val();
            var cantidad = $('#txtCantidad').val();
            var resultado = precio * cantidad;
            $("#txtPrecioTotal").val(resultado);

        }

        function confirmacion() {
            seguro = confirm("¿Esta seguro/a que desea guardar los datos?")

            if (seguro == true) {
                return true;
            } else {
                return false;
            }
        }

        function confirmacionBorrar() {
            borrar = confirm("¿Esta seguro/a que desea borrar los datos?")

            if (borrar == true) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>

</html>