<?php
require_once './config.php';

$link = new mysqli($dbHOST, $dbUSER, $dbPASS, $dbNAME);
$link->query("SET NAMES 'utf8'");
if ($link->connect_errno) {
    echo "Error: Fallo al conectarse a MySQL debido a: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}else{
  $action = $_GET['action'];
  if($action=="proyectos_investigacion"){
      getProyectosInvestigacion($link);
  }
  if($action=="proyectos_investigacion_todos_disciplinas"){
    getProyectosInvestigacionPlanillaMadreDisciplinas($link,"multiidioma","multiarea","todos");
  }
  if($action=="proyectos_investigacion_todos"){
    getProyectosInvestigacionPlanillaMadre($link,"multiidioma","multiarea","todos");
  }
  if($action=="matrix_instituciones"){
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2012","2017");

    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2012","2017");

    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2012","2017");
  }
  if($action=="matrix_disciplina"){
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ciencias Médicas y de la Salud","med","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ciencias Médicas y de la Salud","med","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ciencias Médicas y de la Salud","med","2012","2017");

    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ciencias Agrícolas","agraria","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ciencias Agrícolas","agraria","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ciencias Agrícolas","agraria","2012","2017");

    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ingeniería y Tecnología","tec","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ingeniería y Tecnología","tec","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,"Ingeniería y Tecnología","tec","2012","2017");
  }
  if($action=="matrix_financiacion"){
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ciencias Médicas y de la Salud","med","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ciencias Médicas y de la Salud","med","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ciencias Médicas y de la Salud","med","2012","2017");

    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ciencias Agrícolas","agraria","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ciencias Agrícolas","agraria","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ciencias Agrícolas","agraria","2012","2017");

    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ingeniería y Tecnología","tec","2000","2005");
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ingeniería y Tecnología","tec","2006","2011");
    getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,"Ingeniería y Tecnología","tec","2012","2017");
  }
  if($action=="proyectos_full"){
    $proyectos = array();
    getProyectosInvestigacionPorAreaArray($link,"es","Ciencias Médicas y de la Salud","med",$proyectos);
    getProyectosInvestigacionPorAreaArray($link,"es","Ciencias Agrícolas","agraria",$proyectos);
    getProyectosInvestigacionPorAreaArray($link,"es","Ingeniería y Tecnología","tec",$proyectos);
    $headers = create_csv_proyectos_header_array();
    create_csv_file("proyectos_investigacion_cvuy_completo.csv",$headers,$proyectos);
  }

  if($action=="proyectos_control_inst"){
    $proyectos = array();
    getProyectosInvestigacionPorAreaControlArray($link,"es","Ciencias Médicas y de la Salud","med",$proyectos);
    getProyectosInvestigacionPorAreaControlArray($link,"es","Ciencias Agrícolas","agraria",$proyectos);
    getProyectosInvestigacionPorAreaControlArray($link,"es","Ingeniería y Tecnología","tec",$proyectos);
    $headers = create_csv_proyectos_header_control_array();
    create_csv_file("proyectos_investigacion_cvuy_control.csv",$headers,$proyectos);

  }
  if($action=="recorte"){
      setRecorteProyectosPorAreaYduplicados($link);
  }
}
$link->close();

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

function getProyectosInvestigacionPlanillaMadreDisciplinas($link,$idioma,$area,$area_short){
  $sql = "SELECT * from recorte_proyecto_investigacion";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14712){
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
            $datosProyecto[] = $row["tipo_institucion"];
            $datosProyecto[] = $row["subinstitucion_nueva"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            //$palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            //$financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva	from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",75);
            $peopleArray = getPeopleArrayFromInvestigationProjectDisciplina($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$areasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_proyectos_header_array_disciplinas();
      //exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_".$idioma."_".$area_short,$proyectos);
      create_csv_file("proyectos_investigacion_cvuy_disciplinas_".$idioma."_".$area_short.".csv",$headers,$proyectos);
  }
}

function getProyectosInvestigacionPlanillaMadre($link,$idioma,$area,$area_short){
  $sql = "SELECT * from recorte_proyecto_investigacion";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14712){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          //if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            if($row['idioma']=="en"){
              $datosProyecto[] = $row["tituloTraducido"];
              $datosProyecto[] = $row["descripcionTraducida"];
            }else{
              $datosProyecto[] = $row["titulo"];
              $datosProyecto[] = $row["descripcion"];
            }
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["tipo_institucion"];
            $datosProyecto[] = $row["subinstitucion_nueva"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva	from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",75);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          //}
        }
      }
      $headers = create_csv_proyectos_header_array();
      exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_".$idioma."_".$area_short,$proyectos);
      create_csv_file("proyectos_investigacion_cvuy_".$idioma."_".$area_short.".csv",$headers,$proyectos);
  }
}

