<?php header('Content-type: text/xml'); ?>
<?php echo "<?xml version='1.0'?>" ?>
<rdf:RDF 
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:schema="http://schema.org/"
    xmlns:event="http://schema.org/Event"
    xmlns:place="http://schema.org/PostalAddress">

    <?php foreach ($view["event"] as $event) { ?>
    <rdf:Description rdf:about="<?php echo $event->getIdPatio() ?>" xml:lang="<?php echo $event->getLang() ?>">
    <event:name><?php echo htmlspecialchars($event->getName(), ENT_QUOTES) ?></event:name>
    <event:type><?php echo $event->getType() ?></event:type>
    <event:description><?php echo htmlspecialchars($event->getDescription(), ENT_QUOTES) ?></event:description>
    <event:image><?php echo $event->getImage() ?></event:image>
    <event:place><?php echo $event->getPlace()->getId() ?></event:place>
    </rdf:Description>
    <?php } ?>

    <?php foreach ($view["place"] as $place) { ?>
    <rdf:Description rdf:about="<?php echo $place->getId() ?>">
    <place:addressLocality><?php echo $place->getAddressLocality() ?></place:addressLocality>
    <place:postalCode><?php echo $place->getPostalCode() ?></place:postalCode>
    </rdf:Description>
    <?php } ?>


</rdf:RDF>
