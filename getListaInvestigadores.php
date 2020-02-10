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
  if($action=="armarLista"){
      $investigadoresArray = getLista($link);
  }
  if($action=="datosAlonso"){
    getDatosFaltantes($link);
  }
  if($action=="traerCVs"){
      getCVs($link);
  }
  if($action=="traerVinculos"){
      getVinculos($link);
  }
  if($action=="checkVinculos"){
      checkVinculos($link);
  }
  if($action=="findInvestigationTeam"){
      findAndMatchPeople($link, "proyecto_investigacion_equipo");
  }
  if($action=="findProduccionTecnicaTeam"){
      findAndMatchPeople($link, "produccion_tecnica_productos_coautor");
  }
}
$link->close();

function findAndMatchPeople($link,$tablename){
  $count = 0;
  $countCoincidencias = 0;
  $countCoincidenciasParciales = 0;
  $countCoincidenciasLevenshteinUno = 0;
  $countCoincidenciasLevenshteinDos = 0;
  $countCoincidenciasCitacionTotal = 0;
  $countCoincidenciasCitacionParcial = 0;
  $sql2 = "SELECT * FROM ".$tablename;
  $result2 = $link->query($sql2);
  $namesArray = createNamesArray($link);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        $count += 1;
        $nombres = $row2["nombres"];
        $apellidos = $row2["apellidos"];
        $citacion = $row2["citacion"];
        $citacion = cleanName(strtolower(trim($citacion)));
        $nombreCompleto = $nombres . " " . $apellidos;
        $nombreCompleto = cleanName(strtolower(trim($nombreCompleto)));
        if(str_word_count($nombreCompleto)>=2){
          if(hayDosPalabrasCompletas($nombreCompleto)){
            $idPersonaNotFound = findPersonInNoCVuyList($link,$nombreCompleto);
            if($idPersonaNotFound==false){
              $persona = findPeopleInsideCVUyListByName($namesArray,$nombreCompleto);
              $encontrado = false;
              if($persona != false){
                foreach ($persona as $key => $personaArray) {
                  $countCoincidencias += 1;
                  updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"total");
                  echo $nombreCompleto . " coincide con " . $personaArray['nombreCompleto'] . "<br/>";
                }
                $encontrado = true;
              }else{
                $persona = findPeopleInsideCVUyListByWords($namesArray,$nombreCompleto);
                if($persona != false){
                  foreach ($persona as $key => $personaArray) {
                    $countCoincidenciasParciales += 1;
                    updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"parcial");
                    echo $nombreCompleto . " coincide parcialmente con " . $personaArray['nombreCompleto'] . "<br/>";
                  }
                  $encontrado = true;
                }else{
                  $persona = findPeopleInsideCVUyListByNameLevenshtein($namesArray,$nombreCompleto,1);
                  if($persona != false){
                    foreach ($persona as $key => $personaArray) {
                      $countCoincidenciasLevenshteinUno += 1;
                      updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"Levenshtein_Uno");
                      echo $nombreCompleto . " coincide LevenshteinUno con " . $personaArray['nombreCompleto'] . "<br/>";
                    }
                    $encontrado = true;
                  }else {
                    $persona = findPeopleInsideCVUyListByNameLevenshtein($namesArray,$nombreCompleto,2);
                    if($persona != false){
                      foreach ($persona as $key => $personaArray) {
                        $countCoincidenciasLevenshteinDos += 1;
                        updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"Levenshtein_Dos");
                        echo $nombreCompleto . " coincide LevenshteinDos con " . $personaArray['nombreCompleto'] . "<br/>";
                      }
                      $encontrado = true;
                    }
                  }
                }
              }
              if($encontrado==false){
                insertNotCVUyMember($link,$nombreCompleto);
                $notFoundId = findPersonInNoCVuyList($link,$nombreCompleto);
                if($notFoundId!=false){
                  updateNotFoundPeopleInsideTable($link,$tablename,$row2["id"],$notFoundId);
                }
              }
            }else{
              updateNotFoundPeopleInsideTable($link,$tablename,$row2["id"],$idPersonaNotFound);
            }
          }else{
            $idPersonaNotFound = findPersonInNoCVuyListCitation($link,$citacion);
            if($idPersonaNotFound==false){
              $persona = findPeopleInsideCVUyListByCitationTotal($namesArray,$citacion);
              if($persona != false){
                foreach ($persona as $key => $personaArray) {
                  $countCoincidenciasCitacionTotal += 1;
                  updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"CitacionTotal");
                  echo "La citación" . $citacion . " coincide totalmente con " . $personaArray['citacion'] . "<br/>";
                }
                $encontrado = true;
              }else{
                $persona = findPeopleInsideCVUyListByCitationParcial($namesArray,cleanName($citacion));
                if($persona != false){
                  foreach ($persona as $key => $personaArray) {
                    $countCoincidenciasCitacionParcial += 1;
                    updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"CitacionParcial");
                    echo "La citación" . $citacion . " coincide parcialmente con " . $personaArray['citacion'] . "<br/>";
                  }
                  $encontrado = true;
                }
              }
              if($encontrado==false){
                insertNotCVUyMemberCitation($link,$citacion);
                $notFoundId = findPersonInNoCVuyListCitation($link,$citacion);
                if($notFoundId!=false){
                  updateNotFoundPeopleInsideTable($link,$tablename,$row2["id"],$notFoundId);
                }
              }
            }else{
              updateNotFoundPeopleInsideTable($link,$tablename,$row2["id"],$idPersonaNotFound);
            }
          }
        }else{
          $idPersonaNotFound = findPersonInNoCVuyListCitation($link,$citacion);
          if($idPersonaNotFound==false){
            $persona = findPeopleInsideCVUyListByCitationTotal($namesArray,$citacion);
            if($persona != false){
              foreach ($persona as $key => $personaArray) {
                $countCoincidenciasCitacionTotal += 1;
                updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"CitacionTotal");
                echo "La citación" . $citacion . " coincide totalmente con " . $personaArray['citacion'] . "<br/>";
              }
              $encontrado = true;
            }else{
              $persona = findPeopleInsideCVUyListByCitationParcial($namesArray,cleanName($citacion));
              if($persona != false){
                foreach ($persona as $key => $personaArray) {
                  $countCoincidenciasCitacionParcial += 1;
                  updatePeopleInsideTable($link,$tablename,$row2["id"],$personaArray,"CitacionParcial");
                  echo "La citación" . $citacion . " coincide parcialmente con " . $personaArray['citacion'] . "<br/>";
                }
                $encontrado = true;
              }
            }
            if($encontrado==false){
              insertNotCVUyMemberCitation($link,$citacion);
              $notFoundId = findPersonInNoCVuyListCitation($link,$citacion);
              if($notFoundId!=false){
                updateNotFoundPeopleInsideTable($link,$tablename,$row2["id"],$notFoundId);
              }
            }
          }else{
            updateNotFoundPeopleInsideTable($link,$tablename,$row2["id"],$idPersonaNotFound);
          }
        }
      }
  }
  echo "COINCIDENCIAS TOTALES: " . $countCoincidencias . "<br/>";
  echo "COINCIDENCIAS PARCIALES: " . $countCoincidenciasParciales . "<br/>";
  echo "COINCIDENCIAS Levenshtein 1: " . $countCoincidenciasLevenshteinUno. "<br/>";
  echo "COINCIDENCIAS Levenshtein 2: " . $countCoincidenciasLevenshteinDos. "<br/>";
  echo "COINCIDENCIAS Citación total: " . $countCoincidenciasCitacionTotal. "<br/>";
  echo "COINCIDENCIAS Citación parciales: " . $countCoincidenciasCitacionParcial. "<br/>";

}

function hayDosPalabrasCompletas($str){
  $wordsArray = explode(" ",$str);
  foreach ($wordsArray as $key => $word) {
    if(strlen($word)<=2){
      return false;
    }
  }
  return true;
}

function updateNotFoundPeopleInsideTable($link,$table,$id,$notFoundId){
  $tableCoincidences = $table."_coincidencias";
  $sql = "INSERT into ".$tableCoincidences." (id,id_investigador_no_cv_uy) values ('".$id."','".$notFoundId."')";
  $result = $link->query($sql);
}

function updatePeopleInsideTable($link,$table,$id,$personArray,$coincidence){
  $tableCoincidences = $table."_coincidencias";
  $sql = "INSERT into ".$tableCoincidences." (id,documento_encontrado,tipo_coincidencia) values ('".$id."','".$personArray['documento']."','".$coincidence."')";
  $result = $link->query($sql);
}

function insertNotCVUyMember($link,$nombreCompleto){
  $sql = "INSERT into investigadores_no_cvuy (nombre_completo) values ('".$nombreCompleto."')";
  $result = $link->query($sql);
  return $result;
}

function insertNotCVUyMemberCitation($link,$citacion){
  $sql = "INSERT into investigadores_no_cvuy (citacion) values ('".$citacion."')";
  $result = $link->query($sql);
  return $result;
}

