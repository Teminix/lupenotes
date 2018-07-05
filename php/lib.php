<?php
function getfname($suff = ""){
  return basename(__FILE__,$suff);
}
function getdirname() {
  return dirname(__FILE__)
}
function rescharstr($string,$reschars) {
  foreach ($reschars as $reschar) {
    if (strpos($string,$reschar) !== FALSE) {
      return "TRUE"
    }
  }
  
}
 ?>
