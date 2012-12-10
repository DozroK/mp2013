<?php header('Content-type: text/xml'); ?>
<?php echo "<?xml version='1.0'?>" ?>
<rdf:RDF 
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns:owl="http://www.w3.org/2002/07/owl#" 
    xmlns:dc="http://purl.org/dc/elements/1.1/" 
    xmlns:schema="http://schema.org/" 
    xmlns:event="http://schema.org/Event" 
    xmlns:place="http://schema.org/Place" 
    xmlns:address="http://schema.org/PostalAddress" 
    xmlns:geo="http://schema.org/GeoCoordinates" 
    xmlns:organization="http://schema.org/Organization" 
    xmlns:offer="http://schema.org/Offer" 
    xmlns:openingHours="http://schema.org/OpeningHoursSpecification"
    xmlns:itemOffered="http://schema.org/Product"
    xmlns:priceSpecification="http://schema.org/PriceSpecification"
>

    <!-- The dublin core metadatas of the document. -->
    <rdf:Description rdf:about="http://data.mp2013.fr" >
        <dc:title>Events API publique de MP2013</dc:title>
        <dc:description>The MP2013 events</dc:description>   
        <dc:format>application/rdf+xml</dc:format>
        <dc:publisher>Marseille Provence 2013</dc:publisher>
        <dc:identifier>http://www.mp2013.fr/</dc:identifier>
        <dc:created>2012-11-30T10:31:00+0100</dc:created>
        <dc:modified><?php // TODO ?></dc:modified>
        <dc:license>http://www.data.gouv.fr/Licence-Ouverte-Open-Licence</dc:license>
    </rdf:Description>
    
    <!-- OWL header -->
    <owl:Onthology rdf:about="http://schema.org/docs/schemaorg.owl">
       <dc:title>The schema.org Ontology</dc:title>
       <dc:description>The schema.org ontology to defines all events.</dc:description>
    </owl:Onthology>

    <!-- Standard Event Classes -->
    
    <rdfs:Class rdf:about="http://schema.org/Event">
       <rdfs:comment>An event happening at a certain time at a certain location.</rdfs:comment>
    </rdfs:Class>
    
    <rdfs:Class rdf:about="http://schema.org/ComedyEvent">
       <rdfs:label xml:lang="fr">Arts de la rue et du cirque</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/MusicEvent">
       <rdfs:label xml:lang="fr">Concerts / Musique</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/DanceEvent">
       <rdfs:label xml:lang="fr">Danse et Opéra</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/VisualsArtsEvent">
       <rdfs:label xml:lang="fr">Expositions / Musées</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/Festival">
       <rdfs:label xml:lang="fr">Festivals et Grands rassemblements</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/SocialEvent">
       <rdfs:label xml:lang="fr">Ouverture / Inauguration</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/UserInteraction">
       <rdfs:label xml:lang="fr">Rencontres / Colloques</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>
    <rdfs:Class rdf:about="http://schema.org/TheaterEvent">
       <rdfs:label xml:lang="fr">Théatre et Cinéma</rdfs:label>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>

    <!-- Custom classes --> 

    <rdfs:Class rdf:ID="Episode">
       <rdfs:comment>A MP2013 specific period who contains events.</rdfs:comment>
       <rdfs:subClassOf rdf:resource="http://schema.org/Event"/>
    </rdfs:Class>

    <!-- The episodes of MP2013 --> 
          
    <rdf:Description rdf:ID="a509c46d-0c64-4cb4-89f2-8aa06ae5a53f" >
        <rdf:type rdf:resource="#Episode"/>
        <event:name xml:lang="fr">Episode 1 : Marseille Provence accueille le monde</event:name>
        <event:name xml:lang="en">Marseille Provence welcoming the world</event:name>
    </rdf:Description>

    <rdf:Description rdf:ID="b2eb0aec-e755-4d1d-946f-c49d44fc35e6" >
        <rdf:type rdf:resource="#Episode"/>
        <event:name xml:lang="fr">Episode 2 : Marseille Provence à ciel ouvert</event:name>
        <event:name xml:lang="en">Marseille Provence open sky</event:name>
    </rdf:Description>

    <rdf:Description rdf:ID="905b4dc6-3059-4daa-b64d-08c80cae12dd" >
        <rdf:type rdf:resource="#Episode"/>
        <event:name xml:lang="fr">Episode 3 : Marseille Provence aux milles visages</event:name>
        <event:name xml:lang="en">Marseille Provence land of diversity</event:name>
    </rdf:Description>

    <!-- All main events of MP2013 -->
    
