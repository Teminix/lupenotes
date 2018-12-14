<?php

$root_dir = "http://projhost:8088/";
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

function temp($url,$vars=[]) { // vars is for the moment where the server would need to pass in variables to the template
  $temp = file_get_contents($url);
  $regex = preg_replace("/<\?php|\?>/","",$temp);
  foreach ($vars as $key => $value) {
    $varstring = $varstring."\$$key = '$value';";
  }
  $return = eval($varstring.$regex);
  return $return;
}

function verify_email($string) { // checks if the email address is valid
  if (filter_var($string,FILTER_VALIDATE_EMAIL) == false) {
    return false;
  }
  else {
    return true;
  }
}






// TEMPLATE GENERATION 2 function

function local($file)
{
  $uri = explode("/",$_SERVER["REQUEST_URI"]);
  $count =  count($uri)-2;
  unset($uri[$count+1]);
  unset($uri[$count]);
  unset($uri[0]);
  $file = str_replace(" ",'%20',$file);
  array_push($uri,$file);
  return "http://".$_SERVER["HTTP_HOST"]."/".implode("/",$uri);
}
function basic_curl($file) {
  $curl = curl_init();
  curl_setopt($curl,CURLOPT_URL,$file);
  curl_setopt($curl,CURLOPT_HEADER,false);
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
  $result = curl_exec($curl);
  $curl_close($curl);
}
function temp_curl($path,$data=NULL,$root=NULL,$debug=NULL) {
  if ($root != NULL) {
    $path = $root.$path;
  }
  if ($data == NULL) { // If there is no data needed for the template
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,$path);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_HEADER,false);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
  }

  else {
    if (strpos($path,"?") === FALSE) {
      $path = $path."?";
    }
    else {
      $path = preg_replace('/\$+/gi',"?",$path);
    }
    foreach ($data as $key => $value) {
      $path = $path."$key=$value&";
    }
    $curl = curl_init();
    // $path = local($file);

    curl_setopt($curl, CURLOPT_URL,$path);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_HEADER,false);
    $result = curl_exec($curl);
    curl_close($curl);
    if ($debug == TRUE) {
      return $path.$result;
    }
    return $result;
  }


}

 ?>