function getProyectosInvestigacionPorAreaArray($link,$idioma,$area,$area_short,&$proyectos){
  $sql = "SELECT r.*, i.sni from recorte_proyecto_investigacion r, investigadores i where
  r.documento = i.documento and
  (r.id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or r.documento in (SELECT documento FROM area_principal WHERE area = '".$area."'))";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  //$proyectos = array();
  $proyectosPrimerPeriodo = array();
  $proyectosSegundoPeriodo = array();
  $proyectosTercerPeriodo = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if(1==1){
        //if($count<14512){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          //if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            $datosProyecto[] = $area;
            $datosProyecto[] = $row['sni'];
            if($row['idioma']=="en"){
              $datosProyecto[] = $row["tituloTraducido"];
              $datosProyecto[] = $row["descripcionTraducida"];
            }else{
              $datosProyecto[] = $row["titulo"];
              $datosProyecto[] = $row["descripcion"];
            }
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["tipo_institucion"];
            $datosProyecto[] = $row["subinstitucion_nueva"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva	from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",75);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
            if(isset($row["fecha_inicio"])){
              $anio = substr($row["fecha_inicio"], 0, 4);
              if($anio>="2000" && $anio <="2005"){
                $proyectosPrimerPeriodo[] = $filaArray;
              }
              if($anio>="2006" && $anio <="2011"){
                $proyectosSegundoPeriodo[] = $filaArray;
              }
              if($anio>="2012" && $anio <="2017"){
                $proyectosTercerPeriodo[] = $filaArray;
              }
            }
          //}
        }
      }
      exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_primerPeriodo_".$area_short,$proyectosPrimerPeriodo);
      exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_segundoPeriodo_".$area_short,$proyectosSegundoPeriodo);
      exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_tercerPeriodo_".$area_short,$proyectosTercerPeriodo);
      return $proyectos;
  }
}

