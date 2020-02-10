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
  if($action=="arbitrados"){
      getArbitrados($link);
  }
  if($action=="matrix_instituciones"){
    getMatrixArticulosPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2000","2005");
    getMatrixArticulosPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2006","2011");
    getMatrixArticulosPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2012","2017");

    getMatrixArticulosPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2000","2005");
    getMatrixArticulosPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2006","2011");
    getMatrixArticulosPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2012","2017");

    getMatrixArticulosPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2000","2005");
    getMatrixArticulosPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2006","2011");
    getMatrixArticulosPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2012","2017");
  }
  if($action=="matrix_disciplina"){
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2000","2005");
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2006","2011");
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ciencias Médicas y de la Salud","med","2012","2017");

    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2000","2005");
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2006","2011");
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ciencias Agrícolas","agraria","2012","2017");

    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2000","2005");
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2006","2011");
    getMatrixArticulosDisciplinasPorAreaPeriodo($link,"Ingeniería y Tecnología","tec","2012","2017");
  }
  if($action=="articulos_full"){
    $articulos = array();
    $articulosDimensions = getArticulosDimensions($link);
    $maxCoautores = 0;
    $idsUsadosDimensions = array();
    getArticulosFullPorAreaArray($link,"es","Ciencias Médicas y de la Salud","med",$articulos,$articulosDimensions,$maxCoautores,$idsUsadosDimensions);
    getArticulosFullPorAreaArray($link,"es","Ciencias Agrícolas","agraria",$articulos,$articulosDimensions,$maxCoautores,$idsUsadosDimensions);
    getArticulosFullPorAreaArray($link,"es","Ingeniería y Tecnología","tec",$articulos,$articulosDimensions,$maxCoautores,$idsUsadosDimensions);
    $headers = create_csv_articulos_arbitrados_header_array($maxCoautores);
    create_csv_file("articulos_arbitrados_cvuy_completo.csv",$headers,$articulos);
  }
  if($action=="articulos_full_mex"){
    $articulos = array();
    $articulosDimensions = getArticulosDimensions($link);
    $maxCoautores = 0;
    $idsUsadosDimensions = array();
    getArticulosFullPorAreaArray($link,"es","Ciencias Sociales","social",$articulos,$articulosDimensions,$maxCoautores,$idsUsadosDimensions);
    $headers = create_csv_articulos_arbitrados_header_array($maxCoautores);
    create_csv_file("articulos_arbitrados_cvuy_social.csv",$headers,$articulos);
  }
  if($action=="dimensions_full"){
    $articulos = array();
    getArticulosDimensionsFull($link,$articulos);
    $headers = create_csv_articulos_dimensions_header_array();
    create_csv_file("articulos_dimensions.csv",$headers,$articulos);
  }
}
$link->close();

function getArticulosDimensions($link){
  $articulos = array();
  $sql = "SELECT * from articulos_dimensions";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $autores = getAutoresArticulosDimensions($link,$row['id']);
        $row['authors'] = $autores;
        $articulos[] = $row;
      }
  }
  return $articulos;
}

function getAutoresArticulosDimensions($link,$idArticuloDimensions){
  $autores = array();
  $sql = "SELECT * from articulos_dimensions_authors where id_articulo = '".$idArticuloDimensions."'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $autores[] = $row;
      }
  }
  return $autores;
}

function getArticulosParecidos($articulo,$articulosDimensions){
  $parecidos = array();
  foreach ($articulosDimensions as $key => $otroArticulo) {
      $titulo = cleanName($articulo['titulo']);
      $otroTitulo = cleanName($otroArticulo['title']);
      if($titulo==$otroTitulo){
        //COINCIDENCIA 100%
        $otroArticulo['tipo_coincidencia'] = "total";
        $parecidos []= $otroArticulo;
        return $parecidos;
      }else{
        if(partesDelStringContenidas($titulo,$otroTitulo)!=false){
          //Coincidencia de partes
          $otroArticulo['tipo_coincidencia'] = "partes_contenidas";
          $parecidos []= $otroArticulo;
          return $parecidos;
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
            $otroArticulo['tipo_coincidencia'] = "85_similitud";
            $parecidos []= $otroArticulo;
            return $parecidos;
          }else{
            $hayAutorCoincidente = encontrarAutorEnDimensions($articulo,$otroArticulo);
            if($similaridad>=0.65 && $hayAutorCoincidente){
              $otroArticulo['tipo_coincidencia'] = "autor_y_65_similitud";
              $parecidos []= $otroArticulo;
              return $parecidos;
            }else{
              $mismoAnio = false;
              if($articulo['anio']==$otroArticulo['year']){
                $mismoAnio = true;
              }
              if($similaridad>=0.65 && $mismoAnio==true){
                $otroArticulo['tipo_coincidencia'] = "anio_y_65_similitud";
                $parecidos []= $otroArticulo;
                return $parecidos;
              }
            }
          }
        }
      }
  }
  return $parecidos;
}

