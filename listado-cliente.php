<?php 

include_once "config.php";
include_once "entidades/cliente.php";

$cliente = new Cliente();
$array_clientes = $cliente->obtenerTodos();

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
    <title>Listado de clientes</title> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
<form action="" method="">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h1>clientes</h1>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a href="cliente-formulario.php" class="btn btn-primary">Nuevo</a>
                </div>
            </div>

            <table class="table table-hover border">
                <tr>
                    <th>Nombre</th>
                    <th>Cuit</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Fecha de nacimiento</th>
                    <th>Acciones</th>
                </tr>

                <?php foreach ($array_clientes as $cliente) { ?>
                    <tr>
                        <td><?php echo $cliente->nombre;?></td>
                        <td><?php echo $cliente->cuit;?></td>
                        <td><?php echo $cliente->telefono; ?></td>
                        <td><?php echo $cliente->correo;?></td>
                        <td><?php echo date_format(date_create($cliente->fecha_nac), "d/m/Y");?></td>   
                        <td><a href="cliente-formulario.php?id=<?php echo $cliente->idcliente;?>"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php } ?>

            </table>

        </div>
    </form>
<?php include_once "footer.php";?>   
</body>
</html>