function getProyectosInvestigacionPorAreaControlArray($link,$idioma,$area,$area_short,&$proyectos){
  $sql = "SELECT * from recorte_proyecto_investigacion where
  id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  //$proyectos = array();
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
          //if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            $datosProyecto[] = $area;
            if($row['idioma']=="en"){
              $datosProyecto[] = $row["tituloTraducido"];
              $datosProyecto[] = $row["descripcionTraducida"];
            }else{
              $datosProyecto[] = $row["titulo"];
              $datosProyecto[] = $row["descripcion"];
            }
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["tipo_institucion"];
            $datosProyecto[] = $row["subinstitucion_nueva"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $cantidadInvestigadores = 0;
            $cantidadCVsEncontrados = 0;
            $cantidadCVsEncontradosConInstitucion = 0;
            $controlArray = array();
            foreach ($peopleArray as $key => $value) {
              if(endsWith($key,"0")){
                //Es la cédula
                $cantidadInvestigadores += 1;
              }
              if(endsWith($key,"2")){
                //Si tiene CV o no
                if($value=="Si"){
                  $cantidadCVsEncontrados+=1;
                }
              }
              if(endsWith($key,"3")){
                //Si tiene CV o no
                if($value!=""){
                  $cantidadCVsEncontradosConInstitucion+=1;
                }
              }
            }
            $controlArray []= $cantidadInvestigadores;
            $controlArray []= $cantidadCVsEncontrados;
            $controlArray []= $cantidadCVsEncontradosConInstitucion;
            $filaArray = array_merge($datosProyecto,$controlArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          //}
        }
      }
      return $proyectos;
  }
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function getMatrixProyectosInvestigacionPorAreaPeriodoDisciplina($link,$area,$area_short,$anio_min,$anio_max){
  $sql = "SELECT * from recorte_proyecto_investigacion where
  SUBSTRING(fecha_inicio, 1, 4) >= ".$anio_min."
  and SUBSTRING(fecha_fin, 1, 4) <= ".$anio_max."
  and id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $instituciones = array();
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
          $datosProyecto[] = $id;
          $orgsArray = getPeopleArrayFromInvestigationProjectOnlyDisciplina($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],$instituciones);
          $proyectos[] = $orgsArray;
        }
      }
      sort($instituciones);
      $matrix = array();
      foreach ($instituciones as $key => $org) {
        $matrix[$org] = array();
        foreach ($instituciones as $key2 => $value2) {
          $matrix[$org][$value2] = 0;
        }
      }
      //$pares = array();
      foreach ($proyectos as $key => $orgsArray) {
        //$orgsParArray = array();
        foreach ($orgsArray as $key => $org) {
          //$orgsParArray[$org] = array();
          foreach ($orgsArray as $key => $value) {
            //$orgsParArray[$org] = $value;
            if($org!=$value){
              if(array_key_exists($value, $matrix[$org])){
                $matrix[$org][$value] = $matrix[$org][$value] + 1;
              }else if(array_key_exists($org, $matrix[$value])){
                $matrix[$value][$org] = $matrix[$value][$org] + 1;
              }
            }
          }
        }
        //$pares[] = $orgsParArray;
      }
      $headers = array();
      $headers[]="";
      foreach ($matrix as $org => $instArray) {
              $headers[]= $org;
      }
      $filas = array();
      foreach ($matrix as $org => $instArray) {
        $fila = array();
        $fila[]= $org;
        foreach ($instArray as $inst => $value) {
          if(array_key_exists($inst, $matrix[$org])){
            $fila[]= $matrix[$org][$inst];
          }else if(array_key_exists($org, $matrix[$inst])){
            $fila[]= $matrix[$inst][$org];
          }
        }
        $filas[]=$fila;
      }
      create_csv_file("matriz_disciplinas_proyectos_cvuy_".$area_short."_".$anio_min."_".$anio_max.".csv",$headers,$filas);
  }
}

function getMatrixProyectosInvestigacionPorAreaPeriodoFinanciacion($link,$area,$area_short,$anio_min,$anio_max){
  $sql = "SELECT * from recorte_proyecto_investigacion where
  SUBSTRING(fecha_inicio, 1, 4) >= ".$anio_min."
  and SUBSTRING(fecha_fin, 1, 4) <= ".$anio_max."
  and id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $instituciones = array();
  $institucionesFinanciadoras = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $institucionProyecto = $row["subinstitucion_nueva"];
          if(!in_array($institucionProyecto,$instituciones)){
            $instituciones[]=$institucionProyecto;
          }
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          $datosProyecto[] = $id;
          $orgsArray = getInstitucionesFinanciadoras($link,$id,$institucionProyecto,$institucionesFinanciadoras);
        }
      }
      ksort($institucionesFinanciadoras);
      sort($instituciones);
      $matrix = array();
      foreach ($institucionesFinanciadoras as $key => $org) {
        $matrix[$key] = array();
        foreach ($instituciones as $key2 => $value2) {
          //echo $key ." -> " . $value2 . " = " . $institucionesFinanciadoras[$key][$value2];
          if(array_key_exists($value2,$institucionesFinanciadoras[$key])){
            $matrix[$key][$value2] = $institucionesFinanciadoras[$key][$value2];
          }else{
            $matrix[$key][$value2] = 0;
          }
        }
      }
      $headers = array();
      $headers[]="";
      foreach ($instituciones as $key => $org) {
              $headers[]= $org;
      }
      $filas = array();
      foreach ($matrix as $org => $instArray) {
        $fila = array();
        $fila[]= $org;
        foreach ($instArray as $inst => $value) {
          if(array_key_exists($inst, $matrix[$org])){
            $fila[]= $matrix[$org][$inst];
          }
        }
        $filas[]=$fila;
      }
      create_csv_file("matriz_financiacion_proyectos_cvuy_".$area_short."_".$anio_min."_".$anio_max.".csv",$headers,$filas);
  }
}

