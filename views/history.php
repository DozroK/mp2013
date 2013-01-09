<?php 
// TODO : en php 5.4, passer par JSON_PRETTY_PRINT au lieu de lib\ViewJson::indent
echo lib\ViewJson::indent(json_encode($view));

