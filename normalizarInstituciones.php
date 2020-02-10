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

  $external_url = "http://huffman.csic.edu.uy/wscvuy/instituciones.csv";
  $data = file_get_contents($external_url);
  $rows = str_getcsv($data, "\n"); //parse the rows
  foreach($rows as $key => $row){
          $rowParsed = str_getcsv($row, "|"); //parse the items in rows
          $institucion = $rowParsed[0];
          $subinstitucion = $rowParsed[1];
          $institucion_cod = trim($rowParsed[2]);
          $subinstitucion_nueva = trim($rowParsed[3]);

          $sql = "UPDATE institucion_principal set tipo_institucion = '$institucion_cod',
          subinstitucion_nueva = '$subinstitucion_nueva' where institucion = '$institucion'
          and subinstitucion = '$subinstitucion'";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE recorte_proyecto_investigacion_institucion_financiadora set tipo_institucion = '$institucion_cod',
          subinstitucion_nueva = '$subinstitucion_nueva' where institucion = '$institucion'
          and subinstitucion = '$subinstitucion'";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE vinculo_institucional set tipo_institucion = '$institucion_cod',
          subinstitucion_nueva = '$subinstitucion_nueva' where institucion = '$institucion'
          and subinstitucion = '$subinstitucion'";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE recorte_proyecto_investigacion set tipo_institucion = '$institucion_cod',
          subinstitucion_nueva = '$subinstitucion_nueva' where institucion = '$institucion'
          and dependencia = '$subinstitucion'";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE proyecto_investigacion set tipo_institucion = '$institucion_cod',
          subinstitucion_nueva = '$subinstitucion_nueva' where institucion = '$institucion'
          and dependencia = '$subinstitucion'";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";
  }
}
$link->close();


?>