function encontrarAutorEnDimensions($articulo,$otroArticulo){
   if($articulo["apellidos"]!=""){
     $partesApellido = explode(" ",$articulo["apellidos"]);
     $primerApellido = trim(strtolower($partesApellido[0]));
     foreach ($otroArticulo['authors'] as $key => $author) {
       $apellidoDimensions = trim(strtolower($author['last_name']));
       if (strpos($apellidoDimensions, $primerApellido) !== false) {
          return true;
       }
     }
   }
  return false;
}

function partesDelStringContenidas($texto1, $texto2){
  $textoMayor = $texto2;
  $textoMenor = $texto1;
  /*if(strlen($texto1)>=strlen($texto2)){
    //TEXTO 1 es mayor
    $textoMayor = $texto1;
    $textoMenor = $texto2;
  }else{
    //texto 2 es mayor
    $textoMayor = $texto2;
    $textoMenor = $texto1;
  }*/
  if (strpos($textoMayor, $textoMenor) !== false) {
      return true;
  }else{
    return false;
  }
}

function similaridadTextos($texto1, $texto2){
  $mayorTamanio = max(strlen($texto1),strlen($texto2));
  $levenshtein = levenshtein(substr($texto1,0,250),substr($texto2,0,250));
  $similaridad = 0;
  if($levenshtein!=-1){
    $similaridad = 1 - ($levenshtein/$mayorTamanio);
  }
  //echo "La similaridad de $texto1 ||| $texto2 es: " . $similaridad . "<br/>";
  return $similaridad;
}

function getMatrixArticulosDisciplinasPorAreaPeriodo($link,$area,$area_short,$anio_min,$anio_max){
  $sql = "SELECT * from articulo_revista_arbitrada where
  anio >= ".$anio_min."
  and anio <= ".$anio_max."
  and id in (select idarticulo from articulo_revista_arbitrada_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
  $proyectos = array();
  $instituciones = array();
  $count = 0;
  $articulosDimensions = getArticulosDimensions($link);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<17000){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          $datosProyecto[] = $id;
          $orgsArray = getPeopleArrayFromDimensionsOnlyCategories($link,$row,$tituloNormalizado,$instituciones,$articulosDimensions);
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
      $pares = array();
      foreach ($proyectos as $key => $orgsArray) {
        $orgsParArray = array();
        foreach ($orgsArray as $key => $org) {
          $orgsParArray[$org] = array();
          foreach ($orgsArray as $key => $value) {
            $orgsParArray[$org] = $value;
            if($org!=$value){
              if(array_key_exists($value, $matrix[$org])){
                $matrix[$org][$value] = $matrix[$org][$value] + 1;
              }else if(array_key_exists($org, $matrix[$value])){
                $matrix[$value][$org] = $matrix[$value][$org] + 1;
              }
            }
          }
        }
        $pares[] = $orgsParArray;
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
      create_csv_file("matriz_articulos_disciplinas_cvuy_".$area_short."_".$anio_min."_".$anio_max.".csv",$headers,$filas);
  }
}

function getPeopleArrayFromDimensionsOnlyCategories($link,$articuloCVuy,$tituloNormalizado,&$instituciones,$articulosDimensions){
  $parecidos = getArticulosParecidos($articuloCVuy,$articulosDimensions);
  $orgs = array();
  if(count($parecidos)>0){
     //TOMO EL PRIMER PARECIDO
     $macheo = $parecidos[0];
     //echo $articuloCVuy['titulo'] . "es parecido a " . $macheo['title'] . "<br/>";
     $sql = "SELECT * from articulos_dimensions_categories where id_articulo = '".$macheo['id']."'";
      $result = $link->query($sql);
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            if($row["id"]!=""){
              $partes = explode(" ",$row['category']);
              $catName = $partes[1];
              if(!in_array($catName,$orgs)){
                $orgs[]= $catName;
              }
              if(!in_array($catName,$instituciones)){
                $instituciones[]= $catName;
              }
            }
          }
      }
  }
  return $orgs;
}

