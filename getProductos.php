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
  if($action=="productos_tecnicos"){
      getProductosTecnicos($link);
  }
  if($action=="productos_tecnicos_full"){
    $productos = array();
    getProductosTecnicosFull($link,"Ciencias Médicas y de la Salud",$productos);
    getProductosTecnicosFull($link,"Ciencias Agrícolas",$productos);
    getProductosTecnicosFull($link,"Ingeniería y Tecnología",$productos);
    $headers = create_csv_productos_header_array();
    create_csv_file("productos_tecnicos_productos_completo.csv",$headers,$productos);
  }
  if($action=="productos_tecnicos_disciplinas"){
    $productos = array();
    getProductosTecnicosFullDisciplinas($link,"Ciencias Médicas y de la Salud",$productos);
    getProductosTecnicosFullDisciplinas($link,"Ciencias Agrícolas",$productos);
    getProductosTecnicosFullDisciplinas($link,"Ingeniería y Tecnología",$productos);
    $headers = create_csv_productos_header_disciplinas_array();
    create_csv_file("productos_tecnicos_productos_disciplinas.csv",$headers,$productos);
  }

  if($action=="productos_control_inst"){
    $productos = array();
    getProductosTecnicosFullControl($link,"Ciencias Médicas y de la Salud",$productos);
    getProductosTecnicosFullControl($link,"Ciencias Agrícolas",$productos);
    getProductosTecnicosFullControl($link,"Ingeniería y Tecnología",$productos);
    $headers = create_csv_productos_header_control_array();
    create_csv_file("productos_cvuy_control.csv",$headers,$productos);
  }

}
$link->close();

function endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function getProductosTecnicosFullDisciplinas($link,$area,&$productos){
  $sql = "SELECT p.*,
   (SELECT count(*) from produccion_tecnica_productos_coautor a where a.idproducto = p.id) as cantidadcoautores
   from produccion_tecnica_productos p
   where p.id in
   (select idproducto from produccion_tecnica_productos_area where area = '$area') or p.documento in
     (SELECT documento FROM `area_principal` WHERE area = '$area')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  //$productos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProducto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $anioProducto = $row["anio"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$productos)){
            $datosProducto[] = $id;
            $datosProducto[] = $area;
            $datosProducto[] = $documentoPrincipal;
            $datosProducto[] = $tituloNormalizado;
            $datosProducto[] = $row["cantidadcoautores"];
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from produccion_tecnica_productos_area where idproducto='".$id."'",9);
            $peopleArray = getPeopleArrayFromProductDisciplinas($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["anio"],350);
            $filaArray = array_merge($datosProducto,$areasArray,$peopleArray);
            $productos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      /*$headers = create_csv_productos_header_array();
      create_csv_file("productos_tecnicos_productos_cvuy_full.csv",$headers,$productos);*/
      return $productos;
  }
}

function getProductosTecnicosFull($link,$area,&$productos){
  $sql = "SELECT p.*,
   (SELECT count(*) from produccion_tecnica_productos_coautor a where a.idproducto = p.id) as cantidadcoautores
   from produccion_tecnica_productos p
   where p.id in
   (select idproducto from produccion_tecnica_productos_area where area = '$area') or p.documento in
     (SELECT documento FROM `area_principal` WHERE area = '$area')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  //$productos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProducto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $anioProducto = $row["anio"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$productos)){
            $datosProducto[] = $id;
            $datosProducto[] = $area;
            $datosProducto[] = $documentoPrincipal;
            $datosProducto[] = $tituloNormalizado;
            $datosProducto[] = $row["descripcionProductivaSocial"];
            $datosProducto[] = $row["categoriaTecnica"];
            $datosProducto[] = $row["descripcion"];
            $datosProducto[] = $row["institucionFinanciadora"];
            $datosProducto[] = $row["institucion_financiadora_1"];
            $datosProducto[] = $row["institucion_financiadora_cod_1"];
            $datosProducto[] = $row["institucion_financiadora_2"];
            $datosProducto[] = $row["institucion_financiadora_cod_2"];
            $datosProducto[] = $row["institucion_financiadora_3"];
            $datosProducto[] = $row["institucion_financiadora_cod_3"];
            $datosProducto[] = $row["institucion_financiadora_4"];
            $datosProducto[] = $row["institucion_financiadora_cod_4"];
            $datosProducto[] = $row["institucion_financiadora_5"];
            $datosProducto[] = $row["institucion_financiadora_cod_5"];
            $datosProducto[] = $row["disponibilidad"];
            $datosProducto[] = $row["tipoTecnica"];
            $datosProducto[] = $row["pais"];
            $datosProducto[] = $row["anio"];
            $datosProducto[] = $row["web"];
            $datosProducto[] = $row["relevante"];
            $datosProducto[] = $row["medioDivulgacion"];
            $datosProducto[] = $row["infoAdicional"];
            $datosProducto[] = $row["cantidadcoautores"];
            $institucionDelInvestigadorArray = getVinculoInstitucionalDuranteElPeriodoDelProducto($link,$documentoPrincipal,$anioProducto,"","","");
            if($institucionDelInvestigadorArray==false){
              $institucionDelInvestigadorArray = array();
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
            }
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from produccion_tecnica_productos_palabra_clave where idproducto='".$id."'",7);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from produccion_tecnica_productos_area where idproducto='".$id."'",9);
            $patentesArray = getValuesArrayFromSql($link,"SELECT codigo, titulo, tipoRegistro, patenteNacional, deposito, examen, concesion	from produccion_tecnica_productos_patente where idproducto='".$id."'",56);
            $peopleArray = getPeopleArrayFromProduct($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["anio"],350);
            $filaArray = array_merge($datosProducto,$institucionDelInvestigadorArray,$palabrasArray,$areasArray,$patentesArray,$peopleArray);
            $productos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      /*$headers = create_csv_productos_header_array();
      create_csv_file("productos_tecnicos_productos_cvuy_full.csv",$headers,$productos);*/
      return $productos;
  }
}

