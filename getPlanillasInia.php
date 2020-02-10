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
  $action = $_GET['action'];
  if($action=="proyectos_investigacion_cantidad_financiados"){
    $proyectos_financiados = array();
    getProyectosInvestigacionPorArea($proyectos_financiados,$link,"Ciencias Médicas y de la Salud","med","in");
    getProyectosInvestigacionPorArea($proyectos_financiados,$link,"Ciencias Agrícolas","agraria","in");
    getProyectosInvestigacionPorArea($proyectos_financiados,$link,"Ingeniería y Tecnología","tec","in");
    ksort($proyectos_financiados);
    echo "Año,Agraria,Tecnologíca,Ciencias Médicas y de la Salud<br/>";
    foreach ($proyectos_financiados as $key => $value) {
      $agraria = 0;
      if($value['agraria']){
        $agraria = $value['agraria'];
      }
      $med = 0;
      if($value['med']){
        $med = $value['med'];
      }
      $tec = 0;
      if($value['tec']){
        $tec = $value['tec'];
      }
      echo str_replace("anio_","",$key) . "," . $agraria . "," . $tec . "," . $med . "<br/>";
    }
  }
  if($action=="proyectos_investigacion_size_financiados"){
    getProyectosInvestigacionSizePorArea($link,"Ciencias Médicas y de la Salud","med","in");
    getProyectosInvestigacionSizePorArea($link,"Ciencias Agrícolas","agraria","in");
    getProyectosInvestigacionSizePorArea($link,"Ingeniería y Tecnología","tec","in");
  }
  if($action=="proyectos_investigacion_duration_financiados"){
    getProyectosInvestigacionDurationPorArea($link,"Ciencias Médicas y de la Salud","med","in");
    getProyectosInvestigacionDurationPorArea($link,"Ciencias Agrícolas","agraria","in");
    getProyectosInvestigacionDurationPorArea($link,"Ingeniería y Tecnología","tec","in");
  }
  if($action=="proyectos_investigacion_cantidad_no_financiados"){
    $proyectos_financiados = array();
    getProyectosInvestigacionPorArea($proyectos_financiados,$link,"Ciencias Médicas y de la Salud","med","not in");
    getProyectosInvestigacionPorArea($proyectos_financiados,$link,"Ciencias Agrícolas","agraria","not in");
    getProyectosInvestigacionPorArea($proyectos_financiados,$link,"Ingeniería y Tecnología","tec","not in");
    ksort($proyectos_financiados);
    echo "Año,Agraria,Tecnologíca,Ciencias Médicas y de la Salud<br/>";
    foreach ($proyectos_financiados as $key => $value) {
      $agraria = 0;
      if($value['agraria']){
        $agraria = $value['agraria'];
      }
      $med = 0;
      if($value['med']){
        $med = $value['med'];
      }
      $tec = 0;
      if($value['tec']){
        $tec = $value['tec'];
      }
      echo str_replace("anio_","",$key) . "," . $agraria . "," . $tec . "," . $med . "<br/>";
    }
  }
  if($action=="proyectos_investigacion_size_no_financiados"){
    getProyectosInvestigacionSizePorArea($link,"Ciencias Médicas y de la Salud","med","not in");
    getProyectosInvestigacionSizePorArea($link,"Ciencias Agrícolas","agraria","not in");
    getProyectosInvestigacionSizePorArea($link,"Ingeniería y Tecnología","tec","not in");
  }
  if($action=="proyectos_investigacion_duration_no_financiados"){
    getProyectosInvestigacionDurationPorArea($link,"Ciencias Médicas y de la Salud","med","not in");
    getProyectosInvestigacionDurationPorArea($link,"Ciencias Agrícolas","agraria","not in");
    getProyectosInvestigacionDurationPorArea($link,"Ingeniería y Tecnología","tec","not in");
  }
  if($action=="articulos_arbitrados_cantidad"){
    $articulos = array();
    getArticulosArbitradosPorArea($articulos,$link,"Ciencias Médicas y de la Salud","med","in");
    getArticulosArbitradosPorArea($articulos,$link,"Ciencias Agrícolas","agraria","in");
    getArticulosArbitradosPorArea($articulos,$link,"Ingeniería y Tecnología","tec","in");
    ksort($articulos);
    echo "Año,Agraria,Tecnologíca,Ciencias Médicas y de la Salud<br/>";
    foreach ($articulos as $key => $value) {
      $agraria = 0;
      if($value['agraria']){
        $agraria = $value['agraria'];
      }
      $med = 0;
      if($value['med']){
        $med = $value['med'];
      }
      $tec = 0;
      if($value['tec']){
        $tec = $value['tec'];
      }
      echo str_replace("anio_","",$key) . "," . $agraria . "," . $tec . "," . $med . "<br/>";
    }
  }
  if($action=="articulos_noarbitrados_cantidad"){
    $articulos = array();
    getArticulosNoArbitradosPorArea($articulos,$link,"Ciencias Médicas y de la Salud","med","in");
    getArticulosNoArbitradosPorArea($articulos,$link,"Ciencias Agrícolas","agraria","in");
    getArticulosNoArbitradosPorArea($articulos,$link,"Ingeniería y Tecnología","tec","in");
    ksort($articulos);
    echo "Año,Agraria,Tecnologíca,Ciencias Médicas y de la Salud<br/>";
    foreach ($articulos as $key => $value) {
      $agraria = 0;
      if($value['agraria']){
        $agraria = $value['agraria'];
      }
      $med = 0;
      if($value['med']){
        $med = $value['med'];
      }
      $tec = 0;
      if($value['tec']){
        $tec = $value['tec'];
      }
      echo str_replace("anio_","",$key) . "," . $agraria . "," . $tec . "," . $med . "<br/>";
    }
  }
  if($action=="articulos_arbitrados_size"){
    getArticulosArbitradosSizePorArea($link,"Ciencias Médicas y de la Salud","med","in");
    getArticulosArbitradosSizePorArea($link,"Ciencias Agrícolas","agraria","in");
    getArticulosArbitradosSizePorArea($link,"Ingeniería y Tecnología","tec","in");
  }
  if($action=="articulos_noarbitrados_size"){
    getArticulosNoArbitradosSizePorArea($link,"Ciencias Médicas y de la Salud","med","in");
    getArticulosNoArbitradosSizePorArea($link,"Ciencias Agrícolas","agraria","in");
    getArticulosNoArbitradosSizePorArea($link,"Ingeniería y Tecnología","tec","in");
  }
  if($action =="totalesPorArea"){
    $filas = array();
    $filas[] = getPlanillaTotalesPorArea($link,2000,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2001,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2002,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2003,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2004,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2005,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2006,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2007,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2008,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2009,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2010,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2011,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2012,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2013,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2014,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2015,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2016,"Ciencias Médicas y de la Salud","med");
    $filas[] = getPlanillaTotalesPorArea($link,2017,"Ciencias Médicas y de la Salud","med");

    $filas[] = getPlanillaTotalesPorArea($link,2000,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2001,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2002,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2003,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2004,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2005,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2006,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2007,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2008,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2009,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2010,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2011,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2012,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2013,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2014,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2015,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2016,"Ciencias Agrícolas","agraria");
    $filas[] = getPlanillaTotalesPorArea($link,2017,"Ciencias Agrícolas","agraria");

    $filas[] = getPlanillaTotalesPorArea($link,2000,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2001,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2002,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2003,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2004,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2005,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2006,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2007,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2008,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2009,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2010,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2011,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2012,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2013,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2014,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2015,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2016,"Ingeniería y Tecnología","tec");
    $filas[] = getPlanillaTotalesPorArea($link,2017,"Ingeniería y Tecnología","tec");
    print_r($filas);
    echo "area,anio,articulosArbitradosSNI,ProductosTecnicosSNI,proyectosInvSNI,articulosArbitradosNoSNI,ProductosTecnicosNoSNI,proyectosInvNoSNI"."<br/>";
    foreach ($filas as $key => $value) {
      echo $value['areaPorLaQueEntro'] . ",";
      echo $value['anio'] . ",";
      echo $value['articulosArbitradosSNI'] . ",";
      echo $value['ProductosTecnicosSNI'] . ",";
      echo $value['proyectosInvSNI'] . ",";
      echo $value['articulosArbitradosNoSNI'] . ",";
      echo $value['ProductosTecnicosNoSNI'] . ",";
      echo $value['proyectosInvNoSNI']."<br/>";
    }
  }
}
$link->close();

