<?php header('Content-type: text/xml'); ?>
<?php echo "<?xml version='1.0'?>" ?>
<rdf:RDF 
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:schema="http://schema.org/"
    xmlns:event="http://schema.org/Event"
    xmlns:visualartsevent="http://schema.org/VisualArtsEvent"
    xmlns:festival="http://schema.org/Festival"
    xmlns:danceevent="http://schema.org/DanceEvent"
    xmlns:musicevent="http://schema.org/MusicEvent"
    xmlns:userinteraction="http://schema.org/UserInteraction"
    xmlns:socialevent="http://schema.org/SocialEvent"
    xmlns:theaterevent="http://schema.org/TheaterEvent"
    xmlns:comedyevent="http://schema.org/ComedyEvent"    
    xmlns:place="http://schema.org/Place" 
    xmlns:address="http://schema.org/PostalAddress" 
    xmlns:geo="http://schema.org/GeoCoordinates" 
    xmlns:org="http://schema.org/Organization" 
>
    <!--
    <license rdf:ressource="http://www.data.gouv.fr/Licence-Ouverte-Open-Licence"/>
    
    <dc:title xml:lang="fr">API publique des événements de MP2013</dc:title>
    <dc:title xml:lang="en">Events API publique de MP2013</dc:title>
    <dc:description xml:lang="fr">Les événements de MP2013</dc:description>
    <dc:description xml:lang="en">The MP2013 events</dc:description>
    <dc:format>application/rdf+xml</dc:format>
    <dc:publisher>Marseille Provence 2013</dc:publisher>
    <dc:identifier>http://www.mp2013.fr/</dc:identifier>
    <dc:created>2012-11-30T10:31:00+0100</dc:created>
    <dc:modified>2012-12-02T16:23:00+0100</dc:modified>
    -->
    
<?php foreach ($view["event"] as $idPatio => $event) { ?>
    <rdf:Description rdf:ID="<?php echo $idPatio ?>">
<?php     foreach ($event as $lang => $localizedEvent) { ?>
<?php         if ($localizedEvent->getName()) { ?>
        <?php echo $localizedEvent->getRDFName(); ?>
<?php         } ?>
<?php     } ?>
<?php     foreach ($event as $lang => $localizedEvent) { ?>
<?php         if ($localizedEvent->getDescription()) { ?>
        <?php echo $localizedEvent->getRDFDescription(); ?>
<?php         } ?>
<?php     } ?>
        <?php echo $event["fr"]->getRDFStartDate(); ?>
        <?php echo $event["fr"]->getRDFEndDate(); ?>
        <?php echo $event["fr"]->getRDFImage(); ?>
        <?php echo $event["fr"]->getRDFTag("open", "location"); ?>
            <?php echo $event["fr"]->getRDFTag("open", "place"); ?>
                <place:addressLocality><?php echo $event["fr"]->getPlace()->getAddressLocality() ?></place:addressLocality>
                <place:postalCode><?php echo $event["fr"]->getPlace()->getPostalCode() ?></place:postalCode>
            <?php echo $event["fr"]->getRDFTag("close", "place"); ?>
        <?php echo $event["fr"]->getRDFTag("close", "location"); ?>
    </rdf:Description>
<?php } ?>
</rdf:RDF>
