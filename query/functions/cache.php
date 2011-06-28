<?php

function c_file_get_contents($url,$age = 3600,$prefix = "") { 
   
   $cache = md5($url);
   $filename = $prefix."/tmp/cache_$cache.cache";

  if (file_exists($filename)) {
    $mtime = filemtime($filename);
    $fileage = time() - mtime;
    if ($fileage>$age) {
      $file = file_get_contents($filename);
    } else {
      $file = file_get_contents($url);
      file_put_contents($filename,$file);
    }
  } else {
      $file = file_get_contents($url);
      file_put_contents($filename,$file);
  }
 
  return $file;
}