function createNamesArray($link){
  $investigadores = array();
  $sql2 = "SELECT i.documento as documento, i.nombres as nombres, i.apellidos as apellidos, d.citacion as citacion FROM investigadores i, datos_identificacion d where i.documento = d.documento";
  $result2 = $link->query($sql2);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        $investigador = array();
        $investigador['documento'] = $row2['documento'];
        $nombreCompleto = $row2["nombres"] . " " . $row2["apellidos"];
        $investigador['nombres'] = cleanName(strtolower(trim($row2["nombres"])));
        $investigador['apellidos'] = cleanName(strtolower(trim($row2["apellidos"])));
        $investigador['citacion'] = cleanName(strtolower(trim($row2["citacion"])));
        $investigador['nombreCompleto'] = cleanName(strtolower(trim($nombreCompleto)));
        $investigadores[] = $investigador;
      }
  }
  return $investigadores;
}

function findPersonInNoCVuyList($link, $nombre){
  $sql2 = "SELECT * FROM investigadores_no_cvuy where nombre_completo = '".$nombre."'";
  $result2 = $link->query($sql2);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        return $row2['id'];
      }
  }else{
    return false;
  }
}

function findPersonInNoCVuyListCitation($link, $citacion){
  $sql2 = "SELECT * FROM investigadores_no_cvuy where citacion = '".$citacion."'";
  $result2 = $link->query($sql2);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        return $row2['id'];
      }
  }else{
    return false;
  }
}

function findPeopleInsideCVUyListByCitationTotal($namesArray,$citacion){
  $result = false;
  if(strlen($citacion)>=2){
    foreach ($namesArray as $key => $investigador) {
      if ($investigador['citacion']==$citacion){
          if($result==false){
            $result = array();
          }
          $result []= $investigador;
      }
    }
  }
  return $result;
}

function findPeopleInsideCVUyListByCitationParcial($namesArray,$citacion){
  $result = false;
  if(strlen($citacion)>=2){
    foreach ($namesArray as $key => $investigador) {
      $nombre = $investigador['citacion'];
      $nombreArray = explode(" ",$nombre);
      $otherNameArray = explode(" ",$citacion);
      $nameSize = count($nombreArray);
      $otherNameSize = count($otherNameArray);
      $coincidences = 0;
      if($otherNameSize<=$nameSize){
        foreach ($otherNameArray as $key => $word) {
          if (in_array($word, $nombreArray)) {
              $coincidences += 1;
          }
        }
        if($coincidences==$otherNameSize){
          if($result==false){
            $result = array();
          }
          $result []= $investigador;
        }
      }else{
        foreach ($nombreArray as $key => $word) {
          if (in_array($word, $otherNameArray)) {
              $coincidences += 1;
          }
        }
        if($coincidences==$nameSize){
          if($result==false){
            $result = array();
          }
          $result []= $investigador;
        }
      }
    }
  }
  return $result;
}

function findPeopleInsideCVUyListByName($namesArray,$nombreCompleto){
  $result = false;
  foreach ($namesArray as $key => $investigador) {
    if ($investigador['nombreCompleto']==$nombreCompleto){
        if($result==false){
          $result = array();
        }
        $result []= $investigador;
    }
  }
  return $result;
}

function findPeopleInsideCVUyListByNameLevenshtein($namesArray,$nombreCompleto,$shortest){
  $result = false;
  foreach ($namesArray as $key => $investigador) {
    if (levenshtein($investigador['nombreCompleto'], $nombreCompleto) == $shortest) {
        if($result==false){
          $result = array();
        }
        $result []= $investigador;
    }
  }
  return $result;
}

function findPeopleInsideCVUyListByWords($namesArray,$nombreCompleto){
  $result = false;
  foreach ($namesArray as $key => $investigador) {
    $nombre = $investigador['nombreCompleto'];
    $nombreArray = explode(" ",$nombre);
    $otherNameArray = explode(" ",$nombreCompleto);
    $nameSize = count($nombreArray);
    $otherNameSize = count($otherNameArray);
    $coincidences = 0;
    if($otherNameSize<=$nameSize){
      foreach ($otherNameArray as $key => $word) {
        if (in_array($word, $nombreArray)) {
            $coincidences += 1;
        }
      }
      if($coincidences==$otherNameSize){
        if($result==false){
          $result = array();
        }
        $result []= $investigador;
      }
    }else{
      foreach ($nombreArray as $key => $word) {
        if (in_array($word, $otherNameArray)) {
            $coincidences += 1;
        }
      }
      if($coincidences==$nameSize){
        if($result==false){
          $result = array();
        }
        $result []= $investigador;
      }
    }
  }
  return $result;
}

function cleanName($name){
  $name = preg_replace('/\s+/', ' ',$name);
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
  $name = str_replace("-"," ",$name);
  $name = str_replace("_"," ",$name);
  $name = str_replace(",","",$name);
  $name = str_replace("'","",$name);
  $name = str_replace("´","",$name);
  $name = preg_replace('/^dra /', '', $name);
  $name = preg_replace('/^dr /', '', $name);
  $name = preg_replace('/^Dra /', '', $name);
  $name = preg_replace('/^Dr /', '', $name);
  $name = preg_replace('/^Lic. /', '', $name);
  $name = str_replace(".","",$name);
  return $name;
}

function checkVinculos($link){
  $count = 0;
  $countSin = 0;
  $sql2 = "SELECT * FROM cvs_a_importar where status = 'IRESTRICTO'";
  $result2 = $link->query($sql2);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        $doc = $row2["documento"];
        $sql = "SELECT documento,cv_xml FROM investigadores where documento = '$doc'";
        $result = $link->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $item = array();
              $item['documento'] = $row["documento"];
              $item['cv_xml'] = $row["cv_xml"];
              if($item['documento']!="" && $item['cv_xml'] != ""){
                try{
                  $cv_xmlObject = new SimpleXMLElement($item['cv_xml'],null,false);
                }catch (Exception $error) {
                  echo $error->getMessage();
                }
                $tieneVinculos = checkVinculosPersona($cv_xmlObject,$link,$item['documento']);
                if($tieneVinculos==true){
                  $count += 1;
                }else{
                  echo $item['documento'] . " NO TIENE VINCULOS <br/>";
                  $countSin += 1;
                }
              }
            }
        }
      }
  }
  echo "CANTIDAD DE PERSONAS QUE DEFINEN VINCULOS EN CVUY: " . $count . "<br/>";
  echo "CANTIDAD DE PERSONAS QUE NO DEFINEN VINCULOS EN CVUY: " . $countSin . "<br/>";
}

function checkVinculosPersona($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM as $key => $item) {
    if($item!=null){
      $institucion = $item->institucion;
      foreach ($item->ITEM as $key => $item2) {
        $subInstitucion = $item2->sub_institucion;
        foreach ($item2->VINCULOS->ITEM as $key => $item3) {
          $count += 1;
        }
      }
    }
  }
  if($count>0){
    return true;
  }else{
    return false;
  }
}

function getDatosFaltantes($link){
  $sql2 = "SELECT documento,cv_xml FROM investigadores WHERE cv_xml != ''";
  $result2 = $link->query($sql2);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        $item = array();
        $item['documento'] = $row2["documento"];
        $item['cv_xml'] = $row2["cv_xml"];
        if($item['documento']!="" && $item['cv_xml'] != ""){
          try{
            $cv_xmlObject = new SimpleXMLElement($item['cv_xml'],null,false);
          }catch (Exception $error) {
            echo $error->getMessage();
          }
          parseHijos($cv_xmlObject,$link,$item['documento']);
          parseLibros($cv_xmlObject,$link,$item['documento']);
          parseTrabajosEventos($cv_xmlObject,$link,$item['documento']);
          parsePremios($cv_xmlObject,$link,$item['documento']);
          parseTutorias($cv_xmlObject,$link,$item['documento']);
          parseDocencia($cv_xmlObject,$link,$item['documento']);
          parseGestion($cv_xmlObject,$link,$item['documento']);
          parseDireccion($cv_xmlObject,$link,$item['documento']);
          parseOrganizacionEventos($cv_xmlObject,$link,$item['documento']);
          parseTextosRevistas($cv_xmlObject,$link,$item['documento']);
          parseMaterialDidactico($cv_xmlObject,$link,$item['documento']);
          parseComiteEvaluacionProyectos($cv_xmlObject,$link,$item['documento']);
        }
      }
  }
}

