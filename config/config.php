<?php

ob_start();

@session_start();

ini_set('display_errors', 1);

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);



if(!defined(BASEURL))

define(BASEURL,"http://localhost/shobbu/");

//define(BASEURL,"http://127.0.0.1/sof/");

if(!defined(BASEURL2))

define(BASEURL2,"shobbu/");
define(BASEURL3,"/Applications/XAMPP/xamppfiles/htdocs/shobbu/site_images/Slides/");



// Define database constants ONLINE


define(DB_TYPE, "mysql");
define(DB_HOST, "localhost");
define(DB_USER, "root");
define(DB_PASS, "");
define(DB_NAME, "kvc");



// Define the default display class for errors

define(DEFAULT_DISPLAY_CLASS, 'alert error');






require_once 'autoloader.php';



?>