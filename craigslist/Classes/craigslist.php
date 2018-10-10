<?php
class parseRSS
{
  function __construct() {
    return '__constructed';
  }
  public function data($url){
    $i=0;
    $fileContents = file_get_contents($url);
    if (!$fileContents):
      return json_encode((object)array());
    endif;

    $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
    $fileContents = trim(str_replace('"', "'", $fileContents));
    $fileContents = str_replace("dc:date", "date", $fileContents);//return $fileContents;
    $simpleXml    = simplexml_load_string($fileContents); //return $simpleXml->item[2]->description;

    foreach($simpleXml->item as $item):
      $simpleXml->item[$i]->description = $simpleXml->item[$i]->description;
      $simpleXml->item[$i]->title = $simpleXml->item[$i]->title;
      $simpleXml->item[$i]->date = date('Y-m-d',strtotime($simpleXml->item[$i]->date));
      $i++;
    endforeach;


    return json_encode($simpleXml);
  }
}