function parseComiteEvaluacionProyectos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->EVALUACIONES->PROYECTOS->COMITE->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_comite = $documento . "--" . $count;
      $institucion = $link->real_escape_string($item->institucion);
      $subInstitucion = $link->real_escape_string($item->subInstitucion);
      $nombre = $link->real_escape_string($item->nombre);
      $anio = $link->real_escape_string($item->anio);
      $periodoFin = $link->real_escape_string($item->periodoFin);
        try {
            $query="insert into comite_evaluador_proyectos (
              documento,
              id_comite,
              institucion,
              subInstitucion,
              nombre,
              anio,
              periodoFin
              ) values (
                '".$documento."',
                '".$id_comite."',
                '".$institucion."',
                '".$subInstitucion."',
                '".$nombre."',
                '".$anio."',
                '".$periodoFin."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseMaterialDidactico($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->OTRAS_PRODUCCIONES->DESARROLLO_MATERIAL_DIDACTICO->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_material = $documento . "--" . $count;
      $titulo = $link->real_escape_string($item->titulo);
      $anio = $link->real_escape_string($item->anio);
      $descripcion = $link->real_escape_string($item->descripcion);
      $web = $link->real_escape_string($item->web);
        try {
            $query="insert into material_didactico (
              documento,
              id_material,
              titulo,
              anio,
              descripcion,
              web
              ) values (
                '".$documento."',
                '".$id_material."',
                '".$titulo."',
                '".$anio."',
                '".$descripcion."',
                '".$web."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseTextosRevistas($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->BIBLIOGRAFICA->BIBLIOGRAFICA_TEXTOS->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_texto = $documento . "--" . $count;
      $tituloLugarPublicado = $link->real_escape_string($item->tituloLugarPublicado);
      $anio = $link->real_escape_string($item->anio);
      $claseTexto = $link->real_escape_string($item->claseTexto);
      $titulo = $link->real_escape_string($item->titulo);
      $medioDivulgacion = $link->real_escape_string($item->medioDivulgacion);
        try {
            $query="insert into textos_revistas (
              documento,
              id_texto,
              tituloLugarPublicado,
              anio,
              claseTexto,
              titulo,
              medioDivulgacion
              ) values (
                '".$documento."',
                '".$id_texto."',
                '".$tituloLugarPublicado."',
                '".$anio."',
                '".$claseTexto."',
                '".$titulo."',
                '".$medioDivulgacion."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseOrganizacionEventos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->OTRAS_PRODUCCIONES->PARTICIPACION_EVENTO->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_evento_org = $documento . "--" . $count;
      $tipoOtraProduccion = $link->real_escape_string($item->tipoOtraProduccion);
      $subTipoOtraProduccion = $link->real_escape_string($item->subTipoOtraProduccion);
      $anio = $link->real_escape_string($item->anio);
      $institucionPromotora = $link->real_escape_string($item->institucionPromotora);
      $pais = $link->real_escape_string($item->pais);
      $titulo = $link->real_escape_string($item->titulo);
        try {
            $query="insert into organizacion_eventos (
              documento,
              id_evento_org,
              tipoOtraProduccion,
              subTipoOtraProduccion,
              anio,
              institucionPromotora,
              pais,
              titulo
              ) values (
                '".$documento."',
                '".$id_evento_org."',
                '".$tipoOtraProduccion."',
                '".$subTipoOtraProduccion."',
                '".$anio."',
                '".$institucionPromotora."',
                '".$pais."',
                '".$titulo."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}


function parseDireccion($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM->ITEM->ACTIVIDADES->DIRECCION_ADMINISTRACION->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_direccion = $documento . "--" . $count;
      $dependencia = $link->real_escape_string($item->dependencia);
      $unidad = $link->real_escape_string($item->unidad);
      $cargaHorariaSemanal = $item->cargaHorariaSemanal;
      $descripcion = $link->real_escape_string($item->descripcion);
      $fechaInicio = $item->fechaInicio;
      $fechaFin = $item->fechaFin;
        try {
            $query="insert into direccion_administracion (
              documento,
              id_direccion,
              dependencia,
              unidad,
              fechaInicio,
              fechaFin,
              descripcion,
              cargaHorariaSemanal
              ) values (
                '".$documento."',
                '".$id_direccion."',
                '".$dependencia."',
                '".$unidad."',
                '".$fechaInicio."',
                '".$fechaFin."',
                '".$descripcion."',
                '".$cargaHorariaSemanal."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseGestion($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM->ITEM->ACTIVIDADES->GESTION_ACADEMICA->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_gestion = $documento . "--" . $count;
      $funcionDesempeniada = $link->real_escape_string($item->funcionDesempeniada);
      $tipoGestion = $link->real_escape_string($item->tipoGestion);
      $unidad = $item->unidad;
      $descripcion = $link->real_escape_string($item->descripcion);
      $fechaInicio = $item->fechaInicio;
      $fechaFin = $item->fechaFin;
      $cargaHorariaSemanal = $item->cargaHorariaSemanal;
        try {
            $query="insert into gestion_academica (
              documento,
              id_gestion,
              funcionDesempeniada,
              tipoGestion,
              unidad,
              descripcion,
              fechaInicio,
              fechaFin,
              cargaHorariaSemanal
              ) values (
                '".$documento."',
                '".$id_gestion."',
                '".$funcionDesempeniada."',
                '".$tipoGestion."',
                '".$unidad."',
                '".$descripcion."',
                '".$fechaInicio."',
                '".$fechaFin."',
                '".$cargaHorariaSemanal."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseDocencia($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM->ITEM->ACTIVIDADES->DOCENCIA->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_docencia = $documento . "--" . $count;
      $tipoDocencia = $link->real_escape_string($item->tipoDocencia);
      $descripcion = $link->real_escape_string($item->ASIGNATURAS->ITEM->descripcion);
      $fechaInicio = $item->fechaInicio;
      $fechaFin = $item->fechaFin;
      $tipoCurso = $item->ASIGNATURAS->ITEM->tipoCurso;
      $cargaHoraria = $item->ASIGNATURAS->ITEM->cargaHoraria;
        try {
            $query="insert into docencias (
              documento,
              id_docencia,
              tipoDocencia,
              descripcion,
              fechaInicio,
              fechaFin,
              tipoCurso,
              cargaHoraria
              ) values (
                '".$documento."',
                '".$id_docencia."',
                '".$tipoDocencia."',
                '".$descripcion."',
                '".$fechaInicio."',
                '".$fechaFin."',
                '".$tipoCurso."',
                '".$cargaHoraria."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseTutorias($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->RRHH->TUTORIAS->CONCLUIDA->POSGRADO->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_tutoria = $documento . "--" . $count;
      $titulo = $link->real_escape_string($item->titulo);
      $infoAdicional = $link->real_escape_string($item->infoAdicional);
      $anio = $item->anio;
      $concluida = $item->concluida;
      $tipoTutoria = $item->tipoTutoria;
        try {
            $query="insert into tutorias_concluidas (
              documento,
              id_tutoria,
              titulo,
              anio,
              tipoTutoria,
              concluida,
              infoAdicional
              ) values (
                '".$documento."',
                '".$id_tutoria."',
                '".$titulo."',
                '".$anio."',
                '".$tipoTutoria."',
                '".$concluida."',
                '".$infoAdicional."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}


function parsePremios($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->OTROS_DATOS_RELEVANTES->PREMIOS->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_premio = $documento . "--" . $count;
      $descripcion = $link->real_escape_string($item->descripcion);
      $nombre = $link->real_escape_string($item->nombre);
      $anio = $item->anio;
      $entidadPromotora = $item->entidadPromotora;
      $tipoCaracterEvento = $item->tipoCaracterEvento;
        try {
            $query="insert into premios (
              documento,
              id_premio,
              descripcion,
              nombre,
              anio,
              entidadPromotora,
              tipoCaracterEvento
              ) values (
                '".$documento."',
                '".$id_premio."',
                '".$descripcion."',
                '".$nombre."',
                '".$anio."',
                '".$entidadPromotora."',
                '".$tipoCaracterEvento."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseTrabajosEventos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->BIBLIOGRAFICA->TRABAJOS->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_trabajo = $documento . "--" . $count;
      $referado = $item->referado;
      $clase = $item->clasePublicacion;
      $anio = $item->anio;
      $evento_nombre = $item->EVENTO->nombre;
      $evento_clasificacion = $item->EVENTO->clasificacionEvento;
        try {
            $query="insert into trabajos_eventos (
              documento,
              id_trabajo,
              referado,
              clase,
              anio,
              evento_nombre,
              evento_clasificacion
              ) values (
                '".$documento."',
                '".$id_trabajo."',
                '".$referado."',
                '".$clase."',
                '".$anio."',
                '".$evento_nombre."',
                '".$evento_clasificacion."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseLibros($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->BIBLIOGRAFICA->LIBROS->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
      $id_libro = $documento . "--" . $count;
      $referado = $item->referado;
      $categoria = $item->categoria;
      $anio = $item->anio;
        try {
            $query="insert into libros (
              documento,
              id_libro,
              referado,
              categoria,
              anio
              ) values (
                '".$documento."',
                '".$id_libro."',
                '".$referado."',
                '".$categoria."',
                '".$anio."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseHijos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->DATOS_GENERALES->DATOS_IDENTIFICACION->HIJOS->ITEM as $key => $item) {
    $count += 1;
    if($item!=null){
        $fecha = $item->fecha_nacimiento;
        $id = $documento . "--" . $count;
        try {
            $query="insert into hijos (
              id_hijo,
              documento,
              fecha_nacimiento
              ) values (
                '".$id."',
                '".$documento."',
                '".$fecha."'
              );";
            if ($link->query($query) === TRUE) {
                //echo "New record created successfully <br/>";
                //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function getVinculos($link){
  $sql2 = "SELECT * FROM cvs_a_importar where status = 'IRESTRICTO'";
  $result2 = $link->query($sql2);
  if ($result2->num_rows > 0) {
      while($row2 = $result2->fetch_assoc()) {
        $doc = $row2["documento"];
        $sql = "SELECT documento,cv_xml FROM investigadores where documento = '$doc'";
        $result = $link->query($sql);
        $count = 0;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              $count += 1;
              $item = array();
              $item['documento'] = $row["documento"];
              $item['cv_xml'] = $row["cv_xml"];
              if($item['documento']!="" && $item['cv_xml'] != ""){
                try{
                  $cv_xmlObject = new SimpleXMLElement($item['cv_xml'],null,false);
                }catch (Exception $error) {
                  echo $error->getMessage();
                }
                parseVinculos($cv_xmlObject,$link,$item['documento']);
              }
              /*if($count == 5){
                exit();
              }*/
            }
        }
      }
  }
}

function parseVinculos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM as $key => $item) {
    if($item!=null){
      $institucion = $item->institucion;
      foreach ($item->ITEM as $key => $item2) {
        $subInstitucion = $item2->sub_institucion;
        foreach ($item2->VINCULOS->ITEM as $key => $item3) {
          $count += 1;
          $idvinculo = $documento . "--" . $count;
          $descripcion = $link->real_escape_string($item3->descripcion);
          $cargaHoraria = $link->real_escape_string($item3->cargaHoraria);
          $relevante = $link->real_escape_string($item3->relevante);
          $info = $link->real_escape_string($item3->info);
          $inicio = $link->real_escape_string($item3->inicio);
          $fin = $link->real_escape_string($item3->fin);
          $dedicacionTotal = $link->real_escape_string($item3->dedicacionTotal);
          $tipoVinculo = $link->real_escape_string($item3->tipoVinculo);
          $tipoVinculoDocente = $link->real_escape_string($item3->tipoVinculoDocente);
          $tipoVinculoDocenteCargo = $link->real_escape_string($item3->tipoVinculoDocenteCargo);
          $tipoVinculoDocenteGrado = $link->real_escape_string($item3->tipoVinculoDocenteGrado);
          try {
              $query="insert into vinculo_institucional (
                id,
                documento,
                institucion,
                subinstitucion,
                descripcion,
                cargahoraria,
                relevante,
                info,
                inicio,
                fin,
                dedicaciontotal,
                tipovinculo,
                tipovinculodocente,
                tipovinculodocentecargo,
                tipovinculodocentegrado
                ) values (
                  '".$idvinculo."',
                  '".$documento."',
                  '".$institucion."',
                  '".$subInstitucion."',
                  '".$descripcion."',
                  '".$cargaHoraria."',
                  '".$relevante."',
                  '".$info."',
                  '".$inicio."',
                  '".$fin."',
                  '".$dedicacionTotal."',
                  '".$tipoVinculo."',
                  '".$tipoVinculoDocente."',
                  '".$tipoVinculoDocenteCargo."',
                  '".$tipoVinculoDocenteGrado."'
                );";
              if ($link->query($query) === TRUE) {
                  //echo "New record created successfully <br/>";
                  //echo $documento . " " . $institucion . " " . $subInstitucion . " " . $descripcion . " " . $info . " " . $inicio . "<br/>";
              } else {
                  echo "Error: " . $query . "<br>" . $query->error;
              }
          }catch (Exception $error) {
              echo $error->getMessage();
          }
        }
      }
    }
  }
}


function getLista($link){
  $investigadoresArray = array();
  for( $i= 1 ; $i <= 238 ; $i++ ){
    $url = "https://apicvuy.anii.org.uy/permisos/?config=1&usuario=csic&clave=@n11CsiC2018&pagina=".$i;
    $xml = download_page($url);
    $xmlObject = new SimpleXMLElement($xml);
    foreach ($xmlObject->USUARIOS->USUARIO as $key => $usuario) {
      $item = array();
      $item['nombres'] = $link->real_escape_string($usuario->nombres);
      $item['apellidos'] = $link->real_escape_string($usuario->apellidos);
      $item['documento'] = $link->real_escape_string($usuario['documento']);
      $item['status'] = $link->real_escape_string($usuario->status);
      $investigadoresArray []= $item;
      try {
          $query="insert into cvs_a_importar (documento,nombres,apellidos,status) values ('".$item['documento']."','".$item['nombres']."','".$item['apellidos']."','".$item['status']."');";
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
  return $investigadoresArray;
}

function getCVs($link){
  $investigadoresArray = array();
  $sql = "SELECT * FROM cvs_a_importar where status = 'IRESTRICTO'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $item = array();
        $item['nombres'] = $row["nombres"];
        $item['apellidos'] = $row["apellidos"];
        $item['documento'] = $row["documento"];
        $item['status'] = $row["status"];
        $investigadoresArray []= $item;
      }
  }
  foreach ($investigadoresArray as $key => $usuario) {
      $nombres = $usuario["nombres"];
      $apellidos = $usuario["apellidos"];
      $documento = $usuario["documento"];
      $status = $usuario["status"];
      if($status=="IRESTRICTO"){
        echo $nombres . " " . $apellidos . " " . $documento ."<br/>";
        $cv_url = "https://apicvuy.anii.org.uy/xml/?config=1&documento=".$documento."&usuario=csic&clave=@n11CsiC2018";
        $cv_xml = download_page($cv_url);
        $cv_xml = stripInvalidXml($cv_xml);
        try{
          $cv_xmlObject = new SimpleXMLElement($cv_xml,null,false);
        }catch (Exception $error) {
            echo $error->getMessage();
        }
        $ultima_actualizacion_cv = $cv_xmlObject->CV->ULTIMA_ACTUALIZACION_DATOS;
        $sni = "No";
        foreach ($cv_xmlObject->SNI as $key => $item) {
          if($item!=null){
            if($item->NIVEL!=null && $item->NIVEL != ""){
              $sni = $item->NIVEL;
            }
          }
        }
        save_user_into_bd($link,$documento,$nombres,$apellidos,$cv_xml,$sni,$ultima_actualizacion_cv);
        parseSNI($cv_xmlObject,$link,$documento);
        parseAreasPrincipales($cv_xmlObject,$link,$documento);
        parse_datos_identificacion($cv_xmlObject,$link,$documento);
        parse_datos_contacto($cv_xmlObject,$link,$documento);
        parse_institucion_principal($cv_xmlObject,$link,$documento);
        parse_proyecto_investigacion($cv_xmlObject,$link,$documento);
        parse_proyecto_extension($cv_xmlObject,$link,$documento);
        parse_articulos_arbitrados($cv_xmlObject,$link,$documento);
        parse_articulos_no_arbitrados($cv_xmlObject,$link,$documento);
        parse_productos_tecnicos($cv_xmlObject,$link,$documento);
        parse_procesos_tecnicos($cv_xmlObject,$link,$documento);
        parse_trabajos_tecnicos($cv_xmlObject,$link,$documento);
        parse_formacion($cv_xmlObject,$link,$documento);
        //exit();
      }
  }
}


function parse_formacion($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->FORMACION->ACADEMICA->ITEM as $key => $itemNode) {
    if($itemNode!=null){
        foreach ($itemNode->POSGRADO->ITEM as $key => $itemFormacion) {
          $count += 1;
          $id = $documento ."--".$count;
          $titulo = $link->real_escape_string($itemFormacion->titulo);
          $tutor = $link->real_escape_string($itemFormacion->tutor);
          $web = $link->real_escape_string($itemFormacion->web);
          $inicio = $link->real_escape_string($itemFormacion->inicio);
          $fin = $link->real_escape_string($itemFormacion->fin);
          $obtencion = $link->real_escape_string($itemFormacion->obtencion);
          $nivelAcademico = $link->real_escape_string($itemFormacion->nivelAcademico);
          $institucion = $link->real_escape_string($itemFormacion->institucion);
          $subInstitucion = $link->real_escape_string($itemFormacion->subInstitucion);
          $subInstitucionDesc = $link->real_escape_string($itemFormacion->subInstitucionDesc);
          $programa = $link->real_escape_string($itemFormacion->programa);
          $estatus = $link->real_escape_string($itemFormacion->estatus);
          $pais = $link->real_escape_string($itemFormacion->pais);
          try {
              $query="insert into formacion (
              id,
              documento,
              titulo,
              tutor,
              web,
              inicio,
              fin,
              obtencion,
              nivelAcademico,
              institucion,
              subInstitucion,
              subInstitucionDesc,
              programa,
              estatus,
              pais
              ) values (
                '".$id."',
                '".$documento."',
                '".$titulo."',
                '".$tutor."',
                '".$web."',
                '".$inicio."',
                '".$fin."',
                '".$obtencion."',
                '".$nivelAcademico."',
                '".$institucion."',
                '".$subInstitucion."',
                '".$subInstitucionDesc."',
                '".$programa."',
                '".$estatus."',
                '".$pais."'
              );";
              if ($link->query($query) === TRUE) {
                  echo "New record created successfully <br/>";
                  parseAreasFormacion($itemFormacion,$id,$link,$documento);
                  parsePalabrasClaveFormacion($itemFormacion,$id,$link,$documento);
              } else {
                  echo "Error: " . $query . "<br>" . $query->error;
              }
          }catch (Exception $error) {
              echo $error->getMessage();
          }
        }

        foreach ($itemNode->GRADO->ITEM as $key => $itemFormacion) {
          $count += 1;
          $id = $documento ."--".$count;
          $titulo = $link->real_escape_string($itemFormacion->titulo);
          $tutor = $link->real_escape_string($itemFormacion->tutor);
          $web = $link->real_escape_string($itemFormacion->web);
          $inicio = $link->real_escape_string($itemFormacion->inicio);
          $fin = $link->real_escape_string($itemFormacion->fin);
          $obtencion = $link->real_escape_string($itemFormacion->obtencion);
          $nivelAcademico = $link->real_escape_string($itemFormacion->nivelAcademico);
          $institucion = $link->real_escape_string($itemFormacion->institucion);
          $subInstitucion = $link->real_escape_string($itemFormacion->subInstitucion);
          $subInstitucionDesc = $link->real_escape_string($itemFormacion->subInstitucionDesc);
          $programa = $link->real_escape_string($itemFormacion->programa);
          $estatus = $link->real_escape_string($itemFormacion->estatus);
          $pais = $link->real_escape_string($itemFormacion->pais);
          try {
              $query="insert into formacion (
              id,
              documento,
              titulo,
              tutor,
              web,
              inicio,
              fin,
              obtencion,
              nivelAcademico,
              institucion,
              subInstitucion,
              subInstitucionDesc,
              programa,
              estatus,
              pais
              ) values (
                '".$id."',
                '".$documento."',
                '".$titulo."',
                '".$tutor."',
                '".$web."',
                '".$inicio."',
                '".$fin."',
                '".$obtencion."',
                '".$nivelAcademico."',
                '".$institucion."',
                '".$subInstitucion."',
                '".$subInstitucionDesc."',
                '".$programa."',
                '".$estatus."',
                '".$pais."'
              );";
              if ($link->query($query) === TRUE) {
                  echo "New record created successfully <br/>";
                  parseAreasFormacion($itemFormacion,$id,$link,$documento);
                  parsePalabrasClaveFormacion($itemFormacion,$id,$link,$documento);
              } else {
                  echo "Error: " . $query . "<br>" . $query->error;
              }
          }catch (Exception $error) {
              echo $error->getMessage();
          }
        }
    }
  }
}

function parseAreasFormacion($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into formacion_area (
        id,
        idformacion,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idReferenced."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parsePalabrasClaveFormacion($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into formacion_palabra_clave (
        id,
        idformacion,
        palabra) values (
          '".$id."',
          '".$idReferenced."',
          '".$palabra."'
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

function parse_trabajos_tecnicos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->TECNICA->PROCESOS->ITEM as $key => $itemNode) {
    if($itemNode!=null){
        $count += 1;
        $id = $documento ."--".$count;
        $duracion = $link->real_escape_string($itemNode->duracion);
        $numeroPaginas = $link->real_escape_string($itemNode->numeroPaginas);
        $ciudad = $link->real_escape_string($itemNode->ciudad);
        $finalidad = $link->real_escape_string($itemNode->finalidad);
        $idioma = $link->real_escape_string($itemNode->idioma);
        $descripcion = $link->real_escape_string($itemNode->descripcion);
        $institucionFinanciadora = $link->real_escape_string($itemNode->institucionFinanciadora);
        $disponibilidad = $link->real_escape_string($itemNode->disponibilidad);
        $tipoTecnica = $link->real_escape_string($itemNode->tipoTecnica);
        $pais = $link->real_escape_string($itemNode->pais);
        $infoAdicional = $link->real_escape_string($itemNode->infoAdicional);
        $titulo = $link->real_escape_string($itemNode->titulo);
        $anio = $link->real_escape_string($itemNode->anio);
        $web = $link->real_escape_string($itemNode->web);
        $relevante = $link->real_escape_string($itemNode->relevante);
        $medioDivulgacion = $link->real_escape_string($itemNode->medioDivulgacion);
        try {
            $query="insert into produccion_tecnica_trabajos (
            id,
            documento,
            duracion,
            numeroPaginas,
            ciudad,
            finalidad,
            idioma,
            descripcion,
            institucionFinanciadora,
            disponibilidad,
            tipoTecnica,
            pais,
            infoAdicional,
            titulo,
            anio,
            web,
            relevante,
            medioDivulgacion
            ) values (
              '".$id."',
              '".$documento."',
              '".$duracion."',
              '".$numeroPaginas."',
              '".$ciudad."',
              '".$finalidad."',
              '".$idioma."',
              '".$descripcion."',
              '".$institucionFinanciadora."',
              '".$disponibilidad."',
              '".$tipoTecnica."',
              '".$pais."',
              '".$infoAdicional."',
              '".$titulo."',
              '".$anio."',
              '".$web."',
              '".$relevante."',
              '".$medioDivulgacion."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseAreasTrabajosTecnicos($itemNode,$id,$link,$documento);
                parsePalabrasClaveTrabajosTecnicos($itemNode,$id,$link,$documento);
                parseCoautoresTrabajosTecnicos($itemNode,$id,$link,$documento);
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseAreasTrabajosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into produccion_tecnica_trabajos_area (
        id,
        idproducto,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idReferenced."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parsePalabrasClaveTrabajosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into produccion_tecnica_trabajos_palabra_clave (
        id,
        idproducto,
        palabra) values (
          '".$id."',
          '".$idReferenced."',
          '".$palabra."'
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

function parseCoautoresTrabajosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->COAUTORES->ITEM as $key => $coautor) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $nombres = $link->real_escape_string($coautor->nombres);
    $apellidos = $link->real_escape_string($coautor->apellidos);
    $citacion = $link->real_escape_string($coautor->citacion);
    try {
        $query="insert into produccion_tecnica_trabajos_coautor (
        id,
        idproducto,
        nombres,
        apellidos,
        citacion) values (
          '".$id."',
          '".$idReferenced."',
          '".$nombres."',
          '".$apellidos."',
          '".$citacion."'
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

function parse_procesos_tecnicos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->TECNICA->PROCESOS->ITEM as $key => $itemNode) {
    if($itemNode!=null){
        $count += 1;
        $id = $documento ."--".$count;
        $aplicacionProductivaSocial = $link->real_escape_string($itemNode->aplicacionProductivaSocial);
        $descripcionProductivaSocial = $link->real_escape_string($itemNode->descripcionProductivaSocial);
        $categoriaTecnica = $link->real_escape_string($itemNode->categoriaTecnica);
        $descripcion = $link->real_escape_string($itemNode->descripcion);
        $institucionFinanciadora = $link->real_escape_string($itemNode->institucionFinanciadora);
        $disponibilidad = $link->real_escape_string($itemNode->disponibilidad);
        $tipoTecnica = $link->real_escape_string($itemNode->tipoTecnica);
        $pais = $link->real_escape_string($itemNode->pais);
        $infoAdicional = $link->real_escape_string($itemNode->infoAdicional);
        $titulo = $link->real_escape_string($itemNode->titulo);
        $anio = $link->real_escape_string($itemNode->anio);
        $web = $link->real_escape_string($itemNode->web);
        $relevante = $link->real_escape_string($itemNode->relevante);
        $medioDivulgacion = $link->real_escape_string($itemNode->medioDivulgacion);
        try {
            $query="insert into produccion_tecnica_procesos (
            id,
            documento,
            aplicacionProductivaSocial,
            descripcionProductivaSocial,
            descripcion,
            institucionFinanciadora,
            disponibilidad,
            tipoTecnica,
            pais,
            infoAdicional,
            titulo,
            anio,
            web,
            relevante,
            medioDivulgacion
            ) values (
              '".$id."',
              '".$documento."',
              '".$aplicacionProductivaSocial."',
              '".$descripcionProductivaSocial."',
              '".$descripcion."',
              '".$institucionFinanciadora."',
              '".$disponibilidad."',
              '".$tipoTecnica."',
              '".$pais."',
              '".$infoAdicional."',
              '".$titulo."',
              '".$anio."',
              '".$web."',
              '".$relevante."',
              '".$medioDivulgacion."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseAreasProcesosTecnicos($itemNode,$id,$link,$documento);
                parsePalabrasClaveProcesosTecnicos($itemNode,$id,$link,$documento);
                parseCoautoresProcesosTecnicos($itemNode,$id,$link,$documento);
                parseRegistroPatentesProcesosTecnicos($itemNode,$id,$link,$documento);

            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseAreasProcesosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into produccion_tecnica_procesos_area (
        id,
        idproducto,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idReferenced."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parsePalabrasClaveProcesosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into produccion_tecnica_procesos_palabra_clave (
        id,
        idproducto,
        palabra) values (
          '".$id."',
          '".$idReferenced."',
          '".$palabra."'
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

function parseCoautoresProcesosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->COAUTORES->ITEM as $key => $coautor) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $nombres = $link->real_escape_string($coautor->nombres);
    $apellidos = $link->real_escape_string($coautor->apellidos);
    $citacion = $link->real_escape_string($coautor->citacion);
    try {
        $query="insert into produccion_tecnica_procesos_coautor (
        id,
        idproducto,
        nombres,
        apellidos,
        citacion) values (
          '".$id."',
          '".$idReferenced."',
          '".$nombres."',
          '".$apellidos."',
          '".$citacion."'
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

function parseRegistroPatentesProcesosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->REGISTROS_PROPIEDAD_INTELECTUAL->ITEM as $key => $patente) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $codigo = $patente->codigo;
    $titulo = $patente->titulo;
    $tipoRegistro = $patente->tipoRegistro;
    $patenteNacional = $patente->patenteNacional;
    $deposito = $patente->deposito;
    $examen = $patente->examen;
    $concesion = $patente->concesion;
    try {
        $query="insert into produccion_tecnica_procesos_patente (
        id,
        idproducto,
        codigo,
        titulo,
        tipoRegistro,
        patenteNacional,
        deposito,
        examen,
        concesion
        ) values (
          '".$id."',
          '".$idReferenced."',
          '".$codigo."',
          '".$titulo."',
          '".$tipoRegistro."',
          '".$patenteNacional."',
          '".$deposito."',
          '".$examen."',
          '".$concesion."'
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


function parse_productos_tecnicos($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->TECNICA->PRODUCTOS->ITEM as $key => $itemNode) {
    if($itemNode!=null){
        $count += 1;
        $id = $documento ."--".$count;
        $aplicacionProductivaSocial = $link->real_escape_string($itemNode->aplicacionProductivaSocial);
        $descripcionProductivaSocial = $link->real_escape_string($itemNode->descripcionProductivaSocial);
        $categoriaTecnica = $link->real_escape_string($itemNode->categoriaTecnica);
        $descripcion = $link->real_escape_string($itemNode->descripcion);
        $institucionFinanciadora = $link->real_escape_string($itemNode->institucionFinanciadora);
        $disponibilidad = $link->real_escape_string($itemNode->disponibilidad);
        $tipoTecnica = $link->real_escape_string($itemNode->tipoTecnica);
        $pais = $link->real_escape_string($itemNode->pais);
        $infoAdicional = $link->real_escape_string($itemNode->infoAdicional);
        $titulo = $link->real_escape_string($itemNode->titulo);
        $anio = $link->real_escape_string($itemNode->anio);
        $web = $link->real_escape_string($itemNode->web);
        $relevante = $link->real_escape_string($itemNode->relevante);
        $medioDivulgacion = $link->real_escape_string($itemNode->medioDivulgacion);
        try {
            $query="insert into produccion_tecnica_productos (
            id,
            documento,
            aplicacionProductivaSocial,
            descripcionProductivaSocial,
            categoriaTecnica,
            descripcion,
            institucionFinanciadora,
            disponibilidad,
            tipoTecnica,
            pais,
            infoAdicional,
            titulo,
            anio,
            web,
            relevante,
            medioDivulgacion
            ) values (
              '".$id."',
              '".$documento."',
              '".$aplicacionProductivaSocial."',
              '".$descripcionProductivaSocial."',
              '".$categoriaTecnica."',
              '".$descripcion."',
              '".$institucionFinanciadora."',
              '".$disponibilidad."',
              '".$tipoTecnica."',
              '".$pais."',
              '".$infoAdicional."',
              '".$titulo."',
              '".$anio."',
              '".$web."',
              '".$relevante."',
              '".$medioDivulgacion."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseAreasProductosTecnicos($itemNode,$id,$link,$documento);
                parsePalabrasClaveProductosTecnicos($itemNode,$id,$link,$documento);
                parseCoautoresProductosTecnicos($itemNode,$id,$link,$documento);
                parseRegistroPatentesProductosTecnicos($itemNode,$id,$link,$documento);

            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parseAreasProductosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into produccion_tecnica_productos_area (
        id,
        idproducto,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idReferenced."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parsePalabrasClaveProductosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into produccion_tecnica_productos_palabra_clave (
        id,
        idproducto,
        palabra) values (
          '".$id."',
          '".$idReferenced."',
          '".$palabra."'
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

function parseCoautoresProductosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->COAUTORES->ITEM as $key => $coautor) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $nombres = $link->real_escape_string($coautor->nombres);
    $apellidos = $link->real_escape_string($coautor->apellidos);
    $citacion = $link->real_escape_string($coautor->citacion);
    try {
        $query="insert into produccion_tecnica_productos_coautor (
        id,
        idproducto,
        nombres,
        apellidos,
        citacion) values (
          '".$id."',
          '".$idReferenced."',
          '".$nombres."',
          '".$apellidos."',
          '".$citacion."'
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

function parseRegistroPatentesProductosTecnicos($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->REGISTROS_PROPIEDAD_INTELECTUAL->ITEM as $key => $patente) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $codigo = $patente->codigo;
    $titulo = $patente->titulo;
    $tipoRegistro = $patente->tipoRegistro;
    $patenteNacional = $patente->patenteNacional;
    $deposito = $patente->deposito;
    $examen = $patente->examen;
    $concesion = $patente->concesion;
    try {
        $query="insert into produccion_tecnica_productos_patente (
        id,
        idproducto,
        codigo,
        titulo,
        tipoRegistro,
        patenteNacional,
        deposito,
        examen,
        concesion
        ) values (
          '".$id."',
          '".$idReferenced."',
          '".$codigo."',
          '".$titulo."',
          '".$tipoRegistro."',
          '".$patenteNacional."',
          '".$deposito."',
          '".$examen."',
          '".$concesion."'
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

function parse_articulos_no_arbitrados($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->BIBLIOGRAFICA->ARTICULOS_PUBLICADOS_REVISTAS_NO_ARBITRADAS->ITEM as $key => $itemNode) {
    if($itemNode!=null){
        $count += 1;
        $id = $documento ."--".$count;
        $lugarPublicacion = $link->real_escape_string($itemNode->lugarPublicacion);
        $escritoPorInvitacion = $itemNode->escritoPorInvitacion;
        $volumen = $itemNode->volumen;
        $fasciculo = $itemNode->fasciculo;
        $serie = $itemNode->serie;
        $paginaInicial = $itemNode->paginaInicial;
        $paginaFinal = $itemNode->paginaFinal;
        $arbitrado = $itemNode->arbitrado;
        $scopus = $itemNode->scopus;
        $thompson = $itemNode->thompson;
        $latindex= $itemNode->latindex;
        $scielo = $itemNode->scielo;
        $tipoArticulo = $link->real_escape_string($itemNode->tipoArticulo);
        $infoAdicional = $link->real_escape_string($itemNode->infoAdicional);
        $anio = $itemNode->anio;
        $web = $link->real_escape_string($itemNode->web);
        $relevante = $itemNode->relevante;
        $medioDivulgacion = $link->real_escape_string($itemNode->medioDivulgacion);
        $revista_nombre = $link->real_escape_string($itemNode->REVISTA->nombre);
        $revista_issn = $itemNode->REVISTA->issn;
        $titulo = $link->real_escape_string($itemNode->titulo);
        try {
            $query="insert into articulo_revista_noarbitrada (
            id,
            documento,
            lugarPublicacion,
            escritoPorInvitacion,
            volumen,
            fasciculo,
            serie,
            paginaInicial,
            paginaFinal,
            arbitrado,
            scopus,
            thompson,
            latindex,
            scielo,
            tipoArticulo,
            infoAdicional,
            anio,
            web,
            relevante,
            medioDivulgacion,
            revista_nombre,
            revista_issn,
            titulo
            ) values (
              '".$id."',
              '".$documento."',
              '".$lugarPublicacion."',
              '".$escritoPorInvitacion."',
              '".$volumen."',
              '".$fasciculo."',
              '".$serie."',
              '".$paginaInicial."',
              '".$paginaFinal."',
              '".$arbitrado."',
              '".$scopus."',
              '".$thompson."',
              '".$latindex."',
              '".$scielo."',
              '".$tipoArticulo."',
              '".$infoAdicional."',
              '".$anio."',
              '".$web."',
              '".$relevante."',
              '".$medioDivulgacion."',
              '".$revista_nombre."',
              '".$revista_issn."',
              '".$titulo."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseAreasArticulosNoArbitrados($itemNode,$id,$link,$documento);
                parsePalabrasClaveArticulosNoArbitrados($itemNode,$id,$link,$documento);
                parseCoautoresArticulosNoArbitrados($itemNode,$id,$link,$documento);

            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parsePalabrasClaveArticulosNoArbitrados($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into articulo_revista_noarbitrada_palabra_clave (
        id,
        idarticulo,
        palabra) values (
          '".$id."',
          '".$idReferenced."',
          '".$palabra."'
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

function parseCoautoresArticulosNoArbitrados($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->COAUTORES->ITEM as $key => $coautor) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $nombres = $link->real_escape_string($coautor->nombres);
    $apellidos = $link->real_escape_string($coautor->apellidos);
    $citacion = $link->real_escape_string($coautor->citacion);
    try {
        $query="insert into articulo_revista_noarbitrada_coautor (
        id,
        idarticulo,
        nombres,
        apellidos,
        citacion) values (
          '".$id."',
          '".$idReferenced."',
          '".$nombres."',
          '".$apellidos."',
          '".$citacion."'
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

function parseAreasArticulosNoArbitrados($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into articulo_revista_noarbitrada_area (
        id,
        idarticulo,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idReferenced."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parse_articulos_arbitrados($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->PRODUCCION->BIBLIOGRAFICA->ARTICULOS_PUBLICADOS_REVISTAS_ARBITRADAS->ITEM as $key => $itemNode) {
    if($itemNode!=null){
        $count += 1;
        $id = $documento ."--".$count;
        $lugarPublicacion = $link->real_escape_string($itemNode->lugarPublicacion);
        $escritoPorInvitacion = $itemNode->escritoPorInvitacion;
        $volumen = $itemNode->volumen;
        $fasciculo = $itemNode->fasciculo;
        $serie = $itemNode->serie;
        $paginaInicial = $itemNode->paginaInicial;
        $paginaFinal = $itemNode->paginaFinal;
        $arbitrado = $itemNode->arbitrado;
        $scopus = $itemNode->scopus;
        $thompson = $itemNode->thompson;
        $latindex= $itemNode->latindex;
        $scielo = $itemNode->scielo;
        $tipoArticulo = $link->real_escape_string($itemNode->tipoArticulo);
        $infoAdicional = $link->real_escape_string($itemNode->infoAdicional);
        $anio = $itemNode->anio;
        $web = $link->real_escape_string($itemNode->web);
        $relevante = $itemNode->relevante;
        $medioDivulgacion = $link->real_escape_string($itemNode->medioDivulgacion);
        $revista_nombre = $link->real_escape_string($itemNode->REVISTA->nombre);
        $revista_issn = $itemNode->REVISTA->issn;
        $titulo = $link->real_escape_string($itemNode->titulo);
        try {
            $query="insert into articulo_revista_arbitrada (
            id,
            documento,
            lugarPublicacion,
            escritoPorInvitacion,
            volumen,
            fasciculo,
            serie,
            paginaInicial,
            paginaFinal,
            arbitrado,
            scopus,
            thompson,
            latindex,
            scielo,
            tipoArticulo,
            infoAdicional,
            anio,
            web,
            relevante,
            medioDivulgacion,
            revista_nombre,
            revista_issn,
            titulo
            ) values (
              '".$id."',
              '".$documento."',
              '".$lugarPublicacion."',
              '".$escritoPorInvitacion."',
              '".$volumen."',
              '".$fasciculo."',
              '".$serie."',
              '".$paginaInicial."',
              '".$paginaFinal."',
              '".$arbitrado."',
              '".$scopus."',
              '".$thompson."',
              '".$latindex."',
              '".$scielo."',
              '".$tipoArticulo."',
              '".$infoAdicional."',
              '".$anio."',
              '".$web."',
              '".$relevante."',
              '".$medioDivulgacion."',
              '".$revista_nombre."',
              '".$revista_issn."',
              '".$titulo."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseAreasArticulosArbitrados($itemNode,$id,$link,$documento);
                parsePalabrasClaveArticulosArbitrados($itemNode,$id,$link,$documento);
                parseCoautoresArticulosArbitrados($itemNode,$id,$link,$documento);

            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
    }
  }
}

function parsePalabrasClaveArticulosArbitrados($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into articulo_revista_arbitrada_palabra_clave (
        id,
        idarticulo,
        palabra) values (
          '".$id."',
          '".$idReferenced."',
          '".$palabra."'
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

function parseCoautoresArticulosArbitrados($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->COAUTORES->ITEM as $key => $coautor) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $nombres = $link->real_escape_string($coautor->nombres);
    $apellidos = $link->real_escape_string($coautor->apellidos);
    $citacion = $link->real_escape_string($coautor->citacion);
    try {
        $query="insert into articulo_revista_arbitrada_coautor (
        id,
        idarticulo,
        nombres,
        apellidos,
        citacion) values (
          '".$id."',
          '".$idReferenced."',
          '".$nombres."',
          '".$apellidos."',
          '".$citacion."'
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

function parseAreasArticulosArbitrados($itemNode,$idReferenced,$link,$documento){
  $count = 0;
  foreach ($itemNode->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idReferenced ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into articulo_revista_arbitrada_area (
        id,
        idarticulo,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idReferenced."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parseAreasProyectoExtension($proyecto,$idproyecto,$link,$documento){
  $count = 0;
  foreach ($proyecto->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idproyecto ."--".$count;
    $area = $areaItem->area;
    $subarea = $areaItem->subarea;
    $disciplina = $areaItem->disciplina;
    $especialidad = $areaItem->especialidad;
    try {
        $query="insert into proyecto_extension_area (
        id,
        idproyecto,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idproyecto."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parse_proyecto_extension($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM as $key => $item) {
    if($item!=null){
      $institucion = $item->institucion;
      foreach ($item->ITEM->ACTIVIDADES->EXTENSION->ITEM as $key => $proyecto) {
        $count += 1;
        $id = $documento ."--".$count;
        $titulo = $link->real_escape_string($proyecto->tituloProyecto);
        $descripcion = $link->real_escape_string($proyecto->descripcion);
        $dependencia = $link->real_escape_string($proyecto->dependencia);
        $unidad = $link->real_escape_string($proyecto->unidad);
        $fechaInicio = $proyecto->fechaInicio;
        if($fechaInicio!=""){
          $fechaInicio= "STR_TO_DATE('".$fechaInicio."', '%d/%m/%Y')";
        }else{
          $fechaInicio= "null";
        }
        $fechaFin = $proyecto->fechaFin;
        if($fechaFin!=""){
          $fechaFin = "STR_TO_DATE('".$fechaFin."', '%d/%m/%Y')";
        }else{
          $fechaFin= "null";
        }
        $cargaHorariaSemanal = $proyecto->cargaHorariaSemanal;
        try {
            $query="insert into proyecto_extension (
            id,
            documento,
            institucion,
            descripcion,
            dependencia,
            unidad,
            fecha_inicio,
            fecha_fin,
            cargaHorariaSemanal) values (
              '".$id."',
              '".$documento."',
              '".$institucion."',
              '".$descripcion."',
              '".$dependencia."',
              '".$unidad."',
              ".$fechaInicio.",
              ".$fechaFin.",
              '".$cargaHorariaSemanal."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseAreasProyectoExtension($proyecto,$id,$link,$documento);
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
      }
    }
  }
}

function parseFinanciadorasProyectoInvestigacion($proyecto,$idproyecto,$link,$documento){
  $count = 0;
  foreach ($proyecto->FINANCIADORAS->ITEM as $key => $financiadora) {
    $count += 1;
    $id = $idproyecto ."--".$count;
    $institucion = $link->real_escape_string($financiadora->institucion);
    $subinstitucion = $link->real_escape_string($financiadora->subinstitucion);
    $pais = $link->real_escape_string($financiadora->pais);
    $tipofinanciacion = $link->real_escape_string($financiadora->tipofinanciacion);
    try {
        $query="insert into proyecto_investigacion_institucion_financiadora (
        id,
        idproyecto,
        institucion,
        subinstitucion,
        pais,
        tipofinanciacion) values (
          '".$id."',
          '".$idproyecto."',
          '".$institucion."',
          '".$subinstitucion."',
          '".$pais."',
          '".$tipofinanciacion."'
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

function parseAreasProyectoInvestigacion($proyecto,$idproyecto,$link,$documento){
  $count = 0;
  foreach ($proyecto->AREAS_ACTUACION->ITEM as $key => $areaItem) {
    $count += 1;
    $id = $idproyecto ."--".$count;
    $area = $link->real_escape_string($areaItem->area);
    $subarea = $link->real_escape_string($areaItem->subarea);
    $disciplina = $link->real_escape_string($areaItem->disciplina);
    $especialidad = $link->real_escape_string($areaItem->especialidad);
    try {
        $query="insert into proyecto_investigacion_area (
        id,
        idproyecto,
        area,
        subarea,
        disciplina,
        especialidad) values (
          '".$id."',
          '".$idproyecto."',
          '".$area."',
          '".$subarea."',
          '".$disciplina."',
          '".$especialidad."'
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

function parsePalabrasClaveProyectoInvestigacion($proyecto,$idproyecto,$link,$documento){
  $count = 0;
  foreach ($proyecto->PALABRAS_CLAVE->ITEM as $key => $palabraClave) {
    $count += 1;
    $id = $idproyecto ."--".$count;
    $palabra = $link->real_escape_string($palabraClave->descripcion);
    try {
        $query="insert into proyecto_investigacion_palabra_clave (
        id,
        idproyecto,
        palabra) values (
          '".$id."',
          '".$idproyecto."',
          '".$palabra."'
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

function parseEquipoProyectoInvestigacion($proyecto,$idproyecto,$link,$documento){
  $count = 0;
  foreach ($proyecto->EQUIPO->ITEM as $key => $equipo) {
    $count += 1;
    $id = $idproyecto ."--".$count;
    $nombres = $link->real_escape_string($equipo->nombres);
    $apellidos = $link->real_escape_string($equipo->apellidos);
    $citacion = $link->real_escape_string($equipo->citacion);
    $responsable = $link->real_escape_string($equipo->responsable);
    try {
        $query="insert into proyecto_investigacion_equipo (
        id,
        idproyecto,
        nombres,
        apellidos,
        citacion,
        responsable) values (
          '".$id."',
          '".$idproyecto."',
          '".$nombres."',
          '".$apellidos."',
          '".$citacion."',
          '".$responsable."'
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

function parse_proyecto_investigacion($cv_xmlObject,$link,$documento){
  $count = 0;
  foreach ($cv_xmlObject->ACTUACION_PROFESIONAL->ITEM as $key => $item) {
    if($item!=null){
      $institucion = $item->institucion;
      foreach ($item->ITEM->ACTIVIDADES->PROYECTOS_INVESTIGACION->ITEM as $key => $proyecto) {
        $count += 1;
        $id = $documento ."--".$count;
        $titulo = $link->real_escape_string($proyecto->tituloProyecto);
        $descripcion = $link->real_escape_string($proyecto->descripcion);
        $otra_descripcion = $link->real_escape_string($proyecto->otrosDescripcion);
        $alumnoPreGrado = $proyecto->alumnoPreGrado;
        $alumnoEspecializacion = $proyecto->alumnoEspecializacion;
        $alumnoMaestria = $proyecto->alumnoMaestria;
        $alumnoMaestriaProf = $proyecto->alumnoMaestriaProf;
        $alumnoDoctorado = $proyecto->alumnoDoctorado;
        $tipoParticipacionVinculo = $link->real_escape_string($proyecto->tipoParticipacionVinculo);
        $situacionVinculo = $link->real_escape_string($proyecto->situacionVinculo);
        $tipoClaseVinculo = $link->real_escape_string($proyecto->tipoClaseVinculo);
        $dependencia = $link->real_escape_string($proyecto->dependencia);
        $unidad = $link->real_escape_string($proyecto->unidad);
        $fechaInicio = $proyecto->fechaInicio;
        if($fechaInicio!=""){
          $fechaInicio = "STR_TO_DATE('".$fechaInicio."', '%d/%m/%Y')";
        }else{
          $fechaInicio= "null";
        }
        $fechaFin = $proyecto->fechaFin;
        if($fechaFin!=""){
          $fechaFin = "STR_TO_DATE('".$fechaFin."', '%d/%m/%Y')";
        }else{
          $fechaFin= "null";
        }
        $cargaHorariaSemanal = $proyecto->cargaHorariaSemanal;
        try {
            $query="insert into proyecto_investigacion (
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
              '".$id."',
              '".$documento."',
              '".$institucion."',
              '".$titulo."',
              '".$descripcion."',
              '".$otra_descripcion."',
              '".$alumnoPreGrado."',
              '".$alumnoEspecializacion."',
              '".$alumnoMaestria."',
              '".$alumnoMaestriaProf."',
              '".$alumnoDoctorado."',
              '".$tipoParticipacionVinculo."',
              '".$situacionVinculo."',
              '".$tipoClaseVinculo."',
              '".$dependencia."',
              '".$unidad."',
              ".$fechaInicio.",
              ".$fechaFin.",
              '".$cargaHorariaSemanal."'
            );";
            if ($link->query($query) === TRUE) {
                echo "New record created successfully <br/>";
                parseEquipoProyectoInvestigacion($proyecto,$id,$link,$documento);
                parsePalabrasClaveProyectoInvestigacion($proyecto,$id,$link,$documento);
                parseAreasProyectoInvestigacion($proyecto,$id,$link,$documento);
                parseFinanciadorasProyectoInvestigacion($proyecto,$id,$link,$documento);
            } else {
                echo "Error: " . $query . "<br>" . $query->error;
            }
        }catch (Exception $error) {
            echo $error->getMessage();
        }
      }
    }
  }
}

function parse_institucion_principal($cv_xmlObject,$link,$documento){
  foreach ($cv_xmlObject->DATOS_GENERALES->INSTITUCION_PRINCIPAL as $key => $item) {
    if($item!=null){
      $institucion = $link->real_escape_string($item->institucion);
      $subinstitucion = $link->real_escape_string($item->subInstitucion);
      $dependencia = $link->real_escape_string($item->dependencia);
      $pais = $item->pais;
      try {
          $query="insert into institucion_principal (documento,institucion,subinstitucion,dependencia,pais) values ('".$documento."','".$institucion."','".$subinstitucion."','".$dependencia."','".$pais."');";
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
}

function parse_datos_contacto($cv_xmlObject,$link,$documento){
  foreach ($cv_xmlObject->DATOS_GENERALES->INFORMACION_CONTACTO as $key => $item) {
    if($item!=null){
      $email = $link->real_escape_string($item->email);
      try {
          $query="insert into contacto (documento,email) values ('".$documento."','".$email."');";
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
}

function parse_datos_identificacion($cv_xmlObject,$link,$documento){
  foreach ($cv_xmlObject->DATOS_GENERALES->DATOS_IDENTIFICACION as $key => $item) {
    if($item!=null){
      $citacion = $link->real_escape_string($item->citacion);
      $fecha_nacimiento = $item->fechaNacimiento;
      $genero = $item->genero;
      $pais = $link->real_escape_string($item->pais);
      $nacionalidad = $link->real_escape_string($item->nacionalidad);
      try {
          $query="insert into datos_identificacion (documento,citacion,fecha_nacimiento,genero,pais,nacionalidad) values ('".$documento."','".$citacion."',STR_TO_DATE('".$fecha_nacimiento."', '%d/%m/%Y'),'".$genero."','".$pais."','".$nacionalidad."');";
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
}

function parseSNI($cv_xmlObject,$link,$documento){
  foreach ($cv_xmlObject->SNI as $key => $item) {
    if($item!=null){
      $nivel = $item->NIVEL;
      $categoria = $link->real_escape_string($item->CATEGORIA);
      $area = $link->real_escape_string($item->AREA);
      $subarea = $link->real_escape_string($item->AREA);
      try {
          $query="insert into sni (documento,nivel,categoria,area,subarea) values ('".$documento."','".$nivel."','".$categoria."','".$area."','".$subarea."');";
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
}


function parseAreasPrincipales($cv_xmlObject,$link,$documento){
  foreach ($cv_xmlObject->DATOS_GENERALES->AREAS_ACTUACION as $key => $item) {
    foreach ($item as $key => $value) {
      $area = $link->real_escape_string($value->area);
      $subarea = $link->real_escape_string($value->subarea);
      $disciplina = $link->real_escape_string($value->disciplina);
      $especialidad = $link->real_escape_string($value->especialidad);
      save_area_principal_into_bd($link,$documento,$area,$subarea,$disciplina,$especialidad);
    }
  }
}


function stripInvalidXml($value)
{
    $ret = "";
    $current;
    if (empty($value))
    {
        return $ret;
    }

    $length = strlen($value);
    for ($i=0; $i < $length; $i++)
    {
        $current = ord($value{$i});
        if (($current == 0x9) ||
            ($current == 0xA) ||
            ($current == 0xD) ||
            (($current >= 0x20) && ($current <= 0xD7FF)) ||
            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
            (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $ret .= chr($current);
        }
        else
        {
            $ret .= " ";
        }
    }
    return $ret;
}

function download_page($path){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$path);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 999999);
    $retValue = curl_exec($ch);
    curl_close($ch);
    return $retValue;
}

function save_user_into_bd($link,$documento,$nombres,$apellidos,$xml,$sni,$ultima_actualizacion_cv){
    try {
        $xml = $link->real_escape_string($xml);
        $query="insert into investigadores (documento,nombres,apellidos,cv_xml,sni,ultima_actualizacion_cv) values ('".$documento."','".$nombres."','".$apellidos."','".$xml."','".$sni."','".$ultima_actualizacion_cv."');";
        if ($link->query($query) === TRUE) {
            echo "New record created successfully <br/>";
        } else {
            echo "Error: " . $query . "<br>" . $query->error;
        }
    }catch (Exception $error) {
        echo $error->getMessage();
    }

}

function save_area_principal_into_bd($link,$documento,$area,$subarea,$disciplina,$especialidad){
    try {
        $query="insert into area_principal (documento,area,subarea,disciplina,especialidad) values ('".$documento."','".$area."','".$subarea."','".$disciplina."','".$especialidad."');";
        if ($link->query($query) === TRUE) {
            echo "New record created successfully <br/>";
        } else {
            echo "Error: " . $query . "<br>" . $query->error;
        }
    }catch (Exception $error) {
        echo $error->getMessage();
    }

}


 ?>