function getPlanillaTotalesPorArea($link,$anio,$area,$area_short){
  $sql = 'select

  "'.$area.'" as areaPorLaQueEntro,

  '.$anio.' as anio,

  (SELECT count(*)
  FROM `recorte_articulo_revista_arbitrada` a,
  investigadores i
  where i.sni != "No" and a.anio = '.$anio.' and a.documento = i.documento
  and (a.id in (select idarticulo from articulo_revista_arbitrada_area where area = "'.$area.'" ) or a.documento in (SELECT documento FROM `area_principal` WHERE area = "'.$area.'"))
  group by a.anio) as articulosArbitradosSNI,

  (SELECT count(*)
  FROM `produccion_tecnica_productos` p,
  investigadores i
  where i.sni != "No" and p.anio = '.$anio.' and p.documento = i.documento
  and (p.id in (select idproducto from produccion_tecnica_productos_area where area = "'.$area.'" ) or p.documento in (SELECT documento FROM `area_principal` WHERE area = "'.$area.'"))
  group by p.anio) as ProductosTecnicosSNI,

  (SELECT count(*)
  FROM `proyecto_investigacion` t,
  investigadores i
  where i.sni != "No" and
  LEFT(t.fecha_inicio,4) = "'.$anio.'" and t.documento = i.documento
  and (t.id in (select idproyecto from proyecto_investigacion_area where area = "'.$area.'" ) or t.documento in (SELECT documento FROM `area_principal` WHERE area = "'.$area.'"))
  ) as proyectosInvSNI,

  (SELECT count(*)
  FROM `recorte_articulo_revista_arbitrada` a,
  investigadores i
  where i.sni = "No" and a.anio = '.$anio.' and a.documento = i.documento
  and (a.id in (select idarticulo from articulo_revista_arbitrada_area where area = "'.$area.'" ) or a.documento in (SELECT documento FROM `area_principal` WHERE area = "'.$area.'"))
  group by a.anio) as articulosArbitradosNoSNI,

  (SELECT count(*)
  FROM `produccion_tecnica_productos` p,
  investigadores i
  where i.sni = "No" and p.anio = '.$anio.' and p.documento = i.documento
  and (p.id in (select idproducto from produccion_tecnica_productos_area where area = "'.$area.'" ) or p.documento in (SELECT documento FROM `area_principal` WHERE area = "'.$area.'"))
  group by p.anio) as ProductosTecnicosNoSNI,

  (SELECT count(*)
  FROM `proyecto_investigacion` t,
  investigadores i
  where i.sni = "No" and
  LEFT(t.fecha_inicio,4) = "'.$anio.'" and t.documento = i.documento
  and (t.id in (select idproyecto from proyecto_investigacion_area where area = "'.$area.'" ) or t.documento in (SELECT documento FROM `area_principal` WHERE area = "'.$area.'"))
  ) as proyectosInvNoSNI';

  //print_r($sql);

  //print_r("<br/><br/>");

  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        print_r($row);
        return $row;
      }
  }

}

