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

  $external_url = "http://huffman.csic.edu.uy/wscvuy/instituciones.csv";
  $data = file_get_contents($external_url);
  $rows = str_getcsv($data, "\n"); //parse the rows
  foreach($rows as $key => $row){
          $rowParsed = str_getcsv($row, "|"); //parse the items in rows
          $institucion = $rowParsed[0];
          $subinstitucion = $rowParsed[1];
          $institucion_cod = $rowParsed[2];
          $subinstitucion_nueva = $rowParsed[3];

          $sql = "UPDATE institucion_principal set tipo_institucion = '$institucion_cod'
          where institucion = '$institucion'
          and tipo_institucion is null";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE recorte_proyecto_investigacion_institucion_financiadora set tipo_institucion = '$institucion_cod'
          where institucion = '$institucion'
          and tipo_institucion is null";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE vinculo_institucional set tipo_institucion = '$institucion_cod'
          where institucion = '$institucion'
          and tipo_institucion is null";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";

          $sql = "UPDATE recorte_proyecto_investigacion set tipo_institucion = '$institucion_cod'
          where institucion = '$institucion'
          and tipo_institucion is null";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";
  }
}
$link->close();


?>
