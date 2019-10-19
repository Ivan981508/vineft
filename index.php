<?php
ini_set('display_errors',0);
error_reporting(E_ALL);
session_start();
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');

// Вызов Router
$router = new Router();
$router->run();

 