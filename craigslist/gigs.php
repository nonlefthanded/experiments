<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-type:application/json");
  require_once('Classes/craigslist.php');
  $query = (!isset($_GET['query'])) ? 'wordpress' : $_GET['query'];
  $city  = (!isset($_GET['city'])) ? 'portland' : $_GET['city'];
  $clUrl = sprintf('https://%s.craigslist.org/search/cpg?format=rss&is_paid=all&query=%s',$city,$query);
  $cityInfo = new ParseRSS();
  echo $cityInfo->data($clUrl);
?>
