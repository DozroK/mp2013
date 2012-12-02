<?php header('Content-type: text/xml'); ?>
<?php echo "<?xml version='1.0'?>" ?>
<rdf:RDF 
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:schema="http://schema.org/"
    xmlns:event="http://schema.org/Event"
    xmlns:place="http://schema.org/Place" 
    xmlns:address="http://schema.org/PostalAddress" 
    xmlns:geo="http://schema.org/GeoCoordinates" 
    xmlns:org="http://schema.org/Organization" >

    <?php foreach ($view["event"] as $idPatio => $event) { ?>

    <rdf:Description rdf:about="<?php echo $idPatio ?>">
        <event:name><?php echo htmlspecialchars($event["fr"]->getName(), ENT_QUOTES) ?></event:name>
    
        <?php foreach ($event as $lang => $localizedEvent) { ?>
            <?php if ($localizedEvent->getType()) { ?>
                <event:type xml:lang="<?php echo $lang ?>"><?php echo $localizedEvent->getType() ?></event:type>
            <?php } ?>
            <?php if ($localizedEvent->getDescription()) { ?>
                <event:description xml:lang="<?php echo $lang ?>"><?php echo htmlspecialchars($localizedEvent->getDescription(), ENT_QUOTES) ?></event:description>
            <?php } ?>
        <?php } ?>
        <event:startDate><?php echo $event["fr"]->getStartDate()->format(DateTime::ISO8601) ?></event:startDate>
        <event:endDate><?php echo $event["fr"]->getEndDate()->format(DateTime::ISO8601) ?></event:endDate>
        <event:image><?php echo $event["fr"]->getImage() ?></event:image>
        <event:location>
            <event:place>
                <place:addressLocality><?php echo $event["fr"]->getPlace()->getAddressLocality() ?></place:addressLocality>
                <place:postalCode><?php echo $event["fr"]->getPlace()->getPostalCode() ?></place:postalCode>
            </event:place>
        </event:location>
    </rdf:Description>
    <?php } ?>
</rdf:RDF>