function getMatrixArticulosPorAreaPeriodo($link,$area,$area_short,$anio_min,$anio_max){
  $sql = "SELECT * from articulo_revista_arbitrada where
  anio >= ".$anio_min."
  and anio <= ".$anio_max."
  and id in (select idarticulo from articulo_revista_arbitrada_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
  $proyectos = array();
  $instituciones = array();
  $count = 0;
  $articulosDimensions = getArticulosDimensions($link);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<17000){
        //if($count<100){
          $datosProyecto = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          $datosProyecto[] = $id;
          $orgsArray = getPeopleArrayFromDimensionsOnlyOrgs($link,$row,$tituloNormalizado,$instituciones,$articulosDimensions);
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
      create_csv_file("matriz_articulos_instituciones_cvuy_".$area_short."_".$anio_min."_".$anio_max.".csv",$headers,$filas);
  }
}

function getPeopleArrayFromDimensionsOnlyOrgs($link,$articuloCVuy,$tituloNormalizado,&$instituciones,$articulosDimensions){
  $parecidos = getArticulosParecidos($articuloCVuy,$articulosDimensions);
  $orgs = array();
  if(count($parecidos)>0){
     //TOMO EL PRIMER PARECIDO
     $macheo = $parecidos[0];
     //echo $articuloCVuy['titulo'] . "es parecido a " . $macheo['title'] . "<br/>";
     $sql = "SELECT * from articulos_dimensions_authors where id_articulo = '".$macheo['id']."'";
      $result = $link->query($sql);
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            if($row["org_id"]!=""){
              if(!in_array($row['org_name'],$orgs)){
                $orgs[]= $row['org_name'];
              }
              if(!in_array($row['org_name'],$instituciones)){
                $instituciones[]= $row['org_name'];
              }
            }
          }
      }
  }
  return $orgs;
}

function getMatrixArticulosPorAreaPeriodoFinanciacion($link,$area,$area_short,$anio_min,$anio_max){
  $sql = "SELECT * from articulo_revista_arbitrada where
  anio >= ".$anio_min."
  and anio <= ".$anio_max."
  and id in (select idproyecto from articulo_revista_arbitrada_area where area = '".$area."')
  or documento in (SELECT documento FROM area_principal WHERE area = '".$area."')";
  $result = $link->query($sql);
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
      create_csv_file("matriz_financiacion_articulos_cvuy_".$area_short."_".$anio_min."_".$anio_max.".csv",$headers,$filas);
  }
}

function getArticulosDimensionsFull($link,&$articulos){
   $sql = "SELECT a.* from articulos_dimensions a";
  $result = $link->query($sql);
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if(1==1){
        //Comento los condicionales por cantidad y sustituyo por 1==1 que siempre es true
        //if($count<5){
        //if($count<57301){
          $datosArticulo = array();
          $id = $row["id"];
          $tituloNormalizado = cleanName($row["title"]);
          //if(!array_key_exists_or_approaches($tituloNormalizado,$articulos)){
            $datosArticulo[] = $id;
            $datosArticulo[] = $tituloNormalizado;
            $datosArticulo[] = $row["type"];
            $datosArticulo[] = $row["year"];
            $datosArticulo[] = $row["doi"];
            $datosArticulo[] = $row["linkout"];
            $categoriasArray = getValuesArrayFromSql($link,"SELECT id,category FROM articulos_dimensions_cetegories WHERE id_articulo ='".$id."'",16);
            $conceptsArray = getValuesArrayFromSql($link,"SELECT word FROM articulos_dimensions_concepts WHERE id_articulo ='".$id."' limit 282",282);
            $fundersArray = getValuesArrayFromSql($link,"SELECT id,acronym,name,country_name FROM articulos_dimensions_funders WHERE id_articulo ='".$id."'",88);
            $journalsArray = getValuesArrayFromSql($link,"SELECT id,title FROM articulos_dimensions_journals WHERE id_articulo ='".$id."'",4);
            $meshArray = getValuesArrayFromSql($link,"SELECT word FROM articulos_dimensions_mesh_terms WHERE id_articulo ='".$id."' limit 36",36);
            $termsArray = getValuesArrayFromSql($link,"SELECT word FROM articulos_dimensions_terms WHERE id_articulo ='".$id."' limit 282",282);
            $autoresArray = getValuesArrayFromSql($link,"SELECT first_name,last_name,org_id,org_name,org_city,org_city_id,org_country,org_country_code FROM articulos_dimensions_authors WHERE id_articulo ='".$id."' limit 30",240);
            $filaArray = array_merge($datosArticulo,$categoriasArray,$conceptsArray,$fundersArray,$journalsArray,$meshArray,$termsArray,$autoresArray);
            $articulos[$tituloNormalizado] = $filaArray;
          //}
        }
      }
  }
}

