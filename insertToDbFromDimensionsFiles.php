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
  for ($i=0; $i < 32; $i++) {
    $file = "./dimensionsFiles/dataFull".$i.".json";
    $strJsonFileContents = file_get_contents($file);
    // Convert to array
    $array = json_decode($strJsonFileContents, true);
    foreach ($array["publications"] as $key => $publicacion) {
      insertArticulo($link,$publicacion);
      insertArticuloJournal($link,$publicacion,$publicacion["journal"]);
      foreach ($publicacion["author_affiliations"][0] as $subkey => $obj) {
        insertArticuloAuthor($link,$publicacion,$obj);
      }
      foreach ($publicacion["terms"] as $subkey => $obj) {
        insertArticuloTerms($link,$publicacion,$obj);
      }
      foreach ($publicacion["concepts"] as $subkey => $obj) {
        insertArticuloConcepts($link,$publicacion,$obj);
      }
      foreach ($publicacion["mesh_terms"] as $subkey => $obj) {
        insertArticuloMeshTerms($link,$publicacion,$obj);
      }
      foreach ($publicacion["FOR_first"] as $subkey => $obj) {
        insertArticuloCategory($link,$publicacion,$obj);
      }
      foreach ($publicacion["FOR"] as $subkey => $obj) {
        insertArticuloCategory($link,$publicacion,$obj);
      }
      foreach ($publicacion["funders"] as $subkey => $obj) {
        insertArticuloFunder($link,$publicacion,$obj);
      }
    }
  }
}
$link->close();


function varcharBdValue($link,$origin){
  if(isset($origin)){
    return $link->real_escape_string($origin);
  }else{
    return "";
  }
}

function insertArticulo($link,$publicacion){
  try {
        $query="insert into articulos_dimensions (
        id,
        title,
        type,
        year,
        doi,
        linkout
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$publicacion['title'])."',
          '".varcharBdValue($link,$publicacion['type'])."',
          '".varcharBdValue($link,$publicacion['year'])."',
          '".varcharBdValue($link,$publicacion['doi'])."',
          '".varcharBdValue($link,$publicacion['linkout'])."'
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

function insertArticuloTerms($link,$publicacion,$obj){
  try {
        $query="insert into articulos_dimensions_terms (
        id_articulo,
        word
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj)."'
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

function insertArticuloConcepts($link,$publicacion,$obj){
  try {
        $query="insert into articulos_dimensions_concepts (
        id_articulo,
        word
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj)."'
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

function insertArticuloMeshTerms($link,$publicacion,$obj){
  try {
        $query="insert into articulos_dimensions_mesh_terms (
        id_articulo,
        word
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj)."'
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

function insertArticuloCategory($link,$publicacion,$obj){
  try {
        $query="insert into articulos_dimensions_categories (
        id_articulo,
        id,
        category
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj['id'])."',
          '".varcharBdValue($link,$obj["name"])."'
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

function insertArticuloJournal($link,$publicacion,$obj){
  try {
        $query="insert into articulos_dimensions_journals (
        id_articulo,
        id,
        title
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj['id'])."',
          '".varcharBdValue($link,$obj["title"])."'
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

function insertArticuloFunder($link,$publicacion,$obj){
  try {
        $query="insert into articulos_dimensions_funders (
        id_articulo,
        id,
        acronym,
        name,
        country_name
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj['id'])."',
          '".varcharBdValue($link,$obj['acronym'])."',
          '".varcharBdValue($link,$obj['name'])."',
          '".varcharBdValue($link,$obj["country_name"])."'
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

function insertArticuloAuthor($link,$publicacion,$obj){
  $org = array();
  if(isset($obj["affiliations"][0])){
      $org = $obj["affiliations"][0];
  }
  try {
        $query="insert into articulos_dimensions_authors (
        id_articulo,
        first_name,
        last_name,
        org_id,
        org_name,
        org_city,
        org_city_id,
        org_country,
        org_country_code
        ) values (
          '".varcharBdValue($link,$publicacion['id'])."',
          '".varcharBdValue($link,$obj['first_name'])."',
          '".varcharBdValue($link,$obj['last_name'])."',
          '".varcharBdValue($link,$org['id'])."',
          '".varcharBdValue($link,$org['name'])."',
          '".varcharBdValue($link,$org['city'])."',
          '".varcharBdValue($link,$org['city_id'])."',
          '".varcharBdValue($link,$org['country'])."',
          '".varcharBdValue($link,$org["country_code"])."'
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




 ?>
