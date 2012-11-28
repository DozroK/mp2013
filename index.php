<?php
// Very Short MVC Sytème
require __DIR__.'/controller.php';
$controller = trim($_SERVER["REQUEST_URI"],"/");
if (!is_callable($controller)) {
    throw new Exception('is_callable is false');
}
$view = $controller(); //Controller
include(__DIR__."/views/".$controller.".php"); //View