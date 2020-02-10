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
  $sql = "SELECT * FROM recorte_proyecto_investigacion WHERE idioma = 'en'";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $id = $row['id'];
      $titulo = $row['titulo'];
      $desc = $row['descripcion'];
      $tituloES = getTranslation($titulo);
      $descES = getTranslation($desc);
      $sql2 = "update recorte_proyecto_investigacion set tituloTraducido='$tituloES', descripcionTraducida='$descES' where id = '$id'";
      $result2 = $link->query($sql2);
    }
  }
}
$link->close();


function checkGoogleApiKey(){
  $apiKey = 'AIzaSyAOIlqEHidyCcE_FKqhtERsUfqM4gaqEa8';
  $url = 'https://www.googleapis.com/language/translate/v2/languages?key=' . $apiKey;

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);     //We want the result to be saved into variable, not printed out
  $response = curl_exec($handle);
  curl_close($handle);

  print_r(json_decode($response, true));
}

function getTranslation($text){
  $apiKey = 'AIzaSyAOIlqEHidyCcE_FKqhtERsUfqM4gaqEa8';
  $url = 'https://www.googleapis.com/language/translate/v2?key=' . $apiKey . '&q=' . rawurlencode($text) . '&target=es';

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($handle);
  $responseDecoded = json_decode($response, true);
  curl_close($handle);

  /*echo 'Source: ' . $text . '<br>';
  echo 'Translation: ' . $responseDecoded['data']['translations'][0]['translatedText'];*/
  return $responseDecoded['data']['translations'][0]['translatedText'];
}

?>