function testOverlappingFunction(){
  $startOne = new DateTime('2000-04-05');
  $endOne = new DateTime('2000-04-10');
  $startTwo = new DateTime('2000-04-06');
  $endTwo = new DateTime('2000-04-31');
  $daysCount = datesOverlap($startOne,$endOne,$startTwo,$endTwo);
  echo "los días que se sobreponen son: " . $daysCount;
}

function setRecorteProyectosPorAreaYduplicados($link){
  $sql = "SELECT * from proyecto_investigacion where id in
   (select idproyecto from proyecto_investigacion_area where area = 'Ciencias Médicas y de la Salud'
     or area = 'Ciencias Agrícolas'
     or area = 'Ingeniería y Tecnología' ) or documento in
     (SELECT documento FROM `area_principal` WHERE area = 'Ciencias Médicas y de la Salud'
        or area = 'Ciencias Agrícolas'
        or area = 'Ingeniería y Tecnología')";
  $result = $link->query($sql);
  $proyectos = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $datosProyecto = array();
        $id = $row["id"];
        $documentoPrincipal = $row["documento"];
        $tituloNormalizado = cleanName($row["titulo"]);
        set_or_discard_project($link,$proyectos,$id,$tituloNormalizado,$row);
      }
      llenarTablaRecorteProyectos($link,$proyectos);
  }
}

function set_or_discard_project($link,&$proyectos,$id,$tituloNormalizado,$row){
  $maxDistinctChars = 2;
  $existe = false;
  foreach ($proyectos as $otherKey => $value) {
    if($tituloNormalizado==$otherKey || levenshtein($tituloNormalizado,$otherKey)<=$maxDistinctChars){
      //YA EXISTE EN ARRAY, entonces comparo por cantidad de integrantes
      $existe = true;
      $cantidad = getCantidadIntegrantesProyecto($link, $id);
      $old_project_row = $proyectos[$otherKey];
      $cantidad_old = getCantidadIntegrantesProyecto($link, $old_project_row['id']);
      if($cantidad>$cantidad_old){
        unset($proyectos[$otherKey]);
        $proyectos[$tituloNormalizado] = $row;
      }
    }
  }
  if(!$existe){
    $proyectos[$tituloNormalizado] = $row;
  }
}

function llenarTablaRecorteProyectos($link,$proyectos){
  foreach ($proyectos as $key => $row) {
    try {
        $query="insert into recorte_proyecto_investigacion (
        id,
        documento,
        institucion,
        titulo,
        descripcion,
        otra_descripcion,
        alumno_pregrado,
        alumno_especializacion,
        alumno_maestria,
        alumno_maestria_prof,
        alumno_doctorado,
        tipo_participacion_vinculo,
        situacion_vinculo,
        tipo_clase_vinculo,
        dependencia,
        unidad,
        fecha_inicio,
        fecha_fin,
        cargaHorariaSemanal) values (
          '".$row['id']."',
          '".$row['documento']."',
          '".$link->real_escape_string($row['institucion'])."',
          '".$link->real_escape_string($row['titulo'])."',
          '".$link->real_escape_string($row['descripcion'])."',
          '".$link->real_escape_string($row['otra_descripcion'])."',
          '".$row['alumno_pregrado']."',
          '".$row['alumno_especializacion']."',
          '".$row['alumno_maestria']."',
          '".$row['alumno_maestria_prof']."',
          '".$row['alumno_doctorado']."',
          '".$link->real_escape_string($row['tipo_participacion_vinculo'])."',
          '".$link->real_escape_string($row['situacion_vinculo'])."',
          '".$link->real_escape_string($row['tipo_clase_vinculo'])."',
          '".$link->real_escape_string($row['dependencia'])."',
          '".$link->real_escape_string($row['unidad'])."',
          '".$row['fecha_inicio']."',
          '".$row['fecha_fin']."',
          '".$row['cargaHorariaSemanal']."'
        );";
        if ($link->query($query) === TRUE) {
            echo "New record created successfully <br/>";
        } else {
            echo "Error: " . $query . "<br>" . $query->error;
        }
    }catch (Exception $error) {
        echo $error->getMessage();
    }
  }
}

function getCantidadIntegrantesProyecto($link,$id){
  $sql = "SELECT count(*) as cantidad from proyecto_investigacion_equipo where idproyecto = '".$id."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $cantidad = $row['cantidad'];
        return $cantidad;
      }
  }
  return 0;
}


function getProyectosInvestigacionSoloDatosGenerales($link){
  $sql = "SELECT * from recorte_proyecto_investigacion";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $datosProyecto = array();
        $id = $row["id"];
        $documentoPrincipal = $row["documento"];
        $tituloNormalizado = cleanName($row["titulo"]);
        if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
          $datosProyecto[] = $id;
          $datosProyecto[] = $row["titulo"];
          $datosProyecto[] = $row["descripcion"];
          $datosProyecto[] = $row["otra_descripcion"];
          $datosProyecto[] = $row["institucion"];
          $datosProyecto[] = $row["dependencia"];
          $datosProyecto[] = $row["fecha_inicio"];
          $datosProyecto[] = $row["fecha_fin"];
          $filaArray = $datosProyecto;
          $proyectos[$tituloNormalizado] = $filaArray;
        }
      }
      create_csv_file("solo_proyectos.csv",$proyectos);
  }
}

