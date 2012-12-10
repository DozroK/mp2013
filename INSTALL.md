* La racine du serveur web doit pointer sur le dossier `web`
* Le dossier `xml` doit être en accès en écriture pour l'utilisateur web (en pratique, mettre ce dossier avec droits 777)
* Les paramètres d'accès à la bdd sont à chager dans `bootstrap.php` et dans `tools/cli-config.php`

* Pour installer doctrine2 : 

	    git submodule add git://github.com/doctrine/doctrine2.git externals/doctrine2
	    git submodule update --init --recursive

