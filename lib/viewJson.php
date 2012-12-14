<?php

class ViewJson implements viewInterface
{

    private $rdf
    public function __construct($rdf) {
        $this->rdf = $rdf;
    }
    
    public function get() {
        $url = 'http://rdf-translator.appspot.com/convert/rdfa/rdf-json/content';
        $postData = array();
        $postData['content'] = $this->rdf;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $view = curl_exec($ch);
        curl_close($ch);
        return $view ;
    }
    public function getHeader() {
        return 'Content-Type: application/json';
    }    


}