function create_csv_articulos_dimensions_header_array(){
  $headers = array();
  $headers [] = "ID Dimensions";
  $headers [] = "Title";
  $headers [] = "Year";
  $headers [] = "DOI";
  $headers [] = "Link";
  for ($i=0; $i <8 ; $i++) {
    $headers []= "ID category ".$i;
    $headers []= "Name category ".$i;
  }
  for ($i=0; $i <282 ; $i++) {
    $headers []= "Concept ".$i;
  }
  for ($i=0; $i <22 ; $i++) {
    $headers []= "id Funder ".$i;
    $headers []= "acronym Funder ".$i;
    $headers []= "name Funder ".$i;
    $headers []= "country_name Funder ".$i;
  }
  for ($i=0; $i <2 ; $i++) {
    $headers []= "id Journal ".$i;
    $headers []= "title Journal ".$i;
  }
  for ($i=0; $i <36 ; $i++) {
    $headers []= "Mesh Term ".$i;
  }
  for ($i=0; $i <282 ; $i++) {
    $headers []= "Term ".$i;
  }
  for ($i=0; $i <80 ; $i++) {
    $headers []= "first_name Author ".$i;
    $headers []= "last_name Author ".$i;
    $headers []= "org_id Author ".$i;
    $headers []= "org_name Author ".$i;
    $headers []= "org_city Author ".$i;
    $headers []= "org_city_id Author ".$i;
    $headers []= "org_country Author ".$i;
    $headers []= "org_country_code Author ".$i;

  }
  return $headers;
}

function getArticulosFullPorAreaArray($link,$idioma,$area,$area_short,&$articulos,$articulosDimensions,&$maxCoautores,&$idsUsadosDimensions){
   $sql = "SELECT p.*, i.apellidos, i.sni from articulo_revista_arbitrada p, investigadores i
  where p.documento = i.documento
  and (p.id in
  (select idarticulo from articulo_revista_arbitrada_area where area = '".$area."' ) or p.documento in
    (SELECT documento FROM `area_principal` WHERE area = '".$area."'))";
  $result = $link->query($sql);
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if(1==1){
        //Comento los condicionales por cantidad y sustituyo por 1==1 que siempre es true
        //if($count<100){
        //if($count<57301){
          $datosArticulo = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(str_word_count($tituloNormalizado)>1){
            if(!array_key_exists_or_approaches($tituloNormalizado,$articulos)){
              $datosArticulo[] = $area;
              $datosArticulo[] = $id;
              $datosArticulo[] = $tituloNormalizado;
              $datosArticulo[] = $documentoPrincipal;
              /*$datosArticulo[] = $row["nombres"];
              $datosArticulo[] = $row["apellidos"];
              $datosArticulo[] = $row["sni"];*/
              $datosArticulo[] = $row["sni"];
              $datosArticulo[] = $row["lugarPublicacion"];
              $datosArticulo[] = $row["escritoPorInvitacion"];
              $datosArticulo[] = $row["volumen"];
              $datosArticulo[] = $row["fasciculo"];
              $datosArticulo[] = $row["serie"];
              $datosArticulo[] = $row["paginaInicial"];
              $datosArticulo[] = $row["paginaFinal"];
              $datosArticulo[] = $row["arbitrado"];
              $datosArticulo[] = $row["scopus"];
              $datosArticulo[] = $row["thompson"];
              $datosArticulo[] = $row["latindex"];
              $datosArticulo[] = $row["scielo"];
              $datosArticulo[] = $row["tipoArticulo"];
              $datosArticulo[] = $row["infoAdicional"];
              $datosArticulo[] = $row["anio"];
              $datosArticulo[] = $row["web"];
              $datosArticulo[] = $row["relevante"];
              $datosArticulo[] = $row["medioDivulgacion"];
              $datosArticulo[] = $row["revista_nombre"];
              $datosArticulo[] = $row["revista_issn"];
              //$datosArticulo[] = $row["cantidadcoautores"];
              $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from articulo_revista_arbitrada_palabra_clave where idarticulo='".$id."'",44);
              $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from articulo_revista_arbitrada_area where idarticulo='".$id."'",12);
              $dimensionsId = 0;
              $dimensionsTitle = "";
              $dimensionsMatch = null;
              $orgsArray = getPeopleArrayFromDimensionsOrgs($link,$row,$tituloNormalizado,$articulosDimensions,$maxCoautores,$dimensionsId,$dimensionsTitle,$dimensionsMatch);
//              if($dimensionsMatch!= null && !in_array($dimensionsMatch['id'],$idsUsadosDimensions)){
                //Si encontró el artículo en dimensios, lo agrego sólo una vez
                $datosArticulo[] = $dimensionsMatch['title'];
                $datosArticulo[] = $dimensionsMatch['id'];
                $datosArticulo[] = $dimensionsMatch['tipo_coincidencia'];
                $filaArray = array_merge($datosArticulo,$palabrasArray,$areasArray,$orgsArray);
                $articulos[$tituloNormalizado] = $filaArray;
                $idsUsadosDimensions[]=$dimensionsId;
/*              }else if($dimensionsId==0){
                $datosArticulo[] = "";
                $datosArticulo[] = "";
                $datosArticulo[] = "";
                $filaArray = array_merge($datosArticulo,$palabrasArray,$areasArray,$orgsArray);
                $articulos[$tituloNormalizado] = $filaArray;
              }
*/
            }
          }
        }
      }
  }
}