function getProductosTecnicosFullControl($link,$area,&$productos){
  $sql = "SELECT p.*,
   (SELECT count(*) from produccion_tecnica_productos_coautor a where a.idproducto = p.id) as cantidadcoautores
   from produccion_tecnica_productos p
   where p.id in
   (select idproducto from produccion_tecnica_productos_area where area = '$area') or p.documento in
     (SELECT documento FROM `area_principal` WHERE area = '$area')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  //$productos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProducto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $anioProducto = $row["anio"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$productos)){
            $datosProducto[] = $id;
            $datosProducto[] = $area;
            $datosProducto[] = $documentoPrincipal;
            $datosProducto[] = $tituloNormalizado;
            $datosProducto[] = $row["descripcionProductivaSocial"];
            $datosProducto[] = $row["categoriaTecnica"];
            $datosProducto[] = $row["descripcion"];
            $datosProducto[] = $row["disponibilidad"];
            $datosProducto[] = $row["tipoTecnica"];
            $datosProducto[] = $row["pais"];
            $datosProducto[] = $row["anio"];
            $datosProducto[] = $row["web"];
            $datosProducto[] = $row["relevante"];
            $datosProducto[] = $row["medioDivulgacion"];
            $datosProducto[] = $row["infoAdicional"];
            $peopleArray = getPeopleArrayFromProduct($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["anio"],350);
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
            $filaArray = array_merge($datosProducto,$controlArray);
            $productos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      /*$headers = create_csv_productos_header_array();
      create_csv_file("productos_tecnicos_productos_cvuy_full.csv",$headers,$productos);*/
      return $productos;
  }
}


