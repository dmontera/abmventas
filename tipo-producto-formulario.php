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
$tipoProducto->cargarFormulario($_REQUEST);

if ($_POST) {
    $msg = "Tipo de producto guardado correctamente";
    $msg2 = "Tipo de producto borrado correctamente";
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            header("location:tipo-producto-formulario.php");
            $tipoProducto->actualizar();
        } else {
            header("location:tipo-producto-formulario.php");
            $tipoProducto->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        header("location:tipo-producto-formulario.php");
        $tipoProducto->eliminar();
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $tipoProducto->obtenerPorId();
}

include_once "header.php";

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipo de productos formulario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row pb-3">
            <div class="col-12">
                <h1>Productos</h1>
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
                    <a href="listado-tipo-producto.php" class="btn btn-info mr-1">Listado</a>
                    <a href="tipo-producto-formulario.php" class="btn btn-primary mr-1">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-1" id="btnGuardar" name="btnGuardar" onclick="return confirmacion()">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar" onclick="return confirmacionBorrar()">Borrar</button>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-12 form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" name="txtNombre" id="txtNombre" class="form-control" value="<?php echo $tipoProducto->nombre;?>">
                </div>
            </div>
        </form>
    </div>

    <?php include_once "footer.php"; ?>
    <script>
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