function getArticulosArbitradosPorArea(&$result_array,$link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT count(*) as cantidad, anio as year from articulo_revista_arbitrada where ( id in (select idarticulo from articulo_revista_arbitrada_area where area = '$area' ) or documento in (SELECT documento FROM `area_principal` WHERE area = '$area') ) group by year order by year";
  $result = $link->query($sql);
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $cantidad = $row['cantidad'];
    if(!array_key_exists('anio_'.$year,$result_array)){
      $result_array['anio_'.$year] = array();
    }
    $result_array['anio_'.$year][$area_short] = $cantidad;
  }
  return $result_array;
}

function getArticulosNoArbitradosPorArea(&$result_array,$link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT count(*) as cantidad, anio as year from articulo_revista_noarbitrada where ( id in (select idarticulo from articulo_revista_noarbitrada_area where area = '$area' ) or documento in (SELECT documento FROM `area_principal` WHERE area = '$area') ) group by year order by year";
  $result = $link->query($sql);
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $cantidad = $row['cantidad'];
    if(!array_key_exists('anio_'.$year,$result_array)){
      $result_array['anio_'.$year] = array();
    }
    $result_array['anio_'.$year][$area_short] = $cantidad;
  }
  return $result_array;
}

function getProyectosInvestigacionPorArea(&$result_array,$link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT count(*) as cantidad, LEFT(fecha_inicio , 4) as year from recorte_proyecto_investigacion where id ".$financiado_si_no." (select DISTINCT(idproyecto) from proyecto_investigacion_institucion_financiadora) and ( id in (select idproyecto from proyecto_investigacion_area where area = '$area' ) or documento in (SELECT documento FROM `area_principal` WHERE area = '$area') ) group by year order by year";
  $result = $link->query($sql);
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $cantidad = $row['cantidad'];
    if(!array_key_exists('anio_'.$year,$result_array)){
      $result_array['anio_'.$year] = array();
    }
    $result_array['anio_'.$year][$area_short] = $cantidad;
  }
  return $result_array;
}

function getArticulosArbitradosSizePorArea($link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT p.id, count(e.nombres) as cantidad, anio as year
from articulo_revista_arbitrada p, articulo_revista_arbitrada_coautor e
where p.id = e.idarticulo and ( p.id in (select idarticulo from articulo_revista_arbitrada_area where area = '$area' ) or p.documento in (SELECT documento FROM `area_principal` WHERE area = '$area') )
group by p.id
order by year";
  $result = $link->query($sql);
  $result_array = array();
  $years = array();
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    if(!in_array($year,$years)){
      $years[]=$year;
    }
    $cantidad = $row['cantidad'];
    $id = $row['id'];
    if(!array_key_exists('integrantes_'.$cantidad,$result_array)){
      $result_array['integrantes_'.$cantidad] = array();
      for ($i=1960; $i < 2019; $i++) {
        $result_array['integrantes_'.$cantidad]['anio_'.$i] = 0;
      }
    }
    if(!array_key_exists('anio_'.$year,$result_array['integrantes_'.$cantidad])){
      $result_array['integrantes_'.$cantidad]['anio_'.$year] = 0;
    }
    $result_array['integrantes_'.$cantidad]['anio_'.$year] += 1;
  }
  ksort($result_array);
  sort($years);
  echo "Integrantes,";
  /*foreach ($years as $key => $value) {
    echo $value . ",";
  }*/
  for ($i=1960; $i < 2019; $i++) {
    echo $i . ",";
  }
  echo "<br/>";
  foreach ($result_array as $key => $value) {
    echo str_replace("integrantes_","",$key) .",";
    foreach ($value as $year => $cantidad) {
      echo $cantidad .",";
    }
    echo "<br/>";
  }
  echo "<br/>";
  return $result_array;
}

function getArticulosNoArbitradosSizePorArea($link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT p.id, count(e.nombres) as cantidad, anio as year
from articulo_revista_noarbitrada p, articulo_revista_noarbitrada_coautor e
where p.id = e.idarticulo and ( p.id in (select idarticulo from articulo_revista_noarbitrada_area where area = '$area' ) or p.documento in (SELECT documento FROM `area_principal` WHERE area = '$area') )
group by p.id
order by year";
  $result = $link->query($sql);
  $result_array = array();
  $years = array();
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    if(!in_array($year,$years)){
      $years[]=$year;
    }
    $cantidad = $row['cantidad'];
    $id = $row['id'];
    if(!array_key_exists('integrantes_'.$cantidad,$result_array)){
      $result_array['integrantes_'.$cantidad] = array();
      for ($i=1960; $i < 2019; $i++) {
        $result_array['integrantes_'.$cantidad]['anio_'.$i] = 0;
      }
    }
    if(!array_key_exists('anio_'.$year,$result_array['integrantes_'.$cantidad])){
      $result_array['integrantes_'.$cantidad]['anio_'.$year] = 0;
    }
    $result_array['integrantes_'.$cantidad]['anio_'.$year] += 1;
  }
  ksort($result_array);
  sort($years);
  echo "Integrantes,";
  /*foreach ($years as $key => $value) {
    echo $value . ",";
  }*/
  for ($i=1960; $i < 2019; $i++) {
    echo $i . ",";
  }
  echo "<br/>";
  foreach ($result_array as $key => $value) {
    echo str_replace("integrantes_","",$key) .",";
    foreach ($value as $year => $cantidad) {
      echo $cantidad .",";
    }
    echo "<br/>";
  }
  echo "<br/>";
  return $result_array;
}