function getProductosTecnicos($link){
  $sql = "SELECT p.*,
   (SELECT count(*) from produccion_tecnica_productos_coautor a where a.idproducto = p.id) as cantidadcoautores
   from produccion_tecnica_productos p
   where p.id in
   (select idproducto from produccion_tecnica_productos_area where area = 'Ciencias Médicas y de la Salud'
     or area = 'Ciencias Agrícolas'
     or area = 'Ingeniería y Tecnología' ) or p.documento in
     (SELECT documento FROM `area_principal` WHERE area = 'Ciencias Médicas y de la Salud'
        or area = 'Ciencias Agrícolas'
        or area = 'Ingeniería y Tecnología')";
  $result = $link->query($sql);
  $coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $productos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<14512){
        //if($count<100){
          $datosProducto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $anioProducto = $row["anio"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$productos)){
            $datosProducto[] = $id;
            $datosProducto[] = $documentoPrincipal;
            $datosProducto[] = $tituloNormalizado;
            $datosProducto[] = $row["descripcionProductivaSocial"];
            $datosProducto[] = $row["categoriaTecnica"];
            $datosProducto[] = $row["descripcion"];
            $datosProducto[] = $row["institucionFinanciadora"];
            $datosProducto[] = $row["institucion_financiadora_1"];
            $datosProducto[] = $row["institucion_financiadora_cod_1"];
            $datosProducto[] = $row["institucion_financiadora_2"];
            $datosProducto[] = $row["institucion_financiadora_cod_2"];
            $datosProducto[] = $row["institucion_financiadora_3"];
            $datosProducto[] = $row["institucion_financiadora_cod_3"];
            $datosProducto[] = $row["institucion_financiadora_4"];
            $datosProducto[] = $row["institucion_financiadora_cod_4"];
            $datosProducto[] = $row["institucion_financiadora_5"];
            $datosProducto[] = $row["institucion_financiadora_cod_5"];
            $datosProducto[] = $row["disponibilidad"];
            $datosProducto[] = $row["tipoTecnica"];
            $datosProducto[] = $row["pais"];
            $datosProducto[] = $row["anio"];
            $datosProducto[] = $row["web"];
            $datosProducto[] = $row["relevante"];
            $datosProducto[] = $row["medioDivulgacion"];
            $datosProducto[] = $row["infoAdicional"];
            $datosProducto[] = $row["cantidadcoautores"];
            $institucionDelInvestigadorArray = getVinculoInstitucionalDuranteElPeriodoDelProducto($link,$documentoPrincipal,$anioProducto,"","","");
            if($institucionDelInvestigadorArray==false){
              $institucionDelInvestigadorArray = array();
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
              $institucionDelInvestigadorArray[] = "";
            }
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from produccion_tecnica_productos_palabra_clave where idproducto='".$id."'",7);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from produccion_tecnica_productos_area where idproducto='".$id."'",9);
            $patentesArray = getValuesArrayFromSql($link,"SELECT codigo, titulo, tipoRegistro, patenteNacional, deposito, examen, concesion	from produccion_tecnica_productos_patente where idproducto='".$id."'",56);
            $peopleArray = getPeopleArrayFromProduct($link,$coincidencesCountArray,$id,$tituloNormalizado,$row["anio"],350);
            $filaArray = array_merge($datosProducto,$institucionDelInvestigadorArray,$palabrasArray,$areasArray,$patentesArray,$peopleArray);
            $productos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_productos_header_array();
      create_csv_file("productos_tecnicos_productos_cvuy_full.csv",$headers,$productos);
  }
}

