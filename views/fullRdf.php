<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<rdf___RDF 
    xmlns___rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns___rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns___owl="http://www.w3.org/2002/07/owl#" 
    xmlns___dc="http://purl.org/dc/elements/1.1/" 
    xmlns___schema="http://schema.org/" 
    xmlns___event="http://schema.org/Event" 
    xmlns___place="http://schema.org/Place" 
    xmlns___address="http://schema.org/PostalAddress" 
    xmlns___geo="http://schema.org/GeoCoordinates" 
    xmlns___organization="http://schema.org/Organization" 
    xmlns___offer="http://schema.org/Offer" 
    xmlns___openingHours="http://schema.org/OpeningHoursSpecification"
    xmlns___itemOffered="http://schema.org/Product"
    xmlns___priceSpecification="http://schema.org/PriceSpecification"
>

<?php  include("rdfHeader.php"); ?>
    
    <!-- Custom classes --> 

    <rdfs___Class rdf___about="http://data.mp2013.fr/Episode">
       <rdfs___comment>A MP2013 specific period who contains events.</rdfs___comment>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>

    <rdfs:Class rdf:about="http://data.mp2013.fr/Event">
       <rdfs:comment>An event happening at a certain time at a certain location.</rdfs:comment>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    
    <rdfs:Class rdf:about="http://data.mp2013.fr/ComedyEvent">
       <rdfs:label xml:lang="fr">Arts de la rue et du cirque</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://data.mp2013.fr/MusicEvent">
       <rdfs:label xml:lang="fr">Concerts / Musique</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://data.mp2013.fr/DanceEvent">
       <rdfs:label xml:lang="fr">Danse et Opéra</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://data.mp2013.fr/VisualArtsEvent">
       <rdfs:label xml:lang="fr">Expositions / Musées</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://data.mp2013.fr/Festival">
       <rdfs:label xml:lang="fr">Festivals et Grands rassemblements</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/SocialEvent">
       <rdfs:label xml:lang="fr">Ouverture / Inauguration</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:ID="http://data.mp2013.fr/BusinessEvent">
       <rdfs:label xml:lang="fr">Rencontres / Colloques</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://data.mp2013.fr/TheaterEvent">
       <rdfs:label xml:lang="fr">Théatre et Cinéma</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://data.mp2013.fr/Event"/>
    </rdfs:Class>

    <!-- The episodes of MP2013 --> 
          
    <rdf___Description rdf___about="http://data.mp2013.fr/episode/#1" >
        <rdf___type rdf___resource="http://data.mp2013.fr/Episode"/>
        <event___name xml___lang="fr">Episode 1 ___ Marseille Provence accueille le monde</event___name>
        <event___name xml___lang="en">Marseille Provence welcoming the world</event___name>
    </rdf___Description>

    <rdf___Description rdf___about="http://data.mp2013.fr/episode/#2" >
        <rdf___type rdf___resource="http://data.mp2013.fr/Episode"/>
        <event___name xml___lang="fr">Episode 2 ___ Marseille Provence à ciel ouvert</event___name>
        <event___name xml___lang="en">Marseille Provence open sky</event___name>
    </rdf___Description>

    <rdf___Description rdf___about="http://data.mp2013.fr/episode/#3" >
        <rdf___type rdf___resource="http://data.mp2013.fr/Episode"/>
        <event___name xml___lang="fr">Episode 3 ___ Marseille Provence aux milles visages</event___name>
        <event___name xml___lang="en">Marseille Provence land of diversity</event___name>
    </rdf___Description>

    <!-- All producers of MP2013 -->

<?php foreach ($view["producer"] as $uuid => $producer) { ?>
    <rdf:Description rdf:about="http://data.mp2013.fr/producer/#<?php echo $producer->getUuid() ?> ">
        <rdf:type rdf:resource="http://schema.org/Organization"/>
        <organization:name><?php echo $producer->getName() ?></organization:name>
        <organization:telephone><?php echo $producer->getTelephone() ?></organization:telephone>
        <organization:url><?php echo $producer->getUrl() ?></organization:url>
    </rdf:Description>
<?php } ?>

    <!-- All main events of MP2013 -->
    
