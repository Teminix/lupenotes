<?php

// Link with include: include __DIR__."/php/lib.php;
// Link with require: require __DIR__."/php/lib.php;
function getfname($suff = ""){
  return basename(__FILE__,$suff);
}
function getdirname() {
  return dirname(__FILE__);
}
function rescharstr($string,$reschars) {
  foreach ($reschars as $reschar) {
    if (strpos($string,$reschar) !== FALSE) {
      return "TRUE";
    }
  }
  return "FALSE";
}
function absolutepath($rel_path){
  if ($rel_path[0] == "/") {
    $absolute_path = __DIR__;
  }
  else {
    $absolute_path = __DIR__."/";
  }

  $absolute_path = $absolute_path.$rel_path;
  return $absolute_path;
}
function arraytostr($array,$div = ",") {
  $string = '';
  foreach ($array as $key => $value) {
    $string = $string."'".$key."'=>'".$value."'".$div;
  }
  return substr_replace($string,"","-1");
}
function strtoarray($string) {
  $array = array();
  for ($i=0; $i < strlen($string); $i++) {
    array_push($array,$string[$i]);
  }
  return $array;
}
function concat_array($array,$concat) {
  foreach ($concat as $key) {
    array_push($array,$key);
  }
  return $array;
}
 ?>
