<?php

include_once(__DIR__."/viewInterface.php");

class ViewJson implements viewInterface
{

    private $rdf;
    public function __construct($rdf) {
        $this->rdf = $rdf;
    }
    
    public function get() {
        // TODO : comment gérer proprement namespace avec simple xml
        $xml = simplexml_load_string($this->rdf);
		$json = json_encode($xml);
		return $json;
    }
    public function getHeader() {
        return 'Content-Type: application/json';
    }    



}