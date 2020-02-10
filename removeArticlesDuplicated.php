<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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
  $sql = "SELECT r.id as id, r.titulo as titulo, r.anio as anio, COUNT(e.id) as cantidad
          FROM recorte_articulo_revista_arbitrada r, recorte_articulo_revista_arbitrada_coautor e
          WHERE r.id = e.idarticulo group by e.idarticulo order by r.anio, r.titulo";
  $result = $link->query($sql);
  $proyectos = array();
  $proyectosBorrables = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if(!isset($proyectos[$row['anio']])){
        $proyectos[$row['anio']] = array();
      }
      $proyectos[$row['anio']] []= $row;
    }
    foreach ($proyectos as $anio => $proyectosAnio) {
      foreach ($proyectosAnio as $key => $proyecto) {
        $proyectosParecidos = getProyectosParecidos($proyecto,$proyectosAnio);
        foreach ($proyectosParecidos as $key => $otroProyecto) {
          if($otroProyecto['cantidad']>$proyecto['cantidad']){
            //$proyecto['borrar']=true;
            //$proyectosBorrables[]=$proyecto;
            echo "El artículo " . $proyecto['titulo']
            . " deberia ser borrado por su similar "
            . $otroProyecto['titulo'] . "<br/><br/>";
            $sql2 = "DELETE from recorte_articulo_revista_arbitrada WHERE id = '".$proyecto['id']."'";
            $result2 = $link->query($sql2);
          }
        }
      }
    }
  }
}
$link->close();

function getProyectosParecidos($proyecto,$proyectosAnio){
  $parecidos = array();
  foreach ($proyectosAnio as $key => $otroProyecto) {
    if($proyecto['id']!=$otroProyecto['id']){
      //SON PROYECTOS DISTINTOS DEL MISMO AÑO, COMPARO TITULOS
      $titulo = cleanName($proyecto['titulo']);
      $otroTitulo = cleanName($otroProyecto['titulo']);
      if($titulo==$otroTitulo){
        //COINCIDENCIA 100%
        $parecidos []= $otroProyecto;
      }else{
        if(partesDelStringContenidas($titulo,$otroTitulo)!=false){
          //Coincidencia de partes
          $parecidos []= $otroProyecto;
        }else{
          $similaridad = similaridadTextos($titulo,$otroTitulo);
          if($similaridad>=0.85){
            //SIMILARIDAD mayor al 85 %
            $parecidos []= $otroProyecto;
          }
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
  if(strlen($texto1>253)){
    $texto1 = substr($texto1, 0, 253);
  }
  if(strlen($texto2>253)){
    $texto2 = substr($texto2, 0, 253);
  }
  $mayorTamanio = max(strlen($texto1),strlen($texto2));
  $similaridad = 1 - (levenshtein($texto1,$texto2)/$mayorTamanio);
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
