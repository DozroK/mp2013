<?php

namespace lib;

class ViewFactory
{
    private $view;
    private $rdf;
    
    public function __construct($format, $rdf) {
        $this->view = "lib\View".ucfirst(strtolower($format));
        $this->rdf = $rdf;
    }
    
    public function build() {
        return new  $this->view($this->rdf);
    }
}