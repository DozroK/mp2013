<?php

include_once(__DIR__."/viewInterface.php");

class ViewJson implements viewInterface
{

    private $rdf;
    public function __construct($rdf) {
        $this->rdf = $rdf;
    }
    
    public function get() {

//		$simpleXml = simplexml_load_string($this->rdf);
		$simpleXml = simplexml_load_string('<?xml version="1.0"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:si="http://www.w3schools.com/rdf/"><rdf:Description rdf:about="http://www.w3schools.com"><si:title>W3Schools</si:title><si:author>Jan Egil Refsnes</si:author></rdf:Description></rdf:RDF>');
        if ($simpleXml === false) {
            echo "Erreur lors du chargement du XML\n";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
        }
		$json = json_encode($simpleXml);
		return $json;
    }
    public function getHeader() {
        return 'Content-Type: application/json';
    }    


}