function getInstitucionesFinanciadoras($link,$id,$institucionProyecto,&$institucionesFinanciadoras){
  $sql = "SELECT * from recorte_proyecto_investigacion_institucion_financiadora where idproyecto = '".$id."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if(!array_key_exists($row['subinstitucion_nueva'],$institucionesFinanciadoras)){
          $institucionesFinanciadoras[$row['subinstitucion_nueva']]=array();
        }
        if(!array_key_exists($institucionProyecto,$institucionesFinanciadoras[$row['subinstitucion_nueva']])){
          $institucionesFinanciadoras[$row['subinstitucion_nueva']][$institucionProyecto] = 1;
        }else{
          $institucionesFinanciadoras[$row['subinstitucion_nueva']][$institucionProyecto] += 1;
        }
      }
  }
}

function getMatrixProyectosInvestigacionPorAreaPeriodo($link,$area,$area_short,$anio_min,$anio_max){
  $sql = "SELECT * from recorte_proyecto_investigacion where
  SUBSTRING(fecha_inicio, 1, 4) >= ".$anio_min."
  and SUBSTRING(fecha_fin, 1, 4) <= ".$anio_max."
  and id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $instituciones = array();
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
          $datosProyecto[] = $id;
          $orgsArray = getPeopleArrayFromInvestigationProjectOnlyOrgs($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],$instituciones);
          $proyectos[] = $orgsArray;
        }
      }
      sort($instituciones);
      $matrix = array();
      foreach ($instituciones as $key => $org) {
        $matrix[$org] = array();
        foreach ($instituciones as $key2 => $value2) {
          $matrix[$org][$value2] = 0;
        }
      }
      //$pares = array();
      foreach ($proyectos as $key => $orgsArray) {
        //$orgsParArray = array();
        foreach ($orgsArray as $key => $org) {
          //$orgsParArray[$org] = array();
          foreach ($orgsArray as $key => $value) {
            //$orgsParArray[$org] = $value;
            if($org!=$value){
              if(array_key_exists($value, $matrix[$org])){
                $matrix[$org][$value] = $matrix[$org][$value] + 1;
              }else if(array_key_exists($org, $matrix[$value])){
                $matrix[$value][$org] = $matrix[$value][$org] + 1;
              }
            }
          }
        }
        //$pares[] = $orgsParArray;
      }
      $headers = array();
      $headers[]="";
      foreach ($matrix as $org => $instArray) {
              $headers[]= $org;
      }
      $filas = array();
      foreach ($matrix as $org => $instArray) {
        $fila = array();
        $fila[]= $org;
        foreach ($instArray as $inst => $value) {
          if(array_key_exists($inst, $matrix[$org])){
            $fila[]= $matrix[$org][$inst];
          }else if(array_key_exists($org, $matrix[$inst])){
            $fila[]= $matrix[$inst][$org];
          }
        }
        $filas[]=$fila;
      }
      create_csv_file("matriz_proyectos_cvuy_".$area_short."_".$anio_min."_".$anio_max.".csv",$headers,$filas);

      /*foreach ($matrix as $org => $instArray) {
        echo $org . ",";
      }
      echo "<br/>";
      foreach ($matrix as $org => $instArray) {
        echo $org . ",";
        foreach ($instArray as $inst => $value) {
          if(array_key_exists($inst, $matrix[$org])){
            echo $matrix[$org][$inst] . ",";
          }else if(array_key_exists($org, $matrix[$inst])){
            echo $matrix[$inst][$org] . ",";
          }
        }
        echo "<br/>";
      }*/
  }
}


