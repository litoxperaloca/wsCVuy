<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('memory_limit', '4024M'); // or you could use 1G
error_reporting(E_ALL);
$link = new mysqli('127.0.0.1', 'root', 'My_Csic150', 'wscvuy_proyecto_mineria_datos');
$link->query("SET NAMES 'utf8'");
if ($link->connect_errno) {
    echo "Error: Fallo al conectarse a MySQL debido a: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}else{
    $articulosDimensions = getArticulosDimensions($link);
    $totMed = getTotalArticulosArbitradosPorAreaArray($link,$articulosDimensions,"Ciencias Médicas y de la Salud");
    $totAgro = getTotalArticulosArbitradosPorAreaArray($link,$articulosDimensions,"Ciencias Agrícolas");
    $totTec = getTotalArticulosArbitradosPorAreaArray($link,$articulosDimensions,"Ingeniería y Tecnología");
    echo "Área, Total de artículos con coincidencia"."<br/>";
    echo "Ciencias Médicas y de la Salud, ".$totMed."<br/>";
    echo "Ciencias Agrícolas, ".$totAgro."<br/>";
    echo "Ingeniería y Tecnología, ".$totTec."<br/>";
}

function getArticulosDimensions($link){
  $articulos = array();
  $sql = "SELECT * from articulos_dimensions";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $articulos[] = $row;
      }
  }
  return $articulos;
}

function getTotalArticulosArbitradosPorAreaArray($link,$articulosDimensions,$area){
  $sql = "SELECT * from articulo_revista_arbitrada where
  id in (select idarticulo from articulo_revista_arbitrada_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."') limit 50";
  $result = $link->query($sql);
  $cantidadParecidos = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $parecidos = getArticulosParecidos($row,$articulosDimensions);
        foreach ($parecidos as $key => $value) {
          echo "<b>".$row["titulo"]."</b> es parecido a <b>". $value['title'] ."</b><br/><br/>";
        }
        if(count($parecidos)>0){
          $cantidadParecidos += 1;
        }
      }
  }
  return $cantidadParecidos;
}

function getArticulosParecidos($articulo,$articulosDimensions){
  $parecidos = array();
  foreach ($articulosDimensions as $key => $otroArticulo) {
      $titulo = cleanName($articulo['titulo']);
      $otroTitulo = cleanName($otroArticulo['title']);
      if($titulo==$otroTitulo){
        //COINCIDENCIA 100%
        $parecidos []= $otroArticulo;
      }else{
        if(2==1){
        /*if(partesDelStringContenidas($titulo,$otroTitulo)!=false){
          //Coincidencia de partes
          $parecidos []= $otroArticulo;*/
        }else{
          $similaridadTitulo1 = $titulo;
          $similaridadTitulo2 = $otroTitulo;
          if(strlen($similaridadTitulo1>253)){
            $similaridadTitulo1 = substr($similaridadTitulo1, 0, 253);
          }
          if(strlen($similaridadTitulo2>253)){
            $similaridadTitulo2 = substr($similaridadTitulo2, 0, 253);
          }
          $similaridad = similaridadTextos($similaridadTitulo1,$similaridadTitulo2);
          if($similaridad>=0.85){
            //SIMILARIDAD mayor al 85 %
            $parecidos []= $otroArticulo;
          }
        }
      }
  }
  return $parecidos;
}

function partesDelStringContenidas($texto1, $texto2){
  $textoMayor;
  $textoMenor;
  if(strlen($texto1)>=strlen($texto2)){
    //TEXTO 1 es mayor
    $textoMayor = $texto1;
    $textoMenor = $texto2;
  }else{
    //texto 2 es mayor
    $textoMayor = $texto2;
    $textoMenor = $texto1;
  }
  if (strpos($textoMayor, $textoMenor) !== false) {
      return true;
  }else{
    return false;
  }
}

function similaridadTextos($texto1, $texto2){
  $mayorTamanio = max(strlen($texto1),strlen($texto2));
  $levenshtein = levenshtein($texto1,$texto2);
  $similaridad = 0;
  if($levenshtein!=-1){
    $similaridad = 1 - ($levenshtein/$mayorTamanio);
  }
  //echo "La similaridad de $texto1 ||| $texto2 es: " . $similaridad . "<br/>";
  return $similaridad;
}

function cleanName($name){
  $name = trim($name);
  $name = strtolower($name);
  $name = str_replace("Á","a",$name);
  $name = str_replace("É","e",$name);
  $name = str_replace("Í","i",$name);
  $name = str_replace("Ó","o",$name);
  $name = str_replace("Ú","u",$name);
  $name = str_replace("á","a",$name);
  $name = str_replace("é","e",$name);
  $name = str_replace("í","i",$name);
  $name = str_replace("ó","o",$name);
  $name = str_replace("ú","u",$name);
  $name = str_replace("Ñ","n",$name);
  $name = str_replace("ñ","n",$name);
  $name = str_replace("ü","u",$name);
  $name = str_replace("Ü","u",$name);
  $name = str_replace(".","",$name);
  $name = str_replace("-"," ",$name);
  $name = str_replace("_"," ",$name);
  $name = str_replace(",","",$name);
  $name = str_replace("'","",$name);
  $name = str_replace('"',"",$name);
  $name = str_replace("´","",$name);
  //$name = preg_replace('/^dra /', '', $name);
  //$name = preg_replace('/^dr /', '', $name);
  return $name;
}

?>