function getProyectosInvestigacionSizePorArea($link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT p.id, count(e.nombres) as cantidad, LEFT(p.fecha_inicio , 4) as year from recorte_proyecto_investigacion p, recorte_proyecto_investigacion_equipo e where p.id = e.idproyecto and p.id ".$financiado_si_no." (select DISTINCT(idproyecto) from proyecto_investigacion_institucion_financiadora) and ( p.id in (select idproyecto from proyecto_investigacion_area where area = '$area' ) or p.documento in (SELECT documento FROM `area_principal` WHERE area = '$area') ) group by p.id order by year";
  $result = $link->query($sql);
  $result_array = array();
  $years = array();
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    if(!in_array($year,$years)){
      $years[]=$year;
    }
    $cantidad = $row['cantidad'];
    $id = $row['id'];
    if(!array_key_exists('integrantes_'.$cantidad,$result_array)){
      $result_array['integrantes_'.$cantidad] = array();
      for ($i=1970; $i < 2019; $i++) {
        $result_array['integrantes_'.$cantidad]['anio_'.$i] = 0;
      }
    }
    if(!array_key_exists('anio_'.$year,$result_array['integrantes_'.$cantidad])){
      $result_array['integrantes_'.$cantidad]['anio_'.$year] = 0;
    }
    $result_array['integrantes_'.$cantidad]['anio_'.$year] += 1;
  }
  ksort($result_array);
  sort($years);
  echo "Integrantes,";
  /*foreach ($years as $key => $value) {
    echo $value . ",";
  }*/
  for ($i=1970; $i < 2019; $i++) {
    echo $i . ",";
  }
  echo "<br/>";
  foreach ($result_array as $key => $value) {
    echo str_replace("integrantes_","",$key) .",";
    foreach ($value as $year => $cantidad) {
      echo $cantidad .",";
    }
    echo "<br/>";
  }
  echo "<br/>";
  return $result_array;
}

function getProyectosInvestigacionDurationPorArea($link,$area,$area_short,$financiado_si_no){
  $sql = "SELECT LEFT(fecha_inicio , 4) as year, LEFT(fecha_fin , 4)-LEFT(fecha_inicio , 4) as duration from recorte_proyecto_investigacion where id ".$financiado_si_no." (select DISTINCT(idproyecto) from proyecto_investigacion_institucion_financiadora) and ( id in (select idproyecto from proyecto_investigacion_area where area = '$area' ) or documento in (SELECT documento FROM `area_principal` WHERE area = '$area') ) order by year";
  $result = $link->query($sql);
  $result_array = array();
  $years = array();
  while($row = $result->fetch_assoc()) {
    $year = $row['year'];
    if(!in_array($year,$years)){
      $years[]=$year;
    }
    $duration = $row['duration'];
    if($duration<0){
      $duration = "Sin_dato";
    }else if($duration==0){
      $duration = "Menor_a_un_año";
    }
    $id = $row['id'];
    if(!array_key_exists('duracion_'.$duration,$result_array)){
      $result_array['duracion_'.$duration] = array();
      for ($i=1970; $i < 2019; $i++) {
        $result_array['duracion_'.$duration]['anio_'.$i] = 0;
      }
    }
    if(!array_key_exists('anio_'.$year,$result_array['duracion_'.$duration])){
      $result_array['duracion_'.$duration]['anio_'.$year] = 0;
    }
    $result_array['duracion_'.$duration]['anio_'.$year] += 1;
  }
  ksort($result_array);
  sort($years);
  echo "Duración,";
  /*foreach ($years as $key => $value) {
    echo $value . ",";
  }*/
  for ($i=1970; $i < 2019; $i++) {
    echo $i . ",";
  }
  echo "<br/>";
  foreach ($result_array as $key => $value) {
    echo str_replace("duracion_","",$key) .",";
    foreach ($value as $year => $cantidad) {
      echo $cantidad .",";
    }
    echo "<br/>";
  }
  echo "<br/>";
  return $result_array;
}

function getProyectosInvestigacionIdiomaEn($link){
  $sql = "SELECT * from recorte_proyecto_investigacion where idioma = 'en'";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            $datosProyecto[] = $row["titulo"];
            $datosProyecto[] = $row["descripcion"];
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_proyectos_header_array();
      create_csv_file("proyectos_investigacion_cvuy_en.csv",$headers,$proyectos);
  }
}

function getProyectosInvestigacionIdiomaEs($link){
  $sql = "SELECT * from recorte_proyecto_investigacion where idioma = 'es'";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            $datosProyecto[] = $row["titulo"];
            $datosProyecto[] = $row["descripcion"];
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_proyectos_header_array();
      create_csv_file("proyectos_investigacion_cvuy_es.csv",$headers,$proyectos);
  }
}

function getProyectosInvestigacion($link){
  $sql = "SELECT * from recorte_proyecto_investigacion";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            $datosProyecto[] = $row["titulo"];
            $datosProyecto[] = $row["descripcion"];
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_proyectos_header_array();
      create_csv_file("proyectos_investigacion_cvuy.csv",$headers,$proyectos);
  }
}

