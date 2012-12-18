<?php

namespace lib;

use lib\ViewInterface;

class ViewRDF implements viewInterface
{

    private $rdf;
    public function __construct($rdf) {
        $this->rdf = $rdf;
    }
    
    public function get() {
        return str_replace("___",":",$this->rdf) ;
    }
    public function getHeader() {
        return 'Content-type: text/xml';
//        return 'Content-type: application/rdf+xml';

    }

}