<?php
// Very Short MVC Sytème

$function = trim(parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

require __DIR__.'/../controller.php';
$controller = new Controller();
if (empty($function)) {
        $function = "index";
}

if (!is_callable(array($controller,$function))) {
    $function = "page404";
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