function create_csv_proyectos_header_array(){
  $headers = array();
  $headers []= "ID Proyecto";
  $headers []= "Título proyecto";
  $headers []= "Descripción";
  $headers []= "Otra descripción";
  $headers []= "Institución dónde se presentó el proyecto";
  $headers []= "Dependencia dónde se presentó el proyecto";
  $headers []= "Fecha de inicio del proyecto";
  $headers []= "Fecha de fin del proyecto";
  for ($i=1; $i <13 ; $i++) {
    $headers []= "Palabra clave ".$i;
  }
  for ($i=1; $i <6 ; $i++) {
    $headers []= "Área proyecto  ".$i;
    $headers []= "Subárea proyecto  ".$i;
    $headers []= "Disciplina proyecto  ".$i;
  }
  for ($i=1; $i <16 ; $i++) {
    $headers []= "Institución Financiadora ".$i;
    $headers []= "Subinstitución Financiadora ".$i;
    $headers []= "Tipo financiación ".$i;
  }
  for ($i=1; $i <62 ; $i++) {
    $headers []= "ID investigador ".$i;
    $headers []= "Nombre completo investigador ".$i;
    $headers []= "CVUy si/no investigador ".$i;
    $headers []= "Institución en ese momento investigador ".$i;
    $headers []= "Subinstitución en ese momento investigador ".$i;
    $headers []= "Carga horaria en ese momento investigador ".$i;
    $headers []= "Días de coincidencia entre institución del investigador y el proyecto ".$i;
    $headers []= "Tipo de coincidencia investigador ".$i;
  }
  return $headers;
}

function getPeopleCoincidencesCountArray($link){
  $sql = "SELECT id, count(*) as cantidad FROM `recorte_proyecto_investigacion_equipo_coincidencias` group by id";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $dataArray[$row['id']] = array();
        $dataArray[$row['id']]['cantidad'] = $row['cantidad'];
        $dataArray[$row['id']]['ya_en_planilla'] = false;
      }
  }
  return $dataArray;
}

function getPersonFromCVUyPeopleList($link,$documento_encontrado){
  $sql = "SELECT * from investigadores where documento = '".$documento_encontrado."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $persona = array();
        $persona['nombre_completo'] = $row['nombres'] . " " . $row['apellidos'];
        $persona['cvuy'] = "Si";
        $persona['id'] =  $documento_encontrado;
        return $persona;
      }
  }
  return false;
}

function checkIfPersonAlsoHasProjectInsideCVuy($link,$documento_encontrado,$projectId,$tituloNormalizado){
  $sql = "SELECT * from proyecto_investigacion where documento = '".$documento_encontrado."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $otro_titulo = $row['titulo'];
        $otro_titulo_normalizado = cleanName($otro_titulo);
        if($tituloNormalizado==$otro_titulo_normalizado || levenshtein($tituloNormalizado,$otro_titulo_normalizado)<=3){
          //ENCONTRO EL PROYECTO CON NOMBRE PARECIDO
          return $row['id'];
        }
      }
  }
  return false;
}

function getPersonFromCoincidences($link,$projectId,$tituloNormalizado,$idPersona,&$coincidencesCountArray){
  $sql = "SELECT * from recorte_proyecto_investigacion_equipo_coincidencias where id = '".$idPersona."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $documento_encontrado = $row["documento_encontrado"];
        $tipo_coincidencia = $row["tipo_coincidencia"];
        $id_investigador_no_cv_uy = $row["id_investigador_no_cv_uy"];
        if(!is_null($row["id_investigador_no_cv_uy"])){
          //NO ESTÁ EN CVUY
          $sqlNoCV = "SELECT * from investigadores_no_cvuy where id = '".$id_investigador_no_cv_uy."'";
          $resultNoCv = $link->query($sqlNoCV);
          if ($resultNoCv->num_rows > 0) {
              while($rowNoCV = $resultNoCv->fetch_assoc()) {
                $persona = array();
                $persona['nombre_completo'] = $rowNoCV['nombre_completo'];
                $persona['cvuy'] = "No";
                $persona['id'] =  $id_investigador_no_cv_uy;
                return $persona;
              }
          }
        }else{
          //ESTA EN CVUY
          if($coincidencesCountArray[$idPersona]['cantidad']==1){
            //SOLO HAY UNA COINCIDENCIA CON SÓlO UN INVESTIGADOR DEL CVUY
            $defineProyectoEnSuCv = checkIfPersonAlsoHasProjectInsideCVuy($link,$documento_encontrado,$projectId,$tituloNormalizado);
            if($defineProyectoEnSuCv!=false){
              //APARECE SOLO UNA VEZ Y TAMBIEN DEFINE EL PROYECTO. COINCIDENCIA 100%
              $coincidencesCountArray[$idPersona]['tipo_coincidencia'] = 'unica_con_proyecto';
              $coincidencesCountArray[$idPersona]['id_proyecto_coincidente'] = $defineProyectoEnSuCv;
              $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
            }else{
              //TOMO EL QUE ESTA PORQUE ES EL UNICO QUE ENCONTRO (COINCIDENCIA 80%)
              $coincidencesCountArray[$idPersona]['tipo_coincidencia'] = 'unica_sin_proyecto';
              $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
            }
            return $persona;
          }else{
            //TIENE MAS DE UNA COINCIDENCIA
            //if($coincidencesCountArray[$idPersona]['ya_en_planilla']==false){
              //ME FIJO SI ESTE PUEDE SER EL VALIDO BUSCANDO EL PROYECTO EN SU CVUy
              $defineProyectoEnSuCv = checkIfPersonAlsoHasProjectInsideCVuy($link,$documento_encontrado,$projectId,$tituloNormalizado);
              if($defineProyectoEnSuCv!=false){
                //TIENE TAMBIEN DEFINIDO EL PROYECTO EN SU CV, ENTONCES ES ESTA PERSONA
                $coincidencesCountArray[$idPersona]['tipo_coincidencia'] = 'multiple_con_proyecto';
                $coincidencesCountArray[$idPersona]['id_proyecto_coincidente'] = $defineProyectoEnSuCv;
                $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
                $coincidencesCountArray[$idPersona]['ya_en_planilla']=true;
                return $persona;
              }
            //}
          }
        }
      }
  }
  return false;
}