function create_csv_productos_header_disciplinas_array(){
  $headers = array();
  $headers []= "ID Producto";
  $headers []= "AREA POR LA QUE ENTRO";
  $headers []= "Documento del que define el producto en el CV";
  $headers []= "Título producto";
  $headers []= "Cantidad de coautores";
  for ($i=1; $i <4 ; $i++) {
    $headers []= "Área producto  ".$i;
    $headers []= "Subárea producto  ".$i;
    $headers []= "Disciplina producto  ".$i;
  }
  for ($i=1; $i <36 ; $i++) {
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

function create_csv_productos_header_control_array(){
  $headers = array();
  $headers []= "ID Producto";
  $headers []= "AREA POR LA QUE ENTRO";
  $headers []= "Documento del que define el producto en el CV";
  $headers []= "Título producto";
  $headers []= "descripcionProductivaSocial";
  $headers []= "categoriaTecnica";
  $headers []= "descripcion";
  $headers []= "disponibilidad";
  $headers []= "tipoTecnica";
  $headers []= "pais";
  $headers []= "anio";
  $headers []= "web";
  $headers []= "relevante";
  $headers []= "medioDivulgacion";
  $headers []= "infoAdicional";
  $headers []= "Cantidad de investigadores totales";
  $headers []= "Cantidad de investigadores con CVUy matcheado";
  $headers []= "Cantidad de investigadores con instituciones matcheadas";
  return $headers;
}

function create_csv_productos_header_array(){
  $headers = array();
  $headers []= "ID Producto";
  $headers []= "AREA POR LA QUE ENTRO";
  $headers []= "Documento del que define el producto en el CV";
  $headers []= "Título producto";
  $headers []= "descripcionProductivaSocial";
  $headers []= "categoriaTecnica";
  $headers []= "descripcion";
  $headers []= "institucionFinanciadora";
  $headers []= "institucionFinanciadora1";
  $headers []= "institucionFinanciadora1Cod";
  $headers []= "institucionFinanciadora2";
  $headers []= "institucionFinanciadora2Cod";
  $headers []= "institucionFinanciadora3";
  $headers []= "institucionFinanciadora3Cod";
  $headers []= "institucionFinanciadora4";
  $headers []= "institucionFinanciadora4Cod";
  $headers []= "institucionFinanciadora5";
  $headers []= "institucionFinanciadora5Cod";
  $headers []= "disponibilidad";
  $headers []= "tipoTecnica";
  $headers []= "pais";
  $headers []= "anio";
  $headers []= "web";
  $headers []= "relevante";
  $headers []= "medioDivulgacion";
  $headers []= "infoAdicional";
  $headers []= "Cantidad de coautores";
  $headers []= "Institución en ese momento del investigador principal".$i;
  $headers []= "Subinstitución en ese momento investigador principal".$i;
  $headers []= "Cod Institución en ese momento investigador principal".$i;
  $headers []= "Subinstitución normalizada en ese momento investigador principal".$i;
  $headers []= "Carga horaria en ese momento investigador principal".$i;
  $headers []= "Días de coincidencia entre institución del investigador y el producto principal".$i;
  for ($i=1; $i <8 ; $i++) {
    $headers []= "Palabra clave ".$i;
  }
  for ($i=1; $i <4 ; $i++) {
    $headers []= "Área producto  ".$i;
    $headers []= "Subárea producto  ".$i;
    $headers []= "Disciplina producto  ".$i;
  }
  for ($i=1; $i <9 ; $i++) {
    $headers []= "Patente Código ".$i;
    $headers []= "Patente Título ".$i;
    $headers []= "Patente tipoRegistro ".$i;
    $headers []= "Patente patenteNacional ".$i;
    $headers []= "Patente deposito ".$i;
    $headers []= "Patente examen ".$i;
    $headers []= "Patente concesion ".$i;
  }
  for ($i=1; $i <36 ; $i++) {
    $headers []= "ID investigador ".$i;
    $headers []= "Nombre completo investigador ".$i;
    $headers []= "CVUy si/no investigador ".$i;
    $headers []= "Institución en ese momento investigador ".$i;
    $headers []= "Subinstitución en ese momento investigador ".$i;
    $headers []= "Cod Institución en ese momento investigador".$i;
    $headers []= "Subinstitución normalizada en ese momento investigador".$i;
    $headers []= "Carga horaria en ese momento investigador ".$i;
    $headers []= "Días de coincidencia entre institución del investigador y el producto ".$i;
    $headers []= "Tipo de coincidencia investigador ".$i;
  }
  return $headers;
}

function getPeopleCoincidencesCountArray($link){
  $sql = "SELECT id, count(*) as cantidad FROM `produccion_tecnica_productos_coautor_coincidencias` group by id";
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

function checkIfPersonAlsoHasproductInsideCVuy($link,$documento_encontrado,$productId,$tituloNormalizado){
  $sql = "SELECT * from produccion_tecnica_productos where documento = '".$documento_encontrado."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $otro_titulo = $row['titulo'];
        $otro_titulo_normalizado = cleanName($otro_titulo);
        if($tituloNormalizado==$otro_titulo_normalizado || levenshtein($tituloNormalizado,$otro_titulo_normalizado)<=3){
          //ENCONTRO EL PRODUCTO CON NOMBRE PARECIDO
          return $row['id'];
        }
      }
  }
  return false;
}

function getPersonFromCoincidences($link,$productId,$tituloNormalizado,$idPersona,&$coincidencesCountArray){
  $sql = "SELECT * from produccion_tecnica_productos_coautor_coincidencias where id = '".$idPersona."'";
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
            $defineProductoEnSuCv = checkIfPersonAlsoHasproductInsideCVuy($link,$documento_encontrado,$productId,$tituloNormalizado);
            if($defineProductoEnSuCv!=false){
              //APARECE SOLO UNA VEZ Y TAMBIEN DEFINE EL PRODUCTO. COINCIDENCIA 100%
              $coincidencesCountArray[$idPersona]['tipo_coincidencia'] = 'unica_con_producto';
              $coincidencesCountArray[$idPersona]['id_producto_coincidente'] = $defineProductoEnSuCv;
              $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
            }else{
              //TOMO EL QUE ESTA PORQUE ES EL UNICO QUE ENCONTRO (COINCIDENCIA 80%)
              $coincidencesCountArray[$idPersona]['tipo_coincidencia'] = 'unica_sin_producto';
              $persona = getPersonFromCVUyPeopleList($link,$documento_encontrado);
            }
            return $persona;
          }else{
            //TIENE MAS DE UNA COINCIDENCIA
            //if($coincidencesCountArray[$idPersona]['ya_en_planilla']==false){
              //ME FIJO SI ESTE PUEDE SER EL VALIDO BUSCANDO EL PRODUCTO EN SU CVUy
              $defineProductoEnSuCv = checkIfPersonAlsoHasproductInsideCVuy($link,$documento_encontrado,$productId,$tituloNormalizado);
              if($defineProductoEnSuCv!=false){
                //TIENE TAMBIEN DEFINIDO EL PRODUCTO EN SU CV, ENTONCES ES ESTA PERSONA
                $coincidencesCountArray[$idPersona]['tipo_coincidencia'] = 'multiple_con_producto';
                $coincidencesCountArray[$idPersona]['id_producto_coincidente'] = $defineProductoEnSuCv;
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

function getPeopleArrayFromProductDisciplinas($link,&$coincidencesCountArray,$productId,$tituloNormalizado,$anio,$maxLenghtArray){
  $sql = "SELECT * from produccion_tecnica_productos_coautor where idproducto = '".$productId."'";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idPersona = $row["id"];
        $persona = getPersonFromCoincidences($link,$productId,$tituloNormalizado,$idPersona,$coincidencesCountArray);
        if($persona!=false){
          $dataArray[]= $persona['id'];
          $dataArray[]= $persona['nombre_completo'];
          $dataArray[]= $persona['cvuy'];
          if($persona['cvuy']=="Si"){
            $tipo_coincidencia = $coincidencesCountArray[$idPersona]['tipo_coincidencia'];
            $producto_coincidente = null;
            if($tipo_coincidencia == "unica_con_producto" || $tipo_coincidencia == "multiple_con_producto"){
              $producto_coincidente = $coincidencesCountArray[$idPersona]['id_producto_coincidente'];
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

function getPeopleArrayFromProduct($link,&$coincidencesCountArray,$productId,$tituloNormalizado,$anio,$maxLenghtArray){
  $sql = "SELECT * from produccion_tecnica_productos_coautor where idproducto = '".$productId."'";
  $result = $link->query($sql);
  $dataArray = array();
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $idPersona = $row["id"];
        $persona = getPersonFromCoincidences($link,$productId,$tituloNormalizado,$idPersona,$coincidencesCountArray);
        if($persona!=false){
          $dataArray[]= $persona['id'];
          $dataArray[]= $persona['nombre_completo'];
          $dataArray[]= $persona['cvuy'];
          if($persona['cvuy']=="Si"){
            $tipo_coincidencia = $coincidencesCountArray[$idPersona]['tipo_coincidencia'];
            $producto_coincidente = null;
            if($tipo_coincidencia == "unica_con_producto" || $tipo_coincidencia == "multiple_con_producto"){
              $producto_coincidente = $coincidencesCountArray[$idPersona]['id_producto_coincidente'];
            }
            $vinculoInstitucionalDuranteElPeriodoDelProducto = getVinculoInstitucionalDuranteElPeriodoDelProducto($link,$persona['id'],$anio,$tipo_coincidencia,$tituloNormalizado,$producto_coincidente);
            if($vinculoInstitucionalDuranteElPeriodoDelProducto!=false){
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProducto['institucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProducto['subinstitucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProducto['tipo_institucion'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProducto['subinstitucion_nueva'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProducto['carga_horaria'];
              $dataArray[]= $vinculoInstitucionalDuranteElPeriodoDelProducto['daysOverlaping'];
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

function getInstitucionFromDefinicionProducto($link,$documento,$tituloNormalizado,$producto_coincidente_id,$tipo_coincidencia){
  $sql = "SELECT * from produccion_tecnica_productos where id = '".$producto_coincidente_id."'";
  $result = $link->query($sql);
  $dataArray = false;
  if ($result->num_rows > 0) {
    $dataArray = array();
    while($row = $result->fetch_assoc()) {
      $dataArray['institucion'] = $row['institucion'];
      $dataArray['subinstitucion'] = $row['dependencia'];
      $dataArray['carga_horaria'] = $row['cargaHorariaSemanal'];
      $dataArray['daysOverlaping'] = "No corresponde (tomado de definición de producto)";
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

function getVinculoInstitucionalDuranteElPeriodoDelProducto($link,$documento,$anio,$tipo_coincidencia,$tituloNormalizado,$producto_coincidente_id){
  if(1==2){
  /*if($tipo_coincidencia=="unica_con_producto" || $tipo_coincidencia=="multiple_con_producto"){
    $vinculo = getInstitucionFromDefinicionProducto($link,$documento,$tituloNormalizado,$producto_coincidente_id,$tipo_coincidencia);
    return $vinculo;*/
  }else{
    $sql = "SELECT * from vinculo_institucional where documento = '".$documento."'";
    $result = $link->query($sql);
    $dataArray = array();
    $fechaInicioComparable = DateTime::createFromFormat('Y-m-d', $anio."-01-01");
    $fechaFinComparable = DateTime::createFromFormat('Y-m-d', $anio."-12-31");
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
    foreach ($data_multi_array as $key => $data_array) {
        fputcsv($fp, $data_array);
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