function getPeopleArrayFromDimensionsOrgs($link,$articuloCVuy,$tituloNormalizado,$articulosDimensions,&$maxCoautores,&$dimensionsId, &$dimensionsTitle, &$dimensionsMatch){
  $parecidos = getArticulosParecidos($articuloCVuy,$articulosDimensions);
  $orgs = array();
  if(count($parecidos)>0){
     //TOMO EL PRIMER PARECIDO
     $macheo = $parecidos[0];
     $dimensionsMatch = $macheo;
     $dimensionsId = $macheo['id'];
     $dimensionsTitle = $macheo['title'];
     //echo $articuloCVuy['titulo'] . "es parecido a " . $macheo['title'] . "<br/>";
     $sql = "SELECT * from articulos_dimensions_authors where id_articulo = '".$macheo['id']."'";
      $result = $link->query($sql);
      $count = 0;
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            if($row["org_id"]!=""){
              $count += 1;
              $orgs[]=$row["first_name"]." ".$row["last_name"];
              $orgs[]=$row["org_name"];
              $orgs[]=$row["org_country"];
            }
          }
      }
      if($maxCoautores<$count){
        $maxCoautores = $count;
      }
  }
  return $orgs;
}

function getArbitrados($link){
  $sql = "SELECT p.*,
 (SELECT nombres from investigadores g where g.documento = p.documento) as nombres,
 (SELECT apellidos from investigadores h where h.documento = p.documento) as apellidos,
 (SELECT sni from investigadores i where i.documento = p.documento) as sni,
 (SELECT count(*) from articulo_revista_arbitrada_coautor a where a.idarticulo = p.id) as cantidadcoautores
 from articulo_revista_arbitrada p
 where (p.id in
 (select idarticulo from articulo_revista_arbitrada_area where area = 'Ciencias Médicas y de la Salud'
   or area = 'Ciencias Agrícolas'
   or area = 'Ingeniería y Tecnología' ) or p.documento in
   (SELECT documento FROM `area_principal` WHERE area = 'Ciencias Médicas y de la Salud'
      or area = 'Ciencias Agrícolas'
      or area = 'Ingeniería y Tecnología'))";
  $result = $link->query($sql);
  //$coincidencesCountArray = getPeopleCoincidencesCountArray($link);
  $articulos = array();
  $count = 0;
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $count+=1;
        if($count<19029){
        //if($count<100){
          $datosArticulo = array();
          $id = $row["id"];
          $documentoPrincipal = $row["documento"];
          $tituloNormalizado = cleanName($row["titulo"]);
          if(!array_key_exists_or_approaches($tituloNormalizado,$articulos)){
            $datosArticulo[] = $id;
            $datosArticulo[] = $tituloNormalizado;
            $datosArticulo[] = $documentoPrincipal;
            $datosArticulo[] = $row["nombres"];
            $datosArticulo[] = $row["apellidos"];
            $datosArticulo[] = $row["sni"];
            $datosArticulo[] = $row["lugarPublicacion"];
            $datosArticulo[] = $row["escritoPorInvitacion"];
            $datosArticulo[] = $row["volumen"];
            $datosArticulo[] = $row["fasciculo"];
            $datosArticulo[] = $row["serie"];
            $datosArticulo[] = $row["paginaInicial"];
            $datosArticulo[] = $row["paginaFinal"];
            $datosArticulo[] = $row["arbitrado"];
            $datosArticulo[] = $row["scopus"];
            $datosArticulo[] = $row["thompson"];
            $datosArticulo[] = $row["latindex"];
            $datosArticulo[] = $row["scielo"];
            $datosArticulo[] = $row["tipoArticulo"];
            $datosArticulo[] = $row["infoAdicional"];
            $datosArticulo[] = $row["anio"];
            $datosArticulo[] = $row["web"];
            $datosArticulo[] = $row["relevante"];
            $datosArticulo[] = $row["medioDivulgacion"];
            $datosArticulo[] = $row["revista_nombre"];
            $datosArticulo[] = $row["revista_issn"];
            $datosArticulo[] = $row["cantidadcoautores"];
            $palabrasArray = getValuesArrayFromSql($link,"SELECT palabra from articulo_revista_arbitrada_palabra_clave where idarticulo='".$id."'",44);
            $areasArray = getValuesArrayFromSql($link,"SELECT area, subarea, disciplina from articulo_revista_arbitrada_area where idarticulo='".$id."'",12);
            $filaArray = array_merge($datosArticulo,$palabrasArray,$areasArray);
            $articulos[$tituloNormalizado] = $filaArray;
          }
        }
      }
      $headers = create_csv_articulos_arbitrados_header_array();
      //create_csv_file("articulos_arbitrados_sni_cvuy.csv",$headers,$articulos);
      create_csv_file("articulos_arbitrados_todos_cvuy.csv",$headers,$articulos);
  }
}

