<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
  $cache = new Cache();
  $cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
  $cache->set_tempo(604800);
  $xml = new DOMDocument('1.0', 'UTF-8');
  header("Content-Type: text/xml");
  $cache->start();
  
  if($cache->cached === FALSE){
    $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (integer) $_GET['id'] : FALSE;
    if(!isset($_GET['id']) || (isset($_GET['id']) && $id !== FALSE)){
      $alojamentos = Alojamento::get_alojamentos($id);
      if($alojamentos !== FALSE){
        $xml->formatOutput = TRUE;
        $als = $xml->createElement('alojamentos');
        $xml->appendChild($als);
        foreach($alojamentos as $alojamento){
          $al = $xml->createElement('alojamento');
          
          $id = $xml->createAttribute('id');
          $id->appendChild($xml->createTextNode($alojamento->id));
          $name = $xml->createAttribute('name');
          $name->appendChild($xml->createTextNode($alojamento->nome));
          $info = $xml->createAttribute('info');
          $info->appendChild($xml->createTextNode($alojamento->endereco));
          $tel = $xml->createAttribute('tel');
          $tel->appendChild($xml->createTextNode($alojamento->info_tel));
          $lat = $xml->createAttribute('lat');
          $lat->appendChild($xml->createTextNode($alojamento->latitude));
          $lng = $xml->createAttribute('lng');
          $lng->appendChild($xml->createTextNode($alojamento->longitude));
          
          $als->appendChild($al);
          $al->appendChild($id);
          $al->appendChild($name);
          $al->appendChild($info);
          $al->appendChild($tel);
          $al->appendChild($lat);
          $al->appendChild($lng);
        }
        echo $xml->saveXML();
      }
    }
  }
  $cache->close();
?>