<?php foreach ($view["event"] as $idPatio => $event) { ?>
    <rdf___Description rdf___about="http://data.mp2013.fr/event/#<?php echo $idPatio ?>">
        <?php echo reset($event)->getType(); ?>    
<?php     foreach ($event as $lang => $localizedEvent) { ?>
        <?php echo $localizedEvent->getName(); ?>
<?php     } ?>
<?php     foreach ($event as $lang => $localizedEvent) { ?>
        <?php echo $localizedEvent->getDescription(); ?>
<?php     } ?>
        <?php echo reset($event)->getStartDate(); ?>
        <?php echo reset($event)->getEndDate(); ?>
        <?php echo reset($event)->getImage(); ?>
        <?php echo reset($event)->getSuperEvent(); ?>

        <!-- The producers of the event -->
<?php     foreach (reset($event)->getProducers() as $producer) { ?>
        <event:producer rdf:resource="http://data.mp2013.fr/producer/#<?php echo $producer->getUuid() ?>" />
<?php     } ?>

        <!-- The location of the event or organization. -->
        <event___location rdf___parseType='Literal'>
            <!-- Physical address of the item -->
            <place___address>
                <address___name><?php echo reset($event)->getPlace()->getName() ?></address___name>
                <address___addressLocality><?php echo reset($event)->getPlace()->getAddressLocality() ?></address___addressLocality>
                <address___postalCode><?php echo reset($event)->getPlace()->getPostalCode() ?></address___postalCode>
                <address___streetAddress><?php echo reset($event)->getPlace()->getStreetAddress() ?></address___streetAddress>
            </place___address>
            <place___geo>
                <geo___latitude><?php echo reset($event)->getPlace()->getLatitude() ?></geo___latitude>
                <geo___longitude><?php echo reset($event)->getPlace()->getLongitude() ?></geo___longitude>
            </place___geo>
            
            <!-- opening hours -->
            
            <?php
            foreach (reset($event)->getPlace()->getOpeningHours() as $openingHours) {
            ?>
            <place___openingHoursSpecification>
                <openingHours___dayOfWeek> <?php echo $openingHours->get('dayOfWeek'); ?> </openingHours___dayOfWeek>
                <openingHours___opens> <?php echo $openingHours->get('opens')->format('H___i___s'); ?> </openingHours___opens>
                <openingHours___closes> <?php echo $openingHours->get('closes')->format('H___i___s'); ?> </openingHours___closes>
            </place___openingHoursSpecification>
            <?php
            }
            ?>
        </event___location>
        
        <!-- offers for this item -->
        <event___offers  rdf___parseType='Literal'><?php
            foreach (reset($event)->getOffers() as $offer) {?>
                <event___offer>
<?php
                    $itemOfferedEn = $offer->getItemOfferedEn();
                    $itemOfferedFr = $offer->getItemOfferedFr();
                    if( !empty($itemOfferedEn) or !empty($itemOfferedFr) ){ ?>
                    <offer___itemOffered>
<?php 
                            if(!empty( $itemOfferedEn)){ ?>
                                <itemOffered___name xml___lang="en"><?php
                               echo $itemOfferedEn 
                                ?></itemOffered___name>   
                            <?}
                            if(!empty( $itemOfferedFr)){?> 
                                <itemOffered___name xml___lang="fr"><?php
                                     echo $itemOfferedFr 
                              ?></itemOffered___name>
                            <?}
                        ?>
                    </offer___itemOffered>
<?php           } ?> 
                                
<?php                 if( $value = $offer->getDescriptionEn() and !empty($value) ){ ?>
                        <offer___description xml___lang="en"><?php
                            echo $value 
                        ?></offer___description>
<?php                 } ?>                

<?php                 if( $value = $offer->getDescriptionFr() and !empty($value) ){ ?>
                        <offer___description xml___lang="fr"><?php
                            echo $value 
                        ?></offer___description>
<?php                 } ?>   

<?php                 if( $value = $offer->getEligibleCustomerType() and !empty($value) ){ ?>
                        <offer___eligibleCustomerType><?php 
                            echo $value 
                        ?></offer___eligibleCustomerType>
<?php                 } ?>

<?php
                    $maxPrice = $offer->getMaxPrice();
                    $minPrice = $offer->getMinPrice();
                    if( !empty($maxPrice) or !empty($minPrice) ){ ?>
                    <offer___priceSpecification>
<?php
                            if(!empty($maxPrice)){?> 
                                <priceSpecification___maxPrice><?php 
                                    echo $maxPrice 
                                ?></priceSpecification___maxPrice>   
                            <?}
                            if(!empty($minPrice)){?> 
                                <priceSpecification___minPrice><?php
                                     echo $minPrice 
                                ?></priceSpecification___minPrice>
<?php                             }
                        ?>
                        <priceSpecification___priceCurrency>EUR</priceSpecification___priceCurrency>
                    </offer___priceSpecification>
<?php                 } ?>
            </event___offer>
<?php         }
        ?></event___offers>
    </rdf___Description>
<?php } ?>
</rdf___RDF>
