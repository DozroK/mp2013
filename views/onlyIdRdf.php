<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>
<rdf___RDF 
    xmlns___rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns___rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns___owl="http://www.w3.org/2002/07/owl#" 
    xmlns___dc="http://purl.org/dc/elements/1.1/" 
    xmlns___schema="http://schema.org/" 
    xmlns___event="http://schema.org/Event"
>

<?php  include("rdfHeader.php"); ?>

    <!-- Standard Event Classes -->
    <rdfs___Class rdf___about="http://schema.org/Event">
       <rdfs___comment>An event happening at a certain time at a certain location.</rdfs___comment>
    </rdfs___Class>
    
    <rdfs___Class rdf___about="http://schema.org/ComedyEvent">
       <rdfs___label xml___lang="fr">Arts de la rue et du cirque</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/MusicEvent">
       <rdfs___label xml___lang="fr">Concerts / Musique</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/DanceEvent">
       <rdfs___label xml___lang="fr">Danse et Opéra</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/VisualsArtsEvent">
       <rdfs___label xml___lang="fr">Expositions / Musées</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/Festival">
       <rdfs___label xml___lang="fr">Festivals et Grands rassemblements</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/SocialEvent">
       <rdfs___label xml___lang="fr">Ouverture / Inauguration</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/UserInteraction">
       <rdfs___label xml___lang="fr">Rencontres / Colloques</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    <rdfs___Class rdf___about="http://schema.org/TheaterEvent">
       <rdfs___label xml___lang="fr">Théatre et Cinéma</rdfs___label>
       <rdfs___subClassOf rdf___resource="http://schema.org/Event"/>
    </rdfs___Class>
    
    <!-- All main events of MP2013 -->
    
<?php foreach ($view["event"] as $idPatio => $event) { ?>
        <rdf___Description rdf___about="http://data.mp2013.fr/event/#<?php echo $idPatio ?>" rdf___parseType='Literal'>
<?php      echo reset($event)->getName(); ?>
        </rdf___Description>
<?php } ?>
</rdf___RDF>
