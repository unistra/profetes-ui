Installation sur le serveur www.unistra.fr
==========================================


Pour l'installation sur le serveur www.unistra.fr, symfony2 doit fonctionner en
parallèle de Typo3 pour un sous-ensemble de pages uniquement.


Configuration d'Apache
----------------------

Pour permettre de dérouter les requêtes de Typo3 vers symfony2, les directives
suivantes sont ajoutées à la configuration d'apache :

    AliasMatch ^/formations(.*) /www/unistra/symfony2/web/app.php$1

    <Directory /www/unistra/symfony2/web>
        Order allow,deny
        Allow from All
    </Directory>

La documentation de symfony rajoute dans la clause Directory un `AllowOverride
All` cependant la configuration du .htaccess n'est pas souhaitable.

À noter une modification entre httpd 2.2 et 2.4. Pour la version 2.4, il
faudrait indiquer :

    Require all granted

en lieu et place de

    Order allow,deny
    Allow from All

