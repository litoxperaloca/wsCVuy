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
  $action = $_GET['action'];
  if($action=="coincidencias_multiples"){
      getCoincidenciasMultiples($link);
  }
  if($action=="coincidencias_totales"){
      getCoincidenciasTotales($link);
  }
}
$link->close();

function getCoincidenciasUnicasSinProyecto($link){
  $sql = "SELECT id, count(id) as cantidad FROM recorte_proyecto_investigacion_equipo_coincidencias
          group by id having count(id) = 1";
  $result = $link->query($sql);
  $proyectos = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idIntegrante = $row["id"];
        $datosProyecto = getProyectoByIntegranteId($link,$idIntegrante);
        $tituloNormalizado = cleanName($datosProyecto[3]);
        $datosintegrante = getDatosIntegranteByIntegranteId($link, $idIntegrante);
        $datosintegrante[] = $row['cantidad'];
        $coincidencias = getCoincidenciasByIntegranteId($link, $idIntegrante,$datosProyecto[0],$tituloNormalizado);
        $filaArray = array_merge($datosProyecto,$datosintegrante,$coincidencias);
        $proyectos[$tituloNormalizado] = $filaArray;
      }
      $headers = create_csv_coincidencias_header_array();
      create_csv_file("coincidencias_totales.csv",$headers,$proyectos);
  }
}

function getCoincidenciasTotales($link){
  $sql = "SELECT id, count(id) as cantidad FROM recorte_proyecto_investigacion_equipo_coincidencias
          group by id having count(id) = 1";
  $result = $link->query($sql);
  $proyectos = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idIntegrante = $row["id"];
        $datosProyecto = getProyectoByIntegranteId($link,$idIntegrante);
        $tituloNormalizado = cleanName($datosProyecto[3]);
        $datosintegrante = getDatosIntegranteByIntegranteId($link, $idIntegrante);
        $datosintegrante[] = $row['cantidad'];
        $coincidencias = getCoincidenciasByIntegranteIdConProyecto($link, $idIntegrante,$datosProyecto[0],$tituloNormalizado);
        $filaArray = array_merge($datosProyecto,$datosintegrante,$coincidencias);
        $proyectos[$tituloNormalizado] = $filaArray;
      }
      $headers = create_csv_coincidencias_header_array();
      create_csv_file("coincidencias_multiples.csv",$headers,$proyectos);
  }
}

function getCoincidenciasMultiples($link){
  $sql = "SELECT id, count(id) as cantidad FROM recorte_proyecto_investigacion_equipo_coincidencias
          group by id having count(id) > 1 order by cantidad desc";
  $result = $link->query($sql);
  $proyectos = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idIntegrante = $row["id"];
        $datosProyecto = getProyectoByIntegranteId($link,$idIntegrante);
        $tituloNormalizado = cleanName($datosProyecto[3]);
        $datosintegrante = getDatosIntegranteByIntegranteId($link, $idIntegrante);
        $datosintegrante[] = $row['cantidad'];
        $coincidencias = getCoincidenciasByIntegranteId($link, $idIntegrante,$datosProyecto[0],$tituloNormalizado);
        $filaArray = array_merge($datosProyecto,$datosintegrante,$coincidencias);
        $proyectos[$tituloNormalizado] = $filaArray;
      }
      $headers = create_csv_coincidencias_header_array();
      create_csv_file("coincidencias_multiples.csv",$headers,$proyectos);
  }
}

function create_csv_coincidencias_header_array(){
  $headers = array();
  $headers []= "ID Proyecto";
  $headers []= "Documento de quién presenta proyecto";
  $headers []= "Institución dónde se presentó el proyecto";
  $headers []= "Título proyecto";
  $headers []= "Descripción";
  $headers []= "Fecha de inicio del proyecto";
  $headers []= "Fecha de fin del proyecto";
  $headers []= "Integrante Buscado";
  $headers []= "Citación Integrante Buscado";
  $headers []= "Cantidad de coincidencias encontradas";
  for ($i=1; $i <159 ; $i++) {
    $headers []= "Documento Coincidencia ".$i;
    $headers []= "Tipo Coincidencia ".$i;
    $headers []= "Nombres Coincidencia ".$i;
    $headers []= "Apellidos Coincidencia ".$i;
    $headers []= "Citación Coincidencia ".$i;
    $headers []= "Define el proyecto Coincidencia ".$i;

  }
  return $headers;
}