function getProyectosInvestigacionPorIdiomaArea($link,$idioma,$area,$area_short){
  //COMENTO PARA DESCARTAR IDIOMA PORQUE AHORA TENEMOS LAS TRADUCCIONES
  /*$sql = "SELECT * from recorte_proyecto_investigacion where idioma = '".$idioma."' and
  (id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."'))";*/
  $sql = "SELECT * from recorte_proyecto_investigacion where
  id in (select idproyecto from proyecto_investigacion_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
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
          //if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosProyecto[] = $id;
            if($row['idioma']=="en"){
              $datosProyecto[] = $row["tituloTraducido"];
              $datosProyecto[] = $row["descripcionTraducida"];
            }else{
              $datosProyecto[] = $row["titulo"];
              $datosProyecto[] = $row["descripcion"];
            }
            $datosProyecto[] = $row["otra_descripcion"];
            $datosProyecto[] = $row["institucion"];
            $datosProyecto[] = $row["dependencia"];
            $datosProyecto[] = $row["tipo_institucion"];
            $datosProyecto[] = $row["subinstitucion_nueva"];
            $datosProyecto[] = $row["fecha_inicio"];
            $datosProyecto[] = $row["fecha_fin"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from proyecto_investigacion_palabra_clave where idproyecto='".$id."'",12);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from proyecto_investigacion_area where idproyecto='".$id."'",15);
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva	from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",75);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          //}
        }
      }
      $headers = create_csv_proyectos_header_array();
      exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_".$idioma."_".$area_short,$proyectos);
      create_csv_file("proyectos_investigacion_cvuy_".$idioma."_".$area_short.".csv",$headers,$proyectos);
  }
}

function getProyectosInvestigacionPorIdioma($link,$idioma){
  $sql = "SELECT * from recorte_proyecto_investigacion where idioma = '".$idioma."'";
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
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$financiadorasArray,$peopleArray);
            $proyectos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_proyectos_header_array();
      exportToTxtFormatForIramuteq("proyectos_investigacion_cvuy_".$idioma,$proyectos);
      create_csv_file("proyectos_investigacion_cvuy_".$idioma.".csv",$headers,$proyectos);
  }
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
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
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
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
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
            $financiadorasArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, tipofinanciacion, tipo_institucion, subinstitucion_nueva from recorte_proyecto_investigacion_institucion_financiadora where idproyecto='".$id."'",45);
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

function create_csv_proyectos_header_control_array(){
  $headers = array();
  $headers []= "ID Proyecto";
  $headers []= "AREA POR LA QUE ENTRO";
  $headers []= "Título proyecto";
  $headers []= "Descripción";
  $headers []= "Otra descripción";
  $headers []= "Institución dónde se presentó el proyecto";
  $headers []= "Dependencia dónde se presentó el proyecto";
  $headers []= "Cod Institución dónde se presentó el proyecto";
  $headers []= "Subinstitución normalizada dónde se presentó el proyecto";
  $headers []= "Fecha de inicio del proyecto";
  $headers []= "Fecha de fin del proyecto";
  $headers []= "Cantidad de investigadores totales";
  $headers []= "Cantidad de investigadores con CVUy matcheado";
  $headers []= "Cantidad de investigadores con instituciones matcheadas";
  return $headers;
}

function create_csv_proyectos_header_array_disciplinas(){
  $headers = array();
  $headers []= "ID Proyecto";
  $headers []= "AREA POR LA QUE ENTRO";
  $headers []= "SNI";
  $headers []= "Título proyecto";
  $headers []= "Descripción";
  $headers []= "Otra descripción";
  $headers []= "Institución dónde se presentó el proyecto";
  $headers []= "Dependencia dónde se presentó el proyecto";
  $headers []= "Cod Institución dónde se presentó el proyecto";
  $headers []= "Subinstitución normalizada dónde se presentó el proyecto";
  $headers []= "Fecha de inicio del proyecto";
  $headers []= "Fecha de fin del proyecto";
  for ($i=1; $i <6 ; $i++) {
    $headers []= "Área proyecto  ".$i;
    $headers []= "Subárea proyecto  ".$i;
    $headers []= "Disciplina proyecto  ".$i;
  }
  for ($i=1; $i <62 ; $i++) {
    $headers []= "ID investigador ".$i;
    $headers []= "Nombre completo investigador ".$i;
    $headers []= "CVUy si/no investigador ".$i;
    $headers []= "Área investigador ".$i;
    $headers []= "Subarea investigador ".$i;
    $headers []= "Disciplina investigador ".$i;
    $headers []= "Especialidad investigador ".$i;
    $headers []= "Tipo de coincidencia investigador ".$i;
  }
  return $headers;
}

