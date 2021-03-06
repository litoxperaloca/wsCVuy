<?php
require_once './config.php';


require_once './language-detection-master/src/LanguageDetection/Tokenizer/TokenizerInterface.php';
require_once './language-detection-master/src/LanguageDetection/Tokenizer/WhitespaceTokenizer.php';
require_once './language-detection-master/src/LanguageDetection/NgramParser.php';
require_once './language-detection-master/src/LanguageDetection/Language.php';
require_once './language-detection-master/src/LanguageDetection/LanguageResult.php';
require_once './language-detection-master/src/LanguageDetection/Trainer.php';

use LanguageDetection\Language;

$link = new mysqli($dbHOST, $dbUSER, $dbPASS, $dbNAME);
$link->query("SET NAMES 'utf8'");
if ($link->connect_errno) {
    echo "Error: Fallo al conectarse a MySQL debido a: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}else{
  //$ld = new Language(['en', 'es', 'fr', 'pt-BR', 'pt-PT']);
  $ld = new Language(['en', 'es']);
  $sql = "SELECT * from recorte_proyecto_investigacion";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $titulo = $row["titulo"];
        $descripcion = $row["descripcion"];
        $idioma = $ld->detect($titulo)->bestResults()->close();
        $idiomaEncontrado = "";
        $match = 0;
        foreach ($idioma as $lang => $value) {
          if($value>$match){
            $match = $value;
            $idiomaEncontrado = $lang;
          }
        }
        if($idiomaEncontrado!=""){
          $sql2 = "UPDATE recorte_proyecto_investigacion set idioma = '".$idiomaEncontrado."' where id = '".$id."'";
          $result2 = $link->query($sql2);
        }
      }
  }
}
$link->close();


?>
