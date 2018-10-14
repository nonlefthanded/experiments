<?php
  $contents  = file_get_contents('horoscope.js');
  $contents  = utf8_encode($contents);
  $contents  = json_decode($contents);
  $callback  = (isset($_GET['callback'])) ? htmlentities($_GET['callback']) : null ;
  $a['mod']  = $contents->data->last_modified;
  $a['now']  = time();

  if (isset($_GET['callback'])) { echo trim(htmlentities($_GET['callback'])) . "("; }
  if (($a['now'] - $a['mod']) > 60*60*2) {
    // We're gonna write a new one.
    // Sending back enough to let the page know we're on it...
    $data = new stdClass();
    $data->data->last_modified = $contents->data->last_modified;
    echo json_encode($data);
    $hh = new horoscope_hash;
} else {
// We don't need to write a new one, print it out.
$scopes  = file_get_contents('horoscope.js');
$scopes  = utf8_encode($scopes);
echo $scopes;
}
if (isset($_GET['callback'])) { echo ")"; }

class horoscope_hash {
public function __construct() {
  date_default_timezone_set('America/Los_Angeles');
  $this->data->last_modified       = time();
  $this->data->last_modified_human = strftime("%c",time());
  $signs = "Aries Taurus Gemini Cancer Leo Virgo Libra Scorpio Sagittarius Capricorn Aquarius Pisces";
  foreach (explode(' ', $signs) as $s) {
    $k = strtolower($s);
    $this->signs->$k->name = $s;
    $this->signs->$k->url = "http://www.freewillastrology.com/horoscopes/" . $k . ".html";
    $str = str_replace("\r", "", str_replace("\n", "", preg_replace('/\s\s+/', ' ', file_get_contents($this->signs->$k->url))));
    preg_match("/<!-- main content -->.*?<div class=\"head-red\">(.*?)<\/div>.*?<img src=\"(.*?)\".*?alt=\"$s.*?\((.*?)\)\".*?<br>(.*?)<br>/", $str, $matches);
    // echo "<pre>";
    // print_r($matches);
    // echo "</pre>";
    // die;
    $this->signs->$k->text = trim($matches[4]);
    $this->signs->$k->image = 'http://www.freewillastrology.com' . trim($matches[2]);

    // Split up the dates to figure out what sign it is now...
    $this->signs->$k->date->range = $matches[3];
    $tmp['range']  = explode('-',$matches[3]);
    $tmp['begin']  = explode(' ',$tmp['range'][0]);
    $tmp['end']    = explode(' ',$tmp['range'][1]);
    $tmp['now'][0] = strftime("%B", time());
    $tmp['now'][1] = strftime("%d", time());
    if (
      $tmp['begin'][0] == $tmp['now'][0] && $tmp['begin'][1] <= $tmp['now'][1]
      ||
      $tmp['end'][0] == $tmp['now'][0] && $tmp['end'][1] >= $tmp['now'][1]
    ) {
      $this->signs->$k->current = TRUE;
    }
  }
  // Write file with the results...
  $fh = fopen("horoscope.js", 'w') or die("can't open file");
  fwrite($fh, json_encode($this));
  fclose($fh);
}
}
?>
