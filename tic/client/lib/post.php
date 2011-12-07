<?php

function post($url, $params = null) {
  $cparams = array(
    'http' => array(
      'method' => 'POST',
      'header'=> 'Content-type: application/x-www-form-urlencoded',
      'ignore_errors' => true,
    )
  );

  if ($params !== null) {
      $cparams['http']['content'] = json_encode($params);
  }

  $context = stream_context_create($cparams);
  $fp = fopen($url,
              'rb',
              false,
              $context
             );
  if (!$fp) {
    $res = false;
  }
  else {
    $res = stream_get_contents($fp);
  }

  return $res;
}