function getPeopleArrayFromInvestigationProject($link,&$coincidencesCountArray,$projectId,$tituloNormalizado,$fechaInicio,$fechaFin,$maxLenghtArray){
  $sql = "SELECT * from recorte_proyecto_investigacion_equipo where idproyecto = '".$projectId."'";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idPersona = $row["id"];
        $persona = getPersonFromCoincidences($link,$projectId,$tituloNormalizado,$idPersona,$coincidencesCountArray);
        if($persona!=false){
          $dataArray[]= $persona['id'];
          $dataArray[]= $persona['nombre_completo'];
          $dataArray[]= $persona['cvuy'];
          if($persona['cvuy']=="Si"){
            $tipo_coincidencia = $coincidencesCountArray[$idPersona]['tipo_coincidencia'];
            $proyecto_coincidente = null;
            if($tipo_coincidencia == "unica_con_proyecto" || $tipo_coincidencia == "multiple_con_proyecto"){
              $proyecto_coincidente = $coincidencesCountArray[$idPersona]['id_proyecto_coincidente'];
            }
            $vinculoInstitucionalDuranteElPeriodoDelProyecto = getVinculoInstitucionalDuranteElPeriodoDelProyecto($link,$persona['id'],$fechaInicio,$fechaFin,$tipo_coincidencia,$tituloNormalizado,$proyecto_coincidente);
            if($vinculoInstitucionalDuranteElPeriodoDelProyecto!=false){
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['institucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['subinstitucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['carga_horaria'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['daysOverlaping'];
              $dataArray[] = $tipo_coincidencia;
            }else{
              $dataArray[]="";
              $dataArray[]="";
              $dataArray[]="";
              $dataArray[]="";
              $dataArray[]="";
            }
          }else{
            $dataArray[]="";
            $dataArray[]="";
            $dataArray[]="";
            $dataArray[]="";
            $dataArray[]="";
          }
        }
      }
  }
  return $dataArray;
}

function getInstitucionFromDefinicionProyecto($link,$documento,$tituloNormalizado,$proyecto_coincidente_id,$tipo_coincidencia){
  $sql = "SELECT * from recorte_proyecto_investigacion where id = '".$proyecto_coincidente_id."'";
  $result = $link->query($sql);
  $dataArray = false;
  if ($result->num_rows > 0) {
    $dataArray = array();
    while($row = $result->fetch_assoc()) {
      $dataArray['institucion'] = $row['institucion'];
      $dataArray['subinstitucion'] = $row['dependencia'];
      $dataArray['carga_horaria'] = $row['cargaHorariaSemanal'];
      $dataArray['daysOverlaping'] = "No corresponde (tomado de definición de proyecto)";
    }
  }
  return $dataArray;

}

function datesOverlap($start_one,$end_one,$start_two,$end_two) {
   if( ($start_one <= $end_two && $end_one >= $start_two) ||
       ($start_two <= $end_one && $end_two >= $start_one)) { //If the dates overlap
        return min($end_one,$end_two)->diff(max($start_two,$start_one))->days + 1; //return how many days overlap
   }
   return 0; //Return 0 if there is no overlap
}

function getVinculoInstitucionalDuranteElPeriodoDelProyecto($link,$documento,$fechaInicio,$fechaFin,$tipo_coincidencia,$tituloNormalizado,$proyecto_coincidente_id){
  if($tipo_coincidencia=="unica_con_proyecto" || $tipo_coincidencia=="multiple_con_proyecto"){
    $vinculo = getInstitucionFromDefinicionProyecto($link,$documento,$tituloNormalizado,$proyecto_coincidente_id,$tipo_coincidencia);
    return $vinculo;
  }else{
    $sql = "SELECT * from vinculo_institucional where documento = '".$documento."'";
    $result = $link->query($sql);
    $dataArray = array();
    $fechaInicioComparable = DateTime::createFromFormat('Y-m-d', $fechaInicio);
    $fechaFinComparable = null;
    if(is_null($fechaFin) || $fechaFin==""){
      $fechaFinComparable = new DateTime();
    }else{
      $fechaFinComparable = DateTime::createFromFormat('Y-m-d', $fechaFin);

    }
    $lastMaxDays = 0;
    $lastMaxHours = 0;
    $vinculo = false;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          if($row['inicio']==''){
            //NO TIENE FECHA DE INICIO EN EL VINCULO ENTONCES NO HAGO NADA
          }else{
            $fechaInicioVinculo = DateTime::createFromFormat('d/m/Y', $row['inicio']);
            $fechaFinVinculo = null;
            if(!is_null($row['fin']) && $row['fin']!=""){
              $fechaFinVinculo = DateTime::createFromFormat('d/m/Y', $row['fin']);
            }else{
              $fechaFinVinculo = new DateTime();
            }
            //COMPARO fECHAS
            $daysOverlaping = datesOverlap($fechaInicioComparable,$fechaFinComparable,$fechaInicioVinculo,$fechaFinVinculo);
            if($daysOverlaping>0){
                //SI COINCIDEN ALGUN DIA ENTRE LOS DOS PERIODOS
                if($daysOverlaping>$lastMaxDays){
                  //Si por ahora viene siendo el periodo con más dias de coincidencia
                  $vinculo = array();
                  $vinculo['institucion'] = $row['institucion'];
                  $vinculo['subinstitucion'] = $row['subinstitucion'];
                  $vinculo['carga_horaria'] = $row['cargahoraria'];
                  $vinculo['daysOverlaping'] = $daysOverlaping;
                  $lastMaxDays = $daysOverlaping;
                }else if($daysOverlaping==$lastMaxDays){
                  //SI COINCIDEN LA MISMA CANTIDAD DE DIAS ENTRE LOS PERIODOS TENGO QUE FIJARME LA CARGA HORARIA SEMANAL
                  if($row['cargahoraria']>=$lastMaxHours){
                    $lastMaxDays = $daysOverlaping;
                    $lastMaxHours = $row['cargahoraria'];
                    $vinculo = array();
                    $vinculo['institucion'] = $row['institucion'];
                    $vinculo['subinstitucion'] = $row['subinstitucion'];
                    $vinculo['carga_horaria'] = $row['cargahoraria'];
                    $vinculo['daysOverlaping'] = $daysOverlaping;
                  }
                }

            }
          }
        }
    }
    return $vinculo;
  }
}

