<?php
// Very Short MVC Sytème
require __DIR__.'/../controller.php';
$controller = new Controller();
$function = trim($_SERVER["REDIRECT_URL"],"/");
if (!is_callable(array($controller,$function))) {
    $function = "index";
}
if (!is_callable(array($controller, $function))) {
    throw new Exception('is_callable is false');
}

//TODO : sanitize sur $_GET et $function

if (empty($_GET)) {
    $params = null;
} else {
    $params = $_GET;
}

$view = $controller->$function($params); //Controller

@include(__DIR__."/../views/".$function.".php"); //View