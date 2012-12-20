<?php echo "<?xml version='1.0'?>" ?>
<rdf___RDF 
    xmlns___rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns___rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns___owl="http://www.w3.org/2002/07/owl#" 
    xmlns___dc="http://purl.org/dc/elements/1.1/" 
    xmlns___schema="http://schema.org/" 
    xmlns___event="http://schema.org/Event"
>
    
<?php  include("rdfHeader.php"); ?>
    
    <!-- All main events of MP2013 -->
    
<?php foreach ($view["event"] as $idPatio => $event) { ?>
        <rdf___Description rdf___about="http://data.mp2013.fr/event/#<?php echo $idPatio ?>">
<?php      echo reset($event)->getName(); ?>
        </rdf___Description>
<?php } ?>
</rdf___RDF>
