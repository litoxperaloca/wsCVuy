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

  $external_url = "http://huffman.csic.edu.uy/wscvuy/institucionesProductos.csv";
  $data = file_get_contents($external_url);
  $rows = str_getcsv($data, "\n"); //parse the rows
  foreach($rows as $key => $row){
          $rowParsed = str_getcsv($row, "|"); //parse the items in rows
          $institucion = $rowParsed[0];
          $inst_fin_1 = trim($rowParsed[1]);
          $inst_fin_cod_1 = trim($rowParsed[2]);
          $inst_fin_2 = trim($rowParsed[3]);
          $inst_fin_cod_2 = trim($rowParsed[4]);
          $inst_fin_3 = trim($rowParsed[5]);
          $inst_fin_cod_3 = trim($rowParsed[6]);
          $inst_fin_4 = trim($rowParsed[7]);
          $inst_fin_cod_4 = trim($rowParsed[8]);
          $inst_fin_5 = trim($rowParsed[9]);
          $inst_fin_cod_5 = trim($rowParsed[10]);

          $sql = "UPDATE produccion_tecnica_productos
          set institucion_financiadora_1 = '$inst_fin_1', institucion_financiadora_cod_1 = '$inst_fin_cod_1',
          institucion_financiadora_2 = '$inst_fin_2', institucion_financiadora_cod_2 = '$inst_fin_cod_2',
          institucion_financiadora_3 = '$inst_fin_3', institucion_financiadora_cod_3 = '$inst_fin_cod_3',
          institucion_financiadora_4 = '$inst_fin_4', institucion_financiadora_cod_4 = '$inst_fin_cod_4',
          institucion_financiadora_5 = '$inst_fin_5', institucion_financiadora_cod_5 = '$inst_fin_cod_5'
          where institucionFinanciadora = '$institucion'";
          $result = $link->query($sql);
          echo $sql;
          echo "<br/>";
  }
}
$link->close();


?>