function create_csv_proyectos_header_array(){
  $headers = array();
  $headers []= "ID Proyecto";
  $headers []= "AREA POR LA QUE ENTRO";
  $headers []= "Título proyecto";
  $headers []= "Descripción";
  $headers []= "Otra descripción";
  $headers []= "Institución dónde se presentó el proyecto";
  $headers []= "Dependencia dónde se presentó el proyecto";
  $headers []= "Cod Institución dónde se presentó el proyecto";
  $headers []= "Subinstitución normalizada dónde se presentó el proyecto";
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
    $headers []= "Cod Institución ".$i;
    $headers []= "Subinstitución normalizada ".$i;
  }
  for ($i=1; $i <62 ; $i++) {
    $headers []= "ID investigador ".$i;
    $headers []= "Nombre completo investigador ".$i;
    $headers []= "CVUy si/no investigador ".$i;
    $headers []= "Institución en ese momento investigador ".$i;
    $headers []= "Subinstitución en ese momento investigador ".$i;
    $headers []= "Cod Institución en ese momento investigador".$i;
    $headers []= "Subinstitución normalizada en ese momento investigador".$i;
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
        if($tituloNormalizado==$otro_titulo_normalizado){
          //COINCIDENCIA 100%
          return $row['id'];
        }else{
          if(partesDelStringContenidas($tituloNormalizado,$otro_titulo_normalizado)!=false){
            //Coincidencia de partes
            return $row['id'];
          }else{
            $similaridad = similaridadTextos($tituloNormalizado,$otro_titulo_normalizado);
            if($similaridad>=0.85){
              //SIMILARIDAD mayor al 85 %
              return $row['id'];
            }
          }
        }
      }
  }
  return false;
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
  $similaridad = 1 - (levenshtein($texto1,$texto2)/$mayorTamanio);
  //echo "La similaridad de $texto1 ||| $texto2 es: " . $similaridad . "<br/>";
  return $similaridad;
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

function getDisciplinaPrincipal($link,$documento){
  $sql = "SELECT * from area_principal where documento = '".$documento."' limit 1";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $dataArray['area']= $row['area'];
        $dataArray['subarea']= $row['subarea'];
        $dataArray['disciplina']= $row['disciplina'];
        $dataArray['especialidad']= $row['especialidad'];
        return $dataArray;
      }
  }
  return false;
}

function getPeopleArrayFromInvestigationProjectDisciplina($link,&$coincidencesCountArray,$projectId,$tituloNormalizado,$fechaInicio,$fechaFin,$maxLenghtArray){
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
            $displinaPrincipal = getDisciplinaPrincipal($link,$persona['id']);
            if($displinaPrincipal!=false){
              $dataArray[]= $displinaPrincipal['area'];
              $dataArray[]= $displinaPrincipal['subarea'];
              $dataArray[]= $displinaPrincipal['disciplina'];
              $dataArray[]= $displinaPrincipal['especialidad'];
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

function getPeopleArrayFromInvestigationProjectOnlyDisciplina($link,&$coincidencesCountArray,$projectId,$tituloNormalizado,$fechaInicio,$fechaFin,&$disciplinas){
  $sql = "SELECT * from recorte_proyecto_investigacion_equipo where idproyecto = '".$projectId."'";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idPersona = $row["id"];
        $persona = getPersonFromCoincidences($link,$projectId,$tituloNormalizado,$idPersona,$coincidencesCountArray);
        if($persona!=false){
/*          $dataArray[]= $persona['nombre_completo'];
          $dataArray[]= $persona['cvuy'];*/
          if($persona['cvuy']=="Si"){
            $tipo_coincidencia = $coincidencesCountArray[$idPersona]['tipo_coincidencia'];
            $proyecto_coincidente = null;
            if($tipo_coincidencia == "unica_con_proyecto" || $tipo_coincidencia == "multiple_con_proyecto"){
              $proyecto_coincidente = $coincidencesCountArray[$idPersona]['id_proyecto_coincidente'];
            }
            $displinaPrincipal = getDisciplinaPrincipal($link,$persona['id']);
            if($displinaPrincipal!=false){
              //$dataArray[]= $displinaPrincipal['area'];
              if(!in_array($displinaPrincipal['subarea'],$dataArray)){
                $dataArray[]=$displinaPrincipal['subarea'];
              }
              if(!in_array($displinaPrincipal['subarea'],$disciplinas)){
                $disciplinas[]=$displinaPrincipal['subarea'];
              }
              //$dataArray[]= $displinaPrincipal['disciplina'];
              //$dataArray[]= $displinaPrincipal['especialidad'];
              //$dataArray[] = $tipo_coincidencia;
            }else{
            }
          }else{
          }
        }
      }
  }
  return $dataArray;
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
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['tipo_institucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['subinstitucion_nueva'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['carga_horaria'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['daysOverlaping'];
              $dataArray[] = $tipo_coincidencia;
            }else{
              $dataArray[]="";
              $dataArray[]="";
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
            $dataArray[]="";
            $dataArray[]="";
          }
        }
      }
  }
  return $dataArray;
}

