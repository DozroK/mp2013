<?php
class ViewFactory
{
    private $view;
    public function __construct($format, $rdf) {
        $this->view = "View".ucfirst($format);
    }
    
    public function build() {
        return new $this->view;
    }
}