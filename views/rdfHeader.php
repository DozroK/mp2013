    <!-- The dublin core metadatas of the document. -->
    <rdf___Description rdf___about="http://data.mp2013.fr" >
        <dc___title>Events API publique de MP2013</dc___title>
        <dc___description>The MP2013 events</dc___description>   
        <dc___format>application/rdf+xml</dc___format>
        <dc___publisher>Marseille Provence 2013</dc___publisher>
        <dc___identifier>http://www.mp2013.fr/</dc___identifier>
        <dc___created>2012-11-30T10___31___00+0100</dc___created>
        <dc___modified><?php // TODO ?></dc___modified>
        <dc___license>http://www.data.gouv.fr/Licence-Ouverte-Open-Licence</dc___license>
    </rdf___Description>
    
    <!-- OWL header -->
    <owl___Onthology rdf___about="http://schema.org/docs/schemaorg.owl">
       <dc___title>The schema.org Ontology</dc___title>
       <dc___description>The schema.org ontology to defines all events.</dc___description>
    </owl___Onthology>

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