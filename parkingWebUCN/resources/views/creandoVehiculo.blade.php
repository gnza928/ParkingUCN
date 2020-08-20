@extends('layouts.default')


<?php
require_once 'Ice.php';
require_once(__DIR__ . '\..\..\..\domain.php');

try
{
    // Create a communicator
    //
    $ic = Ice\initialize();

    // Create a proxy
    //
    $obj = $ic->stringToProxy("TheSystem:tcp -z -t 15000 -p 8080");

    // Down-cast the proxy to a Directory proxy
    //
    $rootDir = model\TheSystemPrxHelper::checkedCast($obj);

    if(!$rootDir->obtenerVehiculo($patente)){
        $vehiculo = new \model\Vehiculo($patente, $codigoLogo, $marca, $modelo, intval($anio), $observacion,
            $responsable, $tipoLogo);
        $rootDir->registrarVehiculo($vehiculo);
        $imprimir = "Vehiculo agregado";
    }else{
        $imprimir = "El vehiculo ya existe";
    }



}
catch(Ice\LocalException $ex)
{
    $imprimir = $ex;
    //print_r($ex);
}
if($ic)
{
    $ic->destroy();
}

?>
@section('content')
    <h4><?php echo $imprimir;?></h4><br>
@endsection
