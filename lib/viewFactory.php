<?php

include_once(__DIR__."/viewJson.php");
include_once(__DIR__."/viewRDF.php");
include_once(__DIR__."/viewInterface.php");

class ViewFactory
{
    private $view;
    private $rdf;
    public function __construct($format, $rdf) {
        $this->view = "View".ucfirst($format);
        $this->rdf = $rdf;
    }
    
    public function build() {
        return new $this->view($this->rdf);
    }
}