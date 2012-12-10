<?php
// Very Short MVC Sytème
require __DIR__.'/../controller.php';
$controller = new Controller();
$function = trim($_SERVER["REQUEST_URI"],"/");
if (!is_callable(array($controller,$function))) {
    $function = "index";
}
if (!is_callable(array($controller, $function))) {
    throw new Exception('is_callable is false');
}

$view = $controller->$function(); //Controller

@include(__DIR__."/../views/".$function.".php"); //View