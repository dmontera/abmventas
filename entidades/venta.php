<?php

class Venta {
    private $idventa;
    private $fk_idcliente;
    private $fk_idproducto;
    private $fecha;
    private $cantidad;
    private $preciounitario;
    private $preciototal;

    public function __construct(){
        $this->cantidad = 0;
        $this->preciounitario= 0.0;
        $this->preciototal= 0.0;

    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request){
        $this->idventa = isset($request["id"])? $request["id"] : "";
        $this->fk_idcliente = isset($request["lstCliente"])? $request["lstCliente"] : "";
        $this->fk_idproducto = isset($request["lstProducto"])? $request["lstProducto"]: "";
        $this->fecha = isset($request["txtFecha"])? $request["txtFecha"] . " " . $request["txtHora"]: "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"] :"";
        $this->preciounitario = isset($request["txtPrecioUnitario"])? $request["txtPrecioUnitario"] :"";
        $this->preciototal = isset($request["txtPrecioTotal"])? $request["txtPrecioTotal"] :"";
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO ventas (
                    fk_idcliente, 
                    fk_idproducto, 
                    fecha,  
                    cantidad,
                    preciounitario,
                    preciototal
                ) VALUES (
                    '" . $this->fk_idcliente ."', 
                    '" . $this->fk_idproducto ."', 
                    '" . $this->fecha ."',  
                     " . $this->cantidad .",
                     " . $this->preciounitario .",
                     " . $this->preciototal ."
                );";
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idventa = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE ventas SET
                fk_idcliente = '".$this->fk_idcliente."',
                fk_idproducto = '".$this->fk_idproducto."',
                fecha = '".$this->fecha."',
                cantidad = ".$this->cantidad.",
                preciounitario = ".$this->preciounitario.",
                preciototal = ".$this->preciototal."
                WHERE idventa = " . $this->idventa;
          
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM ventas WHERE idventa = " . $this->idventa;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idventa, 
                        fk_idcliente, 
                        fk_idproducto, 
                        fecha,  
                        cantidad,
                        preciounitario,
                        preciototal
                FROM ventas 
                WHERE idventa = " . $this->idventa;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idventa = $fila["idventa"];
            $this->fk_idcliente = $fila["fk_idcliente"];
            $this->fk_idproducto = $fila["fk_idproducto"];
            $this->fecha = $fila["fecha"];
            $this->cantidad = $fila["cantidad"];
            $this->preciounitario = $fila["preciounitario"];
            $this->preciototal = $fila["preciototal"];
        }  
        $mysqli->close();

    }

    public function cargarGrilla(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
       
        $sql = "SELECT 
                A.idventa,
                A.fecha,
                A.cantidad,
                A.fk_idcliente,
                B.nombre as nombre_cliente,
                A.fk_idproducto,
                A.preciototal,
                A.preciounitario,
                C.nombre as nombre_producto
            FROM ventas A
            INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
            INNER JOIN productos C ON A.fk_idproducto = C.idproducto
            ORDER BY A.fecha DESC";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Venta();
                $entidadAux->idventa = $fila["idventa"];
                $entidadAux->fk_idcliente = $fila["fk_idcliente"];
                $entidadAux->fk_idproducto = $fila["fk_idproducto"];
                $entidadAux->fecha = $fila["fecha"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->preciounitario = $fila["preciounitario"];
                $entidadAux->nombre_cliente = $fila["nombre_cliente"];
                $entidadAux->nombre_producto = $fila["nombre_producto"];
                $entidadAux->preciototal = $fila["preciototal"];
                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;
    }

}
        
?>
