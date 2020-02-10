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
  if($action=="investigadores"){
      getInvestigadores($link);
  }
  if($action=="puntual"){
      getInvestigadorPuntual($link);
  }
}
$link->close();

/*1953536*/
function getInvestigadorPuntual($link){
  $sql = "SELECT * from investigadores where documento = '01953536'";
  $result = $link->query($sql);
  $investigadores = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        //if($count<100){
          $datosInvestigador = array();
          $documentoPrincipal = $row["documento"];
          if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosInvestigador[] = $row["documento"];
            $datosInvestigador[] = "Si";
            $datosInvestigador[] = $row["nombres"];
            $datosInvestigador[] = $row["apellidos"];
            $datosInvestigador[] = $row["sni"];
            $institucionPrincipalArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion from institucion_principal where documento='".$documentoPrincipal."'",2);
            $ultimoNivelAcademicoArray = getUltimoNivelAcademico($link, $documentoPrincipal);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina, especialidad from area_principal where documento='".$documentoPrincipal."'",64);
            print_r($areasArray);
            $vinculosArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, inicio, fin, cargahoraria from vinculo_institucional where documento='".$documentoPrincipal."'",0);
            $filaArray = array_merge($datosInvestigador,$institucionPrincipalArray,$ultimoNivelAcademicoArray,$areasArray,$vinculosArray);
            $investigadores[$documentoPrincipal] = $filaArray;
          }
        //}
      }
      $headers = create_csv_investigadores_header_array();
      create_csv_file("investigadorPuntual.csv",$headers,$investigadores);
  }
}


function getInvestigadores($link){
  $sql = "SELECT * from investigadores";
  $result = $link->query($sql);
  $investigadores = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        //if($count<100){
          $datosInvestigador = array();
          $documentoPrincipal = $row["documento"];
          if(!array_key_exists_or_approaches($tituloNormalizado,$proyectos)){
            $datosInvestigador[] = $row["documento"];
            $datosInvestigador[] = "Si";
            $datosInvestigador[] = $row["nombres"];
            $datosInvestigador[] = $row["apellidos"];
            $datosInvestigador[] = $row["sni"];
            $institucionPrincipalArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion from institucion_principal where documento='".$documentoPrincipal."'",2);
            $ultimoNivelAcademicoArray = getUltimoNivelAcademico($link, $documentoPrincipal);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina, especialidad from area_principal where documento='".$documentoPrincipal."'",64);
            $vinculosArray = getValuesArrayFromSql($link,"SELECT institucion, subinstitucion, inicio, fin, cargahoraria from vinculo_institucional where documento='".$documentoPrincipal."'",0);
            $filaArray = array_merge($datosInvestigador,$institucionPrincipalArray,$ultimoNivelAcademicoArray,$areasArray,$vinculosArray);
            $investigadores[$documentoPrincipal] = $filaArray;
          }
        //}
      }
      $headers = create_csv_investigadores_header_array();
      create_csv_file("investigadores.csv",$headers,$investigadores);
  }
}

function getUltimoNivelAcademico($link, $documento){
  $sql = "SELECT * from formacion where documento = '".$documento."'";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      $mejorNivel = "";
      while($row = $result->fetch_assoc()) {
        if($mejorNivel==""){
          $mejorNivel = $row['nivelAcademico'];
          $dataArray = array();
          $dataArray[] = $row['nivelAcademico'];
          $dataArray[] = $row['obtencion'];
          $dataArray[] = $row['titulo'];
          $dataArray[] = $row['institucion'];
          $dataArray[] = $row['subInstitucion'];
          $dataArray[] = $row['pais'];
        }else{
          if($mejorNivel=="Grado"){
            if($row['nivelAcademico']=="Maestría" || $row['nivelAcademico']=="Doctorado"){
              $mejorNivel = $row['nivelAcademico'];
              $dataArray = array();
              $dataArray[] = $row['nivelAcademico'];
              $dataArray[] = $row['obtencion'];
              $dataArray[] = $row['titulo'];
              $dataArray[] = $row['institucion'];
              $dataArray[] = $row['subInstitucion'];
              $dataArray[] = $row['pais'];
            }
          }else if($mejorNivel=="Maestría"){
            if($row['nivelAcademico']=="Doctorado"){
              $mejorNivel = $row['nivelAcademico'];
              $dataArray = array();
              $dataArray[] = $row['nivelAcademico'];
              $dataArray[] = $row['obtencion'];
              $dataArray[] = $row['titulo'];
              $dataArray[] = $row['institucion'];
              $dataArray[] = $row['subInstitucion'];
              $dataArray[] = $row['pais'];
            }
          }
        }

      }
  }
  if(count($dataArray)<6){
    $lastItem = count($dataArray);
    for ($i=$lastItem; $i < 7; $i++) {
      $dataArray[] = "";
    }
  }
  return $dataArray;
}

function create_csv_investigadores_header_array(){
  $headers = array();
  $headers []= "ID";
  $headers []= "CVUy Si/No";
  $headers []= "Nombres";
  $headers []= "Apellidos";
  $headers []= "SNI si/no";
  $headers []= "Institución principal";
  $headers []= "Subinstitución principal";
  $headers []= "Último nivel académico";
  $headers []= "Fecha obtención último nivel académico";
  $headers []= "Título último nivel académico";
  $headers []= "Institución último nivel académico";
  $headers []= "Dependencia último nivel académico";
  $headers []= "País último nivel académico";
  for ($i=1; $i <17 ; $i++) {
    $headers []= "Área ".$i;
    $headers []= "Subárea ".$i;
    $headers []= "Disciplina ".$i;
    $headers []= "Especialidad ".$i;
  }
  for ($i=1; $i <146 ; $i++) {
    $headers []= "Institución ".$i;
    $headers []= "Subinstitución ".$i;
    $headers []= "Inicio ".$i;
    $headers []= "Fin ".$i;
    $headers []= "Carga horaria ".$i;
  }
  return $headers;
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

function getPersonFromCoincidences($link,$projectId,$tituloNormalizado,$idPersona,$coincidencesCountArray){
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
            //TOMO EL QUE ESTA PORQUE ES EL UNICO QUE ENCONTRO
            $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
            return $persona;
          }else{
            if($coincidencesCountArray[$idPersona]['ya_en_planilla']==false){
              //ME FIJO SI ESTE PUEDE SER EL VALIDO BUSCANDO EL PROYECTO EN SU CVUy
              $defineProyectoEnSuCv = checkIfPersonAlsoHasProjectInsideCVuy($link,$documento_encontrado,$projectId,$tituloNormalizado);
              if($defineProyectoEnSuCv!=false){
                //TIENE TAMBIEN DEFINIDO EL PROYECTO EN SU CV, ENTONCES ES ESTA PERSONA
                $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
                $coincidencesCountArray[$idPersona]['ya_en_planilla']=true;
                return $persona;
              }
            }
          }
        }
      }
  }
  return false;
}

function getPeopleArrayFromInvestigationProject($link,$coincidencesCountArray,$projectId,$tituloNormalizado,$fechaInicio,$fechaFin,$maxLenghtArray){
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