function getPeopleArrayFromInvestigationProjectOnlyOrgs($link,&$coincidencesCountArray,$projectId,$tituloNormalizado,$fechaInicio,$fechaFin,&$instituciones){
  $sql = "SELECT * from recorte_proyecto_investigacion_equipo where idproyecto = '".$projectId."'";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idPersona = $row["id"];
        $persona = getPersonFromCoincidences($link,$projectId,$tituloNormalizado,$idPersona,$coincidencesCountArray);
        if($persona!=false){
          /*$dataArray[]= $persona['id'];
          $dataArray[]= $persona['nombre_completo'];
          $dataArray[]= $persona['cvuy'];*/
          if($persona['cvuy']=="Si"){
            $tipo_coincidencia = $coincidencesCountArray[$idPersona]['tipo_coincidencia'];
            $proyecto_coincidente = null;
            if($tipo_coincidencia == "unica_con_proyecto" || $tipo_coincidencia == "multiple_con_proyecto"){
              $proyecto_coincidente = $coincidencesCountArray[$idPersona]['id_proyecto_coincidente'];
            }
            $vinculoInstitucionalDuranteElPeriodoDelProyecto = getVinculoInstitucionalDuranteElPeriodoDelProyecto($link,$persona['id'],$fechaInicio,$fechaFin,$tipo_coincidencia,$tituloNormalizado,$proyecto_coincidente);
            if($vinculoInstitucionalDuranteElPeriodoDelProyecto!=false){
              $org = $vinculoInstitucionalDuranteElPeriodoDelProyecto['subinstitucion_nueva'];
              //$dataArray[]= $org;
              if(!in_array($org, $dataArray)){
                $dataArray[] = $org;
              }
              if(!in_array($org, $instituciones)){
                $instituciones[] = $org;
              }
            }else{
            }
          }else{
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
      $dataArray['tipo_institucion'] = $row['tipo_institucion'];
      $dataArray['subinstitucion_nueva'] = $row['subinstitucion_nueva'];
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
                  $vinculo['tipo_institucion'] = $row['tipo_institucion'];
                  $vinculo['subinstitucion_nueva'] = $row['subinstitucion_nueva'];
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
                    $vinculo['tipo_institucion'] = $row['tipo_institucion'];
                    $vinculo['subinstitucion_nueva'] = $row['subinstitucion_nueva'];
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
  if(file_exists("/var/www/html/wscvuy/iramuteq/".$filename.".txt")){
    //NO HAGO NADA PORQUE YA EXISTE
  }else{
    $myfile = fopen("/var/www/html/wscvuy/iramuteq/".$filename.".txt", "a+");
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
  if(file_exists("/var/www/html/wscvuy/planillas/".$filename)){
    //NO HAGO NADA PORQUE YA EXISTE
  }else{
    $fp = fopen("/var/www/html/wscvuy/planillas/".$filename, "a+");
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