function getProyectoByIntegranteId($link, $idIntegrante){
  $sql = "SELECT p.*
          FROM `proyecto_investigacion` p, proyecto_investigacion_equipo e
          where p.id = e.idproyecto
          and e.id = '".$idIntegrante."'";
  $result = $link->query($sql);
  $proyecto = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $proyecto[] = $row["id"];
        $proyecto[] = $row["documento"];
        $proyecto[] = $row["institucion"];
        $proyecto[] = $row["titulo"];
        $proyecto[] = $row["descripcion"];
        $proyecto[] = $row["fecha_inicio"];
        $proyecto[] = $row["fecha_fin"];
      }
  }
  return $proyecto;
}

function getDatosIntegranteByIntegranteId($link, $idIntegrante){
  $sql = "SELECT nombres, apellidos, citacion FROM `proyecto_investigacion_equipo` WHERE id = '".$idIntegrante."'";
  $result = $link->query($sql);
  $integrante = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $integrante[] = $row["nombres"] . " " . $row["apellidos"];
        $integrante[] = $row["citacion"];
      }
  }
  return $integrante;
}

function getCoincidenciasByIntegranteIdConProyecto($link, $idIntegrante,$projectId,$tituloNormalizado){
  $sql = "SELECT c.documento_encontrado as documento, c.tipo_coincidencia as tipo,
          i.nombres as nombres, i.apellidos as apellidos,
          d.citacion as citacion
          FROM proyecto_investigacion_equipo_coincidencias c, investigadores i, datos_identificacion d
          WHERE c.documento_encontrado = i.documento
          and i.documento = d.documento
          and id='".$idIntegrante."'";
  $result = $link->query($sql);
  $coincidencias = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $defineProyectoEnSuCv = checkIfPersonAlsoHasProjectInsideCVuy($link,$row["documento"],$projectId,$tituloNormalizado);
        if($defineProyectoEnSuCv!=false){
          $coincidencias[] = $row["documento"];
          $coincidencias[] = $row["tipo"];
          $coincidencias[] = $row["nombres"];
          $coincidencias[] = $row["apellidos"];
          $coincidencias[] = $row["citacion"];
          $coincidencias[] = "ESTE TIENE PROYECTO CON TITULO MUY SIMILAR";
        }else{
        }
      }
  }
  return $coincidencias;
}

function getCoincidenciasByIntegranteId($link, $idIntegrante,$projectId,$tituloNormalizado){
  $sql = "SELECT c.documento_encontrado as documento, c.tipo_coincidencia as tipo,
          i.nombres as nombres, i.apellidos as apellidos,
          d.citacion as citacion
          FROM proyecto_investigacion_equipo_coincidencias c, investigadores i, datos_identificacion d
          WHERE c.documento_encontrado = i.documento
          and i.documento = d.documento
          and id='".$idIntegrante."'";
  $result = $link->query($sql);
  $coincidencias = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $coincidencias[] = $row["documento"];
        $coincidencias[] = $row["tipo"];
        $coincidencias[] = $row["nombres"];
        $coincidencias[] = $row["apellidos"];
        $coincidencias[] = $row["citacion"];
        $defineProyectoEnSuCv = checkIfPersonAlsoHasProjectInsideCVuy($link,$row["documento"],$projectId,$tituloNormalizado);
        if($defineProyectoEnSuCv!=false){
          $coincidencias[] = "ESTE TIENE PROYECTO CON TITULO MUY SIMILAR";
        }else{
          $coincidencias[] = "No";
        }
      }
  }
  return $coincidencias;
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
          '".$row['alumno_getCoincidenciasByIntegranteIdmaestria_prof']."',
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

function getProyectosInvestigacion($link){
  $sql = "SELECT * from recorte_proyecto_investigacion";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $proyectos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<35221){
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
            $peopleArray = getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["fecha_inicio"],$row["fecha_fin"],366);
            $filaArray = array_merge($datosProyecto,$palabrasArray,$areasArray,$peopleArray);
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
  for ($i=1; $i <62 ; $i++) {
    $headers []= "ID investigador ".$i;
    $headers []= "Nombre completo investigador ".$i;
    $headers []= "CVUy si/no investigador ".$i;
    $headers []= "Institución en ese momento investigador ".$i;
    $headers []= "Subinstitución en ese momento investigador ".$i;
    $headers []= "Carga horaria en ese momento investigador ".$i;
  }
  return $headers;
}