function cleanName($name){
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

function cleanForIramuteq($str){
  $str = str_replace("!","",$str);
  $str = str_replace("”","",$str);
  $str = str_replace("·","",$str);
  $str = str_replace("$","",$str);
  $str = str_replace("%","",$str);
  $str = str_replace("/","",$str);
  $str = str_replace("(","",$str);
  $str = str_replace(")","",$str);
  $str = str_replace("=","",$str);
  $str = str_replace("?","",$str);
  $str = str_replace("¿","",$str);
  $str = str_replace("^","",$str);
  $str = str_replace("*","",$str);
  $str = str_replace(";","",$str);
  $str = str_replace("-","_",$str);
  $str = str_replace("’","",$str);
  $str = str_replace("","",$str);
  $str = str_replace("","",$str);
  return $str;
}

function exportToTxtFormatForIramuteq($filename,$data_multi_array){
  //Recorro el array de filas
  if(file_exists($_SERVER["DOCUMENT_ROOT"]."/iramuteq/".$filename.".txt")){
    //NO HAGO NADA PORQUE YA EXISTE
  }else{
    $myfile = fopen($_SERVER["DOCUMENT_ROOT"]."/iramuteq/".$filename.".txt", "a+");
    $proyectos = array();
    foreach ($data_multi_array as $key => $data_array) {
      $id = "proy".cleanForIramuteq($data_array[0]);
      if(array_key_exists($id,$proyectos)){
        //NO HAGO NADA
      }else{
        $titulo = cleanForIramuteq($data_array[1]);
        $descripcion = cleanForIramuteq($data_array[2]);
        $otra_descripcion = cleanForIramuteq($data_array[3]);
        $fecha_inicio = cleanForIramuteq($data_array[6]);
        $fecha_fin = cleanForIramuteq($data_array[7]);
        $p_clave1 =  cleanForIramuteq($data_array[8]);
        $p_clave1 =  cleanForIramuteq($data_array[9]);
        $p_clave1 =  cleanForIramuteq($data_array[10]);
        $p_clave1 =  cleanForIramuteq($data_array[11]);
        $p_clave1 =  cleanForIramuteq($data_array[12]);
        $p_clave1 =  cleanForIramuteq($data_array[13]);
        $p_clave1 =  cleanForIramuteq($data_array[14]);
        $p_clave1 =  cleanForIramuteq($data_array[15]);
        $p_clave1 =  cleanForIramuteq($data_array[16]);
        $p_clave1 =  cleanForIramuteq($data_array[17]);
        $p_clave1 =  cleanForIramuteq($data_array[18]);
        $p_clave1 =  cleanForIramuteq($data_array[19]);
        $str = "\r";
        $str .= "**** " . "*Proy_".$id . " \r";
        $str .= $titulo . "\r";
        $str .= $descripcion . "\r";
        fwrite($myfile, $str);
        $proyectos[$id] = true;
      }
    }
    fclose($myfile);
  }
}

function getValuesArrayFromSql($link,$sql,$maxLenghtArray){
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
          $dataArray[] = $value;
        }
      }
  }
  if(count($dataArray)<$maxLenghtArray){
    $lastItem = count($dataArray);
    for ($i=$lastItem; $i < $maxLenghtArray; $i++) {
      $dataArray[] = "";
    }
  }
  return $dataArray;
}

function create_csv_file($filename, $headers, $data_multi_array){
  if(file_exists($_SERVER["DOCUMENT_ROOT"]."/planillas/".$filename)){
    //NO HAGO NADA PORQUE YA EXISTE
  }else{
    $fp = fopen($_SERVER["DOCUMENT_ROOT"]."/planillas/".$filename, "a+");
    fputcsv($fp,$headers);
    $proyectos = array();
    foreach ($data_multi_array as $key => $data_array) {
      $id = "proy".cleanForIramuteq($data_array[0]);
      if(array_key_exists($id,$proyectos)){
        //NO HAGO NADA
      }else{
        fputcsv($fp, $data_array);
        $proyectos[$id] = true;
      }
    }
    fclose($fp);
  }
}

function array_key_exists_or_approaches($key, $array){
  $maxDistinctChars = 3;
  foreach ($array as $otherKey => $value) {
    if($key==$otherKey || levenshtein($key,$otherKey)<=$maxDistinctChars){
      return true;
    }
  }
  return false;
}


?>