<?php foreach ($view["event"] as $idPatio => $event) { ?>
    <rdf:Description rdf:ID="<?php echo $idPatio ?>">
        <?php echo $event["fr"]->getType(); ?>    
<?php     foreach ($event as $lang => $localizedEvent) { ?>
        <?php echo $localizedEvent->getName(); ?>
<?php     } ?>
<?php     foreach ($event as $lang => $localizedEvent) { ?>
        <?php echo $localizedEvent->getDescription(); ?>
<?php     } ?>
        <?php echo $event["fr"]->getStartDate(); ?>
        <?php echo $event["fr"]->getEndDate(); ?>
        <?php echo $event["fr"]->getImage(); ?>
        <!-- The location of the event or organization. -->
        <event:location>
            <!-- Physical address of the item -->
            <place:address>
                <address:addressLocality><?php echo $event["fr"]->getPlace()->getAddressLocality() ?></address:addressLocality>
                <address:postalCode><?php echo $event["fr"]->getPlace()->getPostalCode() ?></address:postalCode>
            </place:address>
            <place:geo>
                <geo:latitude><?php echo $event["fr"]->getPlace()->getLatitude() ?></geo:latitude>
                <geo:longitude><?php echo $event["fr"]->getPlace()->getLongitude() ?></geo:longitude>
            </place:geo>
            
            <!-- opening hours -->
            
            <?php
            foreach ($event["fr"]->getPlace()->getOpeningHours() as $openingHours) {
            ?>
            <place:openingHoursSpecification>
                <openingHours:dayOfWeek> <?php echo $openingHours->get('dayOfWeek'); ?> </openingHours:dayOfWeek>
                <openingHours:opens> <?php echo $openingHours->get('opens')->format('H:i:s'); ?> </openingHours:opens>
                <openingHours:closes> <?php echo $openingHours->get('closes')->format('H:i:s'); ?> </openingHours:closes>
            </place:openingHoursSpecification>
            <?php
            }
            ?>
        </event:location>
        
        <!-- offers for this item -->
        <event:offers>
        <?php foreach ($event["fr"]->getOffers() as $offer) {?>        
            <event:offer>
                <?php
                    $itemOfferedEn = $offer->getItemOfferedEn();
                    $itemOfferedFr = $offer->getItemOfferedFr();
                    if( !empty($itemOfferedEn) or !empty($itemOfferedFr) ){ ?>
                    <offer:itemOffered>
                        <?php 
                            if(!empty( $itemOfferedEn)){?> 
                                <itemOffered:name xml:lang="en">
                                    <?php echo $itemOfferedEn ?>
                                </itemOffered:name>   
                            <?}
                            if(!empty( $itemOfferedFr)){?> 
                                <itemOffered:name xml:lang="fr">
                                    <?php echo $itemOfferedFr ?>
                                </itemOffered:name>                                
                            <?}
                        ?>
                    </offer:itemOffered>
                <?php } ?> 
                                
                <?php if( $value = $offer->getDescriptionEn() and !empty($value) ){ ?>
                        <offer:description xml:lang="en">
                            <?php echo $value ?>
                        </offer:description>
                <?php } ?>                

                <?php if( $value = $offer->getDescriptionFr() and !empty($value) ){ ?>
                        <offer:description xml:lang="fr">
                            <?php echo $value ?>
                        </offer:description>
                <?php } ?>   

                <?php if( $value = $offer->getEligibleCustomerType() and !empty($value) ){ ?>
                        <offer:eligibleCustomerType>
                            <?php echo $value ?>
                        </offer:eligibleCustomerType>
                <?php } ?>
                
                <?php
                    $maxPrice = $offer->getMaxPrice();
                    $minPrice = $offer->getMinPrice();
                    if( !empty($maxPrice) or !empty($minPrice) ){ ?>
                    <offer:priceSpecification>
                        <?php 
                            if(!empty($maxPrice)){?> 
                                <priceSpecification:maxPrice>
                                    <?php echo $maxPrice ?>
                                </priceSpecification:maxPrice>   
                            <?}
                            if(!empty($minPrice)){?> 
                                <priceSpecification:minPrice>
                                    <?php echo $minPrice ?>
                                </priceSpecification:minPrice>                                
                            <?}
                        ?>
                        <priceSpecification:priceCurrency>
                            EUR
                        </priceSpecification:priceCurrency> 
                    </offer:priceSpecification>
                <?php } ?>                
            </event:offer>
        <?php }?>
        </event:offers>
    </rdf:Description>
<?php } ?>
</rdf:RDF>
