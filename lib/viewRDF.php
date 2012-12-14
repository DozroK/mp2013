<?php

class ViewRDF implements viewInterface
{

    private $rdf;
    public function __construct($rdf) {
        $this->rdf = $rdf;
    }
    
    public function get() {
        return $this->rdf ;
    }
    public function getHeader() {
        return 'Content-type: text/xml';
//        return 'Content-type: application/rdf+xml';

    }

}