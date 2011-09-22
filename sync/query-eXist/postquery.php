<?php

curl_setopt($ch, CURLOPT_URL, 'http://tools.aidinfolabs.org/exist/db/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, "TEST");
$data = curl_exec ($ch);
curl_close ($ch);


?>