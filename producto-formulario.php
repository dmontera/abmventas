<?php

include_once "config.php";
include_once "entidades/tipo-producto.php";
include_once "entidades/producto.php";

if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
}

if ($_POST) {

    if (isset($_POST["btnCerrar"])) {
        session_destroy();
        header("location:login.php");
    }
}

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

$tipoProducto = new TipoProducto();
$array_tipo_producto = $tipoProducto->obtenerTodos();

if ($_POST) {
    $msg = "Producto guardado correctamente";
    $msg2 = "Producto borrado correctamente";
    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
                $nombref = date("Ymdhmsi");
                $archivo_tmp = $_FILES["imagen"]["tmp_name"];
                $nombreArchivo = $_FILES["imagen"]["name"];
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nombreImagen = $nombref . "." . $extension;
                move_uploaded_file($archivo_tmp, "archivos/$nombreImagen");
                $producto->imagen = $nombreImagen;
            } else {
                $productoAux = new Producto();
                $productoAux->idproducto = $_GET["id"];
                $productoAux->obtenerPorId();
                $producto->imagen = $productoAux->imagen;
            }
         
            $producto->actualizar();
        } else {
            if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
                $nombref = date("Ymdhmsi");
                $archivo_tmp = $_FILES["imagen"]["tmp_name"];
                $nombreArchivo = $_FILES["imagen"]["name"];
                $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                $nombreImagen = $nombref . "." . $extension;
                move_uploaded_file($archivo_tmp, "archivos/$nombreImagen");
            }
            $producto->imagen = $nombreImagen;
            $producto->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        $producto->eliminar();
    }
}




if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $producto->obtenerPorId();
}

include_once "header.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto formulario</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row pb-3">
            <div class="col-12">
                <h1>Productos</h1>
            </div>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
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
                    <a href="listado-producto.php" class="btn btn-info mr-1">Listado</a>
                    <a href="producto-formulario.php" class="btn btn-primary mr-1">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-1" id="btnGuardar" name="btnGuardar" onclick="return confirmacion()">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar" onclick="return confirmacionBorrar()">Borrar</button>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-12 form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" name="txtNombre" id="txtNombre" class="form-control" required value="<?php echo $producto->nombre; ?>">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-6 form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" value="<?php echo $producto->$imagen; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="lstTipoProducto">Tipo de producto:</label>
                    <select name="lstTipoProducto" id="lstTipoProducto" class="form-control">
                        <option value disabled selected>Seleccionar</option>
                        <?php foreach ($array_tipo_producto as $tipo) : ?>
                            <?php if ($producto->fk_idtipoproducto == $tipo->idtipoproducto) : ?>
                                <option selected value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                            <?php else : ?>
                                <option value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-6 form-group">
                    <label for="txtCantidad">Cantidad:</label>
                    <input type="text" name="txtCantidad" id="txtCantidad" class="form-control" required value="<?php echo $producto->cantidad; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtPrecio">Precio:</label>
                    <input type="text" name="txtPrecio" id="txtPrecio" class="form-control" required value="<?php echo $producto->precio; ?>">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-12 form-group">
                    <label for="txtDescripcion">Descripción:</label>
                    <textarea type="text" name="txtDescripcion" id="txtDescripcion"><?php echo $producto->descripcion ?></textarea>
                </div>
            </div>
        </form>
    </div>

    <?php include_once("footer.php") ?>

    <script>
        ClassicEditor
            .create(document.querySelector('#txtDescripcion'))
            .catch(error => {
                console.error(error);
            });

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