function create_csv_articulos_arbitrados_header_array($cantidadMaximaCoautores){
  $headers = array();
  $headers [] = "Área por la que entró";
  $headers []= "ID Artículo";
  $headers []= "Título CVUy";
  $headers []= "Documento del que define el artículo en el CV";
  $headers []= "Nivel SNI autor";
  /*$headers []= "Nombres autor";
  $headers []= "Apellidos autor";
  $headers []= "Nivel SNI autor";*/
  $headers []= "lugarPublicacion";
  $headers []= "escritoPorInvitacion";
  $headers []= "volumen";
  $headers []= "fasciculo";
  $headers []= "serie";
  $headers []= "paginaInicial";
  $headers []= "paginaFinal";
  $headers []= "arbitrado";
  $headers []= "scopus";
  $headers []= "thompson";
  $headers []= "latindex";
  $headers []= "scielo";
  $headers []= "tipoArticulo";
  $headers []= "infoAdicional";
  $headers []= "anio";
  $headers []= "web";
  $headers []= "relevante";
  $headers []= "medioDivulgacion";
  $headers []= "revista_nombre";
  $headers []= "revista_issn";
  $headers []= "Título Dimensions";
  $headers []= "ID Dimensions";
  $headers []= "Tipo coincidencia";
  //$headers []= "cantidad coautores";
  for ($i=1; $i <45 ; $i++) {
    $headers []= "Palabra clave ".$i;
  }
  for ($i=1; $i <5 ; $i++) {
    $headers []= "Área artículo ".$i;
    $headers []= "Subárea artículo ".$i;
    $headers []= "Disciplina artículo ".$i;
  }
  for ($i=1; $i <= $cantidadMaximaCoautores ; $i++) {
    $headers []= "Coautor ".$i;
    $headers []= "Institución coautor ".$i;
    $headers []= "País institución coautor ".$i;
  }
  return $headers;
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
