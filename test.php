<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 12/10/15
 * Time: 12:44 PM
 */

$submit_url = "http://localhost/slimt/products";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $submit_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close ($ch);
$data = json_decode($result, true);
var_dump($data);