function getPeopleCoincidencesCountArray($link){
  $sql = "SELECT id, count(*) as cantidad FROM `proyecto_investigacion_equipo_coincidencias` group by id";
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
          return true;
        }
      }
  }
  return false;
}

function getPersonFromCoincidences($link,$projectId,$tituloNormalizado,$idPersona,&$coincidencesCountArray){
  $sql = "SELECT * from proyecto_investigacion_equipo_coincidencias where id = '".$idPersona."'";
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
              $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
            }else{
              //TOMO EL QUE ESTA PORQUE ES EL UNICO QUE ENCONTRO (COINCIDENCIA 80%)
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
  $sql = "SELECT * from proyecto_investigacion_equipo where idproyecto = '".$projectId."'";
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
            $vinculoInstitucionalDuranteElPeriodoDelProyecto = getVinculoInstitucionalDuranteElPeriodoDelProyecto($link,$persona['id'],$fechaInicio,$fechaFin);
            if($vinculoInstitucionalDuranteElPeriodoDelProyecto!=false){
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['institucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['subinstitucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProyecto['carga_horaria'];
            }else{
              $dataArray[]="";
              $dataArray[]="";
              $dataArray[]="";
            }
          }else{
            $dataArray[]="";
            $dataArray[]="";
            $dataArray[]="";
          }
        }
      }
      /*if(count($dataArray<$maxLenghtArray)){
        $lastItem = count($dataArray);
        for ($i=$lastItem; $i < $maxLenghtArray; $i++) {
          $dataArray[] = "";
        }
      }*/
  }
  return $dataArray;
}

function getVinculoInstitucionalDuranteElPeriodoDelProyecto($link,$documento,$fechaInicio,$fechaFin){
  $sql = "SELECT * from vinculo_institucional where documento = '".$documento."'";
  $result = $link->query($sql);
  $dataArray = array();
  $fechaInicioComparable = $fechaInicio;
  $fechaFinComparable = $fechaFin;
  $lastMaxHours = 0;
  $vinculo = false;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        if($row['inicio']==''){

        }else{
          $fechaInicioVinculo = DateTime::createFromFormat('d/m/Y', $row['inicio'])->format('Y-m-d');
          $fechaFinVinculo = null;
          if(!is_null($row['fin']) && $row['fin']!=""){
            $fechaFinVinculo = DateTime::createFromFormat('d/m/Y', $row['fin'])->format('Y-m-d');
          }
          //COMPARO fECHAS
          if($fechaInicioVinculo<=$fechaInicioComparable){
            if($fechaFinComparable==null && $fechaFinVinculo==null){
              if($row['cargahoraria']>$lastMaxHours){
                $lastMaxHours = $row['cargahoraria'];
                $vinculo = array();
                $vinculo['institucion'] = $row['institucion'];
                $vinculo['subinstitucion'] = $row['subinstitucion'];
                $vinculo['carga_horaria'] = $row['cargahoraria'];
                return $vinculo;
              }
            }
            if($fechaFinComparable!=null && $fechaFinVinculo!=null){
              if($fechaFinVinculo>=$fechaFinComparable){
                if($row['cargahoraria']>$lastMaxHours){
                  $lastMaxHours = $row['cargahoraria'];
                  $vinculo = array();
                  $vinculo['institucion'] = $row['institucion'];
                  $vinculo['subinstitucion'] = $row['subinstitucion'];
                  $vinculo['carga_horaria'] = $row['cargahoraria'];
                  return $vinculo;
                }
              }
            }
          }
        }

      }
  }
  return $vinculo;
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
  $fp = fopen($_SERVER["DOCUMENT_ROOT"]."/planillas/".$filename, "a+");
  fputcsv($fp,$headers);
  foreach ($data_multi_array as $key => $data_array) {
    fputcsv($fp, $data_array);
  }
  fclose($fp);
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
